<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use App\Models\Kelompok;
use App\Models\TahunAkademik;
use App\Models\Semester;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminAttendanceInjectController extends Controller
{
    /**
     * Daftar semua absensi yang di-inject oleh admin
     */
    public function index(Request $request)
    {
        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        // Default ke periode aktif
        if (!$request->has('tahun_akademik_id') && !$request->has('semester_id')) {
            if ($tahunAktif && $semesterAktif) {
                $tahun_akademik_id = $tahunAktif->id;
                $semester_id = $semesterAktif->id;
            } else {
                $tahun_akademik_id = null;
                $semester_id = null;
            }
        } else {
            $tahun_akademik_id = $request->query('tahun_akademik_id');
            $semester_id = $request->query('semester_id');
        }

        $query = Absensi::whereNotNull('injected_by')
            ->with(['user', 'kelompok', 'injectedBy'])
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan tahun akademik dan semester melalui user
        if ($tahun_akademik_id || $semester_id) {
            $query->whereHas('user', function ($q) use ($tahun_akademik_id, $semester_id) {
                if ($tahun_akademik_id) {
                    $q->where('tahun_akademik_id', $tahun_akademik_id);
                }
                if ($semester_id) {
                    $q->where('semester_id', $semester_id);
                }
            });
        }

        // Filter by nama mahasiswa
        if ($request->filled('nama')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->nama . '%');
            });
        }

        // Filter by tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->where('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->where('tanggal', '<=', $request->tanggal_akhir);
        }

        $attendances = $query->paginate(15);

        $tahunAkademikList = TahunAkademik::all();
        $semesterList = Semester::all();

        return view('admin.attendance-inject.index', compact(
            'attendances',
            'tahunAkademikList',
            'semesterList',
            'tahun_akademik_id',
            'semester_id'
        ));
    }

    /**
     * Form inject absensi baru
     */
    public function create(Request $request)
    {
        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        // Ambil daftar mahasiswa (yang memiliki kelompok)
        $mahasiswaQuery = User::role('mahasiswa')
            ->whereNotNull('kelompok_id')
            ->with('kelompok.lokasi')
            ->orderBy('name');

        // Filter by tahun akademik dan semester aktif
        if ($tahunAktif) {
            $mahasiswaQuery->where('tahun_akademik_id', $tahunAktif->id);
        }
        if ($semesterAktif) {
            $mahasiswaQuery->where('semester_id', $semesterAktif->id);
        }

        $mahasiswaList = $mahasiswaQuery->get();

        // Ambil daftar kelompok untuk filter
        $kelompokList = Kelompok::with('lokasi')->orderBy('nama_kelompok')->get();

        return view('admin.attendance-inject.create', compact(
            'mahasiswaList',
            'kelompokList',
            'tahunAktif',
            'semesterAktif'
        ));
    }

    /**
     * Simpan inject absensi baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'waktu_masuk' => 'required',
            'waktu_keluar' => 'nullable',
            'lokasi' => 'nullable|string|max:255',
            'foto_kegiatan' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'catatan_inject' => 'required|string|max:1000',
        ], [
            'user_id.required' => 'Pilih mahasiswa terlebih dahulu.',
            'user_id.exists' => 'Mahasiswa tidak ditemukan.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'waktu_masuk.required' => 'Waktu masuk wajib diisi.',
            'catatan_inject.required' => 'Catatan/alasan inject wajib diisi.',
            'catatan_inject.max' => 'Catatan maksimal 1000 karakter.',
            'foto_kegiatan.image' => 'File harus berupa gambar.',
            'foto_kegiatan.max' => 'Ukuran foto maksimal 5MB.',
        ]);

        // Ambil data mahasiswa
        $mahasiswa = User::findOrFail($validated['user_id']);

        // Cek duplikasi — apakah sudah ada absensi di tanggal tersebut
        $existingAttendance = Absensi::where('user_id', $mahasiswa->id)
            ->whereDate('tanggal', Carbon::parse($validated['tanggal']))
            ->first();

        if ($existingAttendance) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Mahasiswa ' . $mahasiswa->name . ' sudah memiliki data absensi pada tanggal ' . Carbon::parse($validated['tanggal'])->format('d M Y') . '.');
        }

        // Upload foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto_kegiatan')) {
            $fotoPath = $request->file('foto_kegiatan')->store('absensi', 'public');
        }

        // Parse waktu
        $tanggal = Carbon::parse($validated['tanggal']);
        $waktuMasuk = Carbon::parse($validated['tanggal'] . ' ' . $validated['waktu_masuk']);
        $waktuKeluar = null;
        if (!empty($validated['waktu_keluar'])) {
            $waktuKeluar = Carbon::parse($validated['tanggal'] . ' ' . $validated['waktu_keluar']);
        }

        // Simpan absensi — langsung validated, tidak perlu approval DPL
        Absensi::create([
            'user_id' => $mahasiswa->id,
            'kelompok_id' => $mahasiswa->kelompok_id,
            'tanggal' => $tanggal,
            'waktu_masuk' => $waktuMasuk,
            'waktu_keluar' => $waktuKeluar,
            'foto_kegiatan' => $fotoPath,
            'lokasi' => $validated['lokasi'] ?? null,
            'status' => 'validated',
            'injected_by' => auth()->id(),
            'catatan_inject' => $validated['catatan_inject'],
        ]);

        return redirect()->route('admin.attendance-inject.index')
            ->with('success', 'Absensi untuk ' . $mahasiswa->name . ' pada tanggal ' . $tanggal->format('d M Y') . ' berhasil di-inject.');
    }

    /**
     * Hapus absensi yang di-inject
     */
    public function destroy(Absensi $attendance)
    {
        // Hanya bisa hapus absensi yang di-inject
        if (!$attendance->isInjected()) {
            return redirect()->back()
                ->with('error', 'Hanya absensi yang di-inject oleh admin yang dapat dihapus dari halaman ini.');
        }

        // Hapus foto jika ada
        if ($attendance->foto_kegiatan) {
            Storage::disk('public')->delete($attendance->foto_kegiatan);
        }
        if ($attendance->foto_keluar) {
            Storage::disk('public')->delete($attendance->foto_keluar);
        }

        $namaUser = $attendance->user->name ?? 'Unknown';
        $tanggal = $attendance->tanggal ? $attendance->tanggal->format('d M Y') : '-';

        $attendance->delete();

        return redirect()->route('admin.attendance-inject.index')
            ->with('success', 'Absensi inject untuk ' . $namaUser . ' tanggal ' . $tanggal . ' berhasil dihapus.');
    }
}

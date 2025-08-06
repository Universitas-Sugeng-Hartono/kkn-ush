<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Pengaduan::class);

        $query = Pengaduan::with(['lokasi', 'admin']);

        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('nomor_pengaduan', 'like', "%{$search}%")
                  ->orWhere('nama_pelapor', 'like', "%{$search}%")
                  ->orWhere('subjek', 'like', "%{$search}%");
            });
        }

        $pengaduan = $query->orderBy('created_at', 'desc')->get();

        return view('pengaduan.index', compact('pengaduan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelapor' => 'required|string|max:255',
            'email_pelapor' => 'required|email|max:255',
            'no_hp_pelapor' => 'required|string|max:20',
            'subjek' => 'required|string|max:255',
            'isi_pengaduan' => 'required|string',
            'lokasi_id' => 'required|exists:lokasi,id',
            'bukti_pendukung' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        if ($request->hasFile('bukti_pendukung')) {
            $path = $request->file('bukti_pendukung')->store('pengaduan', 'public');
            $validated['bukti_pendukung'] = $path;
        }

        $pengaduan = Pengaduan::create($validated);

        return redirect()->back()
            ->with('success', 'Pengaduan berhasil dikirim. Kami akan menindaklanjuti pengaduan Anda secepatnya.')
            ->with('nomor_pengaduan', $pengaduan->nomor_pengaduan);
    }

    public function check(Request $request)
    {
        $request->validate([
            'nomor_pengaduan' => 'required|string'
        ]);

        $pengaduan = Pengaduan::where('nomor_pengaduan', $request->nomor_pengaduan)->first();

        if (!$pengaduan) {
            return redirect()->back()
                ->with('error', 'Nomor pengaduan tidak ditemukan');
        }

        return view('welcome', [
            'pengaduan' => $pengaduan,
            'lokasi' => \App\Models\Lokasi::all(), // untuk form pengaduan
            'galeri' => \App\Models\Galeri::where('aktif', true)
                ->orderBy('urutan')
                ->orderBy('created_at', 'desc')
                ->get(),
            'berita' => \App\Models\Berita::where('is_published', true)
                ->orderBy('published_at', 'desc')
                ->take(3)
                ->get(),
            'kelompokData' => $this->getKelompokData()
        ]);
    }

    private function getKelompokData()
    {
        $lokasi = \App\Models\Lokasi::with(['kelompok' => function($query) {
            $query->withCount('mahasiswa')->with('dpl');
        }])->get();

        $kelompokData = [];
        foreach ($lokasi as $lok) {
            foreach ($lok->kelompok as $kelompok) {
                $kelompokData[] = [
                    'nama' => $kelompok->nama,
                    'lokasi' => $lok->nama,
                    'alamat' => $lok->alamat,
                    'latitude' => $lok->latitude,
                    'longitude' => $lok->longitude,
                    'jumlah_mahasiswa' => $kelompok->mahasiswa_count,
                    'dpl' => $kelompok->dpl->name ?? 'Belum ditentukan'
                ];
            }
        }

        return $kelompokData;
    }

    public function show(Pengaduan $pengaduan)
    {
        $this->authorize('view', $pengaduan);
        return view('pengaduan.show', compact('pengaduan'));
    }

    public function process(Request $request, Pengaduan $pengaduan)
    {
        $this->authorize('process', $pengaduan);

        $validated = $request->validate([
            'tanggapan' => 'required|string',
            'status' => 'required|in:process,resolved,rejected'
        ]);

        $pengaduan->update([
            'status' => $validated['status'],
            'tanggapan' => $validated['tanggapan'],
            'user_id' => auth()->id()
        ]);

        return redirect()->route('pengaduan.show', $pengaduan)
            ->with('success', 'Pengaduan berhasil diproses.');
    }

    public function destroy(Pengaduan $pengaduan)
    {
        $this->authorize('delete', $pengaduan);

        if ($pengaduan->bukti_pendukung) {
            Storage::disk('public')->delete($pengaduan->bukti_pendukung);
        }

        $pengaduan->delete();

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil dihapus.');
    }
} 
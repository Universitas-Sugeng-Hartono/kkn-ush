<?php

namespace App\Http\Controllers;

use App\Http\Requests\LaporanKelompokRequest;
use App\Models\Kelompok;
use App\Models\LaporanKelompok;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LaporanKelompokController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user?->kelompok_id) {
            return view('laporan-kelompok.index', [
                'kelompok' => null,
                'laporan' => collect(),
            ])->with('error', 'Anda belum tergabung ke dalam kelompok.');
        }

        $kelompok = $user->kelompok()->with(['tahunAkademik', 'semester', 'lokasi', 'dpl'])->first();

        $laporan = LaporanKelompok::with('user')
            ->where('kelompok_id', $user->kelompok_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('laporan-kelompok.index', compact('kelompok', 'laporan'));
    }

    public function store(LaporanKelompokRequest $request)
    {
        $user = auth()->user();

        if (!$user?->kelompok_id) {
            return back()->with('error', 'Anda belum tergabung ke dalam kelompok.');
        }

        $file = $request->file('file');
        $path = $file->store('laporan_kelompok/' . $user->kelompok_id, 'public');

        LaporanKelompok::create([
            'kelompok_id' => $user->kelompok_id,
            'user_id' => $user->id,
            'judul' => $request->judul,
            'keterangan' => $request->keterangan,
            'file_path' => $path,
            'file_original_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize() ?? 0,
            'file_mime' => $file->getMimeType(),
        ]);

        return back()->with('success', 'Laporan kelompok berhasil diupload. Anggota satu kelompok sekarang bisa melihatnya.');
    }

    public function download(LaporanKelompok $laporanKelompok)
    {
        $user = auth()->user();

        // Mahasiswa: hanya boleh download laporan dari kelompoknya sendiri.
        if ($user?->hasRole('mahasiswa')) {
            if (!$user->kelompok_id || (int) $user->kelompok_id !== (int) $laporanKelompok->kelompok_id) {
                abort(403);
            }
        }

        // DPL: boleh download jika laporan berasal dari kelompok bimbingannya.
        if ($user?->hasRole('dpl')) {
            $isSupervisor = Kelompok::where('id', $laporanKelompok->kelompok_id)
                ->where('dpl_id', $user->id)
                ->exists();

            if (!$isSupervisor) {
                abort(403);
            }
        }

        if (!$laporanKelompok->file_path || !Storage::disk('public')->exists($laporanKelompok->file_path)) {
            abort(404);
        }

        $extension = pathinfo($laporanKelompok->file_original_name, PATHINFO_EXTENSION) ?: pathinfo($laporanKelompok->file_path, PATHINFO_EXTENSION);
        $baseName = $laporanKelompok->judul ?: pathinfo($laporanKelompok->file_original_name, PATHINFO_FILENAME);
        $filename = Str::slug($baseName) . '.' . $extension;

        return Storage::disk('public')->download($laporanKelompok->file_path, $filename);
    }

    public function destroy(LaporanKelompok $laporanKelompok)
    {
        $user = auth()->user();

        if (!$user?->hasRole('mahasiswa')) {
            abort(403);
        }

        if (!$user->kelompok_id || (int) $user->kelompok_id !== (int) $laporanKelompok->kelompok_id) {
            abort(403);
        }

        // Mahasiswa hanya boleh menghapus laporan yang dia upload sendiri.
        if ((int) $laporanKelompok->user_id !== (int) $user->id) {
            abort(403);
        }

        if ($laporanKelompok->file_path && Storage::disk('public')->exists($laporanKelompok->file_path)) {
            Storage::disk('public')->delete($laporanKelompok->file_path);
        }

        $laporanKelompok->delete();

        return back()->with('success', 'Laporan berhasil dihapus.');
    }
}


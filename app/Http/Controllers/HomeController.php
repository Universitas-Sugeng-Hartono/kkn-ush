<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Lokasi;
use App\Models\Kelompok;
use App\Models\Dokumen;
use App\Models\Galeri;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $berita = Berita::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $lokasi = Lokasi::with(['kelompok' => function($query) {
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

        $galeri = Galeri::where('aktif', true)
            ->orderBy('urutan')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('welcome', compact('berita', 'lokasi', 'kelompokData', 'galeri'));
    }

    public function about()
    {
        return view('about');
    }
} 
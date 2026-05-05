<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Lokasi;
use App\Models\Kelompok;
use App\Models\Dokumen;
use App\Models\Galeri;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\Semester;



class HomeController extends Controller
{
    public function index()
    {
        $berita = Berita::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        // filtering lokasi yang memiliki kelompok diperiode yang aktif saja
        $lokasi = lokasi::whereHas('kelompok', function ($query) use ($tahunAktif, $semesterAktif) {
            if ($tahunAktif) {
                $query->where('tahun_akademik_id', $tahunAktif->id);
            }
            if ($semesterAktif) {
                $query->where('semester_id', $semesterAktif->id);
            }
        })->with(['kelompok' => function ($query) use ($tahunAktif, $semesterAktif) {
            if ($tahunAktif) {
                $query->where('tahun_akademik_id', $tahunAktif->id);
            }
            if ($semesterAktif) {
                $query->where('semester_id', $semesterAktif->id);
            }
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

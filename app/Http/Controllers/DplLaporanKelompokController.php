<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use App\Models\LaporanKelompok;
use App\Models\Semester;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class DplLaporanKelompokController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        // Ambil parameter filter dari request
        $tahun_akademik_id = $request->query('tahun_akademik_id', $tahunAktif?->id);
        $semester_id = $request->query('semester_id', $semesterAktif?->id);
        $selected_kelompok_id = $request->query('kelompok_id');

        // 1. Ambil list semua kelompok DPL ini pada periode yang dipilih (UNTUK DROPDOWN)
        $kelompokListQuery = Kelompok::where('dpl_id', $user->id);
        
        if ($tahun_akademik_id) {
            $kelompokListQuery->where('tahun_akademik_id', $tahun_akademik_id);
        }
        if ($semester_id) {
            $kelompokListQuery->where('semester_id', $semester_id);
        }
        
        $kelompokList = $kelompokListQuery->get();

        // 2. Ambil laporan berdasarkan filter
        $laporanQuery = LaporanKelompok::with(['user', 'kelompok'])
            ->whereHas('kelompok', function($q) use ($user, $tahun_akademik_id, $semester_id) {
                $q->where('dpl_id', $user->id);
                if ($tahun_akademik_id) {
                    $q->where('tahun_akademik_id', $tahun_akademik_id);
                }
                if ($semester_id) {
                    $q->where('semester_id', $semester_id);
                }
            });

        // Jika user memilih kelompok tertentu
        if ($selected_kelompok_id) {
            $laporanQuery->where('kelompok_id', $selected_kelompok_id);
        }

        $laporan = $laporanQuery->orderBy('created_at', 'desc')->get();

        // List untuk filter dropdown periode
        $tahunAkademikList = TahunAkademik::all();
        $semesterList = Semester::all();

        return view('dpl.laporan-kelompok.index', compact(
            'kelompokList', 
            'laporan', 
            'tahunAktif', 
            'semesterAktif', 
            'tahunAkademikList', 
            'semesterList', 
            'tahun_akademik_id', 
            'semester_id',
            'selected_kelompok_id'
        ));
    }
}


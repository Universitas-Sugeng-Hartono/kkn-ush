<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\User;
use Illuminate\Http\Request;

class HistoryLogbookController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $tahunAktif = \App\Models\TahunAkademik::getAktif();
        $semesterAktif = \App\Models\Semester::getAktif();

        // Default ke periode aktif jika tidak ada parameter query sama sekali
        if (!$request->has('tahun_akademik_id') && !$request->has('semester_id')) {
            if ($tahunAktif && $semesterAktif) {
                $tahun_akademik_id = $tahunAktif->id;
                $semester_id = $semesterAktif->id;
            } else {
                $tahun_akademik_id = -1;
                $semester_id = -1;
            }
        } else {
            $tahun_akademik_id = $request->query('tahun_akademik_id');
            $semester_id = $request->query('semester_id');
        }
        
        // Query dasar untuk logbook mahasiswa bimbingan DPL
        $query = Logbook::whereHas('user', function($query) use ($user, $tahun_akademik_id, $semester_id) {
            $query->whereHas('kelompok', function($q) use ($user, $tahun_akademik_id, $semester_id) {
                $q->where('dpl_id', $user->id);
                if ($tahun_akademik_id) {
                    $q->where('tahun_akademik_id', $tahun_akademik_id);
                }
                if ($semester_id) {
                    $q->where('semester_id', $semester_id);
                }
            });
        })
        ->with(['user', 'kelompok', 'photos'])
        ->orderBy('created_at', 'desc');

        // Filter berdasarkan jenis kegiatan
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter berdasarkan nama mahasiswa
        if ($request->filled('nama')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->nama . '%');
            });
        }

        // Filter berdasarkan jurusan
        if ($request->filled('jurusan')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('jurusan', $request->jurusan);
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->where('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->where('tanggal', '<=', $request->tanggal_akhir);
        }

        $logbooks = $query->paginate(15);

        // Data untuk filter dropdown
        $jenisKegiatan = ['individu' => 'Individu', 'desa' => 'Desa', 'kecamatan' => 'Kecamatan'];
        $statusList = ['draft' => 'Draft', 'submitted' => 'Submitted', 'approved' => 'Approved', 'rejected' => 'Rejected'];
        $jurusanList = User::whereHas('kelompok', function($q) use ($user) {
            $q->where('dpl_id', $user->id);
        })->distinct()->pluck('jurusan')->filter()->values();

        $tahunAkademikList = \App\Models\TahunAkademik::all();
        $semesterList = \App\Models\Semester::all();

        return view('history.logbooks.index', compact(
            'logbooks', 
            'jenisKegiatan', 
            'statusList', 
            'jurusanList', 
            'tahunAkademikList', 
            'semesterList', 
            'tahun_akademik_id', 
            'semester_id',
            'tahunAktif',
            'semesterAktif'
        ));
    }

    public function show(Logbook $logbook)
    {
        $user = auth()->user();
        
        // Pastikan DPL hanya bisa melihat logbook mahasiswa bimbingannya
        if (!$logbook->user->kelompok || $logbook->user->kelompok->dpl_id != $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat logbook ini.');
        }

        $logbook->load(['user', 'kelompok', 'photos']);
        
        return view('history.logbooks.show', compact('logbook'));
    }
} 
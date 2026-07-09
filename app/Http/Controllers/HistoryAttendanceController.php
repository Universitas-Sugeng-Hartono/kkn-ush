<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;

class HistoryAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $tahunAktif = \App\Models\TahunAkademik::getAktif();
        $semesterAktif = \App\Models\Semester::getAktif();

        // Default ke periode aktif jika tidak ada parameter query sama sekali
        if (!$request->has('tahun_akademik_id') && !$request->has('semester_id') && $tahunAktif && $semesterAktif) {
            $tahun_akademik_id = $tahunAktif->id;
            $semester_id = $semesterAktif->id;
        } else {
            $tahun_akademik_id = $request->query('tahun_akademik_id');
            $semester_id = $request->query('semester_id');
        }

        // Query dasar berdasarkan role user
        if ($user->hasRole('admin')) {
            // Admin melihat semua absensi
            $query = Absensi::whereHas('user', function($q) use ($tahun_akademik_id, $semester_id) {
                if ($tahun_akademik_id) {
                    $q->where('tahun_akademik_id', $tahun_akademik_id);
                }
                if ($semester_id) {
                    $q->where('semester_id', $semester_id);
                }
            })->with(['user', 'kelompok'])
                ->orderBy('created_at', 'desc');
        } elseif ($user->hasRole('dpl')) {
            // DPL melihat absensi mahasiswa bimbingannya
            $query = Absensi::whereHas('user', function($query) use ($user, $tahun_akademik_id, $semester_id) {
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
            ->with(['user', 'kelompok'])
            ->orderBy('created_at', 'desc');
        } else {
            // Mahasiswa melihat absensi mereka sendiri
            $query = Absensi::where('user_id', $user->id)
                ->with(['user', 'kelompok'])
                ->orderBy('created_at', 'desc');
        }

        // Filter berdasarkan nama mahasiswa (hanya untuk DPL dan Admin)
        if (($user->hasRole('dpl') || $user->hasRole('admin')) && $request->filled('nama')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->nama . '%');
            });
        }

        // Filter berdasarkan jurusan (hanya untuk DPL dan Admin)
        if (($user->hasRole('dpl') || $user->hasRole('admin')) && $request->filled('jurusan')) {
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

        // Filter berdasarkan waktu masuk
        if ($request->filled('waktu_mulai')) {
            $query->where('waktu_masuk', '>=', $request->waktu_mulai);
        }

        if ($request->filled('waktu_akhir')) {
            $query->where('waktu_masuk', '<=', $request->waktu_akhir);
        }

        $attendances = $query->paginate(15);

        // Data untuk filter dropdown
        $statusList = ['hadir' => 'Hadir', 'terlambat' => 'Terlambat', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alpha' => 'Alpha'];
        
        // Jurusan list untuk DPL dan Admin
        $jurusanList = collect();
        if ($user->hasRole('dpl')) {
            $jurusanList = User::whereHas('kelompok', function($q) use ($user) {
                $q->where('dpl_id', $user->id);
            })->distinct()->pluck('jurusan')->filter()->values();
        } elseif ($user->hasRole('admin')) {
            $jurusanList = User::distinct()->pluck('jurusan')->filter()->values();
        }

        $tahunAkademikList = \App\Models\TahunAkademik::all();
        $semesterList = \App\Models\Semester::all();

        return view('history.attendances.index', compact(
            'attendances', 
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

    public function show(Absensi $attendance)
    {
        $user = auth()->user();
        
        // Cek akses berdasarkan role
        if ($user->hasRole('admin')) {
            // Admin bisa melihat semua absensi
            // Tidak ada pengecekan tambahan
        } elseif ($user->hasRole('dpl')) {
            // DPL hanya bisa melihat absensi mahasiswa bimbingannya
            if (!$attendance->user->kelompok || $attendance->user->kelompok->dpl_id != $user->id) {
                abort(403, 'Anda tidak memiliki akses untuk melihat absensi ini.');
            }
        } else {
            // Mahasiswa hanya bisa melihat absensi mereka sendiri
            if ($attendance->user_id != $user->id) {
                abort(403, 'Anda tidak memiliki akses untuk melihat absensi ini.');
            }
        }

        $attendance->load(['user', 'kelompok']);
        
        return view('history.attendances.show', compact('attendance'));
    }
} 
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Logbook;
use Carbon\Carbon;

class MonitoringController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:dpl|admin');
    }

    /**
     * Menampilkan detail absensi mahasiswa untuk periode KKN
     */
    public function attendanceDetail(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin');

        $tahun_akademik_id = $request->query('tahun_akademik_id');
        $semester_id = $request->query('semester_id');
        $dpl_id = $request->query('dpl_id');

        // Ambil list tahun akademik & semester aktif (hanya yang aktif untuk dropdown filter)
        $tahunAkademikList = \App\Models\TahunAkademik::where('is_aktif', true)->orderBy('nama', 'desc')->get();
        $semesterList = \App\Models\Semester::where('is_aktif', true)->orderBy('nama', 'asc')->get();
        
        // Ambil list DPL untuk dropdown filter admin
        $dplList = null;
        if ($isAdmin) {
            $dplList = User::role('dpl')->orderBy('name')->get();
        }

        // Ambil mahasiswa yang dibimbing DPL ini atau semua jika admin
        // Coba dulu via kelompok (jika data kelompok sudah ada)
        $mahasiswaQuery = User::role('mahasiswa')
        ->whereHas('kelompok', function($query) use ($user, $isAdmin, $tahun_akademik_id, $semester_id, $dpl_id) {
            if (!$isAdmin) {
                $query->where('dpl_id', $user->id);
            } elseif ($dpl_id) {
                $query->where('dpl_id', $dpl_id);
            }
            if ($tahun_akademik_id) {
                $query->where('tahun_akademik_id', $tahun_akademik_id);
            }
            if ($semester_id) {
                $query->where('semester_id', $semester_id);
            }
        });

        $mahasiswas = $mahasiswaQuery->orderBy('name')->get();

        // Fallback: jika tidak ada kelompok, filter langsung dari kolom user
        if ($mahasiswas->isEmpty()) {
            $fallbackQuery = User::role('mahasiswa');
            if ($tahun_akademik_id) {
                $fallbackQuery->where('tahun_akademik_id', $tahun_akademik_id);
            }
            if ($semester_id) {
                $fallbackQuery->where('semester_id', $semester_id);
            }
            $mahasiswas = $fallbackQuery->orderBy('name')->get();
        }

        // Cari angkatan berdasarkan filter tahun akademik & semester
        // Cari angkatan yang sesuai
        $angkatan = null;
        if ($tahun_akademik_id && $semester_id) {
            $angkatan = \App\Models\Angkatan::where('tahun_akademik_id', $tahun_akademik_id)
                ->where('semester_id', $semester_id)
                ->first();
        } else {
            // Coba ambil dari tahun/semester aktif pertama
            $taAktif = \App\Models\TahunAkademik::getAktif();
            $semAktif = \App\Models\Semester::getAktif();
            if ($taAktif && $semAktif) {
                $angkatan = \App\Models\Angkatan::where('tahun_akademik_id', $taAktif->id)
                    ->where('semester_id', $semAktif->id)
                    ->first();
            }
        }

        if (!$angkatan) {
            $angkatan = \App\Models\Angkatan::latest('id')->first();
        }

        if ($angkatan && $angkatan->tanggal_mulai && $angkatan->tanggal_selesai) {
            $startDate = Carbon::parse($angkatan->tanggal_mulai);
            $endDate = Carbon::parse($angkatan->tanggal_selesai);
        } else {
            // Fallback default jika tidak ada data angkatan di database
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        }
        
        $days = collect();
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $days->push($currentDate->format('Y-m-d'));
            $currentDate->addDay();
        }

        // Ambil data attendance untuk periode KKN
        $attendances = Absensi::whereIn('user_id', $mahasiswas->pluck('id'))
            ->whereBetween('tanggal', [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d')
            ])
            ->get()
            ->groupBy('user_id');

        // Buat array untuk tracking kehadiran
        $attendanceData = [];
        foreach ($mahasiswas as $mahasiswa) {
            $attendanceData[$mahasiswa->id] = [
                'mahasiswa' => $mahasiswa,
                'days' => []
            ];
            
            foreach ($days as $day) {
                $hasAttendance = null;
                if (isset($attendances[$mahasiswa->id])) {
                    foreach ($attendances[$mahasiswa->id] as $attendance) {
                        if (Carbon::parse($attendance->tanggal)->format('Y-m-d') === $day) {
                            $hasAttendance = $attendance;
                            break;
                        }
                    }
                }
                
                $attendanceData[$mahasiswa->id]['days'][$day] = [
                    'date' => Carbon::parse($day),
                    'status' => $hasAttendance ? $hasAttendance->status : null,
                    'attendance' => $hasAttendance
                ];
            }
        }

        return view('monitoring.attendance-detail', compact(
            'attendanceData', 
            'days', 
            'tahunAkademikList', 
            'semesterList', 
            'tahun_akademik_id', 
            'semester_id',
            'startDate',
            'endDate',
            'dplList',
            'dpl_id'
        ));
    }

    /**
     * Menampilkan detail logbook mahasiswa untuk periode KKN
     */
    public function logbookDetail(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin');

        $tahun_akademik_id = $request->query('tahun_akademik_id');
        $semester_id = $request->query('semester_id');
        $dpl_id = $request->query('dpl_id');
        $tipe = $request->query('tipe', 'individu'); // 'individu' atau 'kelompok'

        // Ambil list tahun akademik & semester aktif (hanya yang aktif untuk dropdown filter)
        $tahunAkademikList = \App\Models\TahunAkademik::where('is_aktif', true)->orderBy('nama', 'desc')->get();
        $semesterList = \App\Models\Semester::where('is_aktif', true)->orderBy('nama', 'asc')->get();

        // Ambil list DPL untuk dropdown filter admin
        $dplList = null;
        if ($isAdmin) {
            $dplList = User::role('dpl')->orderBy('name')->get();
        }

        // Ambil mahasiswa yang dibimbing DPL ini atau semua jika admin
        // Coba dulu via kelompok (jika data kelompok sudah ada)
        $mahasiswaQuery = User::role('mahasiswa')
        ->with(['kelompok'])
        ->whereHas('kelompok', function($query) use ($user, $isAdmin, $tahun_akademik_id, $semester_id, $dpl_id) {
            if (!$isAdmin) {
                $query->where('dpl_id', $user->id);
            } elseif ($dpl_id) {
                $query->where('dpl_id', $dpl_id);
            }
            if ($tahun_akademik_id) {
                $query->where('tahun_akademik_id', $tahun_akademik_id);
            }
            if ($semester_id) {
                $query->where('semester_id', $semester_id);
            }
        });

        $mahasiswas = $mahasiswaQuery->orderBy('name')->get();

        // Fallback: jika tidak ada kelompok, filter langsung dari kolom user
        if ($mahasiswas->isEmpty()) {
            $fallbackQuery = User::role('mahasiswa')->with(['kelompok']);
            if ($tahun_akademik_id) {
                $fallbackQuery->where('tahun_akademik_id', $tahun_akademik_id);
            }
            if ($semester_id) {
                $fallbackQuery->where('semester_id', $semester_id);
            }
            $mahasiswas = $fallbackQuery->orderBy('name')->get();
        }

        // Cari angkatan berdasarkan filter tahun akademik & semester
        // Cari angkatan yang sesuai
        $angkatan = null;
        if ($tahun_akademik_id && $semester_id) {
            $angkatan = \App\Models\Angkatan::where('tahun_akademik_id', $tahun_akademik_id)
                ->where('semester_id', $semester_id)
                ->first();
        } else {
            // Coba ambil dari tahun/semester aktif pertama
            $taAktif = \App\Models\TahunAkademik::getAktif();
            $semAktif = \App\Models\Semester::getAktif();
            if ($taAktif && $semAktif) {
                $angkatan = \App\Models\Angkatan::where('tahun_akademik_id', $taAktif->id)
                    ->where('semester_id', $semAktif->id)
                    ->first();
            }
        }

        if (!$angkatan) {
            $angkatan = \App\Models\Angkatan::latest('id')->first();
        }

        if ($angkatan && $angkatan->tanggal_mulai && $angkatan->tanggal_selesai) {
            $startDate = Carbon::parse($angkatan->tanggal_mulai);
            $endDate = Carbon::parse($angkatan->tanggal_selesai);
        } else {
            // Fallback default jika tidak ada data angkatan di database
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        }
        
        $days = collect();
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $days->push($currentDate->format('Y-m-d'));
            $currentDate->addDay();
        }

        // Ambil data logbook berdasarkan tipe (individu atau kelompok)
        if ($tipe === 'kelompok') {
            $kelompokIds = $mahasiswas->pluck('kelompok_id')->unique()->filter();
            $baseQuery = Logbook::whereIn('kelompok_id', $kelompokIds)
                ->where('is_kelompok', true)
                ->whereBetween('tanggal', [
                    $startDate->format('Y-m-d'),
                    $endDate->format('Y-m-d')
                ]);
        } else {
            $baseQuery = Logbook::whereIn('user_id', $mahasiswas->pluck('id'))
                ->where('is_kelompok', false)
                ->whereBetween('tanggal', [
                    $startDate->format('Y-m-d'),
                    $endDate->format('Y-m-d')
                ]);
        }

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->whereIn('status', ['submitted', 'pending'])->count(),
            'approved' => (clone $baseQuery)->where('status', 'approved')->count(),
            'rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
            'mahasiswa' => $mahasiswas->count(),
            'hari_kkn' => $startDate->diffInDays($endDate) + 1
        ];

        $logbooks = (clone $baseQuery)
            ->with(['user', 'kelompok', 'photos'])
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        return view('monitoring.logbook-detail', compact(
            'logbooks',
            'stats',
            'tahunAkademikList',
            'semesterList',
            'tahun_akademik_id',
            'semester_id',
            'startDate',
            'endDate',
            'dplList',
            'dpl_id',
            'tipe'
        ));
    }

    /**
     * Menampilkan gabungan absensi dan logbook dalam satu view untuk periode KKN
     */
    public function activityDetail(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin');
        
        $tahun_akademik_id = $request->query('tahun_akademik_id');
        $semester_id = $request->query('semester_id');
        $dpl_id = $request->query('dpl_id');
        $tipe = $request->query('tipe', 'individu'); // 'individu' atau 'kelompok'

        // Ambil list tahun akademik & semester aktif (hanya yang aktif untuk dropdown filter)
        $tahunAkademikList = \App\Models\TahunAkademik::where('is_aktif', true)->orderBy('nama', 'desc')->get();
        $semesterList = \App\Models\Semester::where('is_aktif', true)->orderBy('nama', 'asc')->get();

        // Ambil list DPL untuk dropdown filter admin
        $dplList = null;
        if ($isAdmin) {
            $dplList = User::role('dpl')->orderBy('name')->get();
        }

        // Ambil mahasiswa yang dibimbing DPL ini atau semua jika admin
        $mahasiswaQuery = User::role('mahasiswa')
        ->with(['kelompok'])
        ->whereHas('kelompok', function($query) use ($user, $isAdmin, $tahun_akademik_id, $semester_id, $dpl_id) {
            if (!$isAdmin) {
                $query->where('dpl_id', $user->id);
            } elseif ($dpl_id) {
                $query->where('dpl_id', $dpl_id);
            }
            
            if ($tahun_akademik_id) {
                $query->where('tahun_akademik_id', $tahun_akademik_id);
            }
            if ($semester_id) {
                $query->where('semester_id', $semester_id);
            }
        });

        $mahasiswas = $mahasiswaQuery->orderBy('name')->get();

        // Cari angkatan yang sesuai
        $angkatan = null;
        if ($tahun_akademik_id && $semester_id) {
            $angkatan = \App\Models\Angkatan::where('tahun_akademik_id', $tahun_akademik_id)
                ->where('semester_id', $semester_id)
                ->first();
        } else {
            // Coba ambil dari tahun/semester aktif pertama
            $taAktif = \App\Models\TahunAkademik::getAktif();
            $semAktif = \App\Models\Semester::getAktif();
            if ($taAktif && $semAktif) {
                $angkatan = \App\Models\Angkatan::where('tahun_akademik_id', $taAktif->id)
                    ->where('semester_id', $semAktif->id)
                    ->first();
            }
        }

        if (!$angkatan) {
            $angkatan = \App\Models\Angkatan::latest('id')->first();
        }

        if ($angkatan && $angkatan->tanggal_mulai && $angkatan->tanggal_selesai) {
            $startDate = Carbon::parse($angkatan->tanggal_mulai);
            $endDate = Carbon::parse($angkatan->tanggal_selesai);
        } else {
            // Fallback default jika tidak ada data angkatan di database
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        }
        
        $days = collect();
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $days->push($currentDate->format('Y-m-d'));
            $currentDate->addDay();
        }

        // Ambil data absensi untuk periode KKN
        $attendances = Absensi::whereIn('user_id', $mahasiswas->pluck('id'))
            ->whereBetween('tanggal', [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d')
            ])
            ->get()
            ->groupBy('user_id');

        // Ambil data logbook berdasarkan tipe (individu atau kelompok)
        if ($tipe === 'kelompok') {
            $kelompokIds = $mahasiswas->pluck('kelompok_id')->unique()->filter();
            $logbooks = Logbook::whereIn('kelompok_id', $kelompokIds)
                ->where('is_kelompok', true)
                ->whereBetween('tanggal', [
                    $startDate->format('Y-m-d'),
                    $endDate->format('Y-m-d')
                ])
                ->get()
                ->groupBy('kelompok_id');
        } else {
            $logbooks = Logbook::whereIn('user_id', $mahasiswas->pluck('id'))
                ->where('is_kelompok', false)
                ->whereBetween('tanggal', [
                    $startDate->format('Y-m-d'),
                    $endDate->format('Y-m-d')
                ])
                ->get()
                ->groupBy('user_id');
        }

        // Buat array gabungan
        $activityData = [];
        foreach ($mahasiswas as $mahasiswa) {
            $activityData[$mahasiswa->id] = [
                'mahasiswa' => $mahasiswa,
                'days' => []
            ];
            
            foreach ($days as $day) {
                $hasAttendance = isset($attendances[$mahasiswa->id]) 
                    ? $attendances[$mahasiswa->id]->where('tanggal', $day)->first()
                    : null;

                // Cek logbook sesuai tipe
                $hasLogbook = null;
                if ($tipe === 'kelompok') {
                    $kelompokId = $mahasiswa->kelompok_id;
                    if ($kelompokId && isset($logbooks[$kelompokId])) {
                        $hasLogbook = $logbooks[$kelompokId]->where('tanggal', $day)->first();
                    }
                } else {
                    if (isset($logbooks[$mahasiswa->id])) {
                        $hasLogbook = $logbooks[$mahasiswa->id]->where('tanggal', $day)->first();
                    }
                }
                
                $activityData[$mahasiswa->id]['days'][$day] = [
                    'date' => Carbon::parse($day),
                    'attendance' => $hasAttendance,
                    'attendance_status' => $hasAttendance ? $hasAttendance->status : null,
                    'logbook' => $hasLogbook,
                    'logbook_status' => $hasLogbook ? $hasLogbook->status : null
                ];
            }
        }

        return view('monitoring.activity-detail', compact(
            'activityData', 
            'days',
            'tahunAkademikList',
            'semesterList',
            'tahun_akademik_id',
            'semester_id',
            'startDate',
            'endDate',
            'dplList',
            'dpl_id',
            'tipe'
        ));
    }
}
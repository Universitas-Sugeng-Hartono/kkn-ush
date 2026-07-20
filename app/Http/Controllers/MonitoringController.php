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

        $tahunAktif = \App\Models\TahunAkademik::getAktif();
        $semesterAktif = \App\Models\Semester::getAktif();

        // Default ke periode aktif jika tidak ada parameter query
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
        $dpl_id = $request->query('dpl_id');

        // Ambil list tahun akademik & semester (semua, bukan hanya aktif)
        $tahunAkademikList = \App\Models\TahunAkademik::orderBy('nama', 'desc')->get();
        $semesterList = \App\Models\Semester::orderBy('nama', 'asc')->get();
        
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

        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);

        if ($search) {
            $mahasiswaQuery->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('nim', 'like', '%' . $search . '%');
            });
        }

        $mahasiswas = $mahasiswaQuery->orderBy('name')->paginate($perPage)->withQueryString();

        // Fallback: jika tidak ada kelompok, filter langsung dari kolom user
        if ($mahasiswas->isEmpty() && !$search) {
            $fallbackQuery = User::role('mahasiswa');
            if ($tahun_akademik_id) {
                $fallbackQuery->where('tahun_akademik_id', $tahun_akademik_id);
            }
            if ($semester_id) {
                $fallbackQuery->where('semester_id', $semester_id);
            }
            if ($search) {
                $fallbackQuery->where(function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('nim', 'like', '%' . $search . '%');
                });
            }
            $mahasiswas = $fallbackQuery->orderBy('name')->paginate($perPage)->withQueryString();
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
            ->get();

        // Optimasi: Kelompokkan berdasarkan user_id lalu tanggal
        $attendancesGrouped = [];
        foreach ($attendances as $attendance) {
            $dateStr = is_string($attendance->tanggal) ? substr($attendance->tanggal, 0, 10) : $attendance->tanggal->format('Y-m-d');
            $attendancesGrouped[$attendance->user_id][$dateStr] = $attendance;
        }

        // Optimasi: Parse object date hanya sekali
        $daysData = [];
        foreach ($days as $day) {
            $daysData[$day] = Carbon::parse($day);
        }

        // Buat array untuk tracking kehadiran
        $attendanceData = [];
        foreach ($mahasiswas as $mahasiswa) {
            $attendanceData[$mahasiswa->id] = [
                'mahasiswa' => $mahasiswa,
                'days' => []
            ];
            
            foreach ($days as $day) {
                $hasAttendance = $attendancesGrouped[$mahasiswa->id][$day] ?? null;
                
                $attendanceData[$mahasiswa->id]['days'][$day] = [
                    'date' => $daysData[$day],
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
            'dpl_id',
            'mahasiswas'
        ));
    }

    /**
     * Menampilkan detail logbook mahasiswa untuk periode KKN
     */
    public function logbookDetail(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin');

        $tahunAktif = \App\Models\TahunAkademik::getAktif();
        $semesterAktif = \App\Models\Semester::getAktif();

        // Default ke periode aktif jika tidak ada parameter query
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
        $dpl_id = $request->query('dpl_id');
        $tipe = $request->query('tipe', 'individu'); // 'individu' atau 'kelompok'

        // Ambil list tahun akademik & semester (semua, bukan hanya aktif)
        $tahunAkademikList = \App\Models\TahunAkademik::orderBy('nama', 'desc')->get();
        $semesterList = \App\Models\Semester::orderBy('nama', 'asc')->get();

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
        $logbooksGrouped = [];
        if ($tipe === 'kelompok') {
            $kelompokIds = $mahasiswas->pluck('kelompok_id')->unique()->filter();
            $baseQuery = Logbook::whereIn('kelompok_id', $kelompokIds)
                ->where('is_kelompok', true)
                ->whereBetween('tanggal', [
                    $startDate->format('Y-m-d'),
                    $endDate->format('Y-m-d')
                ]);
            $logbooks = (clone $baseQuery)->get();
            foreach ($logbooks as $logbook) {
                $dateStr = is_string($logbook->tanggal) ? substr($logbook->tanggal, 0, 10) : $logbook->tanggal->format('Y-m-d');
                $logbooksGrouped[$logbook->kelompok_id][$dateStr] = $logbook;
            }
        } else {
            $baseQuery = Logbook::whereIn('user_id', $mahasiswas->pluck('id'))
                ->where('is_kelompok', false)
                ->whereBetween('tanggal', [
                    $startDate->format('Y-m-d'),
                    $endDate->format('Y-m-d')
                ]);
            $logbooks = (clone $baseQuery)->get();
            foreach ($logbooks as $logbook) {
                $dateStr = is_string($logbook->tanggal) ? substr($logbook->tanggal, 0, 10) : $logbook->tanggal->format('Y-m-d');
                $logbooksGrouped[$logbook->user_id][$dateStr] = $logbook;
            }
        }

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->whereIn('status', ['submitted', 'pending'])->count(),
            'approved' => (clone $baseQuery)->where('status', 'approved')->count(),
            'rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
            'mahasiswa' => $mahasiswas->count(),
            'hari_kkn' => $startDate->diffInDays($endDate) + 1
        ];

        $search = $request->query('search');
        $perPage = $request->query('per_page', 15);

        if ($search) {
            $baseQuery->where(function($q) use ($search, $tipe) {
                if ($tipe === 'kelompok') {
                    $q->whereHas('kelompok', function($kq) use ($search) {
                        $kq->where('nama', 'like', '%' . $search . '%');
                    });
                } else {
                    $q->whereHas('user', function($uq) use ($search) {
                        $uq->where('name', 'like', '%' . $search . '%')
                          ->orWhere('nim', 'like', '%' . $search . '%');
                    });
                }
            });
        }

        $logbooks = (clone $baseQuery)
            ->with(['user', 'kelompok', 'photos'])
            ->orderBy('tanggal', 'desc')
            ->paginate($perPage)
            ->withQueryString();

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
        
        $tahunAktif = \App\Models\TahunAkademik::getAktif();
        $semesterAktif = \App\Models\Semester::getAktif();

        // Default ke periode aktif jika tidak ada parameter query
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
        $dpl_id = $request->query('dpl_id');
        $tipe = $request->query('tipe', 'individu'); // 'individu' atau 'kelompok'

        // Ambil list tahun akademik & semester (semua, bukan hanya aktif)
        $tahunAkademikList = \App\Models\TahunAkademik::orderBy('nama', 'desc')->get();
        $semesterList = \App\Models\Semester::orderBy('nama', 'asc')->get();

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
            ->get();

        $attendancesGrouped = [];
        foreach ($attendances as $attendance) {
            $dateStr = is_string($attendance->tanggal) ? substr($attendance->tanggal, 0, 10) : $attendance->tanggal->format('Y-m-d');
            $attendancesGrouped[$attendance->user_id][$dateStr] = $attendance;
        }

        // Ambil data logbook berdasarkan tipe (individu atau kelompok)
        $logbooksGrouped = [];
        if ($tipe === 'kelompok') {
            $kelompokIds = $mahasiswas->pluck('kelompok_id')->unique()->filter();
            $logbooks = Logbook::whereIn('kelompok_id', $kelompokIds)
                ->where('is_kelompok', true)
                ->whereBetween('tanggal', [
                    $startDate->format('Y-m-d'),
                    $endDate->format('Y-m-d')
                ])
                ->get();
            foreach ($logbooks as $logbook) {
                $dateStr = is_string($logbook->tanggal) ? substr($logbook->tanggal, 0, 10) : $logbook->tanggal->format('Y-m-d');
                $logbooksGrouped[$logbook->kelompok_id][$dateStr] = $logbook;
            }
        } else {
            $logbooks = Logbook::whereIn('user_id', $mahasiswas->pluck('id'))
                ->where('is_kelompok', false)
                ->whereBetween('tanggal', [
                    $startDate->format('Y-m-d'),
                    $endDate->format('Y-m-d')
                ])
                ->get();
            foreach ($logbooks as $logbook) {
                $dateStr = is_string($logbook->tanggal) ? substr($logbook->tanggal, 0, 10) : $logbook->tanggal->format('Y-m-d');
                $logbooksGrouped[$logbook->user_id][$dateStr] = $logbook;
            }
        }

        // Optimasi: Parse object date hanya sekali
        $daysData = [];
        foreach ($days as $day) {
            $daysData[$day] = Carbon::parse($day);
        }

        // Buat array gabungan
        $activityData = [];
        foreach ($mahasiswas as $mahasiswa) {
            $activityData[$mahasiswa->id] = [
                'mahasiswa' => $mahasiswa,
                'days' => []
            ];
            
            foreach ($days as $day) {
                $hasAttendance = $attendancesGrouped[$mahasiswa->id][$day] ?? null;

                // Cek logbook sesuai tipe
                $hasLogbook = null;
                if ($tipe === 'kelompok') {
                    $kelompokId = $mahasiswa->kelompok_id;
                    if ($kelompokId) {
                        $hasLogbook = $logbooksGrouped[$kelompokId][$day] ?? null;
                    }
                } else {
                    $hasLogbook = $logbooksGrouped[$mahasiswa->id][$day] ?? null;
                }
                
                $activityData[$mahasiswa->id]['days'][$day] = [
                    'date' => $daysData[$day],
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
            'tipe',
            'mahasiswas'
        ));
    }
}
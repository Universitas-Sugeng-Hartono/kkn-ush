<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Logbook;
use App\Models\Absensi;
use App\Models\Kelompok;
use App\Models\Pengaduan;
use App\Models\Semester;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $data = [];

        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        if ($user->hasRole('admin')) {
            $data = $this->getAdminDashboardData($tahunAktif, $semesterAktif);
            return view('dashboard', compact('data', 'tahunAktif', 'semesterAktif'));
        } elseif ($user->hasRole('dpl')) {
            $data = $this->getDplDashboardData($user, $tahunAktif, $semesterAktif);
            return view('dashboard', compact('data', 'tahunAktif', 'semesterAktif'));
        } elseif ($user->hasRole('mahasiswa')) {
            $data = $this->getMahasiswaDashboardData($user);
            
            // Cek apakah user memaksa mode desktop
            $forceDesktop = $request->query('mode') === 'desktop';
            
            // Deteksi device untuk mahasiswa
            $isMobile = session('is_mobile_device', false) && !$forceDesktop;
            
            // Jika belum ada deteksi device, coba deteksi dari User-Agent
            if (!$isMobile && !session('device_info')) {
                $userAgent = $request->header('User-Agent');
                $isMobile = $this->detectMobileFromUserAgent($userAgent);
                
                // Store device info in session
                session(['is_mobile_device' => $isMobile]);
                session(['device_info' => [
                    'is_mobile' => $isMobile,
                    'is_tablet' => false,
                    'is_desktop' => !$isMobile,
                    'screen_width' => 0,
                    'screen_height' => 0,
                    'user_agent' => $userAgent,
                    'detected_at' => now()
                ]]);
            }
            
            if ($isMobile) {
                // Get group information
                $group = null;
                $dpl = null;
                $groupMembers = collect();
                
                if ($user->kelompok_id) {
                    $group = $user->kelompok;
                    if ($group) {
                        $dpl = $group->dpl;
                        $groupMembers = $group->mahasiswa()->where('id', '!=', $user->id)->get();
                    }
                }
                
                // Tampilkan view mobile untuk mahasiswa
                return view('mobile.dashboard', [
                    'totalLogbooks' => $data['logbook_total'],
                    'totalAttendance' => $data['absensi_total'],
                    'pendingLogbooks' => $data['logbook_pending'],
                    'pendingAttendance' => $data['absensi_pending'],
                    'recentActivities' => $this->getRecentActivities($user),
                    'group' => $group,
                    'dpl' => $dpl,
                    'groupMembers' => $groupMembers,
                    'tahunAktif' => $tahunAktif,
                    'semesterAktif' => $semesterAktif,
                ]);
            } else {
                // Tampilkan view desktop untuk mahasiswa
                return view('dashboard', compact('data', 'tahunAktif', 'semesterAktif'));
            }
        }

        return view('dashboard', compact('data', 'tahunAktif', 'semesterAktif'));
    }

    /**
     * Detect mobile device from User-Agent string
     */
    private function detectMobileFromUserAgent($userAgent)
    {
        if (empty($userAgent)) {
            return false;
        }

        $mobileKeywords = [
            'Mobile', 'Android', 'iPhone', 'iPad', 'Windows Phone', 'BlackBerry',
            'Opera Mini', 'IEMobile', 'Mobile Safari', 'Mobile Chrome'
        ];

        foreach ($mobileKeywords as $keyword) {
            if (stripos($userAgent, $keyword) != false) {
                return true;
            }
        }

        return false;
    }

    private function getAdminDashboardData($tahunAktif = null, $semesterAktif = null)
    {
        $kelompokIds = null;
        if ($tahunAktif && $semesterAktif) {
            $kelompokIds = Kelompok::where('tahun_akademik_id', $tahunAktif->id)
                ->where('semester_id', $semesterAktif->id)
                ->pluck('id');
        }

        return [
            'total_mahasiswa' => User::role('mahasiswa')
                ->when($tahunAktif && $semesterAktif, function ($q) use ($tahunAktif, $semesterAktif) {
                    $q->where('tahun_akademik_id', $tahunAktif->id)->where('semester_id', $semesterAktif->id);
                })
                ->count(),
            'total_dpl' => User::role('dpl')->count(),
            'total_kelompok' => Kelompok::when($kelompokIds, fn ($q) => $q->whereIn('id', $kelompokIds))->count(),
            'total_logbook' => Logbook::when($kelompokIds, fn ($q) => $q->whereIn('kelompok_id', $kelompokIds))->count(),
            'total_absensi' => Absensi::when($kelompokIds, fn ($q) => $q->whereIn('kelompok_id', $kelompokIds))->count(),
            'pengaduan_baru' => Pengaduan::where('status', 'pending')->count(),
            'logbook_stats' => $this->getLogbookStats(),
            'absensi_stats' => $this->getAbsensiStats(),
        ];
    }

    private function getDplDashboardData($user, $tahunAktif = null, $semesterAktif = null)
    {

        // Ambil kelompok yang dibimbing oleh dosen ini
        $groups = Kelompok::where('dpl_id', $user->id)
            ->when($tahunAktif && $semesterAktif, function($q) use ($tahunAktif, $semesterAktif) {
                return $q->where('tahun_akademik_id', $tahunAktif->id)
                    ->where('semester_id', $semesterAktif->id);
            })
            ->with(['mahasiswa', 'logbooks', 'absensi'])
            ->get();
        
        // Hitung statistik
        $totalMahasiswa = $groups->sum(function($group) { return $group->mahasiswa->count(); });
        $totalLogbook = $groups->sum(function($group) { return $group->logbooks->count(); });
        $logbookPending = $groups->sum(function($group) { 
            return $group->logbooks->where('status', 'submitted')->count(); 
        });
        $totalAbsensi = $groups->sum(function($group) { return $group->absensi->count(); });
        $absensiPending = $groups->sum(function($group) { 
            return $group->absensi->where('status', 'pending')->count(); 
        });

        // Logbook yang perlu direview hari ini
        $logbookToday = Logbook::whereIn('kelompok_id', $groups->pluck('id'))
            ->where('status', 'submitted')
            ->whereDate('created_at', today())
            ->count();

        // Absensi yang perlu divalidasi hari ini
        $absensiToday = Absensi::whereIn('kelompok_id', $groups->pluck('id'))
            ->where('status', 'pending')
            ->whereDate('created_at', today())
            ->count();

        // Mahasiswa yang belum submit logbook hari ini
        $mahasiswaNoLogbook = $groups->sum(function($group) {
            return $group->mahasiswa->filter(function($mhs) {
                return !$mhs->logbooks()->whereDate('created_at', today())->exists();
            })->count();
        });

        // Mahasiswa yang belum absen hari ini
        $mahasiswaNoAbsensi = $groups->sum(function($group) {
            return $group->mahasiswa->filter(function($mhs) {
                return !$mhs->absensi()->whereDate('created_at', today())->exists();
            })->count();
        });

        return [
            'total_mahasiswa' => $totalMahasiswa,
            'total_kelompok' => $groups->count(),
            'total_logbook' => $totalLogbook,
            'logbook_pending' => $logbookPending,
            'total_absensi' => $totalAbsensi,
            'absensi_pending' => $absensiPending,
            'logbook_today' => $logbookToday,
            'absensi_today' => $absensiToday,
            'mahasiswa_no_logbook' => $mahasiswaNoLogbook,
            'mahasiswa_no_absensi' => $mahasiswaNoAbsensi,
            'groups' => $groups,
            'recent_logbooks' => Logbook::whereIn('kelompok_id', $groups->pluck('id'))
                ->with(['user', 'kelompok'])
                ->latest()
                ->take(5)
                ->get(),
            'recent_absensi' => Absensi::whereIn('kelompok_id', $groups->pluck('id'))
                ->with(['user', 'kelompok'])
                ->latest()
                ->take(5)
                ->get(),
            'logbook_stats' => $this->getDplLogbookStats($groups),
            'absensi_stats' => $this->getDplAbsensiStats($groups),
        ];
    }

    private function getDplLogbookStats($groups)
    {
        $logbooks = Logbook::whereIn('kelompok_id', $groups->pluck('id'))
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->get();

        $stats = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = $logbooks->filter(function($logbook) use ($date) {
                return $logbook->created_at->format('Y-m-d') === $date;
            })->count();
            
            $stats[] = [
                'date' => $date,
                'count' => $count
            ];
        }

        return $stats;
    }

    private function getDplAbsensiStats($groups)
    {
        $absensi = Absensi::whereIn('kelompok_id', $groups->pluck('id'))
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->get();

        $stats = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = $absensi->filter(function($attendance) use ($date) {
                return $attendance->created_at->format('Y-m-d') === $date;
            })->count();
            
            $stats[] = [
                'date' => $date,
                'count' => $count
            ];
        }

        return $stats;
    }

    // Method untuk mendapatkan notifikasi dosen
    public function getNotifications()
    {
        $user = auth()->user();
        
        if (!$user->hasRole('dpl')) {
            return response()->json([]);
        }

        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        $groupsQuery = Kelompok::where('dpl_id', $user->id);
        if ($tahunAktif && $semesterAktif) {
            $groupsQuery->where('tahun_akademik_id', $tahunAktif->id)
                        ->where('semester_id', $semesterAktif->id);
        }
        $groups = $groupsQuery->pluck('id');
        
        $notifications = [];

        // Logbook yang perlu direview
        $pendingLogbooks = Logbook::whereIn('kelompok_id', $groups)
            ->where('status', 'submitted')
            ->with(['user', 'kelompok'])
            ->latest()
            ->take(10)
            ->get();

        foreach ($pendingLogbooks as $logbook) {
            $notifications[] = [
                'type' => 'logbook_pending',
                'title' => 'Logbook Perlu Direview',
                'message' => "Logbook dari {$logbook->user->name} ({$logbook->kelompok->nama}) perlu direview",
                'data' => [
                    'logbook_id' => $logbook->id,
                    'user_name' => $logbook->user->name,
                    'group_name' => $logbook->kelompok->nama,
                    'created_at' => $logbook->created_at->diffForHumans()
                ],
                'created_at' => $logbook->created_at
            ];
        }

        // Absensi yang perlu divalidasi
        $pendingAbsensi = Absensi::whereIn('kelompok_id', $groups)
            ->where('status', 'pending')
            ->with(['user', 'kelompok'])
            ->latest()
            ->take(10)
            ->get();

        foreach ($pendingAbsensi as $absensi) {
            $notifications[] = [
                'type' => 'absensi_pending',
                'title' => 'Absensi Perlu Divalidasi',
                'message' => "Absensi dari {$absensi->user->name} ({$absensi->kelompok->nama}) perlu divalidasi",
                'data' => [
                    'absensi_id' => $absensi->id,
                    'user_name' => $absensi->user->name,
                    'group_name' => $absensi->kelompok->nama,
                    'tanggal' => $absensi->tanggal->format('d/m/Y'),
                    'created_at' => $absensi->created_at->diffForHumans()
                ],
                'created_at' => $absensi->created_at
            ];
        }

        // Mahasiswa yang belum submit logbook hari ini
        $mahasiswaNoLogbook = User::role('mahasiswa')
            ->whereIn('kelompok_id', $groups)
            ->whereDoesntHave('logbooks', function($query) {
                $query->whereDate('created_at', today());
            })
            ->with('kelompok')
            ->get();

        foreach ($mahasiswaNoLogbook as $mhs) {
            $notifications[] = [
                'type' => 'no_logbook_today',
                'title' => 'Mahasiswa Belum Submit Logbook',
                'message' => "{$mhs->name} ({$mhs->kelompok->nama}) belum submit logbook hari ini",
                'data' => [
                    'user_id' => $mhs->id,
                    'user_name' => $mhs->name,
                    'group_name' => $mhs->kelompok->nama,
                    'nim' => $mhs->nim
                ],
                'created_at' => now()
            ];
        }

        // Mahasiswa yang belum absen hari ini
        $mahasiswaNoAbsensi = User::role('mahasiswa')
            ->whereIn('kelompok_id', $groups)
            ->whereDoesntHave('absensi', function($query) {
                $query->whereDate('created_at', today());
            })
            ->with('kelompok')
            ->get();

        foreach ($mahasiswaNoAbsensi as $mhs) {
            $notifications[] = [
                'type' => 'no_absensi_today',
                'title' => 'Mahasiswa Belum Absen',
                'message' => "{$mhs->name} ({$mhs->kelompok->nama}) belum melakukan absensi hari ini",
                'data' => [
                    'user_id' => $mhs->id,
                    'user_name' => $mhs->name,
                    'group_name' => $mhs->kelompok->nama,
                    'nim' => $mhs->nim
                ],
                'created_at' => now()
            ];
        }

        // Urutkan berdasarkan waktu terbaru
        usort($notifications, function($a, $b) {
            return $b['created_at']->timestamp - $a['created_at']->timestamp;
        });

        return response()->json($notifications);
    }

    // Method untuk mendapatkan alert/peringatan
    public function getAlerts()
    {
        $user = auth()->user();
        
        if (!$user->hasRole('dpl')) {
            return response()->json([]);
        }

        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        $groupsQuery = Kelompok::where('dpl_id', $user->id);
        if ($tahunAktif && $semesterAktif) {
            $groupsQuery->where('tahun_akademik_id', $tahunAktif->id)
                        ->where('semester_id', $semesterAktif->id);
        }
        $groups = $groupsQuery->pluck('id');
        
        $alerts = [];

        // Logbook yang pending lebih dari 3 hari
        $oldPendingLogbooks = Logbook::whereIn('kelompok_id', $groups)
            ->where('status', 'submitted')
            ->where('created_at', '<', now()->subDays(3))
            ->count();

        if ($oldPendingLogbooks > 0) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Logbook Pending Lama',
                'message' => "Ada {$oldPendingLogbooks} logbook yang pending lebih dari 3 hari",
                'action' => 'review_logbooks'
            ];
        }

        // Absensi yang pending lebih dari 1 hari
        $oldPendingAbsensi = Absensi::whereIn('kelompok_id', $groups)
            ->where('status', 'pending')
            ->where('created_at', '<', now()->subDay())
            ->count();

        if ($oldPendingAbsensi > 0) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Absensi Pending Lama',
                'message' => "Ada {$oldPendingAbsensi} absensi yang pending lebih dari 1 hari",
                'action' => 'validate_absensi'
            ];
        }

        $startDate = '2025-08-04';
        $endDate = '2025-08-26';
        if ($tahunAktif && $semesterAktif) {
            $angkatan = \App\Models\Angkatan::where('tahun_akademik_id', $tahunAktif->id)
                ->where('semester_id', $semesterAktif->id)
                ->first();
            if ($angkatan && $angkatan->tanggal_mulai && $angkatan->tanggal_selesai) {
                $startDate = $angkatan->tanggal_mulai->format('Y-m-d');
                $endDate = $angkatan->tanggal_selesai->format('Y-m-d');
            }
        }

        // Mahasiswa yang tidak aktif (tidak ada logbook dalam periode KKN)
        $inactiveMahasiswaLogbook = User::role('mahasiswa')
            ->whereIn('kelompok_id', $groups)
            ->whereDoesntHave('logbooks', function($query) use ($startDate, $endDate) {
                $query->where('tanggal', '>=', $startDate)
                      ->where('tanggal', '<=', $endDate);
            })
            ->count();

        if ($inactiveMahasiswaLogbook > 0) {
            $alerts[] = [
                'type' => 'danger',
                'title' => 'Mahasiswa Tidak Aktif',
                'message' => "Ada {$inactiveMahasiswaLogbook} mahasiswa yang tidak submit logbook dalam periode KKN",
                'action' => 'check_inactive_students',
                'url' => route('monitoring.logbook-detail')
            ];
        }

        // Mahasiswa yang tidak aktif (tidak ada absensi dalam periode KKN)
        $inactiveMahasiswaAbsensi = User::role('mahasiswa')
            ->whereIn('kelompok_id', $groups)
            ->whereDoesntHave('absensi', function($query) use ($startDate, $endDate) {
                $query->where('tanggal', '>=', $startDate)
                      ->where('tanggal', '<=', $endDate);
            })
            ->count();

        if ($inactiveMahasiswaAbsensi > 0) {
            $alerts[] = [
                'type' => 'danger',
                'title' => 'Mahasiswa Tidak Aktif',
                'message' => "Ada {$inactiveMahasiswaAbsensi} mahasiswa yang tidak absen dalam periode KKN",
                'action' => 'check_inactive_attendance',
                'url' => route('monitoring.attendance-detail')
            ];
        }

        return response()->json($alerts);
    }

    private function getMahasiswaDashboardData($user)
    {
        $kelompok = $user->kelompok;
        $anggota_kelompok = [];
        $dpl = null;
        $lokasi = null;

        if ($kelompok) {
            $anggota_kelompok = User::where('kelompok_id', $kelompok->id)
                ->where('id', '!=', $user->id)
                ->get();
            $dpl = User::find($kelompok->dpl_id);
            $lokasi = $kelompok->lokasi;
        }

        return [
            'logbook_total' => Logbook::where('user_id', $user->id)->count(),
            'logbook_approved' => Logbook::where('user_id', $user->id)
                ->where('status', 'approved')
                ->count(),
            'logbook_rejected' => Logbook::where('user_id', $user->id)
                ->where('status', 'rejected')
                ->count(),
            'logbook_pending' => Logbook::where('user_id', $user->id)
                ->where('status', 'submitted')
                ->count(),
            'absensi_total' => Absensi::where('user_id', $user->id)->count(),
            'absensi_hadir' => Absensi::where('user_id', $user->id)
                ->where('status', 'hadir')
                ->count(),
            'absensi_pending' => Absensi::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'logbook_stats' => $this->getLogbookStats([$user->id]),
            'absensi_stats' => $this->getAbsensiStats([$user->id]),
            // Data tambahan
            'kelompok' => $kelompok,
            'anggota_kelompok' => $anggota_kelompok,
            'dpl' => $dpl,
            'lokasi' => $lokasi
        ];
    }

    private function getLogbookStats($user_ids = null)
    {
        $query = Logbook::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(7);

        if ($user_ids) {
            $query->whereIn('user_id', $user_ids);
        }

        $stats = $query->get();

        return [
            'labels' => $stats->pluck('date')->map(function($date) {
                return date('d M', strtotime($date));
            })->toArray(),
            'data' => $stats->pluck('count')->toArray(),
        ];
    }

    private function getAbsensiStats($user_ids = null)
    {
        $query = Absensi::selectRaw('status, COUNT(*) as count')
            ->groupBy('status');

        if ($user_ids) {
            $query->whereIn('user_id', $user_ids);
        }

        $stats = $query->get();

        return [
            'labels' => $stats->pluck('status')->toArray(),
            'data' => $stats->pluck('count')->toArray(),
        ];
    }

    private function getRecentActivities($user)
    {
        $activities = collect();

        // Ambil logbook terbaru
        $logbooks = Logbook::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function($logbook) {
                return (object) [
                    'type' => 'logbook',
                    'title' => $logbook->judul,
                    'created_at' => $logbook->created_at
                ];
            });

        // Ambil absensi terbaru
        $absensi = Absensi::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function($attendance) {
                return (object) [
                    'type' => 'attendance',
                    'title' => 'Absensi ' . $attendance->tanggal->format('d/m/Y'),
                    'created_at' => $attendance->created_at
                ];
            });

        // Gabungkan dan urutkan berdasarkan waktu
        return $activities->merge($logbooks)->merge($absensi)
            ->sortByDesc('created_at')
            ->take(10)
            ->values();
    }
} 

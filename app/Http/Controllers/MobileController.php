<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Logbook;
use App\Models\Absensi;
use App\Models\Notification;
use App\Models\LaporanKelompok;

class MobileController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        $totalLogbooks = Logbook::where('user_id', $user->id)->count();
        $totalAttendance = Absensi::where('user_id', $user->id)->count();
        $pendingLogbooks = Logbook::where('user_id', $user->id)->where('status', 'submitted')->count();
        $pendingAttendance = Absensi::where('user_id', $user->id)->where('status', 'pending')->count();
        
        // Get recent activities (logbooks and attendance)
        $recentLogbooks = Logbook::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($logbook) {
                $logbook->type = 'logbook';
                $logbook->title = $logbook->judul;
                return $logbook;
            });
            
        $recentAttendance = Absensi::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($attendance) {
                $attendance->type = 'attendance';
                $attendance->title = ucfirst($attendance->jenis) . ' - ' . $attendance->lokasi;
                return $attendance;
            });
            
        $recentActivities = $recentLogbooks->concat($recentAttendance)
            ->sortByDesc('created_at')
            ->take(5);
        
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
        
        return view('mobile.dashboard', compact(
            'totalLogbooks', 
            'totalAttendance', 
            'pendingLogbooks', 
            'pendingAttendance', 
            'recentActivities',
            'group',
            'dpl',
            'groupMembers'
        ));
    }

    public function logbooks()
    {
        $user = Auth::user();
        $logbooks = \App\Models\Logbook::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);
        // Ambil semua tanggal logbook yang statusnya submitted/approved
        $logbookDates = \App\Models\Logbook::where('user_id', $user->id)
            ->whereIn('status', ['submitted', 'approved'])
            ->pluck('tanggal')
            ->map(fn($d) => $d->format('Y-m-d'))
            ->toArray();
        return view('mobile.logbooks.index', compact('logbooks', 'logbookDates'));
    }

    public function createLogbook()
    {
        return view('mobile.logbooks.create');
    }

    public function showLogbook($id)
    {
        $logbook = Logbook::where('user_id', Auth::id())->findOrFail($id);
        return view('mobile.logbooks.show', compact('logbook'));
    }

    public function editLogbook($id)
    {
        $logbook = Logbook::where('user_id', Auth::id())->findOrFail($id);
        
        return view('mobile.logbooks.edit', compact('logbook'));
    }

    public function attendance()
    {
        $user = Auth::user();
        
        $attendances = Absensi::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->get();
        
        // Cek absensi hari ini
        $todayAttendance = $attendances->first(function($attendance) {
            return $attendance->tanggal->isToday();
        });
        
        // Cek status absen hari ini
        $hasMasuk = $todayAttendance ? true : false;
        $hasKeluar = $todayAttendance && $todayAttendance->waktu_keluar ? true : false;
        
        $totalAttendance = $attendances->count();
        $approvedAttendance = $attendances->where('status', 'validated')->count();
        $pendingAttendance = $attendances->where('status', 'pending')->count();
        $rejectedAttendance = $attendances->where('status', 'rejected')->count();
        
        $recentAttendance = $attendances->take(10);
            
        return view('mobile.attendance.index', compact(
            'attendances',
            'todayAttendance',
            'hasMasuk',
            'hasKeluar',
            'totalAttendance', 
            'approvedAttendance', 
            'pendingAttendance', 
            'rejectedAttendance',
            'recentAttendance'
        ));
    }

    public function createAttendance()
    {
        return view('mobile.attendance.create');
    }

    public function showAttendance($id)
    {
        $attendance = Absensi::where('user_id', Auth::id())->findOrFail($id);
        return view('mobile.attendance.show', compact('attendance'));
    }

    public function laporanKelompok()
    {
        $user = Auth::user();

        if (!$user?->kelompok_id) {
            return view('mobile.laporan-kelompok.index', [
                'kelompok' => null,
                'laporan' => collect(),
            ]);
        }

        $kelompok = $user->kelompok()->with(['tahunAkademik', 'semester', 'lokasi', 'dpl'])->first();

        $laporan = LaporanKelompok::with('user')
            ->where('kelompok_id', $user->kelompok_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mobile.laporan-kelompok.index', compact('kelompok', 'laporan'));
    }

    public function notifications()
    {
        $user = Auth::user();
        
        $totalNotifications = $user->notifications()->count();
        $unreadNotifications = $user->unreadNotifications()->count();
        
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('mobile.notifications.index', compact(
            'totalNotifications', 
            'unreadNotifications', 
            'notifications'
        ));
    }

    public function profile()
    {
        $user = Auth::user();
        
        $totalLogbooks = Logbook::where('user_id', $user->id)->count();
        $totalAttendance = Absensi::where('user_id', $user->id)->count();
        
        return view('mobile.profile', compact('totalLogbooks', 'totalAttendance'));
    }

    public function editProfile()
    {
        return view('mobile.profile.edit');
    }

    public function changePassword()
    {
        return view('mobile.profile.password');
    }

    public function markNotificationAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }

    public function markAllNotificationsAsRead()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        
        return response()->json(['success' => true]);
    }
} 

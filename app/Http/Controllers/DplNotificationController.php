<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;

class DplNotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if (!$user->hasRole('dpl')) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $groups = \App\Models\Kelompok::where('dpl_id', $user->id)->pluck('id');
        
        $notifications = [];

        // Logbook yang perlu direview
        $pendingLogbooks = Logbook::whereIn('kelompok_id', $groups)
            ->where('status', 'submitted')
            ->with(['user', 'kelompok'])
            ->latest()
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

        return view('dpl.notifications', compact('notifications'));
    }

    private function getNotificationIcon($type)
    {
        switch($type) {
            case 'logbook_pending': return 'book';
            case 'absensi_pending': return 'calendar-check';
            case 'no_logbook_today': return 'exclamation-triangle';
            case 'no_absensi_today': return 'exclamation-triangle';
            default: return 'info-circle';
        }
    }

    private function getNotificationColor($type)
    {
        switch($type) {
            case 'logbook_pending': return 'warning';
            case 'absensi_pending': return 'info';
            case 'no_logbook_today': return 'danger';
            case 'no_absensi_today': return 'danger';
            default: return 'primary';
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class StudentNotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Deteksi device untuk mahasiswa
        $isMobile = session('is_mobile_device', false);
        
        if ($isMobile && auth()->user()->hasRole('mahasiswa')) {
            return redirect()->route('mobile.notifications');
        }

        if (auth()->user()->hasRole('dpl')) {
            return redirect()->route('dpl.notifications.index');
        }

        return view('students.notifications', compact('notifications'));
    }

    public function getUnreadCount()
    {
        $count = NotificationService::getUnreadCount(auth()->id());
        
        return response()->json(['count' => $count]);
    }

    public function markAsRead($id)
    {
        $notification = NotificationService::markAsRead($id, auth()->id());
        
        if ($notification) {
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }

    public function markAllAsRead()
    {
        NotificationService::markAllAsRead(auth()->id());
        
        return response()->json(['success' => true]);
    }

    public function getNotifications()
    {
        $allNotifications = auth()->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        $unreadCount = auth()->user()->notifications()->where('is_read', false)->count();

        $notifications = $allNotifications->map(function ($notif) {
            $url = '#';
            $dataObj = !empty($notif->data) ? (is_string($notif->data) ? json_decode($notif->data, true) : $notif->data) : [];
            if (str_contains($notif->type, 'logbook')) {
                $url = route('logbooks.index');
            } elseif (str_contains($notif->type, 'attendance') || str_contains($notif->type, 'absensi')) {
                $url = route('attendance.index');
            } elseif (str_contains($notif->type, 'broadcast')) {
                if (!empty($dataObj['action_url'])) {
                    $url = $dataObj['action_url'];
                } else {
                    $url = route('notifications.index');
                }
            }
            
            $notifArray = $notif->toArray();
            $notifArray['url'] = $url;
            $notifArray['urgency'] = $dataObj['urgency'] ?? 'normal';
            $notifArray['category'] = $dataObj['category'] ?? '';
            $notifArray['banner_image'] = $dataObj['banner_image'] ?? null;
            return $notifArray;
        });

        \Illuminate\Support\Facades\Log::info('DEBUG_NOTIF: ' . json_encode($notifications));

        return response()->json([
            'notifications' => collect($notifications)->values()->all(),
            'unread_count' => $unreadCount
        ]);
    }
}

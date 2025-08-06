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
            return view('mobile.notifications.index', compact('notifications'));
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
        $notifications = auth()->user()->notifications()
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json($notifications);
    }
}

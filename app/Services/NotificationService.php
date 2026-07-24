<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Logbook;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FcmNotification;

class NotificationService
{
    public static function createNotification($userId, $type, $title, $message, $data = null)
    {
        $notification = Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data
        ]);

        self::sendPushNotification($userId, $title, $message, $data);

        return $notification;
    }

    protected static function sendPushNotification($userId, $title, $body, $data = [])
    {
        try {
            $user = User::with('fcmTokens')->find($userId);
            if (!$user || $user->fcmTokens->isEmpty()) {
                Log::info("FCM Push: No tokens found for user {$userId}");
                return;
            }

            $messaging = app('firebase.messaging');

            $tokens = $user->fcmTokens->pluck('token')->toArray();
            Log::info("FCM Push: Sending to tokens: ", $tokens);
            
            $message = CloudMessage::new()
                ->withNotification(FcmNotification::create($title, $body))
                ->withData($data ?? []);

            $report = $messaging->sendMulticast($message, $tokens);
            Log::info("FCM Push Report: Successes: {$report->successes()->count()}, Failures: {$report->failures()->count()}");
            
            if ($report->hasFailures()) {
                foreach ($report->failures()->getItems() as $failure) {
                    Log::error('FCM Push Failure: ' . $failure->error()->getMessage());
                }
            }
        } catch (\Throwable $e) {
            Log::error('FCM Push Notification Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }

    public static function logbookSubmitted(Logbook $logbook)
    {
        $dpl_id = $logbook->user->kelompok->dpl_id ?? null;
        if (!$dpl_id) return null;

        return self::createNotification(
            $dpl_id,
            'logbook_submitted',
            'Logbook Baru',
            "Mahasiswa {$logbook->user->name} telah men-submit logbook baru untuk tanggal {$logbook->tanggal->format('d/m/Y')}.",
            [
                'logbook_id' => $logbook->id,
                'tanggal' => $logbook->tanggal->format('d/m/Y'),
                'mahasiswa' => $logbook->user->name
            ]
        );
    }

    public static function logbookApproved(Logbook $logbook)
    {
        return self::createNotification(
            $logbook->user_id,
            'logbook_approved',
            'Logbook Disetujui',
            "Logbook Anda tanggal {$logbook->tanggal->format('d/m/Y')} telah disetujui oleh DPL.",
            [
                'logbook_id' => $logbook->id,
                'tanggal' => $logbook->tanggal->format('d/m/Y'),
                'judul' => $logbook->judul
            ]
        );
    }

    public static function logbookRejected(Logbook $logbook)
    {
        return self::createNotification(
            $logbook->user_id,
            'logbook_rejected',
            'Logbook Ditolak',
            "Logbook Anda tanggal {$logbook->tanggal->format('d/m/Y')} ditolak oleh DPL. Silakan perbaiki dan submit ulang.",
            [
                'logbook_id' => $logbook->id,
                'tanggal' => $logbook->tanggal->format('d/m/Y'),
                'judul' => $logbook->judul,
                'komentar' => $logbook->komentar_dpl
            ]
        );
    }

    public static function attendanceApproved(Absensi $absensi)
    {
        return self::createNotification(
            $absensi->user_id,
            'attendance_approved',
            'Absensi Disetujui',
            "Absensi Anda tanggal {$absensi->tanggal->format('d/m/Y')} telah disetujui oleh DPL.",
            [
                'attendance_id' => $absensi->id,
                'tanggal' => $absensi->tanggal->format('d/m/Y')
            ]
        );
    }

    public static function attendanceSubmitted(Absensi $absensi)
    {
        $dpl_id = $absensi->user->kelompok->dpl_id ?? null;
        if (!$dpl_id) return null;

        return self::createNotification(
            $dpl_id,
            'attendance_submitted',
            'Absensi Baru',
            "Mahasiswa {$absensi->user->name} telah melakukan absensi untuk tanggal {$absensi->tanggal->format('d/m/Y')}.",
            [
                'attendance_id' => $absensi->id,
                'tanggal' => $absensi->tanggal->format('d/m/Y'),
                'mahasiswa' => $absensi->user->name
            ]
        );
    }

    public static function attendanceRejected(Absensi $absensi)
    {
        return self::createNotification(
            $absensi->user_id,
            'attendance_rejected',
            'Absensi Ditolak',
            "Absensi Anda tanggal {$absensi->tanggal->format('d/m/Y')} ditolak oleh DPL. Silakan absen ulang.",
            [
                'attendance_id' => $absensi->id,
                'tanggal' => $absensi->tanggal->format('d/m/Y')
            ]
        );
    }

    public static function checkLateAttendance()
    {
        // Cek mahasiswa yang belum absen hari ini setelah jam 9 pagi
        $now = Carbon::now();
        
        if ($now->hour >= 9) {
            $mahasiswa = User::role('mahasiswa')
                ->whereHas('kelompok')
                ->whereDoesntHave('absensi', function($query) {
                    $query->whereDate('tanggal', today());
                })
                ->get();

            foreach ($mahasiswa as $mhs) {
                // Cek apakah sudah ada notifikasi late attendance hari ini
                $existingNotification = Notification::where('user_id', $mhs->id)
                    ->where('type', 'late_attendance')
                    ->whereDate('created_at', today())
                    ->first();

                if (!$existingNotification) {
                    self::createNotification(
                        $mhs->id,
                        'late_attendance',
                        'Peringatan: Belum Absen',
                        'Anda belum melakukan absensi hari ini. Silakan segera absen untuk menghindari keterlambatan.',
                        [
                            'tanggal' => today()->format('d/m/Y')
                        ]
                    );
                }
            }
        }
    }

    public static function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    public static function markAsRead($notificationId, $userId)
    {
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return $notification;
    }

    public static function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
    }
} 
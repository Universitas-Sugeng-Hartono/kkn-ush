<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Logbook;
use App\Models\Absensi;
use Carbon\Carbon;

class NotificationService
{
    public static function createNotification($userId, $type, $title, $message, $data = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data
        ]);
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
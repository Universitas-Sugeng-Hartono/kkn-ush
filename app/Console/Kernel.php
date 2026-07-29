<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\SetupDefaultImages::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Mengecek mahasiswa yang terlambat absen setiap jam 09:00 pagi
        $schedule->call(function () {
            \App\Services\NotificationService::checkLateAttendance();
        })->dailyAt('09:00');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
} 
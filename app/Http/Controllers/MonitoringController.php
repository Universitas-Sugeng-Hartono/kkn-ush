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
        $this->middleware('role:dpl');
    }

    /**
     * Menampilkan detail absensi mahasiswa untuk periode KKN
     */
    public function attendanceDetail()
    {
        $dpl = auth()->user();
        
        // Ambil mahasiswa yang dibimbing DPL ini
        $mahasiswas = User::role('mahasiswa')
        ->whereHas('kelompok', function($query) use ($dpl) {
            $query->where('dpl_id', $dpl->id);
        })
        ->orderBy('name')
        ->get();

        // Periode KKN: 4 Agustus - 26 Agustus 2025 (23 hari)
        $startDate = Carbon::create(2025, 8, 4); // 4 Agustus 2025
        $endDate = Carbon::create(2025, 8, 26); // 26 Agustus 2025
        
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

        return view('monitoring.attendance-detail', compact('attendanceData', 'days'));
    }

    /**
     * Menampilkan detail logbook mahasiswa untuk periode KKN
     */
    public function logbookDetail()
    {
        $dpl = auth()->user();
        
        // Ambil mahasiswa yang dibimbing DPL ini
        $mahasiswas = User::role('mahasiswa')
        ->whereHas('kelompok', function($query) use ($dpl) {
            $query->where('dpl_id', $dpl->id);
        })
        ->orderBy('name')
        ->get();

        // Periode KKN: 4 Agustus - 26 Agustus 2025 (23 hari)
        $startDate = Carbon::create(2025, 8, 4); // 4 Agustus 2025
        $endDate = Carbon::create(2025, 8, 26); // 26 Agustus 2025
        
        $days = collect();
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $days->push($currentDate->format('Y-m-d'));
            $currentDate->addDay();
        }

        // Ambil data logbook untuk periode KKN
        $logbooks = Logbook::whereIn('user_id', $mahasiswas->pluck('id'))
            ->whereBetween('tanggal', [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d')
            ])
            ->get()
            ->groupBy('user_id');

        // Buat array untuk tracking logbook
        $logbookData = [];
        foreach ($mahasiswas as $mahasiswa) {
            $logbookData[$mahasiswa->id] = [
                'mahasiswa' => $mahasiswa,
                'days' => []
            ];
            
            foreach ($days as $day) {
                $hasLogbook = null;
                if (isset($logbooks[$mahasiswa->id])) {
                    foreach ($logbooks[$mahasiswa->id] as $logbook) {
                        if (Carbon::parse($logbook->tanggal)->format('Y-m-d') === $day) {
                            $hasLogbook = $logbook;
                            break;
                        }
                    }
                }
                
                $logbookData[$mahasiswa->id]['days'][$day] = [
                    'date' => Carbon::parse($day),
                    'status' => $hasLogbook ? $hasLogbook->status : null,
                    'logbook' => $hasLogbook
                ];
            }
        }

        return view('monitoring.logbook-detail', compact('logbookData', 'days'));
    }

    /**
     * Menampilkan gabungan absensi dan logbook dalam satu view untuk periode KKN
     */
    public function activityDetail()
    {
        $dpl = auth()->user();
        
        // Ambil mahasiswa yang dibimbing DPL ini
        $mahasiswas = User::role('mahasiswa')
        ->whereHas('kelompok', function($query) use ($dpl) {
            $query->where('dpl_id', $dpl->id);
        })
        ->orderBy('name')
        ->get();

        // Periode KKN: 4 Agustus - 26 Agustus 2025 (23 hari)
        $startDate = Carbon::create(2025, 8, 4); // 4 Agustus 2025
        $endDate = Carbon::create(2025, 8, 26); // 26 Agustus 2025
        
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

        // Ambil data logbook untuk periode KKN
        $logbooks = Logbook::whereIn('user_id', $mahasiswas->pluck('id'))
            ->whereBetween('tanggal', [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d')
            ])
            ->get()
            ->groupBy('user_id');

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
                
                $hasLogbook = isset($logbooks[$mahasiswa->id]) 
                    ? $logbooks[$mahasiswa->id]->where('tanggal', $day)->first()
                    : null;
                
                $activityData[$mahasiswa->id]['days'][$day] = [
                    'date' => Carbon::parse($day),
                    'attendance' => $hasAttendance,
                    'attendance_status' => $hasAttendance ? $hasAttendance->status : null,
                    'logbook' => $hasLogbook,
                    'logbook_status' => $hasLogbook ? $hasLogbook->status : null
                ];
            }
        }

        return view('monitoring.activity-detail', compact('activityData', 'days'));
    }
} 
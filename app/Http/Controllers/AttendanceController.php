<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $attendances = Absensi::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        // Cek absensi hari ini
        $todayAttendance = $attendances->first(function($attendance) {
            return $attendance->tanggal->isToday();
        });

        // Cek status absen hari ini (untuk semua view)
        $hasMasuk = $todayAttendance ? true : false;
        $hasKeluar = $todayAttendance && $todayAttendance->waktu_keluar ? true : false;

        // Data untuk kalender
        $events = $attendances->map(function($attendance) {
            return [
                'id' => $attendance->id,
                'title' => 'Absen',
                'start' => $attendance->tanggal->format('Y-m-d'),
                'backgroundColor' => $this->getStatusColor($attendance->status),
                'borderColor' => $this->getStatusColor($attendance->status),
                'classNames' => ['present']
            ];
        });

        // Statistik
        $stats = [
            'total' => $attendances->count(),
            'validated' => $attendances->where('status', 'validated')->count(),
            'pending' => $attendances->where('status', 'pending')->count(),
            'rejected' => $attendances->where('status', 'rejected')->count(),
        ];

        // Deteksi device untuk mahasiswa
        $isMobile = session('is_mobile_device', false);
        
        // Jika belum ada deteksi device, coba deteksi dari User-Agent
        if (!$isMobile && !session('device_info')) {
            $userAgent = request()->header('User-Agent');
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
        
        if ($isMobile && auth()->user()->hasRole('mahasiswa')) {
            // Statistik untuk mobile
            $totalAttendance = $attendances->count();
            $approvedAttendance = $attendances->where('status', 'validated')->count();
            $pendingAttendance = $attendances->where('status', 'pending')->count();
            $rejectedAttendance = $attendances->where('status', 'rejected')->count();
            
            // Recent attendance untuk mobile
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
            
        return view('attendance.index', compact('attendances', 'todayAttendance', 'events', 'stats', 'hasMasuk', 'hasKeluar'));
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

    private function getStatusColor($status)
    {
        return match($status) {
            'validated' => '#28a745', // success
            'rejected' => '#dc3545',  // danger
            default => '#ffc107'      // warning
        };
    }

    public function create()
    {
        // Deteksi device untuk mahasiswa
        $isMobile = session('is_mobile_device', false);
        
        if ($isMobile && auth()->user()->hasRole('mahasiswa')) {
            return view('mobile.attendance.create');
        }

        return view('attendance.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Validasi kelompok
        if (!$user->kelompok_id) {
            $msg = 'Anda belum terdaftar dalam kelompok manapun. Silakan hubungi admin.';
            if ($request->wantsJson()) return response()->json(['message' => $msg, 'status' => 'error'], 422);
            return redirect()->back()->with('error', $msg);
        }

        // Validasi rentang tanggal KKN Aktif
        $tahunAktif = \App\Models\TahunAkademik::getAktif();
        $semesterAktif = \App\Models\Semester::getAktif();
        $angkatanAktif = null;
        if ($tahunAktif && $semesterAktif) {
            $angkatanAktif = \App\Models\Angkatan::where('tahun_akademik_id', $tahunAktif->id)
                ->where('semester_id', $semesterAktif->id)
                ->first();
        }
        if (!$angkatanAktif || !now()->startOfDay()->between($angkatanAktif->tanggal_mulai, $angkatanAktif->tanggal_selesai)) {
            $msg = 'Anda hanya dapat mengisi absensi saat masa KKN sedang berjalan sesuai jadwal dari Universitas Sugeng Hartono.';
            if ($request->wantsJson()) return response()->json(['message' => $msg, 'status' => 'error'], 422);
            return redirect()->back()->with('error', $msg);
        }

        // Validasi input
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'waktu_masuk' => 'required',
            'foto' => 'required|string|max:30000000', // 20MB dalam base64
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'lokasi' => 'required|string',
            'jenis_absen' => 'required|in:masuk,keluar'
        ]);

        try {
            // Validasi ukuran foto (base64)
            $fotoSize = strlen($validated['foto']);
            if ($fotoSize > 30000000) { // 20MB dalam base64
                return response()->json([
                    'message' => 'Ukuran foto terlalu besar. Maksimal 20MB.',
                    'status' => 'error'
                ], 422);
            }
            
            // Validasi format foto (base64)
            if (!preg_match('/^data:image\/(jpeg|jpg|png);base64,/', $validated['foto'])) {
                return response()->json([
                    'message' => 'Format foto tidak valid.',
                    'status' => 'error'
                ], 422);
            }
            
            // Validasi foto tidak kosong
            $base64Data = str_replace(['data:image/jpeg;base64,', 'data:image/jpg;base64,', 'data:image/png;base64,'], '', $validated['foto']);
            if (empty($base64Data) || strlen($base64Data) < 100) {
                return response()->json([
                    'message' => 'Foto tidak valid atau terlalu kecil.',
                    'status' => 'error'
                ], 422);
            }
            
            // Cek apakah sudah absen hari ini
            $existingAttendance = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', Carbon::parse($validated['tanggal']))
                ->first();

            if ($validated['jenis_absen'] === 'masuk') {
                // Absensi masuk
                if ($existingAttendance) {
                    return response()->json([
                        'message' => 'Anda sudah melakukan absensi masuk hari ini',
                        'status' => 'error'
                    ], 422);
                }

                // Simpan foto
                $image = $validated['foto'];
                $image = str_replace('data:image/jpeg;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = 'absensi/' . uniqid() . '.jpg';
                Storage::disk('public')->put($imageName, base64_decode($image));

                // Simpan absensi masuk
                $absensi = Absensi::create([
                    'user_id' => $user->id,
                    'kelompok_id' => $user->kelompok_id,
                    'tanggal' => $validated['tanggal'],
                    'waktu_masuk' => $validated['waktu_masuk'],
                    'foto_kegiatan' => $imageName,
                    'latitude' => $validated['latitude'],
                    'longitude' => $validated['longitude'],
                    'lokasi' => $validated['lokasi'],
                    'status' => 'pending'
                ]);

                $message = 'Absensi masuk berhasil disimpan';
            } else {
                // Absensi keluar
                if (!$existingAttendance) {
                    return response()->json([
                        'message' => 'Anda belum melakukan absensi masuk hari ini',
                        'status' => 'error'
                    ], 422);
                }

                if ($existingAttendance->waktu_keluar) {
                    return response()->json([
                        'message' => 'Anda sudah melakukan absensi keluar hari ini',
                        'status' => 'error'
                    ], 422);
                }

                // Simpan foto keluar
                $image = $validated['foto'];
                $image = str_replace('data:image/jpeg;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = 'absensi/' . uniqid() . '.jpg';
                Storage::disk('public')->put($imageName, base64_decode($image));

                // Update absensi dengan data keluar
                $existingAttendance->update([
                    'waktu_keluar' => $validated['waktu_masuk'],
                    'foto_keluar' => $imageName,
                    'latitude_keluar' => $validated['latitude'],
                    'longitude_keluar' => $validated['longitude'],
                    'lokasi_keluar' => $validated['lokasi']
                ]);

                $absensi = $existingAttendance;
                $message = 'Absensi keluar berhasil disimpan';
            }

            // Kirim notifikasi ke DPL
            NotificationService::attendanceSubmitted($absensi);

            return response()->json([
                'message' => $message,
                'status' => 'success',
                'data' => $absensi
            ]);

        } catch (\Exception $e) {
            \Log::error('Attendance store error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan absensi. Silakan coba lagi.',
                'status' => 'error'
            ], 500);
        }
    }

    public function checkTodayAttendance()
    {
        $user = auth()->user();
        $todayAttendance = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', today())
            ->first();

        return response()->json([
            'has_masuk' => $todayAttendance ? true : false,
            'has_keluar' => $todayAttendance && $todayAttendance->waktu_keluar ? true : false,
            'attendance' => $todayAttendance
        ]);
    }

    public function show(Absensi $attendance)
    {
        // Load relasi yang diperlukan
        $attendance->load(['user.kelompok.lokasi', 'kelompok']);

        // Jika user adalah DPL, pastikan absensi dari mahasiswa bimbingannya
        if (auth()->user()->hasRole('dpl')) {
            // Cek apakah user yang absen memiliki kelompok dan apakah DPL adalah pembimbingnya
            if (!$attendance->user->kelompok || $attendance->user->kelompok->dpl_id != auth()->id()) {
                abort(403, 'Anda tidak memiliki akses untuk melihat absensi ini.');
            }
        }
        // Jika user adalah mahasiswa, pastikan absensi miliknya sendiri
        elseif (auth()->user()->hasRole('mahasiswa')) {
            if ($attendance->user_id != auth()->id()) {
                abort(403, 'Anda tidak memiliki akses untuk melihat absensi ini.');
            }
        }

        // Jika request ingin JSON (untuk modal detail)
        if (request()->wantsJson()) {
            return response()->json([
                'tanggal' => $attendance->tanggal->format('d/m/Y'),
                'waktu_masuk' => $attendance->waktu_masuk->format('H:i:s'),
                'waktu_keluar' => $attendance->waktu_keluar ? $attendance->waktu_keluar->format('H:i:s') : null,
                'status' => $attendance->status,
                'lokasi_masuk' => $attendance->lokasi ?? 'Tidak ada data lokasi',
                'lokasi_keluar' => $attendance->lokasi_keluar ?? null,
                'foto_masuk_url' => $attendance->foto_kegiatan ? asset('storage/' . $attendance->foto_kegiatan) : null,
                'foto_keluar_url' => $attendance->foto_keluar ? asset('storage/' . $attendance->foto_keluar) : null,
                'latitude' => $attendance->latitude,
                'longitude' => $attendance->longitude,
                'latitude_keluar' => $attendance->latitude_keluar,
                'longitude_keluar' => $attendance->longitude_keluar,
                'keterangan' => $attendance->keterangan
            ]);
        }

        return view('attendance.show', compact('attendance'));
    }

    public function validateAttendance(Absensi $attendance)
    {
        if (!auth()->user()->hasRole('dpl')) {
            abort(403);
        }

        $attendance->update(['status' => 'validated']);

        return redirect()->route('attendance.pending')
            ->with('success', 'Absensi berhasil divalidasi');
    }

    public function destroy(Absensi $attendance)
    {
        if ($attendance->foto_kegiatan) {
            Storage::disk('public')->delete($attendance->foto_kegiatan);
        }
        
        if ($attendance->foto_keluar) {
            Storage::disk('public')->delete($attendance->foto_keluar);
        }

        $attendance->delete();

        return redirect()->route('attendance.index')
            ->with('success', 'Absensi berhasil dihapus');
    }

    public function pending()
    {
        $tahunAktif = \App\Models\TahunAkademik::getAktif();
        $semesterAktif = \App\Models\Semester::getAktif();

        // Ambil absensi yang pending dari mahasiswa bimbingan DPL
        $attendances = Absensi::whereHas('user', function($query) use ($tahunAktif, $semesterAktif) {
            $query->whereHas('kelompok', function($q) use ($tahunAktif, $semesterAktif) {
                $q->where('dpl_id', auth()->id());
                if ($tahunAktif && $semesterAktif) {
                    $q->where('tahun_akademik_id', $tahunAktif->id)
                      ->where('semester_id', $semesterAktif->id);
                }
            });
        })
        ->where('status', 'pending')
        ->with(['user', 'kelompok'])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('attendance.pending', compact('attendances'));
    }

    public function approve(Absensi $attendance)
    {
        // Pastikan DPL hanya bisa approve absensi dari mahasiswa bimbingannya
        if ($attendance->user->kelompok->dpl_id != auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk menyetujui absensi ini.');
        }

        $attendance->update(['status' => 'validated']);

        // Kirim notifikasi ke mahasiswa
        NotificationService::attendanceApproved($attendance);

        return redirect()->route('attendance.pending')
            ->with('success', 'Absensi berhasil disetujui.');
    }

    public function reject(Absensi $attendance)
    {
        // Pastikan DPL hanya bisa reject absensi dari mahasiswa bimbingannya
        if ($attendance->user->kelompok->dpl_id != auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk menolak absensi ini.');
        }

        $attendance->update(['status' => 'rejected']);

        // Kirim notifikasi ke mahasiswa
        NotificationService::attendanceRejected($attendance);

        return redirect()->route('attendance.pending')
            ->with('success', 'Absensi berhasil ditolak.');
    }

    public function rejectAttendance(Absensi $attendance)
    {
        if (!auth()->user()->hasRole('dpl')) {
            abort(403);
        }

        $attendance->update(['status' => 'rejected']);

        return redirect()->route('attendance.pending')
            ->with('success', 'Absensi berhasil ditolak');
    }

    public function approveAll()
    {
        $tahunAktif = \App\Models\TahunAkademik::getAktif();
        $semesterAktif = \App\Models\Semester::getAktif();

        $attendances = Absensi::whereHas('user', function($query) use ($tahunAktif, $semesterAktif) {
            $query->whereHas('kelompok', function($q) use ($tahunAktif, $semesterAktif) {
                $q->where('dpl_id', auth()->id());
                if ($tahunAktif && $semesterAktif) {
                    $q->where('tahun_akademik_id', $tahunAktif->id)
                      ->where('semester_id', $semesterAktif->id);
                }
            });
        })
        ->where('status', 'pending')
        ->get();

        foreach ($attendances as $attendance) {
            $attendance->update([
                'status' => 'validated'
            ]);
            
            // Kirim notifikasi ke mahasiswa
            NotificationService::attendanceApproved($attendance);
        }

        return redirect()->route('attendance.pending')
            ->with('success', 'Semua absensi pending berhasil disetujui.');
    }

    public function rejectAll()
    {
        $tahunAktif = \App\Models\TahunAkademik::getAktif();
        $semesterAktif = \App\Models\Semester::getAktif();

        $attendances = Absensi::whereHas('user', function($query) use ($tahunAktif, $semesterAktif) {
            $query->whereHas('kelompok', function($q) use ($tahunAktif, $semesterAktif) {
                $q->where('dpl_id', auth()->id());
                if ($tahunAktif && $semesterAktif) {
                    $q->where('tahun_akademik_id', $tahunAktif->id)
                      ->where('semester_id', $semesterAktif->id);
                }
            });
        })
        ->where('status', 'pending')
        ->get();

        foreach ($attendances as $attendance) {
            $attendance->update([
                'status' => 'rejected'
            ]);
            
            // Kirim notifikasi ke mahasiswa
            NotificationService::attendanceRejected($attendance);
        }

        return redirect()->route('attendance.pending')
            ->with('success', 'Semua absensi pending berhasil ditolak.');
    }
} 
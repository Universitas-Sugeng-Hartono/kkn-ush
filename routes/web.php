<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\StudentNotificationController;
use App\Http\Controllers\DplNotificationController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\MobileController;
use App\Http\Controllers\LaporanKelompokController;
use App\Http\Controllers\DplLaporanKelompokController;
use App\Http\Controllers\TahunAkademikController;
use App\Http\Controllers\FcmTokenController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::post('/pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');
Route::get('/pengaduan/check', [PengaduanController::class, 'check'])->name('pengaduan.check');

// Berita Routes
Route::get('/berita/public', [BeritaController::class, 'publicIndex'])->name('berita.public.index');
Route::get('/berita/public/{berita:slug}', [BeritaController::class, 'publicShow'])->name('berita.public.show');

// Dokumen Routes
Route::get('/dokumen/kategori/{jenis}', [DokumenController::class, 'kategori'])->name('dokumen.kategori');
Route::get('/dokumen/{dokumen}/download', [DokumenController::class, 'download'])->name('dokumen.download');

// Guest Routes (Login, Register, etc)
Route::middleware('guest')->group(function () {
    // ... auth routes will be here
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/fcm-token', [FcmTokenController::class, 'store'])->name('fcm-token.store');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Device detection routes
    Route::post('/device/update', [DeviceController::class, 'update'])->name('device.update');
    Route::get('/device/info', [DeviceController::class, 'info'])->name('device.info');
    Route::post('/device/force-mobile', [DeviceController::class, 'forceMobile'])->name('device.force-mobile');
    Route::post('/device/force-desktop', [DeviceController::class, 'forceDesktop'])->name('device.force-desktop');
    Route::post('/device/reset', [DeviceController::class, 'reset'])->name('device.reset');
    Route::get('/device/test', function() {
        return view('device-test');
    })->name('device.test');
    Route::get('/device/debug', function() {
        return view('device-debug');
    })->name('device.debug');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.update-photo');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware('role:admin')->group(function () {
        Route::post('users/import', [UserController::class, 'import'])->name('users.import');
        Route::get('users/template', [UserController::class, 'downloadTemplate'])->name('users.template');
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('users/bulk-reset-password', [UserController::class, 'bulkResetPassword'])->name('users.bulk-reset-password');
        Route::resource('users', UserController::class);
        Route::get('pengaturan/akademik', [TahunAkademikController::class, 'index'])->name('tahun-akademik.index');
        Route::post('pengaturan/tahun-akademik', [TahunAkademikController::class, 'store'])->name('tahun-akademik.store');
        Route::put('pengaturan/tahun-akademik/{tahunAkademik}', [TahunAkademikController::class, 'update'])->name('tahun-akademik.update');
        Route::delete('pengaturan/tahun-akademik/{tahunAkademik}', [TahunAkademikController::class, 'destroy'])->name('tahun-akademik.destroy');
        Route::post('pengaturan/tahun-akademik/{tahunAkademik}/aktif', [TahunAkademikController::class, 'setAktif'])->name('tahun-akademik.set-aktif');
        Route::post('pengaturan/tahun-akademik/{tahunAkademik}/nonaktif', [TahunAkademikController::class, 'setNonaktif'])->name('tahun-akademik.set-nonaktif');

        Route::post('pengaturan/semester', [TahunAkademikController::class, 'storeSemester'])->name('semester.store');
        Route::put('pengaturan/semester/{semester}', [TahunAkademikController::class, 'updateSemester'])->name('semester.update');
        Route::delete('pengaturan/semester/{semester}', [TahunAkademikController::class, 'destroySemester'])->name('semester.destroy');
        Route::post('pengaturan/semester/{semester}/aktif', [TahunAkademikController::class, 'setSemesterAktif'])->name('semester.set-aktif');
        Route::post('pengaturan/semester/{semester}/nonaktif', [TahunAkademikController::class, 'setSemesterNonaktif'])->name('semester.set-nonaktif');
        Route::post('pengaturan/angkatan', [TahunAkademikController::class, 'storeOrUpdateAngkatan'])->name('angkatan.store-or-update');
        Route::delete('pengaturan/angkatan/{angkatan}', [TahunAkademikController::class, 'destroyAngkatan'])->name('angkatan.destroy');

        Route::get('locations/map', [LocationController::class, 'map'])->name('locations.map');
        Route::get('locations/data', [LocationController::class, 'getLocations'])->name('locations.data');
        Route::resource('locations', LocationController::class);
        Route::get('groups/map', [GroupController::class, 'map'])->name('groups.map');
        Route::get('groups/data', [GroupController::class, 'getMapData'])->name('groups.data');
        Route::resource('groups', GroupController::class)->except(['monitoring', 'monitoringMap', 'getMonitoringData']);
        Route::resource('berita', BeritaController::class)->parameters([
            'berita' => 'berita'
        ]);
        Route::resource('dokumen', DokumenController::class)->except(['download'])->parameters([
            'dokumen' => 'dokumen'
        ]);
        Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
        Route::get('/pengaduan/{pengaduan}', [PengaduanController::class, 'show'])->name('pengaduan.show');
        Route::put('/pengaduan/{pengaduan}/process', [PengaduanController::class, 'process'])->name('pengaduan.process');
        Route::delete('/pengaduan/{pengaduan}', [PengaduanController::class, 'destroy'])->name('pengaduan.destroy');
        Route::resource('galeri', GaleriController::class);
    });

    // DPL Routes
    Route::middleware('role:dpl')->group(function () {
        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::get('/students/{user}', [StudentController::class, 'show'])->name('students.show');
        Route::resource('grades', GradeController::class);
        Route::put('/logbooks/{logbook}/review', [LogbookController::class, 'review'])->name('logbooks.review');
        
        // Validasi Logbook dan Absensi
        Route::get('/logbooks/pending', [LogbookController::class, 'pending'])->name('logbooks.pending');
        Route::get('/attendance/pending', [AttendanceController::class, 'pending'])->name('attendance.pending');
        
        // Approve dan Reject Logbook
        Route::patch('/logbooks/{logbook}/approve', [LogbookController::class, 'approve'])->name('logbooks.approve');
        Route::patch('/logbooks/{logbook}/reject', [LogbookController::class, 'reject'])->name('logbooks.reject');
        // Approve Semua Logbook Pending
        Route::post('/logbooks/approve-all', [LogbookController::class, 'approveAll'])->name('logbooks.approveAll');
        // Reject Semua Logbook Pending
        Route::post('/logbooks/reject-all', [LogbookController::class, 'rejectAll'])->name('logbooks.rejectAll');
        
        // Approve dan Reject Absensi
        Route::patch('/attendance/{attendance}/approve', [AttendanceController::class, 'approve'])->name('attendance.approve');
        Route::patch('/attendance/{attendance}/reject', [AttendanceController::class, 'reject'])->name('attendance.reject');
        // Approve Semua Absensi Pending
        Route::post('/attendance/approve-all', [AttendanceController::class, 'approveAll'])->name('attendance.approveAll');
        // Reject Semua Absensi Pending
        Route::post('/attendance/reject-all', [AttendanceController::class, 'rejectAll'])->name('attendance.rejectAll');
        

        // Notifikasi dan Alert
        Route::get('/dpl/notifications', [DashboardController::class, 'getNotifications'])->name('dpl.notifications.get');
        Route::get('/alerts', [DashboardController::class, 'getAlerts'])->name('alerts.get');
        Route::get('/dpl/notifications-page', [DplNotificationController::class, 'index'])->name('dpl.notifications.index');
        Route::post('/dpl/notifications/read', [DashboardController::class, 'markNotificationAsRead'])->name('dpl.notifications.mark-read');
        
        // History Logbook dan Absensi
        Route::get('/history/logbooks', [App\Http\Controllers\HistoryLogbookController::class, 'index'])->name('history.logbooks.index');
        Route::get('/history/logbooks/export-pdf-all', [App\Http\Controllers\HistoryLogbookController::class, 'exportPdfAll'])->name('history.logbooks.export-pdf-all');
        Route::get('/history/logbooks/{logbook}', [App\Http\Controllers\HistoryLogbookController::class, 'show'])->name('history.logbooks.show');
    });

    // Monitoring Detail Aktivitas Mahasiswa (DPL & Admin)
    Route::middleware(['auth', 'role:dpl|admin'])->group(function () {
        Route::get('/monitoring/attendance-detail', [App\Http\Controllers\MonitoringController::class, 'attendanceDetail'])->name('monitoring.attendance-detail');
        Route::get('/monitoring/logbook-detail', [App\Http\Controllers\MonitoringController::class, 'logbookDetail'])->name('monitoring.logbook-detail');
        Route::get('/monitoring/activity-detail', [App\Http\Controllers\MonitoringController::class, 'activityDetail'])->name('monitoring.activity-detail');
    });

    // Monitoring Kelompok untuk DPL
    Route::middleware(['auth', 'role:dpl'])->group(function () {
        Route::get('/dpl/monitoring', [GroupController::class, 'monitoring'])->name('groups.monitoring');
        Route::get('/dpl/monitoring/map', [GroupController::class, 'monitoringMap'])->name('groups.monitoring.map');
        Route::get('/dpl/monitoring/data', [GroupController::class, 'getMonitoringData'])->name('groups.monitoring.data');

        Route::get('/dpl/laporan-kelompok', [DplLaporanKelompokController::class, 'index'])->name('dpl.laporan-kelompok.index');
    });

    // Shared Routes (Admin, DPL & Mahasiswa)
    Route::middleware('role:admin|dpl|mahasiswa')->group(function () {
        // History Logbook dan Absensi
        Route::get('/history/attendances', [App\Http\Controllers\HistoryAttendanceController::class, 'index'])->name('history.attendances.index');
        Route::get('/history/attendances/{attendance}', [App\Http\Controllers\HistoryAttendanceController::class, 'show'])->name('history.attendances.show');

        // Export PDF Logbook
        Route::get('/logbooks/export-pdf-all', [LogbookController::class, 'exportPdfAll'])->name('logbooks.export-pdf-all');
        Route::get('/logbooks/{logbook}/export-pdf', [LogbookController::class, 'exportPdf'])->name('logbooks.export-pdf');
    });

    // Mahasiswa Routes
    Route::middleware('role:mahasiswa')->group(function () {
        Route::resource('logbooks', LogbookController::class);
        Route::put('/logbooks/{logbook}/submit', [LogbookController::class, 'submit'])->name('logbooks.submit');
        Route::resource('attendance', AttendanceController::class)->except(['show']);
        Route::get('/attendance/check-today', [AttendanceController::class, 'checkTodayAttendance'])->name('attendance.check-today');
        Route::get('/laporan-kelompok', [LaporanKelompokController::class, 'index'])->name('laporan-kelompok.index');
        Route::post('/laporan-kelompok', [LaporanKelompokController::class, 'store'])->name('laporan-kelompok.store');
        Route::delete('/laporan-kelompok/{laporanKelompok}', [LaporanKelompokController::class, 'destroy'])->name('laporan-kelompok.destroy');
        Route::get('/notifications', [StudentNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/mark-read', [StudentNotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('/notifications/mark-all-read', [StudentNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::get('/notifications/unread-count', [StudentNotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
        Route::get('/notifications/get', [StudentNotificationController::class, 'getNotifications'])->name('notifications.get');
        
        // Mobile Routes
        Route::prefix('mobile')->group(function () {
            Route::get('/dashboard', [MobileController::class, 'dashboard'])->name('mobile.dashboard');
            Route::get('/logbooks', [MobileController::class, 'logbooks'])->name('mobile.logbooks');
            Route::get('/logbooks/create', [MobileController::class, 'createLogbook'])->name('mobile.logbooks.create');
            Route::get('/logbooks/{id}', [MobileController::class, 'showLogbook'])->name('mobile.logbooks.show');
            Route::get('/logbooks/{id}/edit', [MobileController::class, 'editLogbook'])->name('mobile.logbooks.edit');
            Route::get('/attendance', [MobileController::class, 'attendance'])->name('mobile.attendance');
            Route::get('/attendance/create', [MobileController::class, 'createAttendance'])->name('mobile.attendance.create');
            Route::get('/attendance/{id}', [MobileController::class, 'showAttendance'])->name('mobile.attendance.show');
            Route::get('/laporan-kelompok', [MobileController::class, 'laporanKelompok'])->name('mobile.laporan-kelompok.index');
            Route::get('/notifications', [MobileController::class, 'notifications'])->name('mobile.notifications');
            Route::get('/profile', [MobileController::class, 'profile'])->name('mobile.profile');
            Route::get('/profile/edit', [MobileController::class, 'editProfile'])->name('mobile.profile.edit');
            Route::get('/profile/password', [MobileController::class, 'changePassword'])->name('mobile.profile.password');
            Route::post('/notifications/{id}/mark-read', [MobileController::class, 'markNotificationAsRead'])->name('mobile.notifications.mark-read');
            Route::post('/notifications/mark-all-read', [MobileController::class, 'markAllNotificationsAsRead'])->name('mobile.notifications.mark-all-read');
        });
        
        // Shared routes for mobile and web
        Route::delete('/logbooks/photos/{photo}', [LogbookController::class, 'deletePhoto'])->name('logbooks.photos.delete');
        Route::delete('/logbooks/{logbook}/attachments', [LogbookController::class, 'deleteAttachment'])->name('logbooks.attachments.delete');
    });

    // Shared Routes (Admin, DPL & Mahasiswa)
    Route::middleware('role:admin|dpl|mahasiswa')->group(function () {
        Route::get('/logbooks/{logbook}', [LogbookController::class, 'show'])->name('logbooks.show');
        Route::delete('/logbooks/{logbook}', [LogbookController::class, 'destroy'])->name('logbooks.destroy');
        Route::get('/logbooks/{logbook}/validate', [LogbookController::class, 'validate'])->name('logbooks.validate');
        Route::get('/attendance/{attendance}', [AttendanceController::class, 'show'])->name('attendance.show');
        Route::get('/attendance/{attendance}/validate', [AttendanceController::class, 'validateAttendance'])->name('attendance.validate');
        Route::get('/laporan-kelompok/{laporanKelompok}/download', [LaporanKelompokController::class, 'download'])->name('laporan-kelompok.download');
    });
    // DPL Only Routes
    Route::middleware('role:dpl')->group(function () {
        Route::put('/attendance/{attendance}/reject', [AttendanceController::class, 'rejectAttendance'])->name('attendance.reject');
    });

});

Route::get('/test-db', function() {
    try {
        DB::connection()->getPdo();
        return 'Berhasil terhubung ke database: ' . DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return 'Gagal terhubung ke database: ' . $e->getMessage();
    }
});





require __DIR__.'/auth.php';

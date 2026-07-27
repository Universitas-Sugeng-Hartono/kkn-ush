<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use App\Models\Lokasi;
use App\Models\Notification;
use App\Models\Semester;
use App\Models\TahunAkademik;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BroadcastController extends Controller
{
    /**
     * Tampilkan halaman Broadcast untuk Admin.
     */
    public function adminIndex()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();
        $lokasis = Lokasi::orderBy('nama_kabupaten')
            ->orderBy('nama_kecamatan')
            ->orderBy('nama_desa')
            ->get();

        $broadcasts = Notification::where('type', 'broadcast')
            ->select(DB::raw('MAX(id) as id'), 'title', 'message', 'created_at', DB::raw('count(*) as total_recipients'), DB::raw('MAX(data) as data'))
            ->groupBy('title', 'message', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.broadcast.index', compact('broadcasts', 'tahunAktif', 'semesterAktif', 'lokasis'));
    }

    /**
     * Kirim Broadcast dari Admin.
     */
    public function adminStore(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki akses untuk mengirim broadcast.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target' => 'required|in:all,mahasiswa,dpl',
            'lokasi_id' => 'nullable|exists:lokasi,id',
            'status_aktivitas' => 'nullable|in:no_logbook_today,no_absensi_today',
            'category' => 'nullable|string|max:50',
            'urgency' => 'nullable|in:normal,important,urgent',
            'action_url' => 'nullable|url|max:255',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $bannerImageUrl = null;
        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('broadcasts', 'public');
            $bannerImageUrl = asset('storage/' . $path);
        }

        $query = User::where('id', '!=', auth()->id());
        if ($request->target === 'mahasiswa') {
            $query->role('mahasiswa');
        } elseif ($request->target === 'dpl') {
            $query->role('dpl');
        }

        // Filter otomatis di latar belakang berdasarkan Tahun Akademik & Semester Aktif
        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();
        if ($tahunAktif) {
            $query->where('tahun_akademik_id', $tahunAktif->id);
        }
        if ($semesterAktif) {
            $query->where('semester_id', $semesterAktif->id);
        }

        // Filter Lokasi KKN (melalui relasi kelompok)
        if ($request->filled('lokasi_id')) {
            $query->whereHas('kelompok', function ($q) use ($request) {
                $q->where('lokasi_id', $request->lokasi_id);
            });
        }

        // Filter Status Aktivitas Mahasiswa (Smart Reminder)
        if ($request->filled('status_aktivitas')) {
            if ($request->status_aktivitas === 'no_logbook_today') {
                $query->whereDoesntHave('logbooks', function ($q) {
                    $q->whereDate('created_at', today());
                });
            } elseif ($request->status_aktivitas === 'no_absensi_today') {
                $query->whereDoesntHave('absensi', function ($q) {
                    $q->whereDate('created_at', today());
                });
            }
        }

        $users = $query->get();

        if ($users->isEmpty()) {
            return back()->with('error', 'Tidak ada pengguna yang ditemukan untuk filter/target yang dipilih pada Tahun Akademik Aktif.');
        }

        $now = now();
        $insertData = [];
        $userIds = [];
        $dataArray = [
            'sender_id' => auth()->id(),
            'sender_name' => auth()->user()->name,
            'sender_role' => 'admin',
            'target' => $request->target,
            'type' => 'broadcast',
            'category' => $request->input('category', 'Akademik'),
            'urgency' => $request->input('urgency', 'normal'),
            'action_url' => $request->input('action_url'),
            'banner_image' => $bannerImageUrl,
            'lokasi_id' => $request->lokasi_id,
            'status_aktivitas' => $request->status_aktivitas,
        ];
        $dataJson = json_encode($dataArray);

        foreach ($users as $user) {
            $userIds[] = $user->id;
            $insertData[] = [
                'user_id' => $user->id,
                'type' => 'broadcast',
                'title' => $request->title,
                'message' => $request->message,
                'data' => $dataJson,
                'is_read' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Batch insert ke database dalam chunk 500
        foreach (array_chunk($insertData, 500) as $chunk) {
            Notification::insert($chunk);
        }

        // Kirim FCM Web Push Notification dalam chunk
        NotificationService::sendBroadcastPush(
            $userIds,
            $request->title,
            $request->message,
            [
                'type' => 'broadcast',
                'sender_name' => auth()->user()->name,
                'url' => $request->filled('action_url') ? $request->action_url : route('notifications.index'),
                'category' => $request->input('category', 'Akademik'),
                'urgency' => $request->input('urgency', 'normal'),
                'banner_image' => $bannerImageUrl,
            ],
            [
                'urgency' => $request->input('urgency', 'normal'),
                'banner_image' => $bannerImageUrl,
            ]
        );

        return back()->with('success', 'Broadcast berhasil dikirim ke ' . count($userIds) . ' pengguna terdaftar di Tahun Akademik Aktif!');
    }

    /**
     * Tampilkan halaman Broadcast untuk DPL.
     */
    public function dplIndex()
    {
        if (!auth()->user()->hasRole('dpl')) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();
        $groupsQuery = Kelompok::where('dpl_id', auth()->id());
        if ($tahunAktif) {
            $groupsQuery->where('tahun_akademik_id', $tahunAktif->id);
        }
        if ($semesterAktif) {
            $groupsQuery->where('semester_id', $semesterAktif->id);
        }
        $groups = $groupsQuery->get();

        $broadcasts = Notification::where('type', 'dpl_broadcast')
            ->where('data->sender_id', auth()->id())
            ->select(DB::raw('MAX(id) as id'), 'title', 'message', 'created_at', DB::raw('count(*) as total_recipients'), DB::raw('MAX(data) as data'))
            ->groupBy('title', 'message', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dpl.broadcast.index', compact('groups', 'broadcasts', 'tahunAktif', 'semesterAktif'));
    }

    /**
     * Kirim Broadcast dari DPL ke mahasiswa bimbingannya.
     */
    public function dplStore(Request $request)
    {
        if (!auth()->user()->hasRole('dpl')) {
            abort(403, 'Anda tidak memiliki akses untuk mengirim broadcast.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'kelompok_id' => 'required',
            'status_aktivitas' => 'nullable|in:no_logbook_today,no_absensi_today',
            'category' => 'nullable|string|max:50',
            'urgency' => 'nullable|in:normal,important,urgent',
            'action_url' => 'nullable|url|max:255',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $bannerImageUrl = null;
        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('broadcasts', 'public');
            $bannerImageUrl = asset('storage/' . $path);
        }

        $dplGroups = Kelompok::where('dpl_id', auth()->id())->pluck('id')->toArray();

        if (empty($dplGroups)) {
            return back()->with('error', 'Anda belum memiliki kelompok bimbingan.');
        }

        if ($request->kelompok_id === 'all') {
            $targetGroupIds = $dplGroups;
        } else {
            if (!in_array($request->kelompok_id, $dplGroups)) {
                return back()->with('error', 'Kelompok tidak valid atau bukan di bawah bimbingan Anda.');
            }
            $targetGroupIds = [$request->kelompok_id];
        }

        $query = User::role('mahasiswa')
            ->whereIn('kelompok_id', $targetGroupIds);

        // Filter otomatis di latar belakang berdasarkan Tahun Akademik & Semester Aktif
        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();
        if ($tahunAktif) {
            $query->where('tahun_akademik_id', $tahunAktif->id);
        }
        if ($semesterAktif) {
            $query->where('semester_id', $semesterAktif->id);
        }

        // Filter Status Aktivitas Mahasiswa
        if ($request->filled('status_aktivitas')) {
            if ($request->status_aktivitas === 'no_logbook_today') {
                $query->whereDoesntHave('logbooks', function ($q) {
                    $q->whereDate('created_at', today());
                });
            } elseif ($request->status_aktivitas === 'no_absensi_today') {
                $query->whereDoesntHave('absensi', function ($q) {
                    $q->whereDate('created_at', today());
                });
            }
        }

        $users = $query->get();

        if ($users->isEmpty()) {
            return back()->with('error', 'Tidak ada mahasiswa yang ditemukan untuk kriteria filter yang dipilih pada Tahun Akademik Aktif.');
        }

        $now = now();
        $insertData = [];
        $userIds = [];
        $dataArray = [
            'sender_id' => auth()->id(),
            'sender_name' => auth()->user()->name,
            'sender_role' => 'dpl',
            'kelompok_id' => $request->kelompok_id,
            'type' => 'dpl_broadcast',
            'category' => $request->input('category', 'Monev'),
            'urgency' => $request->input('urgency', 'normal'),
            'action_url' => $request->input('action_url'),
            'banner_image' => $bannerImageUrl,
            'status_aktivitas' => $request->status_aktivitas,
        ];
        $dataJson = json_encode($dataArray);

        foreach ($users as $user) {
            $userIds[] = $user->id;
            $insertData[] = [
                'user_id' => $user->id,
                'type' => 'dpl_broadcast',
                'title' => $request->title,
                'message' => $request->message,
                'data' => $dataJson,
                'is_read' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Batch insert ke database
        foreach (array_chunk($insertData, 500) as $chunk) {
            Notification::insert($chunk);
        }

        // Kirim FCM Push
        NotificationService::sendBroadcastPush(
            $userIds,
            $request->title,
            $request->message,
            [
                'type' => 'dpl_broadcast',
                'sender_name' => auth()->user()->name,
                'url' => $request->filled('action_url') ? $request->action_url : route('notifications.index'),
                'category' => $request->input('category', 'Monev'),
                'urgency' => $request->input('urgency', 'normal'),
                'banner_image' => $bannerImageUrl,
            ],
            [
                'urgency' => $request->input('urgency', 'normal'),
                'banner_image' => $bannerImageUrl,
            ]
        );

        return back()->with('success', 'Broadcast berhasil dikirim ke ' . count($userIds) . ' mahasiswa bimbingan di Tahun Akademik Aktif!');
    }

    /**
     * Update riwayat broadcast Admin.
     */
    public function adminUpdate(Request $request, $id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'category' => 'nullable|string|max:50',
            'urgency' => 'nullable|in:normal,important,urgent',
            'action_url' => 'nullable|url|max:255',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $notif = Notification::where('type', 'broadcast')->findOrFail($id);

        $dataObj = !empty($notif->data) ? (is_string($notif->data) ? json_decode($notif->data, true) : $notif->data) : [];

        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('broadcasts', 'public');
            $dataObj['banner_image'] = asset('storage/' . $path);
        }

        $dataObj['category'] = $request->input('category', $dataObj['category'] ?? 'Akademik');
        $dataObj['urgency'] = $request->input('urgency', $dataObj['urgency'] ?? 'normal');
        $dataObj['action_url'] = $request->input('action_url');
        $dataObj['url'] = $request->filled('action_url') ? $request->action_url : route('notifications.index');

        $newDataJson = json_encode($dataObj);

        $updated = Notification::where('type', 'broadcast')
            ->where('title', $notif->title)
            ->where('message', $notif->message)
            ->where('created_at', $notif->created_at)
            ->update([
                'title' => $request->title,
                'message' => $request->message,
                'data' => $newDataJson,
                'updated_at' => now(),
            ]);

        return back()->with('success', "Berhasil memperbarui riwayat broadcast ($updated pesan penerima diperbarui).");
    }

    /**
     * Hapus riwayat broadcast Admin.
     */
    public function adminDestroy($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $notif = Notification::where('type', 'broadcast')->findOrFail($id);

        $deleted = Notification::where('type', 'broadcast')
            ->where('title', $notif->title)
            ->where('message', $notif->message)
            ->where('created_at', $notif->created_at)
            ->delete();

        return back()->with('success', "Berhasil menghapus riwayat broadcast ($deleted pesan penerima dihapus).");
    }

    /**
     * Update riwayat broadcast DPL.
     */
    public function dplUpdate(Request $request, $id)
    {
        if (!auth()->user()->hasRole('dpl')) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'category' => 'nullable|string|max:50',
            'urgency' => 'nullable|in:normal,important,urgent',
            'action_url' => 'nullable|url|max:255',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $notif = Notification::where('type', 'dpl_broadcast')
            ->where('data->sender_id', auth()->id())
            ->findOrFail($id);

        $dataObj = !empty($notif->data) ? (is_string($notif->data) ? json_decode($notif->data, true) : $notif->data) : [];

        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('broadcasts', 'public');
            $dataObj['banner_image'] = asset('storage/' . $path);
        }

        $dataObj['category'] = $request->input('category', $dataObj['category'] ?? 'Monev');
        $dataObj['urgency'] = $request->input('urgency', $dataObj['urgency'] ?? 'normal');
        $dataObj['action_url'] = $request->input('action_url');
        $dataObj['url'] = $request->filled('action_url') ? $request->action_url : route('notifications.index');

        $newDataJson = json_encode($dataObj);

        $updated = Notification::where('type', 'dpl_broadcast')
            ->where('title', $notif->title)
            ->where('message', $notif->message)
            ->where('created_at', $notif->created_at)
            ->update([
                'title' => $request->title,
                'message' => $request->message,
                'data' => $newDataJson,
                'updated_at' => now(),
            ]);

        return back()->with('success', "Berhasil memperbarui riwayat broadcast DPL ($updated pesan penerima diperbarui).");
    }

    /**
     * Hapus riwayat broadcast DPL.
     */
    public function dplDestroy($id)
    {
        if (!auth()->user()->hasRole('dpl')) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $notif = Notification::where('type', 'dpl_broadcast')
            ->where('data->sender_id', auth()->id())
            ->findOrFail($id);

        $deleted = Notification::where('type', 'dpl_broadcast')
            ->where('title', $notif->title)
            ->where('message', $notif->message)
            ->where('created_at', $notif->created_at)
            ->delete();

        return back()->with('success', "Berhasil menghapus riwayat broadcast DPL ($deleted pesan penerima dihapus).");
    }
}

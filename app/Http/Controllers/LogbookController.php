<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\LogbookPhoto;
use App\Models\User;
use App\Services\NotificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LogbookController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $tipe = $request->query('tipe', 'individu');
        
        $logbooksIndividu = Logbook::where('user_id', $user->id)
            ->where('is_kelompok', false)
            ->with(['photos'])
            ->orderBy('tanggal', 'desc')
            ->paginate(10, ['*'], 'page_individu');

        $logbooksKelompok = Logbook::where('kelompok_id', $user->kelompok_id)
            ->where('is_kelompok', true)
            ->with(['user', 'photos'])
            ->orderBy('tanggal', 'desc')
            ->paginate(10, ['*'], 'page_kelompok');

        // Backward compatibility for view that expects $logbooks
        $logbooks = $tipe === 'kelompok' ? $logbooksKelompok : $logbooksIndividu;

        // Tentukan query aktivitas berdasarkan tipe terpilih
        if ($tipe === 'kelompok') {
            $myActivitiesQuery = function($query) use ($user) {
                $query->where('kelompok_id', $user->kelompok_id)
                      ->where('is_kelompok', true);
            };
        } else {
            $myActivitiesQuery = function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('is_kelompok', false);
            };
        }

        $stats = [
            'total' => Logbook::where($myActivitiesQuery)->count(),
            'draft' => Logbook::where($myActivitiesQuery)->where('status', 'draft')->count(),
            'submitted' => Logbook::where($myActivitiesQuery)->where('status', 'submitted')->count(),
            'approved' => Logbook::where($myActivitiesQuery)->where('status', 'approved')->count(),
            'rejected' => Logbook::where($myActivitiesQuery)->where('status', 'rejected')->count(),
        ];

        // Data untuk kalender
        $allLogbooks = Logbook::where($myActivitiesQuery)->get();
        $events = $allLogbooks->map(function($logbook) {
            return [
                'id' => $logbook->id,
                'title' => ($logbook->is_kelompok ? '[Kelompok] ' : '') . $logbook->judul,
                'start' => $logbook->tanggal->format('Y-m-d'),
                'backgroundColor' => $this->getStatusColor($logbook->status),
                'borderColor' => $this->getStatusColor($logbook->status),
                'textColor' => '#ffffff'
            ];
        });

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
        
        // Pluck logbookDates for mobile calendar view
        $logbookDates = Logbook::where(function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhere(function ($q) use ($user) {
                      $q->where('kelompok_id', $user->kelompok_id)
                        ->where('is_kelompok', true);
                  });
        })->whereIn('status', ['submitted', 'approved'])->pluck('tanggal')->map(fn($d) => $d->format('Y-m-d'))->toArray();

        if ($isMobile && auth()->user()->hasRole('mahasiswa')) {
            return view('mobile.logbooks.index', compact('logbooksIndividu', 'logbooksKelompok', 'logbookDates'));
        }

        return view('logbooks.index', compact('logbooks', 'logbooksIndividu', 'logbooksKelompok', 'stats', 'events', 'tipe'));
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
            'draft' => '#ffc107',     // warning
            'submitted' => '#17a2b8', // info
            'approved' => '#28a745',  // success
            'rejected' => '#dc3545',  // danger
            default => '#6c757d'      // secondary
        };
    }

    public function create()
    {
        // Deteksi device untuk mahasiswa
        $isMobile = session('is_mobile_device', false);
        
        if ($isMobile && auth()->user()->hasRole('mahasiswa')) {
            return view('mobile.logbooks.create');
        }

        return view('logbooks.create');
    }

    public function store(Request $request)
    {
        // Validasi kelompok terlebih dahulu
        if (!auth()->user()->kelompok_id) {
            return response()->json([
                'message' => 'Anda belum terdaftar dalam kelompok manapun. Silakan hubungi admin untuk ditambahkan ke dalam kelompok.',
                'status' => 'error'
            ], 422);
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'judul' => 'required|string|max:255',
            'jenis' => 'required|in:individu,desa,kecamatan',
            'keterangan' => 'required|string',
            'lokasi' => 'required|string',
            'photos' => 'required|array|min:1',
            'photos.*' => 'image|mimes:jpg,jpeg,png|max:20480',
            'attachments' => 'sometimes|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png,gif|max:20480',
            'status' => 'required|in:draft,submitted',
            'is_kelompok' => 'nullable'
        ]);

        // Create logbook
        $logbook = Logbook::create([
            'user_id' => auth()->id(),
            'kelompok_id' => auth()->user()->kelompok_id,
            'tanggal' => $validated['tanggal'],
            'waktu_mulai' => $validated['waktu_mulai'],
            'waktu_selesai' => $validated['waktu_selesai'],
            'judul' => $validated['judul'],
            'jenis' => $validated['jenis'],
            'keterangan' => $validated['keterangan'],
            'lokasi' => $validated['lokasi'],
            'status' => $validated['status'],
            'is_kelompok' => $request->boolean('is_kelompok')
        ]);

        // Upload photos
        foreach($request->file('photos') as $photo) {
            $path = $photo->store('logbook-photos', 'public');
            LogbookPhoto::create([
                'logbook_id' => $logbook->id,
                'path' => $path
            ]);
        }

        // Upload attachments
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach($request->file('attachments') as $attachment) {
                $path = $attachment->store('logbook-attachments', 'public');
                $attachments[] = [
                    'name' => $attachment->getClientOriginalName(),
                    'path' => $path,
                    'size' => $attachment->getSize(),
                    'type' => $attachment->getMimeType(),
                    'extension' => $attachment->getClientOriginalExtension()
                ];
            }
            
            $logbook->update(['attachments' => $attachments]);
        }

        // Check if redirect to mobile
        if ($request->has('redirect_to') && $request->redirect_to === 'mobile') {
            return redirect()->route('mobile.logbooks')
                ->with('success', 'Logbook berhasil disimpan.');
        }

        // Check if JSON request
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Logbook berhasil disimpan',
                'status' => 'success',
                'data' => $logbook
            ]);
        }

        return redirect()->route('logbooks.index')
            ->with('success', 'Logbook berhasil disimpan.');
    }

    public function show(Logbook $logbook)
    {
        // $this->authorize('view', $logbook);
        
        // Deteksi device untuk mahasiswa
        $isMobile = session('is_mobile_device', false);
        
        if ($isMobile && auth()->user()->hasRole('mahasiswa')) {
            return view('mobile.logbooks.show', compact('logbook'));
        }

        return view('logbooks.show', compact('logbook'));
    }

    public function edit(Logbook $logbook)
    {
        // $this->authorize('update', $logbook);
        
        // Deteksi device untuk mahasiswa
        $isMobile = session('is_mobile_device', false);
        
        if ($isMobile && auth()->user()->hasRole('mahasiswa')) {
            return view('mobile.logbooks.edit', compact('logbook'));
        }
        
        return view('logbooks.edit', compact('logbook'));
    }

    public function update(Request $request, Logbook $logbook)
    {
        // $this->authorize('update', $logbook);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'judul' => 'required|string|max:255',
            'jenis' => 'required|in:individu,desa,kecamatan',
            'keterangan' => 'required|string',
            'lokasi' => 'required|string',
            'photos' => 'sometimes|array',
            'photos.*' => 'image|mimes:jpg,jpeg,png|max:20480',
            'attachments' => 'sometimes|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png,gif|max:20480',
            'status' => 'required|in:draft,submitted',
            'is_kelompok' => 'nullable'
        ]);

        // Update logbook
        $logbook->update([
            'tanggal' => $validated['tanggal'],
            'waktu_mulai' => $validated['waktu_mulai'],
            'waktu_selesai' => $validated['waktu_selesai'],
            'judul' => $validated['judul'],
            'jenis' => $validated['jenis'],
            'keterangan' => $validated['keterangan'],
            'lokasi' => $validated['lokasi'],
            'status' => $validated['status'],
            'is_kelompok' => $request->boolean('is_kelompok')
        ]);

        // Upload new photos if any
        if($request->hasFile('photos')) {
            foreach($request->file('photos') as $photo) {
                $path = $photo->store('logbook-photos', 'public');
                LogbookPhoto::create([
                    'logbook_id' => $logbook->id,
                    'path' => $path
                ]);
            }
        }

        // Upload new attachments if any
        if ($request->hasFile('attachments')) {
            $existingAttachments = $logbook->attachments ?? [];
            $newAttachments = [];
            
            foreach($request->file('attachments') as $attachment) {
                $path = $attachment->store('logbook-attachments', 'public');
                $newAttachments[] = [
                    'name' => $attachment->getClientOriginalName(),
                    'path' => $path,
                    'size' => $attachment->getSize(),
                    'type' => $attachment->getMimeType(),
                    'extension' => $attachment->getClientOriginalExtension()
                ];
            }
            
            $logbook->update(['attachments' => array_merge($existingAttachments, $newAttachments)]);
        }

        // Check if redirect to mobile
        if ($request->has('redirect_to') && $request->redirect_to === 'mobile') {
            return redirect()->route('mobile.logbooks.show', $logbook)
                ->with('success', 'Logbook berhasil diperbarui.');
        }

        // Check if JSON request
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Logbook berhasil diperbarui',
                'status' => 'success',
                'data' => $logbook
            ]);
        }

        return redirect()->route('logbooks.show', $logbook)
            ->with('success', 'Logbook berhasil diperbarui.');
    }

    public function destroy(Logbook $logbook)
    {
        // $this->authorize('delete', $logbook);

        // Delete photos
        foreach($logbook->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
            $photo->delete();
        }

        // Delete attachments
        if ($logbook->attachments) {
            foreach($logbook->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $logbook->delete();

        return response()->json([
            'message' => 'Logbook berhasil dihapus',
            'status' => 'success'
        ]);
    }

    public function deletePhoto(LogbookPhoto $photo)
    {
        // $this->authorize('delete', $photo->logbook);

        try {
            Storage::disk('public')->delete($photo->path);
            $photo->delete();

            return response()->json([
                'message' => 'Foto berhasil dihapus',
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus foto: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }

    public function deleteAttachment(Request $request, Logbook $logbook)
    {
        // $this->authorize('delete', $logbook);

        $attachmentIndex = $request->input('index');
        $attachments = $logbook->attachments ?? [];

        if (isset($attachments[$attachmentIndex])) {
            $attachment = $attachments[$attachmentIndex];
            Storage::disk('public')->delete($attachment['path']);
            
            // Remove attachment from array
            unset($attachments[$attachmentIndex]);
            $attachments = array_values($attachments); // Re-index array
            
            $logbook->update(['attachments' => $attachments]);

            return response()->json([
                'message' => 'File berhasil dihapus',
                'status' => 'success'
            ]);
        }

        return response()->json([
            'message' => 'File tidak ditemukan',
            'status' => 'error'
        ], 404);
    }

    public function submit(Logbook $logbook)
    {
        // $this->authorize('submit', $logbook);

        $logbook->update(['status' => 'submitted']);

        return redirect()->route('logbooks.index')
            ->with('success', 'Logbook berhasil disubmit untuk review.');
    }

    public function review(Request $request, Logbook $logbook)
    {
        // $this->authorize('review', $logbook);

        $validated = $request->validate([
            'komentar' => 'nullable|string',
            'action' => 'required|in:approve,reject'
        ]);

        $status = $validated['action'] === 'approve' ? 'approved' : 'rejected';

        $logbook->update([
            'status' => $status,
            'komentar_dpl' => $validated['komentar']
        ]);

        $message = $status === 'approved' ? 'disetujui' : 'ditolak';
        return redirect()->route('logbooks.pending')
            ->with('success', "Logbook berhasil $message.");
    }

    public function pending(Request $request)
    {
        $tahunAktif = \App\Models\TahunAkademik::getAktif();
        $semesterAktif = \App\Models\Semester::getAktif();

        // Ambil logbook yang pending (submitted) dari mahasiswa bimbingan DPL
        $query = Logbook::whereHas('user', function($query) use ($tahunAktif, $semesterAktif) {
            $query->whereHas('kelompok', function($q) use ($tahunAktif, $semesterAktif) {
                $q->where('dpl_id', auth()->id());
                if ($tahunAktif && $semesterAktif) {
                    $q->where('tahun_akademik_id', $tahunAktif->id)
                      ->where('semester_id', $semesterAktif->id);
                }
            });
        })
        ->where('status', 'submitted')
        ->with(['user', 'kelompok', 'photos']);

        // Filter berdasarkan jenis kegiatan
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter berdasarkan nama mahasiswa
        if ($request->filled('nama')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->nama . '%');
            });
        }

        // Filter berdasarkan jurusan
        if ($request->filled('jurusan')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('jurusan', $request->jurusan);
            });
        }

        $logbooks = $query->orderBy('created_at', 'desc')->paginate(10);

        // Data untuk filter dropdown
        $jenisKegiatan = ['individu' => 'Individu', 'desa' => 'Desa', 'kecamatan' => 'Kecamatan'];
        $jurusanList = User::whereHas('kelompok', function($q) {
            $q->where('dpl_id', auth()->id());
        })->distinct()->pluck('jurusan')->filter()->values();

        return view('logbooks.pending', compact('logbooks', 'jenisKegiatan', 'jurusanList'));
    }

    public function approve(Logbook $logbook)
    {
        // Pastikan DPL hanya bisa approve logbook dari mahasiswa bimbingannya
        if ($logbook->user->kelompok->dpl_id != auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk menyetujui logbook ini.');
        }

        $logbook->update([
            'status' => 'approved',
            'komentar_dpl' => 'Logbook disetujui oleh DPL'
        ]);

        // Kirim notifikasi ke mahasiswa
        NotificationService::logbookApproved($logbook);

        return redirect()->route('logbooks.pending')
            ->with('success', 'Logbook berhasil disetujui.');
    }

    public function reject(Logbook $logbook)
    {
        // Pastikan DPL hanya bisa reject logbook dari mahasiswa bimbingannya
        if ($logbook->user->kelompok->dpl_id != auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk menolak logbook ini.');
        }

        $logbook->update([
            'status' => 'rejected',
            'komentar_dpl' => 'Logbook ditolak oleh DPL'
        ]);

        // Kirim notifikasi ke mahasiswa
        NotificationService::logbookRejected($logbook);

        return redirect()->route('logbooks.pending')
            ->with('success', 'Logbook berhasil ditolak.');
    }

    public function approveAll()
    {
        $tahunAktif = \App\Models\TahunAkademik::getAktif();
        $semesterAktif = \App\Models\Semester::getAktif();

        $logbooks = Logbook::whereHas('user', function($query) use ($tahunAktif, $semesterAktif) {
            $query->whereHas('kelompok', function($q) use ($tahunAktif, $semesterAktif) {
                $q->where('dpl_id', auth()->id());
                if ($tahunAktif && $semesterAktif) {
                    $q->where('tahun_akademik_id', $tahunAktif->id)
                      ->where('semester_id', $semesterAktif->id);
                }
            });
        })
        ->where('status', 'submitted')
        ->get();

        foreach ($logbooks as $logbook) {
            $logbook->update([
                'status' => 'approved',
                'komentar_dpl' => 'Logbook disetujui massal oleh DPL'
            ]);
            
            // Kirim notifikasi ke mahasiswa
            NotificationService::logbookApproved($logbook);
        }

        return redirect()->route('logbooks.pending')
            ->with('success', 'Semua logbook pending berhasil disetujui.');
    }

    public function rejectAll()
    {
        $tahunAktif = \App\Models\TahunAkademik::getAktif();
        $semesterAktif = \App\Models\Semester::getAktif();

        $logbooks = Logbook::whereHas('user', function($query) use ($tahunAktif, $semesterAktif) {
            $query->whereHas('kelompok', function($q) use ($tahunAktif, $semesterAktif) {
                $q->where('dpl_id', auth()->id());
                if ($tahunAktif && $semesterAktif) {
                    $q->where('tahun_akademik_id', $tahunAktif->id)
                      ->where('semester_id', $semesterAktif->id);
                }
            });
        })
        ->where('status', 'submitted')
        ->get();

        foreach ($logbooks as $logbook) {
            $logbook->update([
                'status' => 'rejected',
                'komentar_dpl' => 'Logbook ditolak massal oleh DPL'
            ]);
            
            // Kirim notifikasi ke mahasiswa
            NotificationService::logbookRejected($logbook);
        }

        return redirect()->route('logbooks.pending')
            ->with('success', 'Semua logbook pending berhasil ditolak.');
    }

    public function exportPdf(Logbook $logbook)
    {
        $user = auth()->user();
        
        // Authorization: Admin can view all, DPL can view their students, Mahasiswa can view their own or their group's
        if ($user->hasRole('mahasiswa')) {
            $isOwnLogbook = $logbook->user_id == $user->id;
            $isSameKelompok = $logbook->is_kelompok && $user->kelompok_id && $user->kelompok_id == $logbook->kelompok_id;
            
            if (!$isOwnLogbook && !$isSameKelompok) {
                abort(403, 'Unauthorized - Not your logbook');
            }
        } elseif ($user->hasRole('dpl')) {
            $isBimbingan = User::whereHas('kelompok', function($q) use ($user) {
                $q->where('dpl_id', $user->id);
            })->where('id', $logbook->user_id)->exists();
            
            if (!$isBimbingan) {
                abort(403, 'Unauthorized');
            }
        }

        $logbook->load(['user', 'kelompok', 'photos']);
        
        // Load image base64 for logo
        $logoPath = public_path('images/ush.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }

        $pdf = Pdf::loadView('logbooks.pdf', compact('logbook', 'logoBase64'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Logbook_' . $logbook->judul . '.pdf');
    }

    public function exportPdfAll(Request $request)
    {
        $user = auth()->user();
        $tipe = $request->query('tipe', 'individu');

        if ($tipe === 'kelompok') {
            $query = function($q) use ($user) {
                $q->where('kelompok_id', $user->kelompok_id)
                  ->where('is_kelompok', true);
            };
        } else {
            $query = function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->where('is_kelompok', false);
            };
        }

        $logbooks = Logbook::where($query)
            ->with(['user', 'kelompok', 'photos'])
            ->orderBy('tanggal', 'asc')
            ->get();

        if ($logbooks->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data logbook untuk di-export.');
        }

        $logoPath = public_path('images/ush.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }

        $pdf = Pdf::loadView('logbooks.pdf_all', compact('logbooks', 'logoBase64', 'tipe'))
            ->setPaper('a4', 'portrait');

        $filename = 'Semua_Logbook_' . ucfirst($tipe) . '_' . $user->name . '.pdf';
        return $pdf->stream($filename);
    }
} 
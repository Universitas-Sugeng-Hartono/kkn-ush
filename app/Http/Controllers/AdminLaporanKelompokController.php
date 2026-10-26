<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use App\Models\LaporanKelompok;
use App\Models\Semester;
use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class AdminLaporanKelompokController extends Controller
{
    /**
     * Tampilkan semua laporan kelompok untuk admin (dengan filter).
     */
    public function index(Request $request)
    {
        $tahunAktif = TahunAkademik::getAktif();
        $semesterAktif = Semester::getAktif();

        [$tahun_akademik_id, $semester_id] = $this->resolvePeriode($request, $tahunAktif, $semesterAktif);
        $selected_kelompok_id = $request->query('kelompok_id');
        $selected_dpl_id = $request->query('dpl_id');
        $search = trim((string) $request->query('search', ''));

        // List kelompok untuk dropdown (mengikuti filter periode & DPL)
        $kelompokList = Kelompok::query()
            ->when($tahun_akademik_id, fn ($q) => $q->where('tahun_akademik_id', $tahun_akademik_id))
            ->when($semester_id, fn ($q) => $q->where('semester_id', $semester_id))
            ->when($selected_dpl_id, fn ($q) => $q->where('dpl_id', $selected_dpl_id))
            ->orderBy('nama_kelompok')
            ->get();

        $laporan = $this->buildQuery($tahun_akademik_id, $semester_id, $selected_dpl_id, $selected_kelompok_id, $search)
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Statistik ringkas
        $statsQuery = $this->buildQuery($tahun_akademik_id, $semester_id, $selected_dpl_id, $selected_kelompok_id, $search);
        $stats = [
            'total_file' => (clone $statsQuery)->count(),
            'total_kelompok' => (clone $statsQuery)->distinct('kelompok_id')->count('kelompok_id'),
            'total_size' => (int) (clone $statsQuery)->sum('file_size'),
        ];

        $tahunAkademikList = TahunAkademik::orderBy('nama', 'desc')->get();
        $semesterList = Semester::orderBy('nama', 'asc')->get();
        $dplList = User::role('dpl')->orderBy('name')->get(['id', 'name']);

        return view('admin.laporan-kelompok.index', compact(
            'laporan',
            'stats',
            'kelompokList',
            'dplList',
            'tahunAktif',
            'semesterAktif',
            'tahunAkademikList',
            'semesterList',
            'tahun_akademik_id',
            'semester_id',
            'selected_kelompok_id',
            'selected_dpl_id',
            'search'
        ));
    }

    /**
     * Lihat detail satu laporan (preview inline jika PDF/gambar).
     */
    public function show(LaporanKelompok $laporanKelompok)
    {
        $laporanKelompok->load(['user', 'kelompok.dpl', 'kelompok.lokasi', 'kelompok.tahunAkademik', 'kelompok.semester']);

        $fileExists = $laporanKelompok->file_path && Storage::disk('public')->exists($laporanKelompok->file_path);
        $mime = $laporanKelompok->file_mime ?? '';
        $canPreview = $fileExists && (Str::startsWith($mime, 'image/') || $mime === 'application/pdf');

        return view('admin.laporan-kelompok.show', compact('laporanKelompok', 'fileExists', 'canPreview'));
    }

    /**
     * Stream file secara inline untuk preview di browser.
     */
    public function preview(LaporanKelompok $laporanKelompok)
    {
        if (!$laporanKelompok->file_path || !Storage::disk('public')->exists($laporanKelompok->file_path)) {
            abort(404);
        }

        return response()->file(Storage::disk('public')->path($laporanKelompok->file_path), [
            'Content-Type' => $laporanKelompok->file_mime ?: 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="' . addslashes($laporanKelompok->file_original_name) . '"',
        ]);
    }

    /**
     * Unduh semua laporan sesuai filter sebagai ZIP (dikelompokkan per folder kelompok).
     */
    public function downloadZip(Request $request)
    {
        if (!class_exists(ZipArchive::class)) {
            return back()->with('error', 'Ekstensi ZIP PHP tidak tersedia di server.');
        }

        [$tahun_akademik_id, $semester_id] = $this->resolvePeriode($request, TahunAkademik::getAktif(), Semester::getAktif());

        $items = $this->buildQuery(
            $tahun_akademik_id,
            $semester_id,
            $request->query('dpl_id'),
            $request->query('kelompok_id'),
            trim((string) $request->query('search', ''))
        )->orderBy('kelompok_id')->orderBy('created_at')->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'Tidak ada laporan untuk diunduh sesuai filter.');
        }

        $tmpDir = storage_path('app/tmp');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        $zipName = 'laporan-kelompok-' . now()->format('Ymd-His') . '.zip';
        $zipPath = $tmpDir . DIRECTORY_SEPARATOR . Str::random(8) . '-' . $zipName;

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'Gagal membuat file ZIP.');
        }

        $added = 0;
        $usedNames = [];
        foreach ($items as $item) {
            if (!$item->file_path || !Storage::disk('public')->exists($item->file_path)) {
                continue;
            }

            $folder = Str::slug($item->kelompok?->nama_kelompok ?: (string) $item->kelompok_id);
            if (!Str::startsWith($folder, 'kelompok')) {
                $folder = 'kelompok-' . $folder;
            }
            $ext = pathinfo($item->file_original_name, PATHINFO_EXTENSION) ?: pathinfo($item->file_path, PATHINFO_EXTENSION);
            $base = Str::slug($item->judul ?: pathinfo($item->file_original_name, PATHINFO_FILENAME)) ?: 'laporan-' . $item->id;

            $entry = "{$folder}/{$base}.{$ext}";
            $i = 1;
            while (isset($usedNames[$entry])) {
                $entry = "{$folder}/{$base}-" . (++$i) . ".{$ext}";
            }
            $usedNames[$entry] = true;

            $zip->addFile(Storage::disk('public')->path($item->file_path), $entry);
            $added++;
        }

        $zip->close();

        if ($added === 0) {
            @unlink($zipPath);
            return back()->with('error', 'File laporan tidak ditemukan di penyimpanan.');
        }

        return response()->download($zipPath, $zipName)->deleteFileAfterSend(true);
    }

    private function resolvePeriode(Request $request, $tahunAktif, $semesterAktif): array
    {
        if (!$request->has('tahun_akademik_id') && !$request->has('semester_id')) {
            return [$tahunAktif?->id, $semesterAktif?->id];
        }

        return [$request->query('tahun_akademik_id'), $request->query('semester_id')];
    }

    private function buildQuery($tahun_akademik_id, $semester_id, $dpl_id, $kelompok_id, $search)
    {
        return LaporanKelompok::with(['user', 'kelompok.dpl'])
            ->when($tahun_akademik_id || $semester_id || $dpl_id, function ($query) use ($tahun_akademik_id, $semester_id, $dpl_id) {
                $query->whereHas('kelompok', function ($q) use ($tahun_akademik_id, $semester_id, $dpl_id) {
                    if ($tahun_akademik_id) {
                        $q->where('tahun_akademik_id', $tahun_akademik_id);
                    }
                    if ($semester_id) {
                        $q->where('semester_id', $semester_id);
                    }
                    if ($dpl_id) {
                        $q->where('dpl_id', $dpl_id);
                    }
                });
            })
            ->when($kelompok_id, fn ($q) => $q->where('kelompok_id', $kelompok_id))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($w) use ($search) {
                    $w->where('judul', 'like', "%{$search}%")
                        ->orWhere('file_original_name', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
                });
            });
    }
}

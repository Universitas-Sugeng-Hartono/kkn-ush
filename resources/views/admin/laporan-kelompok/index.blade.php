<x-app-layout>
    @php
        $formatSize = function ($bytes) {
            $bytes = (int) $bytes;
            if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
            if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
            if ($bytes >= 1024) return number_format($bytes / 1024, 1) . ' KB';
            return $bytes . ' B';
        };
        $fileIcon = function ($name) {
            $ext = strtolower(pathinfo($name ?? '', PATHINFO_EXTENSION));
            return match (true) {
                $ext === 'pdf' => ['fa-file-pdf', 'text-danger'],
                in_array($ext, ['doc', 'docx']) => ['fa-file-word', 'text-primary'],
                in_array($ext, ['xls', 'xlsx', 'csv']) => ['fa-file-excel', 'text-success'],
                in_array($ext, ['ppt', 'pptx']) => ['fa-file-powerpoint', 'text-warning'],
                in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']) => ['fa-file-image', 'text-info'],
                in_array($ext, ['zip', 'rar', '7z']) => ['fa-file-archive', 'text-secondary'],
                default => ['fa-file-alt', 'text-muted'],
            };
        };
        $filterQuery = request()->only(['tahun_akademik_id', 'semester_id', 'dpl_id', 'kelompok_id', 'search']);
    @endphp

    <div class="container-fluid">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <div>
                <h2 class="fw-bold mb-0"><i class="fas fa-folder-open me-2 text-primary"></i>Laporan Kelompok</h2>
                <p class="text-muted mb-0">
                    Lihat dan unduh laporan yang diupload oleh seluruh kelompok KKN
                    @if($tahunAktif && $semesterAktif)
                        &middot; Periode aktif: {{ $tahunAktif->nama }} - {{ $semesterAktif->nama }}
                    @endif
                </p>
            </div>
            <a href="{{ route('admin.laporan-kelompok.download-zip', $filterQuery) }}"
               class="btn btn-success {{ $stats['total_file'] == 0 ? 'disabled' : '' }}"
               id="btnDownloadZip">
                <i class="fas fa-file-archive me-2"></i>Unduh Semua (ZIP)
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Statistik --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;">
                            <i class="fas fa-file-alt fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total File</div>
                            <div class="h4 mb-0 fw-bold">{{ $stats['total_file'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Kelompok Mengupload</div>
                            <div class="h4 mb-0 fw-bold">{{ $stats['total_kelompok'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;">
                            <i class="fas fa-hdd fa-lg"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Ukuran</div>
                            <div class="h4 mb-0 fw-bold">{{ $formatSize($stats['total_size']) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.laporan-kelompok.index') }}" class="row g-3 align-items-end" id="filterForm">
                    <div class="col-md-2">
                        <label class="form-label fw-bold small">Tahun Akademik</label>
                        <select name="tahun_akademik_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Tahun</option>
                            @foreach($tahunAkademikList as $ta)
                                <option value="{{ $ta->id }}" {{ $tahun_akademik_id == $ta->id ? 'selected' : '' }}>
                                    {{ $ta->nama }} {{ $ta->is_aktif ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold small">Semester</label>
                        <select name="semester_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Semester</option>
                            @foreach($semesterList as $sem)
                                <option value="{{ $sem->id }}" {{ $semester_id == $sem->id ? 'selected' : '' }}>
                                    {{ $sem->nama }} {{ $sem->is_aktif ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold small">DPL</label>
                        <select name="dpl_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua DPL</option>
                            @foreach($dplList as $dpl)
                                <option value="{{ $dpl->id }}" {{ $selected_dpl_id == $dpl->id ? 'selected' : '' }}>{{ $dpl->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold small">Kelompok</label>
                        <select name="kelompok_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Kelompok</option>
                            @foreach($kelompokList as $k)
                                <option value="{{ $k->id }}" {{ $selected_kelompok_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kelompok }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Cari</label>
                        <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Judul, nama file, atau uploader...">
                    </div>
                    <div class="col-md-1 d-flex gap-1">
                        <button type="submit" class="btn btn-sm btn-primary w-100" title="Cari"><i class="fas fa-search"></i></button>
                        <a href="{{ route('admin.laporan-kelompok.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset"><i class="fas fa-undo"></i></a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Laporan</h5>
                <span class="badge bg-secondary">{{ $laporan->total() }} file</span>
            </div>
            <div class="card-body">
                @if($laporan->isEmpty())
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-folder-open fa-3x mb-3 opacity-50"></i>
                        <div>Belum ada laporan sesuai filter.</div>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Kelompok</th>
                                    <th>Judul / File</th>
                                    <th>Uploader</th>
                                    <th>Ukuran</th>
                                    <th>Tanggal</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($laporan as $item)
                                    @php
                                        [$icon, $color] = $fileIcon($item->file_original_name);
                                        $exists = $item->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->file_path);
                                    @endphp
                                    <tr>
                                        <td class="text-muted">{{ $laporan->firstItem() + $loop->index }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ $item->kelompok?->nama_kelompok ?? '-' }}</div>
                                            <div class="text-muted small">DPL: {{ $item->kelompok?->dpl?->name ?? '-' }}</div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas {{ $icon }} {{ $color }} fa-lg me-2"></i>
                                                <div>
                                                    <div class="fw-semibold">{{ $item->judul ?: $item->file_original_name }}</div>
                                                    <div class="text-muted small">{{ $item->file_original_name }}</div>
                                                    @unless($exists)
                                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle mt-1"><i class="fas fa-exclamation-triangle me-1"></i>File tidak ditemukan</span>
                                                    @endunless
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $item->user?->name ?? '-' }}</td>
                                        <td class="text-nowrap">{{ $formatSize($item->file_size) }}</td>
                                        <td class="text-nowrap">{{ $item->created_at?->format('d M Y H:i') }}</td>
                                        <td class="text-end text-nowrap">
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.laporan-kelompok.show', $item) }}" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($exists)
                                                <a class="btn btn-sm btn-success" href="{{ route('laporan-kelompok.download', $item) }}" title="Unduh">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled title="File tidak ditemukan">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $laporan->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

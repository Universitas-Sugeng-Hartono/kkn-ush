<x-app-layout>
    @php
        $bytes = (int) $laporanKelompok->file_size;
        $size = $bytes >= 1048576 ? number_format($bytes / 1048576, 2) . ' MB' : number_format($bytes / 1024, 1) . ' KB';
        $kelompok = $laporanKelompok->kelompok;
        $isImage = \Illuminate\Support\Str::startsWith($laporanKelompok->file_mime ?? '', 'image/');
    @endphp

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <div>
                <h2 class="fw-bold mb-0"><i class="fas fa-file-alt me-2 text-primary"></i>Detail Laporan</h2>
                <p class="text-muted mb-0">{{ $laporanKelompok->judul ?: $laporanKelompok->file_original_name }}</p>
            </div>
            <div class="d-flex gap-2">
                @if($fileExists)
                    <a href="{{ route('laporan-kelompok.download', $laporanKelompok) }}" class="btn btn-success">
                        <i class="fas fa-download me-2"></i>Unduh
                    </a>
                @endif
                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('admin.laporan-kelompok.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header"><h5 class="mb-0">Informasi</h5></div>
                    <div class="card-body">
                        <dl class="mb-0">
                            <dt class="small text-muted">Judul</dt>
                            <dd>{{ $laporanKelompok->judul ?: '-' }}</dd>
                            <dt class="small text-muted">Keterangan</dt>
                            <dd>{!! nl2br(e($laporanKelompok->keterangan ?: '-')) !!}</dd>
                            <dt class="small text-muted">Kelompok</dt>
                            <dd>{{ $kelompok?->nama_kelompok ?? '-' }}</dd>
                            <dt class="small text-muted">DPL</dt>
                            <dd>{{ $kelompok?->dpl?->name ?? '-' }}</dd>
                            <dt class="small text-muted">Lokasi</dt>
                            <dd>{{ $kelompok?->lokasi ? trim($kelompok->lokasi->nama_desa . ', ' . $kelompok->lokasi->nama_kecamatan, ', ') : '-' }}</dd>
                            <dt class="small text-muted">Periode</dt>
                            <dd>{{ $kelompok?->tahunAkademik?->nama ?? '-' }} - {{ $kelompok?->semester?->nama ?? '-' }}</dd>
                            <dt class="small text-muted">Diupload oleh</dt>
                            <dd>{{ $laporanKelompok->user?->name ?? '-' }}</dd>
                            <dt class="small text-muted">Tanggal Upload</dt>
                            <dd>{{ $laporanKelompok->created_at?->format('d M Y H:i') }}</dd>
                            <dt class="small text-muted">File</dt>
                            <dd class="mb-0">{{ $laporanKelompok->file_original_name }} <span class="text-muted">({{ $size }})</span></dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header"><h5 class="mb-0">Pratinjau</h5></div>
                    <div class="card-body">
                        @if(!$fileExists)
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>File tidak ditemukan di penyimpanan.
                            </div>
                        @elseif($canPreview && $isImage)
                            <img src="{{ route('admin.laporan-kelompok.preview', $laporanKelompok) }}" class="img-fluid rounded" alt="{{ $laporanKelompok->judul }}">
                        @elseif($canPreview)
                            <iframe src="{{ route('admin.laporan-kelompok.preview', $laporanKelompok) }}" style="width:100%;height:75vh;border:0;" class="rounded"></iframe>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-file fa-3x mb-3 opacity-50"></i>
                                <p>Pratinjau tidak tersedia untuk jenis file ini.</p>
                                <a href="{{ route('laporan-kelompok.download', $laporanKelompok) }}" class="btn btn-success">
                                    <i class="fas fa-download me-2"></i>Unduh File
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

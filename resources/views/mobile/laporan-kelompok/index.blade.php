@extends('layouts.mobile-app')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h2>Laporan Kelompok</h2>
                <p class="date-info">Kelola berkas laporan kelompok Anda</p>
            </div>
            <div class="header-actions">
                <button class="btn-action" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
    </div>

    @if(!$kelompok)
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-users-slash"></i>
            </div>
            <p>Belum Ada Kelompok</p>
            <span>Anda belum tergabung ke kelompok manapun. Silakan hubungi Admin.</span>
        </div>
    @else
        <!-- Group Info Card -->
        <div class="group-info-card mx-3 mb-4">
            <div class="info-content">
                <div class="info-main">
                    <span class="group-label">Kelompok Aktif</span>
                    <h3 class="group-name">{{ $kelompok->nama_kelompok }}</h3>
                </div>
                <div class="info-meta">
                    @if($kelompok->angkatan)
                        <div class="meta-item">
                            <i class="fas fa-graduation-cap"></i>
                            <span>{{ $kelompok->angkatan->nama_angkatan }}</span>
                        </div>
                    @endif
                    <div class="meta-item">
                        <i class="fas fa-file-alt"></i>
                        <span>{{ $laporan->count() }} Laporan</span>
                    </div>
                </div>
            </div>
            <div class="info-icon">
                <i class="fas fa-users"></i>
            </div>
        </div>

        <!-- Reports List -->
        <div class="reports-section mx-3 pb-5">
            <div class="section-header d-flex justify-content-between align-items-center mb-3">
                <h3 class="section-title">Daftar Berkas</h3>
                @if(!$laporan->isEmpty())
                    <span class="badge-count">{{ $laporan->count() }} Berkas</span>
                @endif
            </div>

            @if($laporan->isEmpty())
                <div class="empty-state py-5">
                    <div class="empty-icon small">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <p>Belum Ada Laporan</p>
                    <span>Klik tombol tambah untuk mengunggah laporan pertama kelompok Anda.</span>
                </div>
            @else
                <div class="reports-list">
                    @foreach($laporan as $item)
                        <div class="report-card mb-3">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="file-icon {{ str_contains(strtolower($item->file_original_name), '.pdf') ? 'pdf' : 'doc' }}">
                                        @if(str_contains(strtolower($item->file_original_name), '.pdf'))
                                            <i class="fas fa-file-pdf"></i>
                                        @else
                                            <i class="fas fa-file-word"></i>
                                        @endif
                                    </div>
                                    <div class="file-info ms-3 flex-grow-1">
                                        <h4 class="file-name text-truncate" style="max-width: 200px;">
                                            {{ $item->judul ?: $item->file_original_name }}
                                        </h4>
                                        <div class="file-meta">
                                            <span class="uploader">{{ $item->user?->name ?? 'User' }}</span>
                                            <span class="dot"></span>
                                            <span class="time">{{ $item->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($item->keterangan)
                                    <p class="file-desc mb-3 text-muted small">
                                        {{ Str::limit($item->keterangan, 80) }}
                                    </p>
                                @endif

                                <div class="card-actions d-flex gap-2">
                                    <a href="{{ route('laporan-kelompok.download', $item) }}" class="btn btn-download flex-grow-1">
                                        <i class="fas fa-download me-2"></i> Download
                                    </a>
                                    @if((int) $item->user_id === (int) auth()->id())
                                        <form action="{{ route('laporan-kelompok.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus laporan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Floating Action Button -->
        <button class="fab-btn" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="fas fa-plus"></i>
        </button>

        <!-- Upload Modal -->
        <div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mx-3">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
                    <div class="modal-header border-0 pb-0 pt-4 px-4">
                        <h5 class="modal-title fw-bold">Upload Laporan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('laporan-kelompok.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Judul Laporan</label>
                                <input type="text" name="judul" class="form-control custom-input" placeholder="Contoh: Laporan Mingguan 1" value="{{ old('judul') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Keterangan</label>
                                <textarea name="keterangan" class="form-control custom-input" rows="3" placeholder="Tambahkan catatan singkat...">{{ old('keterangan') }}</textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold">Pilih Berkas <span class="text-danger">*</span></label>
                                <div class="file-upload-wrapper">
                                    <input type="file" name="file" id="file-input" class="file-input" accept=".pdf,.doc,.docx" required>
                                    <div class="file-upload-content text-center py-4">
                                        <i class="fas fa-cloud-upload-alt fs-2 mb-2 text-primary"></i>
                                        <p class="mb-0 small" id="file-name-display">Tap untuk memilih file PDF/Word</p>
                                    </div>
                                </div>
                                @error('file') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm">
                                <i class="fas fa-upload me-2"></i> Unggah Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('styles')
<style>
    /* Group Info Card */
    .group-info-card {
        background: linear-gradient(135deg, #0B1F3A 0%, #163057 100%);
        border-radius: 20px;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: white;
        box-shadow: 0 10px 20px rgba(11, 31, 58, 0.2);
        position: relative;
        overflow: hidden;
    }

    .group-info-card::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    .info-content {
        z-index: 1;
    }

    .group-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.8;
        display: block;
        margin-bottom: 4px;
    }

    .group-name {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .info-meta {
        display: flex;
        gap: 15px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.75rem;
        opacity: 0.9;
    }

    .info-icon {
        width: 56px;
        height: 56px;
        background: rgba(242, 183, 5, 0.2);
        color: #F2B705;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        z-index: 1;
    }

    /* Section Title */
    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0B1F3A;
        margin: 0;
    }

    .badge-count {
        background: #f1f3f4;
        color: #6c757d;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    /* Report Card */
    .report-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #f1f3f4;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s;
    }

    .report-card:active {
        transform: scale(0.98);
    }

    .file-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .file-icon.pdf {
        background: #ffebee;
        color: #f44336;
    }

    .file-icon.doc {
        background: #e3f2fd;
        color: #2196f3;
    }

    .file-name {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 2px;
    }

    .file-meta {
        font-size: 0.7rem;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .dot {
        width: 3px;
        height: 3px;
        background: #cbd5e0;
        border-radius: 50%;
    }

    .card-actions .btn {
        padding: 10px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .btn-download {
        background: #f8f9fa;
        color: #0B1F3A;
        border: 1px solid #e2e8f0;
    }

    .btn-delete {
        background: #ffebee;
        color: #f44336;
        border: none;
        width: 44px;
    }

    /* FAB */
    .fab-btn {
        position: fixed;
        bottom: 90px;
        right: 20px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #F2B705;
        color: #0B1F3A;
        border: none;
        box-shadow: 0 4px 15px rgba(242, 183, 5, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        z-index: 99;
        transition: transform 0.2s;
    }

    .fab-btn:active {
        transform: scale(0.9);
    }

    /* Modal Form */
    .custom-input {
        background: #f8f9fa;
        border: 2px solid #f1f3f4;
        border-radius: 12px;
        padding: 12px;
        font-size: 0.9rem;
    }

    .custom-input:focus {
        background: white;
        border-color: #F2B705;
        box-shadow: none;
    }

    .file-upload-wrapper {
        position: relative;
        border: 2px dashed #cbd5e0;
        border-radius: 16px;
        transition: all 0.2s;
    }

    .file-upload-wrapper:hover {
        border-color: #F2B705;
        background: rgba(242, 183, 5, 0.05);
    }

    .file-input {
        position: absolute;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 2;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        background: #f8f9fa;
        color: #cbd5e0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin: 0 auto 20px;
    }

    .empty-icon.small {
        width: 60px;
        height: 60px;
        font-size: 1.8rem;
    }

    .empty-state p {
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 8px;
    }

    .empty-state span {
        font-size: 0.85rem;
        color: #6c757d;
        display: block;
    }
</style>
@endpush

@push('scripts')
<script>
    // Preview file name in upload modal
    document.getElementById('file-input')?.addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'Tap untuk memilih file PDF/Word';
        document.getElementById('file-name-display').textContent = fileName;
        document.getElementById('file-name-display').classList.add('fw-bold', 'text-primary');
    });

    @if(session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            const layoutAlert = document.querySelector('.app-content > .alert-success');
            if (layoutAlert) layoutAlert.style.display = 'none';

            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{!! session('success') !!}',
                confirmButtonColor: '#0B1F3A',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        });
    @endif
    
    @if(session('error'))
        document.addEventListener('DOMContentLoaded', function() {
            const layoutAlert = document.querySelector('.app-content > .alert-danger');
            if (layoutAlert) layoutAlert.style.display = 'none';

            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{!! session('error') !!}',
                confirmButtonColor: '#0B1F3A'
            });
        });
    @endif
</script>
@endpush

<x-app-layout>
    <style>
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 10px;
            }
            
            .card {
                margin-bottom: 15px;
            }
            
            .card-body {
                padding: 15px;
            }
            
            /* Text adjustments */
            h2.fw-bold {
                font-size: 1.5rem;
            }
            
            h5.card-title {
                font-size: 1.1rem;
            }
            
            h6.fw-bold {
                font-size: 1rem;
            }
            
            /* Form controls */
            .form-control {
                font-size: 14px;
                padding: 8px 12px;
            }
            
            .form-label {
                font-size: 14px;
                font-weight: 500;
            }
            
            /* Button adjustments */
            .btn {
                font-size: 14px;
                padding: 8px 12px;
            }
            
            /* Badge adjustments */
            .badge {
                font-size: 12px;
            }
            
            /* Table adjustments */
            .table {
                font-size: 14px;
            }
            
            .table th,
            .table td {
                padding: 8px 4px;
            }
            
            /* Image adjustments */
            .img-fluid {
                max-height: 200px;
                object-fit: cover;
            }
            
            /* Stack header buttons vertically */
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 10px;
            }
            
            .d-flex.justify-content-between .btn {
                width: 100%;
            }
            
            /* Stack form buttons vertically */
            .d-flex.gap-2 {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .d-flex.gap-2 .btn {
                width: 100%;
            }
            
            /* Grid adjustments */
            .row .col-md-6,
            .row .col-md-8,
            .row .col-md-4 {
                margin-bottom: 15px;
            }
            
            /* Icon adjustments */
            .fas {
                font-size: 12px;
            }
            
            .fas.fa-3x {
                font-size: 2rem !important;
            }
        }
        
        /* Extra small devices */
        @media (max-width: 576px) {
            .container-fluid {
                padding: 5px;
            }
            
            .card-body {
                padding: 10px;
            }
            
            h2.fw-bold {
                font-size: 1.3rem;
            }
            
            h5.card-title {
                font-size: 1rem;
            }
            
            h6.fw-bold {
                font-size: 0.9rem;
            }
            
            .form-control {
                font-size: 13px;
                padding: 6px 10px;
            }
            
            .form-label {
                font-size: 13px;
            }
            
            .btn {
                font-size: 13px;
                padding: 6px 10px;
            }
            
            .badge {
                font-size: 11px;
            }
            
            .table {
                font-size: 13px;
            }
            
            .table th,
            .table td {
                padding: 6px 2px;
            }
            
            .img-fluid {
                max-height: 150px;
            }
            
            .fas {
                font-size: 11px;
            }
            
            .fas.fa-3x {
                font-size: 1.5rem !important;
            }
        }
    </style>

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">Detail History Logbook</h2>
                        <p class="text-muted mb-0">
                            <i class="fas fa-calendar me-2"></i>{{ $logbook->tanggal->isoFormat('dddd, D MMMM Y') }} · 
                            <span class="badge {{ $logbook->status === 'approved' ? 'bg-success' : 
                                   ($logbook->status === 'rejected' ? 'bg-danger' : 
                                   ($logbook->status === 'submitted' ? 'bg-info' : 
                                   'bg-warning')) }}">
                                {{ ucfirst($logbook->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('logbooks.export-pdf', $logbook) }}" class="btn btn-danger" target="_blank">
                            <i class="fas fa-file-pdf me-2"></i>Export PDF
                        </a>
                        <a href="{{ route('history.logbooks.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke History
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <!-- Detail Logbook -->
                <div class="card mb-4">
                    <div class="card-header" style="background-color: #f2b70d;">
                        <h5 class="card-title mb-0" style="color: #0B1F3A;">
                            <i class="fas fa-file-alt me-2"></i>Detail Kegiatan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6 class="fw-bold">Judul Kegiatan</h6>
                            <p>{{ $logbook->judul }}</p>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold">Waktu Kegiatan</h6>
                            <p>
                                {{ \Carbon\Carbon::parse($logbook->waktu_mulai)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($logbook->waktu_selesai)->format('H:i') }} WIB
                            </p>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold">Jenis Kegiatan</h6>
                            <span class="badge {{ $logbook->jenis === 'individu' ? 'bg-primary' : 
                                   ($logbook->jenis === 'desa' ? 'bg-success' : 'bg-info') }}">
                                {{ ucfirst($logbook->jenis) }}
                            </span>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold">Lokasi</h6>
                            <p>{{ $logbook->lokasi }}</p>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold">Keterangan</h6>
                            <p class="text-justify">{{ $logbook->keterangan }}</p>
                        </div>

                        @if($logbook->komentar_dpl)
                            <div class="mb-4">
                                <h6 class="fw-bold">Komentar DPL</h6>
                                <p>{{ $logbook->komentar_dpl }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Foto Kegiatan -->
                @if($logbook->photos->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header" style="background-color: #f2b70d;">
                            <h5 class="card-title mb-0" style="color: #0B1F3A;">
                                <i class="fas fa-images me-2"></i>Foto Kegiatan
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach($logbook->photos as $photo)
                                    <div class="col-md-6">
                                        <div class="position-relative">
                                            <img src="{{ Storage::url($photo->path) }}" 
                                                 alt="Foto Kegiatan" 
                                                 class="img-fluid rounded shadow-sm"
                                                 style="cursor: pointer;"
                                                 onclick="openImageModal('{{ Storage::url($photo->path) }}')">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- File Lampiran -->
                @if($logbook->attachments && count($logbook->attachments) > 0)
                    <div class="card">
                        <div class="card-header" style="background-color: #f2b70d;">
                            <h5 class="card-title mb-0" style="color: #0B1F3A;">
                                <i class="fas fa-paperclip me-2"></i>File Lampiran
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($logbook->attachments as $attachment)
                                    @php
                                        // Handle different attachment formats
                                        $fileName = '';
                                        $filePath = '';
                                        
                                        if (is_string($attachment)) {
                                            // If attachment is a simple string (file path)
                                            $fileName = basename($attachment);
                                            $filePath = $attachment;
                                        } elseif (is_array($attachment)) {
                                            // If attachment is an array with file info
                                            $fileName = $attachment['name'] ?? basename($attachment['path'] ?? '');
                                            $filePath = $attachment['path'] ?? $attachment['file'] ?? '';
                                        } else {
                                            // Fallback
                                            $fileName = 'Unknown file';
                                            $filePath = '';
                                        }
                                    @endphp
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-center p-2 border rounded">
                                            <i class="fas fa-file me-2 text-primary"></i>
                                            <div class="flex-grow-1">
                                                <small class="d-block">{{ $fileName }}</small>
                                            </div>
                                            @if($filePath)
                                                <a href="{{ asset('storage/' . $filePath) }}" 
                                                   class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                <!-- Informasi Mahasiswa -->
                <div class="card mb-4">
                    <div class="card-header" style="background-color: #f2b70d;">
                        <h5 class="card-title mb-0" style="color: #0B1F3A;">
                            <i class="fas fa-user-graduate me-2"></i>Informasi Mahasiswa
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            @if($logbook->user->foto_profil)
                                <img src="{{ asset('storage/'.$logbook->user->foto_profil) }}" 
                                     alt="{{ $logbook->user->name }}" 
                                     class="rounded-circle mb-3"
                                     width="120" height="120"
                                     style="object-fit: cover;">
                            @else
                                <div class="bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                                     style="width: 120px; height: 120px;">
                                    <span class="text-white" style="font-size: 48px;">
                                        {{ substr($logbook->user->name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            <h4 class="fw-bold mb-1">{{ $logbook->user->name }}</h4>
                            <p class="text-muted mb-0">{{ $logbook->user->nim ?? 'NIM belum diisi' }}</p>
                        </div>

                        <table class="table">
                            <tr>
                                <td class="fw-bold">Jurusan</td>
                                <td>
                                    @if($logbook->user->jurusan)
                                        <span class="badge" style="background-color: #0B1F3A; color: white;">
                                            {{ ucfirst($logbook->user->jurusan) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Email</td>
                                <td>{{ $logbook->user->email }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Kelompok</td>
                                <td>
                                    @if($logbook->user->kelompok)
                                        <span class="badge bg-info">{{ $logbook->user->kelompok->nama_kelompok }}</span>
                                    @else
                                        <span class="text-muted">Belum ditentukan</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Lokasi KKN</td>
                                <td>
                                    @if($logbook->user->kelompok && $logbook->user->kelompok->lokasi)
                                        <span class="badge bg-success">{{ $logbook->user->kelompok->lokasi->nama_desa }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Informasi Sistem -->
                <div class="card">
                    <div class="card-header" style="background-color: #f2b70d;">
                        <h5 class="card-title mb-0" style="color: #0B1F3A;">
                            <i class="fas fa-info-circle me-2"></i>Informasi Sistem
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Dibuat Pada</label>
                            <p class="form-control-plaintext">{{ $logbook->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Terakhir Diupdate</label>
                            <p class="form-control-plaintext">{{ $logbook->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @if($logbook->status == 'submitted')
                            <div class="mb-3">
                                <label class="form-label fw-bold">Dikirim Pada</label>
                                <p class="form-control-plaintext">{{ $logbook->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Foto Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid" alt="Foto Kegiatan">
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openImageModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }
    </script>
    @endpush
</x-app-layout> 
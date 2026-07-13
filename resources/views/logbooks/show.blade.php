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
                        <h2 class="fw-bold">Detail Logbook</h2>
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
                        @php
                            $backUrl = route('logbooks.index');
                            if (auth()->user()->hasRole('admin')) {
                                $backUrl = route('monitoring.logbook-detail');
                            }
                        @endphp
                        <a href="{{ $backUrl }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        @can('update', $logbook)
                            <a href="{{ route('logbooks.edit', $logbook) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>Edit
                            </a>
                        @endcan
                        @can('delete', $logbook)
                            <button type="button" class="btn btn-danger" onclick="deleteLogbookShow({{ $logbook->id }})">
                                <i class="fas fa-trash me-2"></i>Hapus
                            </button>
                        @endcan
                        @can('submit', $logbook)
                            @if($logbook->status === 'draft')
                            <form action="{{ route('logbooks.submit', $logbook) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-paper-plane me-2"></i>Submit ke DPL
                                </button>
                            </form>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <!-- Detail Logbook -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
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
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-images me-2"></i>Foto Kegiatan
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($logbook->photos->count() > 0)
                            <div class="row g-3">
                                @foreach($logbook->photos as $photo)
                                    <div class="col-md-6">
                                        <div class="position-relative">
                                            <img src="{{ Storage::url($photo->path) }}" 
                                                 alt="Foto Kegiatan" 
                                                 class="img-fluid rounded shadow-sm">
                                            <a href="{{ Storage::url($photo->path) }}" 
                                               target="_blank" 
                                               class="position-absolute top-0 end-0 m-2 btn btn-sm btn-light">
                                                <i class="fas fa-expand"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Tidak ada foto kegiatan</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- File Lampiran -->
                @if($logbook->attachments && count($logbook->attachments) > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-paperclip me-2"></i>File Lampiran
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($logbook->attachments as $index => $attachment)
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded border">
                                        <div class="d-flex align-items-center gap-3 flex-grow-1 min-w-0">
                                            @php
                                                $extension = strtolower(pathinfo($attachment['name'], PATHINFO_EXTENSION));
                                                $iconClass = 'fas fa-file';
                                                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                                    $iconClass = 'fas fa-image';
                                                } elseif ($extension === 'pdf') {
                                                    $iconClass = 'fas fa-file-pdf';
                                                } elseif (in_array($extension, ['doc', 'docx'])) {
                                                    $iconClass = 'fas fa-file-word';
                                                } elseif (in_array($extension, ['xls', 'xlsx'])) {
                                                    $iconClass = 'fas fa-file-excel';
                                                } elseif (in_array($extension, ['ppt', 'pptx'])) {
                                                    $iconClass = 'fas fa-file-powerpoint';
                                                } elseif ($extension === 'txt') {
                                                    $iconClass = 'fas fa-file-alt';
                                                }
                                            @endphp
                                            <i class="{{ $iconClass }} text-primary"></i>
                                            <div class="min-w-0">
                                                <div class="text-sm font-weight-bold text-truncate">{{ $attachment['name'] }}</div>
                                                <div class="text-xs text-muted">{{ number_format($attachment['size'] / 1024, 1) }} KB</div>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-1">
                                            <a href="{{ Storage::url($attachment['path']) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            @if(auth()->user()->can('delete', $logbook))
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="deleteAttachment({{ $logbook->id }}, {{ $index }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-md-4">
                <!-- Status Logbook -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Status Logbook
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Status</th>
                                <td>
                                    <span class="badge {{ $logbook->status === 'approved' ? 'bg-success' : 
                                           ($logbook->status === 'rejected' ? 'bg-danger' : 
                                           ($logbook->status === 'submitted' ? 'bg-info' : 
                                           'bg-warning')) }}">
                                        {{ ucfirst($logbook->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Dibuat pada</th>
                                <td>{{ $logbook->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Terakhir diupdate</th>
                                <td>{{ $logbook->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Form Review (untuk DPL) -->
                @if($logbook->status === 'submitted' && auth()->user()->hasRole('dpl'))
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-check-circle me-2"></i>Review Logbook
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('logbooks.review', $logbook) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="komentar" class="form-label">Komentar</label>
                                    <textarea class="form-control" 
                                              name="komentar" 
                                              rows="3" 
                                              placeholder="Tambahkan komentar untuk mahasiswa"></textarea>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" name="action" value="approve" class="btn btn-success flex-grow-1">
                                        <i class="fas fa-check me-2"></i>Setujui
                                    </button>
                                    <button type="submit" name="action" value="reject" class="btn btn-danger flex-grow-1">
                                        <i class="fas fa-times me-2"></i>Tolak
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .text-justify {
            text-align: justify;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function viewAttachment(url) {
            window.open(url, '_blank');
        }

        function deleteLogbookShow(id) {
            if (confirm('Yakin ingin menghapus logbook ini? Tindakan ini tidak dapat dibatalkan.')) {
                fetch(`/logbooks/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        window.close(); // Attempt to close tab if opened via target="_blank"
                        window.location.href = '/dashboard'; // Fallback
                    } else {
                        alert('Gagal menghapus logbook: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus logbook');
                });
            }
        }
    </script>
    <script>
        function deleteAttachment(logbookId, index) {
            if (confirm('Apakah Anda yakin ingin menghapus file ini?')) {
                fetch(`/logbooks/${logbookId}/attachments`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ index: index })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Reload halaman untuk memperbarui tampilan
                        location.reload();
                    } else {
                        alert('Gagal menghapus file: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus file');
                });
            }
        }
    </script>
    @endpush
</x-app-layout> 
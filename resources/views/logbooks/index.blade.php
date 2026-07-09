<x-app-layout>
    <style>
        /* Responsive Design untuk Mobile */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 10px;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .card-header {
                padding: 0.75rem 1rem;
            }
            
            .card-header h5 {
                font-size: 1rem;
            }
            
            .table-responsive {
                font-size: 0.875rem;
            }
            
            .table th,
            .table td {
                padding: 0.5rem 0.25rem;
                vertical-align: middle;
            }
            
            .btn-group .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
            
            .btn-group .btn i {
                font-size: 0.75rem;
            }
            
            .badge {
                font-size: 0.75rem;
            }
            
            .card-subtitle {
                font-size: 0.75rem;
            }
            
            .card-title {
                font-size: 1.25rem;
            }
            
            .rounded-circle {
                padding: 0.5rem !important;
                width: 35px;
                height: 35px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .rounded-circle i {
                font-size: 0.75rem;
            }
            
            .modal-dialog {
                margin: 0.5rem;
                max-width: calc(100% - 1rem);
            }
            
            .modal-body {
                padding: 1rem;
            }
            
            .modal-header {
                padding: 0.75rem 1rem;
            }
            
            .modal-title {
                font-size: 1rem;
            }
            
            .form-label {
                font-size: 0.875rem;
            }
            
            .form-control,
            .form-select {
                font-size: 0.875rem;
                padding: 0.375rem 0.75rem;
            }
            
            .btn {
                font-size: 0.875rem;
                padding: 0.375rem 0.75rem;
            }
            
            .col-md-8,
            .col-md-4 {
                margin-bottom: 1rem;
            }
            
            .row.g-3 {
                margin: 0;
            }
            
            .row.g-3 .col-6 {
                padding: 0 5px;
            }
            
            .card.border-0 .card-body {
                padding: 1rem;
            }
            
            .card.border-0 .card-subtitle {
                font-size: 0.75rem;
                margin-bottom: 0.5rem;
            }
            
            .card.border-0 .card-title {
                font-size: 1.25rem;
                margin-bottom: 0;
            }
        }
        
        @media (max-width: 576px) {
            .container-fluid {
                padding: 5px;
            }
            
            .card-body {
                padding: 0.75rem;
            }
            
            .card-header {
                padding: 0.5rem 0.75rem;
            }
            
            .table-responsive {
                font-size: 0.8rem;
            }
            
            .table th,
            .table td {
                padding: 0.375rem 0.125rem;
            }
            
            .btn-group {
                flex-direction: column;
                gap: 2px;
            }
            
            .btn-group .btn {
                width: 100%;
                margin-bottom: 2px;
            }
            
            .modal-dialog {
                margin: 0.25rem;
                max-width: calc(100% - 0.5rem);
            }
            
            .modal-body {
                padding: 0.75rem;
            }
            
            .modal-header {
                padding: 0.5rem 0.75rem;
            }
            
            .form-control,
            .form-select {
                font-size: 0.8rem;
                padding: 0.25rem 0.5rem;
            }
            
            .btn {
                font-size: 0.8rem;
                padding: 0.25rem 0.5rem;
            }
            
            .row.g-3 .col-6 {
                padding: 0 2px;
            }
            
            .card.border-0 .card-body {
                padding: 0.75rem;
            }
            
            .card.border-0 .card-subtitle {
                font-size: 0.7rem;
            }
            
            .card.border-0 .card-title {
                font-size: 1.1rem;
            }
            
            .rounded-circle {
                width: 30px;
                height: 30px;
                padding: 0.375rem !important;
            }
            
            .rounded-circle i {
                font-size: 0.7rem;
            }
        }
    </style>
    
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 bg-white p-3 rounded shadow-sm">
                    <div>
                        <h2 class="fw-bold mb-1">Logbook Harian</h2>
                        <p class="text-muted mb-0 small">Catat aktivitas KKN harian Anda dengan detail lokasi, foto, dan keterangan lengkap</p>
                    </div>
                    <div>
                        <div class="btn-group shadow-sm" role="group">
                            <a href="{{ route('logbooks.index', ['tipe' => 'individu']) }}" 
                               class="btn {{ $tipe === 'individu' ? 'btn-primary text-white' : 'btn-outline-primary' }} fw-semibold px-4">
                                <i class="fas fa-user me-2"></i> Logbook Individu
                            </a>
                            <a href="{{ route('logbooks.index', ['tipe' => 'kelompok']) }}" 
                               class="btn {{ $tipe === 'kelompok' ? 'btn-success text-white' : 'btn-outline-success' }} fw-semibold px-4">
                                <i class="fas fa-users me-2"></i> Logbook Kelompok
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <!-- Calendar Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar me-2"></i>Kalender Aktivitas ({{ $tipe === 'kelompok' ? 'Kelompok' : 'Individu' }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>

                <!-- Logbook List Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            @if($tipe === 'kelompok')
                                <i class="fas fa-users me-2 text-success"></i>Riwayat Logbook Kelompok
                            @else
                                <i class="fas fa-user me-2 text-primary"></i>Riwayat Logbook Individu
                            @endif
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover logbook-datatable" id="logbookTable">
                                @if($tipe === 'kelompok')
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Waktu</th>
                                            <th>Judul Kegiatan</th>
                                            <th>Jenis</th>
                                            <th>Oleh</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($logbooksKelompok as $logbook)
                                            <tr>
                                                <td>{{ $logbook->tanggal->format('d/m/Y') }}</td>
                                                <td>{{ $logbook->waktu_mulai }} - {{ $logbook->waktu_selesai }}</td>
                                                <td>{{ $logbook->judul }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $logbook->jenis === 'individu' ? 'primary' : ($logbook->jenis === 'desa' ? 'success' : 'info') }}">
                                                        {{ ucfirst($logbook->jenis) }}
                                                    </span>
                                                </td>
                                                <td>{{ $logbook->user?->name ?? '-' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $logbook->status === 'draft' ? 'warning' : ($logbook->status === 'submitted' ? 'info' : ($logbook->status === 'approved' ? 'success' : 'danger')) }}">
                                                        {{ ucfirst($logbook->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('logbooks.show', $logbook) }}" 
                                                            class="btn btn-sm btn-info text-white"
                                                            title="Lihat Detail">
                                                            <i class="fas fa-eye text-white"></i>
                                                        </a>
                                                        @if($logbook->status === 'draft' && Gate::allows('update', $logbook))
                                                            <a href="{{ route('logbooks.edit', $logbook) }}" 
                                                                class="btn btn-sm btn-primary"
                                                                title="Edit">
                                                                <i class="fas fa-edit text-white"></i>
                                                            </a>
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-danger delete-logbook" 
                                                                    data-id="{{ $logbook->id }}"
                                                                    title="Hapus"
                                                                    onclick="deleteLogbook({{ $logbook->id }})">
                                                                <i class="fas fa-trash text-white"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-success text-white" title="Submit ke DPL" onclick="submitLogbookToDpl({{ $logbook->id }})">
                                                                <i class="fab fa-telegram-plane text-white"></i>
                                                            </button>
                                                            <form id="submit-logbook-{{ $logbook->id }}" action="{{ route('logbooks.submit', $logbook) }}" method="POST" style="display:none;">
                                                                @csrf
                                                                @method('PUT')
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                @else
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Waktu</th>
                                            <th>Judul Kegiatan</th>
                                            <th>Jenis</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($logbooksIndividu as $logbook)
                                            <tr>
                                                <td>{{ $logbook->tanggal->format('d/m/Y') }}</td>
                                                <td>{{ $logbook->waktu_mulai }} - {{ $logbook->waktu_selesai }}</td>
                                                <td>{{ $logbook->judul }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $logbook->jenis === 'individu' ? 'primary' : ($logbook->jenis === 'desa' ? 'success' : 'info') }}">
                                                        {{ ucfirst($logbook->jenis) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $logbook->status === 'draft' ? 'warning' : ($logbook->status === 'submitted' ? 'info' : ($logbook->status === 'approved' ? 'success' : 'danger')) }}">
                                                        {{ ucfirst($logbook->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('logbooks.show', $logbook) }}" 
                                                            class="btn btn-sm btn-info text-white"
                                                            title="Lihat Detail">
                                                            <i class="fas fa-eye text-white"></i>
                                                        </a>
                                                        @if($logbook->status === 'draft' && Gate::allows('update', $logbook))
                                                            <a href="{{ route('logbooks.edit', $logbook) }}" 
                                                                class="btn btn-sm btn-primary"
                                                                title="Edit">
                                                                <i class="fas fa-edit text-white"></i>
                                                            </a>
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-danger delete-logbook" 
                                                                    data-id="{{ $logbook->id }}"
                                                                    title="Hapus"
                                                                    onclick="deleteLogbook({{ $logbook->id }})">
                                                                <i class="fas fa-trash text-white"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-success text-white" title="Submit ke DPL" onclick="submitLogbookToDpl({{ $logbook->id }})">
                                                                <i class="fab fa-telegram-plane text-white"></i>
                                                            </button>
                                                            <form id="submit-logbook-{{ $logbook->id }}" action="{{ route('logbooks.submit', $logbook) }}" method="POST" style="display:none;">
                                                                @csrf
                                                                @method('PUT')
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Quick Stats -->
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="card border-0 bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="card-subtitle mb-2">Total Logbook</h6>
                                        <h2 class="card-title mb-0">{{ $stats['total'] ?? 0 }}</h2>
                                    </div>
                                    <div class="rounded-circle p-3 bg-white bg-opacity-25">
                                        <i class="fas fa-book fa-fw"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="card-subtitle mb-2">Disetujui</h6>
                                        <h2 class="card-title mb-0">{{ $stats['approved'] ?? 0 }}</h2>
                                    </div>
                                    <div class="rounded-circle p-3 bg-white bg-opacity-25">
                                        <i class="fas fa-check fa-fw"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="card-subtitle mb-2">Draft</h6>
                                        <h2 class="card-title mb-0">{{ $stats['draft'] ?? 0 }}</h2>
                                    </div>
                                    <div class="rounded-circle p-3 bg-white bg-opacity-25">
                                        <i class="fas fa-pencil-alt fa-fw"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="card-subtitle mb-2">Menunggu</h6>
                                        <h2 class="card-title mb-0">{{ $stats['submitted'] ?? 0 }}</h2>
                                    </div>
                                    <div class="rounded-circle p-3 bg-white bg-opacity-25">
                                        <i class="fas fa-clock fa-fw"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guide Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Panduan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="fw-bold">Cara Membuat Logbook:</h6>
                            <ol class="ps-3">
                                <li>Klik tanggal di kalender</li>
                                <li>Isi form dengan lengkap</li>
                                <li>Upload foto dokumentasi</li>
                                <li>Simpan sebagai draft atau submit</li>
                            </ol>
                        </div>
                        <div class="mb-3">
                            <h6 class="fw-bold">Status Logbook:</h6>
                            <ul class="list-unstyled mb-0">
                                <li><span class="badge bg-warning me-2">Draft</span>Belum disubmit</li>
                                <li><span class="badge bg-info me-2">Submitted</span>Menunggu persetujuan</li>
                                <li><span class="badge bg-success me-2">Approved</span>Disetujui DPL</li>
                                <li><span class="badge bg-danger me-2">Rejected</span>Ditolak DPL</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logbook Form Modal -->
        <div class="modal fade" id="logbookModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="logbookForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Logbook</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal</label>
                                    <input type="date" class="form-control" name="tanggal" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Waktu Mulai</label>
                                    <input type="time" class="form-control" name="waktu_mulai" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Waktu Selesai</label>
                                    <input type="time" class="form-control" name="waktu_selesai" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Judul Kegiatan</label>
                                    <input type="text" class="form-control" name="judul" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tipe Logbook</label>
                                    <select class="form-select" id="modal_is_kelompok_display" disabled>
                                        <option value="0">Logbook Individu</option>
                                        <option value="1">Logbook Kelompok (Sharing)</option>
                                    </select>
                                    <input type="hidden" name="is_kelompok" id="modal_is_kelompok">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jenis Kegiatan</label>
                                    <select class="form-select" name="jenis" id="modal_jenis" required>
                                        <option value="individu">Individu</option>
                                        <option value="desa">Desa</option>
                                        <option value="kecamatan">Kecamatan</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Lokasi Kegiatan</label>
                                    <textarea class="form-control" name="lokasi" rows="2" required placeholder="Masukkan lokasi kegiatan secara detail"></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Keterangan</label>
                                    <textarea class="form-control" name="keterangan" rows="4" required></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Foto Dokumentasi</label>
                                    <div class="mb-2">
                                        <button type="button" class="btn btn-success btn-sm" id="addPhotoBtn">
                                            <i class="fas fa-plus me-2"></i>Tambah Foto
                                        </button>
                                    </div>
                                    <div id="photoInputs">
                                        <div class="mb-3 photo-input-group">
                                            <div class="input-group">
                                                <input type="file" class="form-control" name="photos[]" accept="image/*" required>
                                                <button type="button" class="btn btn-danger remove-photo" disabled>
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-text">Format: JPG, PNG (Max. 2MB per foto)</div>
                                </div>
                                <div id="preview" class="col-12">
                                    <div class="row g-2">
                                        <!-- Preview foto akan ditampilkan di sini -->
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">File Lampiran (Opsional)</label>
                                    <div class="mb-2">
                                        <button type="button" class="btn btn-info btn-sm" id="addAttachmentBtn">
                                            <i class="fas fa-paperclip me-2"></i>Tambah File
                                        </button>
                                    </div>
                                    <div id="attachmentInputs">
                                        <div class="mb-3 attachment-input-group">
                                            <div class="input-group">
                                                <input type="file" class="form-control" name="attachments[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif">
                                                <button type="button" class="btn btn-danger remove-attachment" disabled>
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-text">Format: PDF, DOC, DOCX, Excel, PowerPoint, TXT, JPG, PNG, GIF (Max. 10MB per file)</div>
                                </div>
                                <div id="attachmentPreview" class="col-12">
                                    <div class="row g-2">
                                        <!-- Preview attachment akan ditampilkan di sini -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" name="status" value="draft" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Simpan Draft
                            </button>
                            <button type="submit" name="status" value="submitted" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="{{ asset("assets/css/fullcalendar.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/css/leaflet.min.css") }}">
    <style>
        #calendar {
            background: white;
        }

        /* Mengatur tampilan kalender */
        .fc-theme-standard td, 
        .fc-theme-standard th {
            border-color: #e5e7eb;
        }

        .fc-theme-standard .fc-scrollgrid {
            border-color: #e5e7eb;
        }

        /* Header kalender */
        .fc .fc-toolbar-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .fc .fc-button-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .fc .fc-button-primary:hover {
            background-color: #0a1829;
            border-color: #0a1829;
        }

        .fc .fc-button-primary:disabled {
            background-color: #4b5563;
            border-color: #4b5563;
        }

        /* Tanggal */
        .fc .fc-daygrid-day-number {
            color: #4b5563;
            text-decoration: none;
            padding: 0.5rem;
        }

        .fc .fc-daygrid-day.fc-day-today {
            background-color: rgba(242, 183, 5, 0.1);
        }

        .fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
            color: var(--accent-color);
            font-weight: 600;
        }

        /* Event */
        .fc-event {
            cursor: pointer;
            border: none;
            padding: 2px 4px;
            font-size: 0.875rem;
        }

        .fc-event-title {
            font-weight: 500;
        }

        .fc-day:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        /* Header hari */
        .fc-col-header-cell {
            background-color: #f8f9fa;
            padding: 0.75rem 0;
        }

        .fc-col-header-cell-cushion {
            color: #4b5563;
            font-weight: 600;
            text-decoration: none;
        }

        /* Preview foto */
        .preview-image {
            object-fit: cover;
            height: 150px;
            border-radius: 0.5rem;
        }

        .preview-remove {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: rgba(0,0,0,0.5);
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            line-height: 24px;
            text-align: center;
            padding: 0;
            cursor: pointer;
        }

        .preview-remove:hover {
            background: rgba(0,0,0,0.7);
        }
    </style>
    @endpush

    @push('scripts')
    <script src="{{ asset("assets/js/fullcalendar.min.js") }}"></script>
    <script>
        // Fungsi untuk menghapus logbook
        function deleteLogbook(id) {
            if (confirm('Apakah Anda yakin ingin menghapus logbook ini?')) {
                fetch(`/logbooks/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Reload halaman setelah berhasil menghapus
                        window.location.reload();
                    } else {
                        alert('Gagal menghapus logbook');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus logbook');
                });
            }
        }

        // Kompresi gambar sebelum upload
        function compressImage(file, callback) {
            const maxWidth = 800;
            const quality = 0.5;
            const reader = new FileReader();
            reader.onload = function(event) {
                const img = new Image();
                img.onload = function() {
                    let scale = Math.min(maxWidth / img.width, 1);
                    let canvas = document.createElement('canvas');
                    canvas.width = img.width * scale;
                    canvas.height = img.height * scale;
                    let ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    canvas.toBlob(function(blob) {
                        callback(new File([blob], file.name, {type: 'image/jpeg'}));
                    }, 'image/jpeg', quality);
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Function to dynamically adjust jenis select options based on is_kelompok value
            function adjustJenisOptions() {
                const isKelompok = $('#modal_is_kelompok').val();
                const jenisSelect = $('#modal_jenis');
                
                if (isKelompok === '1') {
                    // Kelompok: hide/disable individu
                    jenisSelect.find('option[value="individu"]').hide().prop('disabled', true);
                    jenisSelect.find('option[value="desa"], option[value="kecamatan"]').show().prop('disabled', false);
                    if (jenisSelect.val() === 'individu' || !jenisSelect.val()) {
                        jenisSelect.val('desa');
                    }
                } else {
                    // Individu: hide/disable desa/kecamatan
                    jenisSelect.find('option[value="individu"]').show().prop('disabled', false);
                    jenisSelect.find('option[value="desa"], option[value="kecamatan"]').hide().prop('disabled', true);
                    jenisSelect.val('individu');
                }
            }

            // Initialize Calendar
            var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'dayGridMonth',
                locale: 'id',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                events: @json($events),
                dateClick: function(info) {
                    // Reset form
                    $('#logbookForm')[0].reset();
                    $('#preview').empty();
                    $('#attachmentPreview').empty();
                    
                    // Set tanggal
                    $('input[name="tanggal"]').val(info.dateStr);
                    
                    // Set default tipe based on active filter
                    const activeTipe = '{{ $tipe }}' === 'kelompok' ? '1' : '0';
                    $('#modal_is_kelompok').val(activeTipe);
                    $('#modal_is_kelompok_display').val(activeTipe);
                    adjustJenisOptions();

                    // Set default action
                    $('#logbookForm').attr('action', '{{ route("logbooks.store") }}');
                    
                    // Show modal
                    $('#logbookModal').modal('show');
                },
                eventClick: function(info) {
                    // Load logbook detail
                    window.location.href = '/logbooks/' + info.event.id;
                }
            });
            calendar.render();

            // Handle add photo button
            $('#addPhotoBtn').on('click', function() {
                const photoGroup = `
                    <div class="mb-3 photo-input-group">
                        <div class="input-group">
                            <input type="file" class="form-control" name="photos[]" accept="image/*" required>
                            <button type="button" class="btn btn-danger remove-photo">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                `;
                $('#photoInputs').append(photoGroup);
                
                // Enable/disable remove buttons
                updateRemoveButtons();
            });

            // Handle remove photo input
            $(document).on('click', '.remove-photo', function() {
                $(this).closest('.photo-input-group').remove();
                updateRemoveButtons();
            });

            // Function to update remove buttons state
            function updateRemoveButtons() {
                const inputs = $('.photo-input-group');
                if(inputs.length === 1) {
                    inputs.find('.remove-photo').prop('disabled', true);
                } else {
                    inputs.find('.remove-photo').prop('disabled', false);
                }
            }

            // Handle add attachment button
            $('#addAttachmentBtn').on('click', function() {
                const attachmentGroup = `
                    <div class="mb-3 attachment-input-group">
                        <div class="input-group">
                            <input type="file" class="form-control" name="attachments[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif">
                            <button type="button" class="btn btn-danger remove-attachment">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                `;
                $('#attachmentInputs').append(attachmentGroup);
                
                // Enable/disable remove buttons
                updateAttachmentRemoveButtons();
            });

            // Handle remove attachment input
            $(document).on('click', '.remove-attachment', function() {
                $(this).closest('.attachment-input-group').remove();
                updateAttachmentRemoveButtons();
            });

            // Function to update attachment remove buttons state
            function updateAttachmentRemoveButtons() {
                const inputs = $('.attachment-input-group');
                if(inputs.length === 1) {
                    inputs.find('.remove-attachment').prop('disabled', true);
                } else {
                    inputs.find('.remove-attachment').prop('disabled', false);
                }
            }

            // Handle attachment preview
            $(document).on('change', 'input[name="attachments[]"]', function(e) {
                const input = this;
                const previewRow = $('#attachmentPreview .row');
                
                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    
                    // Validasi ukuran file
                    if (file.size > 10 * 1024 * 1024) { // 10MB
                        alert('Ukuran file tidak boleh lebih dari 10MB!');
                        input.value = '';
                        return;
                    }
                    
                    // Tentukan icon berdasarkan tipe file
                    const fileExtension = file.name.split('.').pop().toLowerCase();
                    let iconClass = 'fas fa-file';
                    
                    if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                        iconClass = 'fas fa-image';
                    } else if (['pdf'].includes(fileExtension)) {
                        iconClass = 'fas fa-file-pdf';
                    } else if (['doc', 'docx'].includes(fileExtension)) {
                        iconClass = 'fas fa-file-word';
                    } else if (['xls', 'xlsx'].includes(fileExtension)) {
                        iconClass = 'fas fa-file-excel';
                    } else if (['ppt', 'pptx'].includes(fileExtension)) {
                        iconClass = 'fas fa-file-powerpoint';
                    } else if (['txt'].includes(fileExtension)) {
                        iconClass = 'fas fa-file-alt';
                    }
                    
                    const previewId = 'attachment-preview-' + Math.random().toString(36).substr(2, 9);
                    const preview = `
                        <div class="col-md-6 position-relative mb-3" id="${previewId}">
                            <div class="d-flex align-items-center p-2 bg-light rounded border">
                                <i class="${iconClass} text-primary me-2"></i>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="text-sm font-weight-bold text-truncate">${file.name}</div>
                                    <div class="text-xs text-muted">${formatFileSize(file.size)}</div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-2 attachment-preview-remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    previewRow.append(preview);
                }
            });

            // Handle remove attachment preview
            $(document).on('click', '.attachment-preview-remove', function() {
                const previewDiv = $(this).closest('.col-md-6');
                previewDiv.remove();
            });

            // Function to format file size
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // Handle foto preview dengan kompresi
            $(document).on('change', 'input[name="photos[]"]', function(e) {
                const input = this;
                const previewRow = $('#preview .row');
                
                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    
                    // Validasi ukuran file
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file tidak boleh lebih dari 2MB!');
                        input.value = '';
                        return;
                    }
                    
                    // Kompresi gambar
                    compressImage(file, function(compressed) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewId = 'preview-' + Math.random().toString(36).substr(2, 9);
                            const preview = `
                                <div class="col-md-4 position-relative mb-3" id="${previewId}">
                                    <img src="${e.target.result}" class="w-100 preview-image">
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-success">Compressed</span>
                                    </div>
                                    <button type="button" class="preview-remove" data-input="${input.id}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            `;
                            previewRow.append(preview);
                            
                            // Replace original file with compressed file
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(compressed);
                            input.files = dataTransfer.files;
                        }
                        reader.readAsDataURL(compressed);
                    });
                }
            });

            // Handle remove preview
            $(document).on('click', '.preview-remove', function() {
                const previewDiv = $(this).closest('.col-md-4');
                previewDiv.remove();
            });

            // Initialize DataTable
            $('.logbook-datatable').DataTable({
                order: [[0, 'desc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                }
            });
        });
    </script>
    <script>
        function submitLogbookToDpl(id) {
            document.getElementById('submit-logbook-' + id).submit();
        }
    </script>
    @endpush
</x-app-layout> 
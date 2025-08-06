<x-app-layout>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-12">
                <h2 class="fw-bold">Daftar Pengaduan</h2>
                <p class="text-muted">Kelola pengaduan masyarakat terkait KKN Universitas Sugeng Hartono</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="pengaduanTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" 
                                id="pending-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#pending" 
                                type="button" 
                                role="tab">
                            <i class="fas fa-clock me-2"></i>Menunggu
                            <span class="badge bg-warning ms-2">{{ $pengaduan->where('status', 'pending')->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" 
                                id="process-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#process" 
                                type="button" 
                                role="tab">
                            <i class="fas fa-spinner me-2"></i>Diproses
                            <span class="badge bg-info ms-2">{{ $pengaduan->where('status', 'process')->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" 
                                id="resolved-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#resolved" 
                                type="button" 
                                role="tab">
                            <i class="fas fa-check-circle me-2"></i>Selesai
                            <span class="badge bg-success ms-2">{{ $pengaduan->where('status', 'resolved')->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" 
                                id="rejected-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#rejected" 
                                type="button" 
                                role="tab">
                            <i class="fas fa-times-circle me-2"></i>Ditolak
                            <span class="badge bg-danger ms-2">{{ $pengaduan->where('status', 'rejected')->count() }}</span>
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="pengaduanTabsContent">
                    @foreach(['pending', 'process', 'resolved', 'rejected'] as $status)
                        <div class="tab-pane fade {{ $status === 'pending' ? 'show active' : '' }}" 
                             id="{{ $status }}" 
                             role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover datatable w-100" id="{{ $status }}Table">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%">No. Pengaduan</th>
                                            <th style="width: 12%">Tanggal</th>
                                            <th style="width: 20%">Pelapor</th>
                                            <th style="width: 18%">Subjek</th>
                                            <th style="width: 15%">Lokasi</th>
                                            <th style="width: 10%">Status</th>
                                            <th style="width: 10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($pengaduan->where('status', $status)->count() > 0)
                                            @foreach($pengaduan->where('status', $status) as $item)
                                                <tr>
                                                    <td>
                                                        <span class="fw-bold">{{ $item->nomor_pengaduan }}</span>
                                                    </td>
                                                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold">{{ $item->nama_pelapor }}</span>
                                                            <small class="text-muted">{{ $item->email_pelapor }}</small>
                                                            <small class="text-muted">{{ $item->no_hp_pelapor }}</small>
                                                        </div>
                                                    </td>
                                                    <td>{{ $item->subjek }}</td>
                                                    <td>{{ $item->lokasi->nama }}</td>
                                                    <td>
                                                        <span class="badge {{ $item->getStatusBadgeClass() }}">
                                                            {{ $item->getStatusOptions()[$item->status] }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="{{ route('pengaduan.show', $item) }}" 
                                                               class="btn btn-sm btn-info" 
                                                               data-bs-toggle="tooltip" 
                                                               title="Lihat Detail">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            
                                                            @if($item->status === 'pending' || $item->status === 'process')
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-primary" 
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#processModal{{ $item->id }}"
                                                                        title="{{ $item->status === 'pending' ? 'Proses Pengaduan' : 'Update Status' }}">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            @endif

                                                            <form action="{{ route('pengaduan.destroy', $item) }}" 
                                                                  method="POST" 
                                                                  class="d-inline" 
                                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="btn btn-sm btn-danger"
                                                                        data-bs-toggle="tooltip" 
                                                                        title="Hapus Pengaduan">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>

                                                        <!-- Process Modal -->
                                                        <div class="modal fade" id="processModal{{ $item->id }}" tabindex="-1">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <form action="{{ route('pengaduan.process', $item) }}" method="POST">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">
                                                                                {{ $item->status === 'pending' ? 'Proses Pengaduan' : 'Update Status Pengaduan' }} 
                                                                                #{{ $item->nomor_pengaduan }}
                                                                            </h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                        </div>
                                                                        
                                                                        <div class="modal-body">
                                                                            <div class="mb-3">
                                                                                <label for="status" class="form-label">Status</label>
                                                                                <select class="form-select" name="status" required>
                                                                                    @if($item->status === 'pending')
                                                                                        <option value="process">Diproses</option>
                                                                                    @endif
                                                                                    <option value="resolved">Selesai</option>
                                                                                    <option value="rejected">Ditolak</option>
                                                                                </select>
                                                                            </div>
                                                                            
                                                                            <div class="mb-3">
                                                                                <label for="tanggapan" class="form-label">
                                                                                    {{ $item->tanggapan ? 'Update Tanggapan' : 'Tanggapan' }}
                                                                                </label>
                                                                                <textarea class="form-control" 
                                                                                          name="tanggapan" 
                                                                                          rows="4" 
                                                                                          required>{{ $item->tanggapan }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                            <button type="submit" class="btn btn-primary">
                                                                                <i class="fas fa-save me-2"></i>Simpan
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-center">
                                                    <div class="p-3">
                                                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                                        <p class="h5 text-muted">
                                                            Tidak ada pengaduan dengan status 
                                                            @switch($status)
                                                                @case('pending')
                                                                    Menunggu
                                                                    @break
                                                                @case('process')
                                                                    Diproses
                                                                    @break
                                                                @case('resolved')
                                                                    Selesai
                                                                    @break
                                                                @case('rejected')
                                                                    Ditolak
                                                                    @break
                                                            @endswitch
                                                        </p>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .table {
            width: 100% !important;
            margin-bottom: 0;
        }
        
        .dataTables_wrapper {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .card-body {
            padding: 0;
        }

        .tab-content {
            padding: 1rem;
        }

        .table > :not(caption) > * > * {
            padding: 1rem;
        }

        .btn-group {
            display: flex;
            gap: 0.25rem;
        }

        .table td {
            vertical-align: middle;
        }

        .dataTables_length,
        .dataTables_filter {
            padding: 1rem 1rem 0;
        }

        .dataTables_info,
        .dataTables_paginate {
            padding: 1rem;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Initialize DataTables
        $(document).ready(function() {
            $('.datatable').each(function() {
                $(this).DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                    },
                    order: [[1, 'desc']], // Sort by date descending
                    pageLength: 10,
                    responsive: true,
                    columnDefs: [
                        { responsivePriority: 1, targets: [0, 2, 5, 6] }, // Nomor, Pelapor, Status, Aksi
                        { responsivePriority: 2, targets: [1, 3] }, // Tanggal, Subjek
                        { responsivePriority: 3, targets: 4 }, // Lokasi
                        { orderable: false, targets: 6 }, // Aksi tidak bisa diurutkan
                        { width: '15%', targets: 0 }, // No. Pengaduan
                        { width: '12%', targets: 1 }, // Tanggal
                        { width: '20%', targets: 2 }, // Pelapor
                        { width: '18%', targets: 3 }, // Subjek
                        { width: '15%', targets: 4 }, // Lokasi
                        { width: '10%', targets: 5 }, // Status
                        { width: '10%', targets: 6 }  // Aksi
                    ],
                    dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                         "<'row'<'col-sm-12'tr>>" +
                         "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    initComplete: function() {
                        // Menambahkan class untuk styling
                        $('.dataTables_length select').addClass('form-select form-select-sm');
                        $('.dataTables_filter input').addClass('form-control form-control-sm');
                    }
                });
            });
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Reinitialize tooltips after DataTables page change
        $('.datatable').on('draw.dt', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
    @endpush
</x-app-layout> 
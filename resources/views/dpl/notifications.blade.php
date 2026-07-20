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
            
            /* Button adjustments */
            .btn {
                font-size: 14px;
                padding: 8px 12px;
            }
            
            /* List group adjustments */
            .list-group-item {
                padding: 12px !important;
            }
            
            .list-group-item h6 {
                font-size: 1rem;
            }
            
            .list-group-item p {
                font-size: 13px;
            }
            
            .list-group-item small {
                font-size: 11px;
            }
            
            /* Icon adjustments */
            .rounded-circle {
                width: 35px !important;
                height: 35px !important;
            }
            
            .rounded-circle i {
                font-size: 14px;
            }
            
            /* Quick actions adjustments */
            .row .col-md-6 {
                margin-bottom: 15px;
            }
            
            .card-header h5 {
                font-size: 1rem;
            }
            
            /* Stack buttons vertically on mobile */
            .mt-3 .btn {
                margin-bottom: 8px;
                width: 100%;
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
            
            .btn {
                font-size: 13px;
                padding: 6px 10px;
            }
            
            .list-group-item {
                padding: 10px !important;
            }
            
            .list-group-item h6 {
                font-size: 0.9rem;
            }
            
            .list-group-item p {
                font-size: 12px;
            }
            
            .list-group-item small {
                font-size: 10px;
            }
            
            .rounded-circle {
                width: 30px !important;
                height: 30px !important;
            }
            
            .rounded-circle i {
                font-size: 12px;
            }
            
            .card-header h5 {
                font-size: 0.9rem;
            }
            
            /* Stack header buttons vertically */
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 10px;
            }
            
            .d-flex.justify-content-between .btn {
                width: 100%;
            }
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notifikasi DPL') }}
        </h2>
    </x-slot>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">Notifikasi DPL</h2>
                        <p class="text-muted">Daftar notifikasi terkait mahasiswa bimbingan Anda</p>
                    </div>
                    <div>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @if(count($notifications) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover datatable w-100">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="20%">Kategori</th>
                                    <th width="45%">Pesan Notifikasi</th>
                                    <th width="15%">Waktu</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notifications as $index => $notification)
                                    @php
                                        switch($notification['type']) {
                                            case 'logbook_pending':
                                                $icon = 'book'; $color = 'warning'; break;
                                            case 'absensi_pending':
                                                $icon = 'calendar-check'; $color = 'info'; break;
                                            case 'no_logbook_today':
                                                $icon = 'book-dead'; $color = 'danger'; break;
                                            case 'no_absensi_today':
                                                $icon = 'user-clock'; $color = 'warning'; break;
                                            default:
                                                $icon = 'info-circle'; $color = 'primary'; break;
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} px-2 py-1 rounded">
                                                <i class="fas fa-{{ $icon }} me-1"></i> {{ $notification['title'] }}
                                            </span>
                                        </td>
                                        <td>{{ $notification['message'] }}</td>
                                        <td>
                                            <small class="text-muted" data-order="{{ $notification['created_at']->timestamp }}">
                                                {{ $notification['created_at']->diffForHumans() }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($notification['type'] == 'logbook_pending')
                                                <a href="{{ route('logbooks.pending') }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-eye"></i> Review
                                                </a>
                                            @elseif($notification['type'] == 'absensi_pending')
                                                <a href="{{ route('attendance.pending') }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-check"></i> Validasi
                                                </a>
                                            @elseif($notification['type'] == 'no_logbook_today')
                                                <a href="{{ route('students.show', $notification['data']['user_id']) }}" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-user"></i> Detail
                                                </a>
                                            @elseif($notification['type'] == 'no_absensi_today')
                                                <a href="{{ route('students.show', $notification['data']['user_id']) }}" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-user"></i> Detail
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak Ada Notifikasi</h5>
                        <p class="text-muted">Semua mahasiswa bimbingan Anda sudah melengkapi tugas hari ini</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        @if(count($notifications) > 0)
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-book me-2"></i>Aksi Cepat Logbook
                        </h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('logbooks.pending') }}" class="btn btn-warning me-2">
                            <i class="fas fa-eye"></i> Review Logbook Pending
                        </a>
                        <a href="{{ route('students.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-users"></i> Lihat Mahasiswa
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar-check me-2"></i>Aksi Cepat Absensi
                        </h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('attendance.pending') }}" class="btn btn-info me-2">
                            <i class="fas fa-check"></i> Validasi Absensi
                        </a>
                        <a href="{{ route('groups.monitoring') }}" class="btn btn-outline-success">
                            <i class="fas fa-map"></i> Monitoring Kelompok
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                },
                order: [
                    [3, 'desc'] // Urutkan berdasarkan waktu secara default
                ],
                columnDefs: [
                    { orderable: false, targets: 4 } // Aksi tidak perlu bisa diurutkan
                ]
            });
        });
    </script>
    @endpush
</x-app-layout> 
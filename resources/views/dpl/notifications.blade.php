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
                    <div class="list-group list-group-flush">
                        @foreach($notifications as $notification)
                            @php
                                switch($notification['type']) {
                                    case 'logbook_pending':
                                        $icon = 'book'; $color = 'warning'; break;
                                    case 'absensi_pending':
                                        $icon = 'calendar-check'; $color = 'info'; break;
                                    case 'no_logbook_today':
                                        $icon = 'exclamation-triangle'; $color = 'danger'; break;
                                    case 'no_absensi_today':
                                        $icon = 'exclamation-triangle'; $color = 'danger'; break;
                                    default:
                                        $icon = 'info-circle'; $color = 'primary'; break;
                                }
                            @endphp
                            <div class="list-group-item d-flex align-items-start p-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px; background-color: #007bff;">
                                        <i class="fas fa-{{ $icon }} text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1 fw-bold text-{{ $color }}">
                                                {{ $notification['title'] }}
                                            </h6>
                                            <p class="mb-1 text-muted">{{ $notification['message'] }}</p>
                                            <small class="text-muted">
                                                {{ $notification['created_at']->diffForHumans() }}
                                            </small>
                                        </div>
                                        <div>
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
                                                    <i class="fas fa-user"></i> Detail Mahasiswa
                                                </a>
                                            @elseif($notification['type'] == 'no_absensi_today')
                                                <a href="{{ route('students.show', $notification['data']['user_id']) }}" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-user"></i> Detail Mahasiswa
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
</x-app-layout> 
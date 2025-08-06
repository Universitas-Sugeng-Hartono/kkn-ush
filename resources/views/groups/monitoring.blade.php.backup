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
            
            /* Kelompok cards - Stack vertically */
            .row .col-md-6 {
                margin-bottom: 15px;
            }
            
            /* Text adjustments */
            h2.fw-bold {
                font-size: 1.5rem;
            }
            
            h5.card-title {
                font-size: 1.1rem;
            }
            
            /* Button adjustments */
            .btn-sm {
                font-size: 12px;
                padding: 6px 10px;
                margin-bottom: 5px;
            }
            
            /* Alert adjustments */
            .alert {
                font-size: 12px;
                padding: 8px 12px;
            }
            
            /* Stats adjustments */
            .text-center h6 {
                font-size: 1.2rem;
            }
            
            .text-center small {
                font-size: 11px;
            }
            
            /* Row spacing */
            .row.mb-3 {
                margin-bottom: 15px !important;
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
            
            .btn-sm {
                font-size: 11px;
                padding: 5px 8px;
            }
            
            .alert {
                font-size: 11px;
                padding: 6px 10px;
            }
            
            .text-center h6 {
                font-size: 1.1rem;
            }
            
            .text-center small {
                font-size: 10px;
            }
            
            /* Stack buttons vertically on very small screens */
            .mt-3 .btn {
                display: block;
                width: 100%;
                margin-bottom: 8px;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <h2 class="fw-bold">Monitoring Kelompok KKN</h2>
                <p class="text-muted">Pantau aktivitas mahasiswa bimbingan Anda</p>
            </div>
        </div>



        <!-- Alert Notifications -->
        <div id="alerts-container" class="mb-4">
            <!-- Alerts will be loaded here -->
        </div>

        <!-- Kelompok List -->
        <div class="row">
            @foreach($groups as $group)
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-users me-2"></i>
                            {{ $group->nama }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Lokasi:</strong><br>
                                {{ $group->lokasi->nama }}<br>
                                <small class="text-muted">{{ $group->lokasi->alamat }}</small>
                            </div>
                            <div class="col-md-6">
                                <strong>Angkatan:</strong><br>
                                {{ $group->angkatan->nama }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h6 class="text-primary">{{ $group->mahasiswa->count() }}</h6>
                                    <small>Mahasiswa</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h6 class="text-success">{{ $group->logbooks->count() }}</h6>
                                    <small>Logbook</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h6 class="text-info">{{ $group->absensi->count() }}</h6>
                                    <small>Absensi</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="alert alert-warning py-2 mb-2">
                                    <small>
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $group->logbooks->where('status', 'submitted')->count() }} logbook pending
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-info py-2 mb-2">
                                    <small>
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $group->absensi->where('status', 'pending')->count() }} absensi pending
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('students.index') }}?group={{ $group->id }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye me-1"></i> Lihat Detail
                            </a>
                            <a href="{{ route('groups.monitoring.map') }}?group={{ $group->id }}" class="btn btn-success btn-sm">
                                <i class="fas fa-map me-1"></i> Lihat Peta
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($groups->isEmpty())
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5>Belum Ada Kelompok</h5>
                        <p class="text-muted">Anda belum ditugaskan sebagai DPL untuk kelompok manapun.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
        // Load alerts
        function loadAlerts() {
            fetch('{{ route("alerts.get") }}')
                .then(response => response.json())
                .then(alerts => {
                    const container = document.getElementById('alerts-container');
                    container.innerHTML = '';
                    
                    alerts.forEach(alert => {
                        const alertHtml = `
                            <div class="alert alert-${alert.type} alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>${alert.title}:</strong> ${alert.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `;
                        container.innerHTML += alertHtml;
                    });
                });
        }

        // Load alerts on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadAlerts();
            
            // Refresh alerts every 5 minutes
            setInterval(loadAlerts, 300000);
        });
    </script>
    @endpush
</x-app-layout> 
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
            
            /* Statistik Cards - Stack vertically on mobile */
            .row .col-md-3 {
                margin-bottom: 15px;
            }
            
            /* Quick Actions - Stack buttons vertically */
            .row .col-md-3 .btn {
                margin-bottom: 10px;
                font-size: 14px;
                padding: 10px 15px;
            }
            
            /* Recent Activities - Stack cards vertically */
            .row .col-md-6 {
                margin-bottom: 20px;
            }
            
            /* Charts - Stack vertically */
            .row .col-md-8,
            .row .col-md-4 {
                margin-bottom: 20px;
            }
            
            /* Welcome card adjustments */
            .welcome-info .row .col-md-4 {
                margin-bottom: 10px;
            }
            
            .info-item {
                font-size: 14px;
                margin-bottom: 5px;
            }
            
            /* Profile image smaller on mobile */
            .rounded-circle {
                width: 80px !important;
                height: 80px !important;
                font-size: 24px !important;
            }
            
            /* Text adjustments */
            h2.fw-bold {
                font-size: 1.5rem;
            }
            
            h4 {
                font-size: 1.2rem;
            }
            
            /* Button text smaller */
            .btn {
                font-size: 14px;
            }
            
            /* Badge adjustments */
            .badge {
                font-size: 12px;
            }
            
            /* Table responsive */
            .table-responsive {
                font-size: 14px;
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
            
            h4 {
                font-size: 1.1rem;
            }
            
            .btn {
                font-size: 13px;
                padding: 8px 12px;
            }
        }
    </style>

    <div class="container-fluid">
        <!-- Device Detection Indicator -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <div>
                        <strong>Device Detection:</strong>
                        <span id="deviceIndicator">Detecting...</span>
                        <small class="ms-2 text-muted">(Updates in real-time)</small>
                    </div>
                </div>
            </div>
        </div>

        @role('admin')
        <!-- Admin Dashboard -->
        <div class="row mb-4">
            <div class="col-md-12">
                <h2 class="fw-bold">Dashboard Admin</h2>
                <p class="text-muted">Ringkasan data KKN Universitas Sugeng Hartono</p>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="card-subtitle mb-2 text-muted">Total Mahasiswa</h6>
                                <h2 class="card-title mb-0">{{ $data['total_mahasiswa'] }}</h2>
                            </div>
                            <div class="bg-primary rounded-circle p-3">
                                <i class="fas fa-users fa-fw text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="card-subtitle mb-2 text-muted">Total DPL</h6>
                                <h2 class="card-title mb-0">{{ $data['total_dpl'] }}</h2>
                            </div>
                            <div class="bg-success rounded-circle p-3">
                                <i class="fas fa-user-tie fa-fw text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="card-subtitle mb-2 text-muted">Total Kelompok</h6>
                                <h2 class="card-title mb-0">{{ $data['total_kelompok'] }}</h2>
                            </div>
                            <div class="bg-success rounded-circle p-3">
                                <i class="fas fa-users-cog fa-fw text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <a href="{{ route('pengaduan.index') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-muted">Pengaduan Baru</h6>
                                    <h2 class="card-title mb-0">{{ $data['pengaduan_baru'] }}</h2>
                                </div>
                                <div class="bg-warning rounded-circle p-3">
                                    <i class="fas fa-exclamation-circle fa-fw text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Statistik Logbook</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="logbookChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Statistik Absensi</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="absensiChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endrole

        @role('dpl')
        <!-- DPL Dashboard -->
        <div class="row mb-4">
            <div class="col-md-12">
                <h2 class="fw-bold">Dashboard Dosen Pembimbing</h2>
                <p class="text-muted">Ringkasan aktivitas mahasiswa bimbingan Anda</p>
            </div>
        </div>

        <!-- Alert Notifications -->
        <div id="alerts-container" class="mb-4">
            <!-- Alerts will be loaded here -->
        </div>

        <!-- Statistik Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <a href="{{ route('students.index') }}" class="text-decoration-none">
                    <div class="card bg-primary text-white" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $data['total_mahasiswa'] }}</h4>
                                    <p class="mb-0">Total Mahasiswa</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small>Klik untuk lihat daftar mahasiswa</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('groups.monitoring') }}" class="text-decoration-none">
                    <div class="card bg-success text-white" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $data['total_kelompok'] }}</h4>
                                    <p class="mb-0">Total Kelompok</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-layer-group fa-2x"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small>Klik untuk lihat monitoring kelompok</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('logbooks.pending') }}" class="text-decoration-none">
                    <div class="card bg-warning text-white" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $data['logbook_pending'] }}</h4>
                                    <p class="mb-0">Logbook Pending</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small>Klik untuk review logbook</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('attendance.pending') }}" class="text-decoration-none">
                    <div class="card bg-info text-white" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $data['absensi_pending'] }}</h4>
                                    <p class="mb-0">Absensi Pending</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-calendar-check fa-2x"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small>Klik untuk validasi absensi</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Aksi Cepat</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="{{ route('students.index') }}" class="btn btn-primary w-100 mb-2">
                                    <i class="fas fa-users me-2"></i>Lihat Mahasiswa
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('groups.monitoring') }}" class="btn btn-success w-100 mb-2">
                                    <i class="fas fa-chart-line me-2"></i>Monitoring Kelompok
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('groups.monitoring.map') }}" class="btn btn-info w-100 mb-2">
                                    <i class="fas fa-map-marked-alt me-2"></i>Peta Lokasi
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('grades.index') }}" class="btn btn-warning w-100 mb-2">
                                    <i class="fas fa-star me-2"></i>Penilaian
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Logbook Terbaru</h5>
                    </div>
                    <div class="card-body">
                        @if($data['recent_logbooks']->count() > 0)
                            @foreach($data['recent_logbooks'] as $logbook)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-1">{{ $logbook->user->name }}</h6>
                                    @if($logbook->user->nim)
                                        <small class="text-muted">NIM: {{ $logbook->user->nim }}</small><br>
                                        @if($logbook->user->jurusan)
                                            <small class="text-muted">Jurusan: {{ ucfirst($logbook->user->jurusan) }}</small><br>
                                        @endif
                                    @endif
                                    <small class="text-muted">{{ $logbook->judul }}</small><br>
                                    <small class="text-muted">{{ $logbook->kelompok->nama }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-{{ $logbook->status === 'submitted' ? 'warning' : ($logbook->status === 'approved' ? 'success' : 'secondary') }}">
                                        {{ ucfirst($logbook->status) }}
                                    </span><br>
                                    <small class="text-muted">{{ $logbook->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <p class="text-muted">Belum ada logbook terbaru</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Absensi Terbaru</h5>
                    </div>
                    <div class="card-body">
                        @if($data['recent_absensi']->count() > 0)
                            @foreach($data['recent_absensi'] as $absensi)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-1">{{ $absensi->user->name }}</h6>
                                    @if($absensi->user->nim)
                                        <small class="text-muted">NIM: {{ $absensi->user->nim }}</small><br>
                                        @if($absensi->user->jurusan)
                                            <small class="text-muted">Jurusan: {{ ucfirst($absensi->user->jurusan) }}</small><br>
                                        @endif
                                    @endif
                                    <small class="text-muted">{{ $absensi->tanggal->format('d/m/Y') }}</small><br>
                                    <small class="text-muted">{{ $absensi->kelompok->nama }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-{{ $absensi->status === 'hadir' ? 'success' : ($absensi->status === 'izin' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($absensi->status) }}
                                    </span><br>
                                    <small class="text-muted">{{ $absensi->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <p class="text-muted">Belum ada absensi terbaru</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row g-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Statistik Logbook Mahasiswa</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="logbookChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Statistik Absensi Mahasiswa</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="absensiChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endrole

        @role('mahasiswa')
        <!-- Mahasiswa Dashboard -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card bg-gradient-orange text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="fw-bold mb-2">Dashboard Mahasiswa</h2>
                                <div class="welcome-info">
                                    <h4 class="mb-3">
                                        <i class="fas fa-user-graduate me-2"></i>
                                        Selamat datang, Mahasiswa KKN
                                    </h4>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="info-item">
                                                <i class="fas fa-id-card me-2"></i>
                                                <strong>NIM:</strong> {{ auth()->user()->nim ?? 'Tidak tersedia' }}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-item">
                                                <i class="fas fa-graduation-cap me-2"></i>
                                                <strong>Jurusan:</strong> {{ auth()->user()->jurusan ? ucfirst(auth()->user()->jurusan) : 'Tidak tersedia' }}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-item">
                                                <i class="fas fa-users me-2"></i>
                                                <strong>Kelompok:</strong> {{ auth()->user()->kelompok ? auth()->user()->kelompok->nama : 'Belum ditentukan' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                @if(auth()->user()->photo)
                                    <img src="{{ Storage::url(auth()->user()->photo) }}" 
                                         alt="{{ auth()->user()->name }}" 
                                         class="rounded-circle border border-white"
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center border border-white"
                                         style="width: 100px; height: 100px; font-size: 32px; font-weight: bold;">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="card-subtitle mb-2 text-muted">Total Logbook</h6>
                                <h2 class="card-title mb-0">{{ $data['logbook_total'] }}</h2>
                            </div>
                            <div class="bg-primary rounded-circle p-3">
                                <i class="fas fa-book fa-fw text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="card-subtitle mb-2 text-muted">Logbook Disetujui</h6>
                                <h2 class="card-title mb-0">{{ $data['logbook_approved'] }}</h2>
                            </div>
                            <div class="bg-success rounded-circle p-3">
                                <i class="fas fa-check-circle fa-fw text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="card-subtitle mb-2 text-muted">Total Absensi</h6>
                                <h2 class="card-title mb-0">{{ $data['absensi_total'] }}</h2>
                            </div>
                            <div class="bg-info rounded-circle p-3">
                                <i class="fas fa-clipboard-check fa-fw text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Statistik Logbook</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="logbookChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Statistik Absensi</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="absensiChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Kelompok -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informasi Kelompok</h5>
                    </div>
                    <div class="card-body">
                        @if($data['kelompok'])
                            <div class="mb-3">
                                <h6 class="fw-bold">Nama Kelompok:</h6>
                                <p>{{ $data['kelompok']->nama }}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="fw-bold">Dosen Pembimbing Lapangan:</h6>
                                <p>{{ $data['dpl']->name ?? 'Belum ditentukan' }}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="fw-bold">Anggota Kelompok:</h6>
                                <ul class="list-unstyled">
                                    @foreach($data['anggota_kelompok'] as $anggota)
                                        <li>{{ $anggota->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                Anda belum terdaftar dalam kelompok manapun.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Lokasi KKN</h5>
                    </div>
                    <div class="card-body">
                        @if($data['lokasi'])
                            <div class="mb-3">
                                <h6 class="fw-bold">Alamat:</h6>
                                <p>{{ $data['lokasi']->alamat }}</p>
                            </div>
                            <div id="map" style="height: 300px;" class="mb-3"></div>
                            <input type="hidden" id="latitude" value="{{ $data['lokasi']->latitude }}">
                            <input type="hidden" id="longitude" value="{{ $data['lokasi']->longitude }}">
                        @else
                            <div class="alert alert-warning">
                                Informasi lokasi belum tersedia.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endrole
    </div>

    @push('styles')
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .bg-gradient-orange {
            background: linear-gradient(135deg, #f2b70d 0%, #f39c12 100%);
        }
        
        .welcome-info .info-item {
            padding: 8px 0;
            font-size: 16px;
        }
        
        .welcome-info .info-item i {
            width: 20px;
            text-align: center;
        }
        
        .card.bg-gradient-primary,
        .card.bg-gradient-orange {
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .card.bg-gradient-primary .card-body,
        .card.bg-gradient-orange .card-body {
            padding: 2rem;
        }
        
        .welcome-info h4 {
            color: rgba(255,255,255,0.9);
            font-weight: 600;
        }
        
        .welcome-info .info-item {
            color: rgba(255,255,255,0.9);
        }
        
        .welcome-info .info-item strong {
            color: white;
        }
        
        /* Responsive Design untuk Mobile */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 10px;
            }
            
            .card.bg-gradient-primary .card-body,
            .card.bg-gradient-orange .card-body {
                padding: 1.5rem;
            }
            
            .welcome-info h4 {
                font-size: 1.1rem;
                margin-bottom: 1rem;
            }
            
            .welcome-info .info-item {
                font-size: 14px;
                padding: 6px 0;
                margin-bottom: 8px;
            }
            
            .welcome-info .row {
                margin: 0;
            }
            
            .welcome-info .col-md-4 {
                padding: 0 5px;
                margin-bottom: 10px;
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
            
            .card-subtitle {
                font-size: 0.875rem;
            }
            
            .card-title {
                font-size: 1.5rem;
            }
            
            .bg-primary.rounded-circle,
            .bg-success.rounded-circle,
            .bg-info.rounded-circle {
                padding: 0.5rem !important;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .bg-primary.rounded-circle i,
            .bg-success.rounded-circle i,
            .bg-info.rounded-circle i {
                font-size: 0.875rem;
            }
            
            #map {
                height: 200px !important;
            }
            
            .d-flex.justify-content-between.align-items-center {
                flex-direction: column;
                text-align: center;
            }
            
            .d-flex.justify-content-between.align-items-center > div:first-child {
                margin-bottom: 1rem;
            }
            
            .text-end img,
            .text-end div {
                width: 80px !important;
                height: 80px !important;
                font-size: 24px !important;
            }
        }
        
        @media (max-width: 576px) {
            .welcome-info .row {
                flex-direction: column;
            }
            
            .welcome-info .col-md-4 {
                width: 100%;
                margin-bottom: 15px;
            }
            
            .card-body {
                padding: 0.75rem;
            }
            
            .card-header {
                padding: 0.5rem 0.75rem;
            }
            
            .btn {
                font-size: 0.875rem;
                padding: 0.375rem 0.75rem;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="{{ asset("assets/css/leaflet.min.css") }}" />
    <!-- Leaflet JS -->
    <script src="{{ asset("assets/js/leaflet.min.js") }}"></script>

    <script>
        // Load alerts for DPL
        function loadAlerts() {
            fetch('{{ route("alerts.get") }}')
                .then(response => response.json())
                .then(alerts => {
                    const container = document.getElementById('alerts-container');
                    if (container) {
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
                    }
                });
        }

        // Load alerts on page load for DPL
        document.addEventListener('DOMContentLoaded', function() {
            @role('dpl')
            loadAlerts();
            
            // Refresh alerts every 5 minutes
            setInterval(loadAlerts, 300000);
            @endrole
        });

        // Charts for DPL
        @role('dpl')
        document.addEventListener('DOMContentLoaded', function() {
            // Logbook Chart
            const logbookCtx = document.getElementById('logbookChart');
            if (logbookCtx) {
                const logbookData = @json($data['logbook_stats']);
                new Chart(logbookCtx, {
                    type: 'line',
                    data: {
                        labels: logbookData.map(item => item.date),
                        datasets: [{
                            label: 'Logbook',
                            data: logbookData.map(item => item.count),
                            borderColor: 'rgb(75, 192, 192)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Absensi Chart
            const absensiCtx = document.getElementById('absensiChart');
            if (absensiCtx) {
                const absensiData = @json($data['absensi_stats']);
                new Chart(absensiCtx, {
                    type: 'bar',
                    data: {
                        labels: absensiData.map(item => item.date),
                        datasets: [{
                            label: 'Absensi',
                            data: absensiData.map(item => item.count),
                            backgroundColor: 'rgba(54, 162, 235, 0.8)',
                            borderColor: 'rgb(54, 162, 235)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        });
        @endrole

        // Charts for Admin and Mahasiswa
        @role('admin|mahasiswa')
        document.addEventListener('DOMContentLoaded', function() {
            // Logbook Chart
            const logbookCtx = document.getElementById('logbookChart');
            if (logbookCtx && @json(isset($data['logbook_stats']['labels']))) {
                new Chart(logbookCtx, {
                    type: 'line',
                    data: {
                        labels: @json($data['logbook_stats']['labels'] ?? []),
                        datasets: [{
                            label: 'Jumlah Logbook',
                            data: @json($data['logbook_stats']['data'] ?? []),
                            borderColor: '#0B1F3A',
                            tension: 0.1,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }

            // Absensi Chart
            const absensiCtx = document.getElementById('absensiChart');
            if (absensiCtx && @json(isset($data['absensi_stats']['labels']))) {
                new Chart(absensiCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($data['absensi_stats']['labels'] ?? []),
                        datasets: [{
                            data: @json($data['absensi_stats']['data'] ?? []),
                            backgroundColor: ['#0B1F3A', '#F2B705', '#dc3545', '#6c757d']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
        });
        @endrole

        // Device Detection Indicator
        function updateDeviceIndicator() {
            const deviceInfo = getDeviceInfo();
            const indicator = document.getElementById('deviceIndicator');
            
            if (deviceInfo && indicator) {
                let deviceType = '';
                let deviceIcon = '';
                
                if (deviceInfo.isMobile) {
                    deviceType = 'Mobile Device';
                    deviceIcon = '📱';
                } else if (deviceInfo.isTablet) {
                    deviceType = 'Tablet Device';
                    deviceIcon = '📱';
                } else {
                    deviceType = 'Desktop Device';
                    deviceIcon = '💻';
                }
                
                indicator.innerHTML = `
                    ${deviceIcon} ${deviceType} (${deviceInfo.width}×${deviceInfo.height})
                    <span class="badge bg-secondary ms-2">${window.devicePixelRatio || 1}x</span>
                `;
            }
        }

        // Update device indicator on page load and resize
        document.addEventListener('DOMContentLoaded', function() {
            updateDeviceIndicator();
            
            // Update on resize
            window.addEventListener('resize', function() {
                setTimeout(updateDeviceIndicator, 250);
            });
        });

        // Initialize map if location exists
        const latitude = document.getElementById('latitude');
        const longitude = document.getElementById('longitude');
        
        if (latitude && longitude) {
            const lat = parseFloat(latitude.value);
            const lng = parseFloat(longitude.value);
            
            const map = L.map('map').setView([lat, lng], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            L.marker([lat, lng]).addTo(map)
                .bindPopup('Lokasi KKN')
                .openPopup();
        }
    </script>
    @endpush
</x-app-layout>


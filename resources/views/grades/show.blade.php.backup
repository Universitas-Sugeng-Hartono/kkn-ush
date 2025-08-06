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
            
            h6 {
                font-size: 1rem;
            }
            
            /* Profile image adjustments */
            .rounded-circle {
                width: 60px !important;
                height: 60px !important;
            }
            
            .bg-secondary.rounded-circle {
                width: 60px !important;
                height: 60px !important;
            }
            
            .bg-secondary.rounded-circle i {
                font-size: 1.5rem !important;
            }
            
            /* Tab navigation adjustments */
            .nav-tabs .nav-link {
                font-size: 14px;
                padding: 8px 12px;
            }
            
            .nav-tabs .nav-link i {
                font-size: 12px;
            }
            
            /* Display adjustments */
            .display-4 {
                font-size: 2.5rem;
            }
            
            /* Button adjustments */
            .btn {
                font-size: 14px;
                padding: 8px 12px;
            }
            
            /* Stack header buttons vertically */
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 10px;
            }
            
            .d-flex.justify-content-between .btn {
                width: 100%;
            }
            
            /* Grid adjustments */
            .row .col-md-6,
            .row .col-md-8,
            .row .col-md-4 {
                margin-bottom: 15px;
            }
            
            /* Badge adjustments */
            .badge {
                font-size: 12px;
            }
            
            .badge.fs-6 {
                font-size: 14px !important;
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
            
            h6 {
                font-size: 0.9rem;
            }
            
            .rounded-circle {
                width: 50px !important;
                height: 50px !important;
            }
            
            .bg-secondary.rounded-circle {
                width: 50px !important;
                height: 50px !important;
            }
            
            .bg-secondary.rounded-circle i {
                font-size: 1.2rem !important;
            }
            
            .nav-tabs .nav-link {
                font-size: 13px;
                padding: 6px 8px;
            }
            
            .nav-tabs .nav-link i {
                font-size: 11px;
            }
            
            .display-4 {
                font-size: 2rem;
            }
            
            .btn {
                font-size: 13px;
                padding: 6px 10px;
            }
            
            .badge {
                font-size: 11px;
            }
            
            .badge.fs-6 {
                font-size: 12px !important;
            }
            
            /* Stack profile info vertically */
            .d-flex.align-items-center {
                flex-direction: column;
                text-align: center;
            }
            
            .d-flex.align-items-center .me-4 {
                margin-right: 0 !important;
                margin-bottom: 15px;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">Detail Penilaian</h2>
                        <p class="text-muted">Detail penilaian mahasiswa KKN Tematik</p>
                    </div>
                    <div>
                        <a href="{{ route('grades.edit', $grade) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('grades.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="row mb-4">
            <div class="col-md-12">
                <ul class="nav nav-tabs nav-fill" id="gradeTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                            <i class="fas fa-user me-2"></i>Informasi Mahasiswa
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="grade-tab" data-bs-toggle="tab" data-bs-target="#grade" type="button" role="tab">
                            <i class="fas fa-chart-bar me-2"></i>Hasil Penilaian
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="gradeTabsContent">
            <!-- Tab Informasi Mahasiswa -->
            <div class="tab-pane fade show active" id="info" role="tabpanel">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-user me-2"></i>Informasi Mahasiswa
                                </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <div class="d-flex align-items-center">
                                    @if($grade->user->photo)
                                        <img src="{{ Storage::url($grade->user->photo) }}" 
                                                     class="rounded-circle me-4" 
                                                     width="80" height="80" 
                                                     alt="{{ $grade->user->name }}"
                                                     style="object-fit: cover;">
                                    @else
                                                <div class="bg-secondary rounded-circle me-4 d-flex align-items-center justify-content-center" 
                                                     style="width: 80px; height: 80px;">
                                            <i class="fas fa-user fa-2x text-white"></i>
                                        </div>
                                    @endif
                                    <div>
                                                <h4 class="mb-1">{{ $grade->user->name }}</h4>
                                                <p class="text-muted mb-1">{{ $grade->user->email }}</p>
                                                <div class="d-flex gap-2">
                                                    <span class="badge bg-primary">{{ $grade->user->nim ?? 'NIM tidak tersedia' }}</span>
                                                    @if($grade->user->jurusan)
                                                        <span class="badge bg-info">{{ ucfirst($grade->user->jurusan) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title text-primary mb-3">
                                                    <i class="fas fa-info-circle me-2"></i>Informasi Pribadi
                                                </h6>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">Nama Lengkap</label>
                                                    <p class="mb-0">{{ $grade->user->name }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">NIM</label>
                                                    <p class="mb-0">{{ $grade->user->nim ?? '-' }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">Jurusan</label>
                                                    <p class="mb-0">{{ $grade->user->jurusan ? ucfirst($grade->user->jurusan) : '-' }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">Email</label>
                                                    <p class="mb-0">{{ $grade->user->email }}</p>
                                                </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title text-success mb-3">
                                                    <i class="fas fa-users me-2"></i>Informasi KKN
                                                </h6>
                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">Kelompok</label>
                                                    <p class="mb-0">
                                        @if($grade->user->kelompok)
                                                            <span class="badge bg-primary fs-6">{{ $grade->user->kelompok->nama }}</span>
                                        @else
                                            <span class="text-muted">Belum ditentukan</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">Dosen Pembimbing</label>
                                                    <p class="mb-0">{{ $grade->dpl->name ?? 'Belum ditentukan' }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-muted">Tanggal Penilaian</label>
                                                    <p class="mb-0">{{ $grade->created_at->format('d/m/Y H:i') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chart-pie me-2"></i>Ringkasan Nilai
                                </h5>
                            </div>
                            <div class="card-body text-center">
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-muted">Nilai Akhir</label>
                                    <h1 class="text-primary display-4 fw-bold">{{ number_format($grade->nilai_akhir, 1) }}</h1>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-muted">Grade</label>
                                    <h2 class="text-{{ $grade->grade === 'A' ? 'success' : ($grade->grade === 'B' ? 'info' : ($grade->grade === 'C' ? 'warning' : 'danger')) }} fw-bold">
                                        {{ $grade->grade }}
                                    </h2>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-muted">Status</label>
                                    <div>
                                        @if($grade->nilai_akhir >= 60)
                                            <span class="badge bg-success fs-6 px-3 py-2">LULUS</span>
                                        @else
                                            <span class="badge bg-danger fs-6 px-3 py-2">TIDAK LULUS</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chart-line me-2"></i>Statistik
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="border-end">
                                            <h6 class="text-muted">Rata-rata</h6>
                                            <h5 class="text-primary fw-bold">{{ number_format(($grade->nilai_kehadiran_pembekalan + $grade->nilai_sikap_pembekalan + $grade->nilai_kehadiran_lokasi + $grade->nilai_sikap_pelaksanaan + $grade->nilai_keterlibatan_kegiatan + $grade->nilai_relevansi_program + $grade->nilai_keberhasilan_program + $grade->nilai_sistematika_laporan + $grade->nilai_konten_medsos + $grade->nilai_bahasa + $grade->nilai_analisis + $grade->nilai_ketepatan_waktu + $grade->nilai_produk_teknologi) / 13, 1) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <h6 class="text-muted">Tertinggi</h6>
                                        <h5 class="text-success fw-bold">{{ max($grade->nilai_kehadiran_pembekalan, $grade->nilai_sikap_pembekalan, $grade->nilai_kehadiran_lokasi, $grade->nilai_sikap_pelaksanaan, $grade->nilai_keterlibatan_kegiatan, $grade->nilai_relevansi_program, $grade->nilai_keberhasilan_program, $grade->nilai_sistematika_laporan, $grade->nilai_konten_medsos, $grade->nilai_bahasa, $grade->nilai_analisis, $grade->nilai_ketepatan_waktu, $grade->nilai_produk_teknologi) }}</h5>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Tab Hasil Penilaian -->
            <div class="tab-pane fade" id="grade" role="tabpanel">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                    <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chart-bar me-2"></i>Komponen Penilaian KKN Tematik
                                </h5>
                    </div>
                    <div class="card-body">
                                <!-- Tahap Pembekalan (10%) -->
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-graduation-cap text-white"></i>
                                        </div>
                                        <h6 class="text-primary mb-0">1. Tahap Pembekalan (10%)</h6>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card bg-light mb-3">
                                                <div class="card-body">
                                                    <label class="form-label fw-bold">Kehadiran Selama Pembekalan</label>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress flex-grow-1 me-3" style="height: 25px;">
                                                            <div class="progress-bar bg-{{ $grade->nilai_kehadiran_pembekalan >= 80 ? 'success' : ($grade->nilai_kehadiran_pembekalan >= 60 ? 'warning' : 'danger') }}" 
                                                                 style="width: {{ $grade->nilai_kehadiran_pembekalan }}%">
                                                                {{ $grade->nilai_kehadiran_pembekalan }}
                                                            </div>
                                                        </div>
                                                        <span class="badge bg-primary">5%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card bg-light mb-3">
                                                <div class="card-body">
                                                    <label class="form-label fw-bold">Sikap (Etika, Kesopanan, Kesantunan, Kerapian, Kedisiplinan)</label>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-3" style="height: 25px;">
                                                            <div class="progress-bar bg-{{ $grade->nilai_sikap_pembekalan >= 80 ? 'success' : ($grade->nilai_sikap_pembekalan >= 60 ? 'warning' : 'danger') }}" 
                                                                 style="width: {{ $grade->nilai_sikap_pembekalan }}%">
                                                                {{ $grade->nilai_sikap_pembekalan }}
                                                            </div>
                                                        </div>
                                                        <span class="badge bg-primary">5%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pelaksanaan (60%) -->
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-tasks text-white"></i>
                                        </div>
                                        <h6 class="text-success mb-0">2. Pelaksanaan (60%)</h6>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card bg-light mb-3">
                                                <div class="card-body">
                                                    <label class="form-label fw-bold">Kehadiran di Lokasi KKN</label>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-3" style="height: 25px;">
                                                            <div class="progress-bar bg-{{ $grade->nilai_kehadiran_lokasi >= 80 ? 'success' : ($grade->nilai_kehadiran_lokasi >= 60 ? 'warning' : 'danger') }}" 
                                                                 style="width: {{ $grade->nilai_kehadiran_lokasi }}%">
                                                                {{ $grade->nilai_kehadiran_lokasi }}
                                                            </div>
                                                        </div>
                                                        <span class="badge bg-success">5%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card bg-light mb-3">
                                                <div class="card-body">
                                                    <label class="form-label fw-bold">Sikap (Etika, Kesopanan, Kesantunan, Kerapian, Kedisiplinan)</label>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress flex-grow-1 me-3" style="height: 25px;">
                                                            <div class="progress-bar bg-{{ $grade->nilai_sikap_pelaksanaan >= 80 ? 'success' : ($grade->nilai_sikap_pelaksanaan >= 60 ? 'warning' : 'danger') }}" 
                                                                 style="width: {{ $grade->nilai_sikap_pelaksanaan }}%">
                                                                {{ $grade->nilai_sikap_pelaksanaan }}
                                                            </div>
                                                        </div>
                                                        <span class="badge bg-success">5%</span>
                                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                            <div class="card bg-light mb-3">
                                                <div class="card-body">
                                                    <label class="form-label fw-bold">Keterlibatan dalam Kegiatan Kemasyarakatan</label>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress flex-grow-1 me-3" style="height: 25px;">
                                                            <div class="progress-bar bg-{{ $grade->nilai_keterlibatan_kegiatan >= 80 ? 'success' : ($grade->nilai_keterlibatan_kegiatan >= 60 ? 'warning' : 'danger') }}" 
                                                                 style="width: {{ $grade->nilai_keterlibatan_kegiatan }}%">
                                                                {{ $grade->nilai_keterlibatan_kegiatan }}
                                                            </div>
                                                        </div>
                                                        <span class="badge bg-success">15%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card bg-light mb-3">
                                                <div class="card-body">
                                                    <label class="form-label fw-bold">Relevansi Program Kerja dengan Kondisi Masyarakat dan Tema KKN Tematik</label>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress flex-grow-1 me-3" style="height: 25px;">
                                                            <div class="progress-bar bg-{{ $grade->nilai_relevansi_program >= 80 ? 'success' : ($grade->nilai_relevansi_program >= 60 ? 'warning' : 'danger') }}" 
                                                                 style="width: {{ $grade->nilai_relevansi_program }}%">
                                                                {{ $grade->nilai_relevansi_program }}
                                                            </div>
                                                        </div>
                                                        <span class="badge bg-success">15%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card bg-light mb-3">
                                                <div class="card-body">
                                                    <label class="form-label fw-bold">Keberhasilan Program Kerja dan Produk Teknologi Tepat Guna atau Produk Kewirausahaan</label>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress flex-grow-1 me-3" style="height: 25px;">
                                                            <div class="progress-bar bg-{{ $grade->nilai_keberhasilan_program >= 80 ? 'success' : ($grade->nilai_keberhasilan_program >= 60 ? 'warning' : 'danger') }}" 
                                                                 style="width: {{ $grade->nilai_keberhasilan_program }}%">
                                                                {{ $grade->nilai_keberhasilan_program }}
                                                            </div>
                                                        </div>
                                                        <span class="badge bg-success">20%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Laporan KKN Tematik (30%) -->
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                        <h6 class="text-info mb-0">3. Laporan KKN Tematik (30%)</h6>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card bg-light mb-3">
                                                <div class="card-body">
                                                    <label class="form-label fw-bold">Kesesuaian dengan Sistematika Penyusunan Laporan</label>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress flex-grow-1 me-3" style="height: 25px;">
                                                            <div class="progress-bar bg-{{ $grade->nilai_sistematika_laporan >= 80 ? 'success' : ($grade->nilai_sistematika_laporan >= 60 ? 'warning' : 'danger') }}" 
                                                                 style="width: {{ $grade->nilai_sistematika_laporan }}%">
                                                                {{ $grade->nilai_sistematika_laporan }}
                                                            </div>
                                                        </div>
                                                        <span class="badge bg-info">3%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card bg-light mb-3">
                                                <div class="card-body">
                                                    <label class="form-label fw-bold">Konten Media Sosial</label>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-3" style="height: 25px;">
                                                            <div class="progress-bar bg-{{ $grade->nilai_konten_medsos >= 80 ? 'success' : ($grade->nilai_konten_medsos >= 60 ? 'warning' : 'danger') }}" 
                                                                 style="width: {{ $grade->nilai_konten_medsos }}%">
                                                                {{ $grade->nilai_konten_medsos }}
                                                            </div>
                                                        </div>
                                                        <span class="badge bg-info">7%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card bg-light mb-3">
                                                <div class="card-body">
                                                    <label class="form-label fw-bold">Penggunaan Bahasa yang Baik dan Benar</label>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress flex-grow-1 me-3" style="height: 25px;">
                                                            <div class="progress-bar bg-{{ $grade->nilai_bahasa >= 80 ? 'success' : ($grade->nilai_bahasa >= 60 ? 'warning' : 'danger') }}" 
                                                                 style="width: {{ $grade->nilai_bahasa }}%">
                                                                {{ $grade->nilai_bahasa }}
                                                            </div>
                                                        </div>
                                                        <span class="badge bg-info">2%</span>
                                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                            <div class="card bg-light mb-3">
                                                <div class="card-body">
                                                    <label class="form-label fw-bold">Ketepatan Analisis dan Pembahasan</label>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-3" style="height: 25px;">
                                                            <div class="progress-bar bg-{{ $grade->nilai_analisis >= 80 ? 'success' : ($grade->nilai_analisis >= 60 ? 'warning' : 'danger') }}" 
                                                                 style="width: {{ $grade->nilai_analisis }}%">
                                                                {{ $grade->nilai_analisis }}
                                                            </div>
                                                        </div>
                                                        <span class="badge bg-info">3%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card bg-light mb-3">
                                                <div class="card-body">
                                                    <label class="form-label fw-bold">Ketepatan Waktu Pengumpulan</label>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress flex-grow-1 me-3" style="height: 25px;">
                                                            <div class="progress-bar bg-{{ $grade->nilai_ketepatan_waktu >= 80 ? 'success' : ($grade->nilai_ketepatan_waktu >= 60 ? 'warning' : 'danger') }}" 
                                                                 style="width: {{ $grade->nilai_ketepatan_waktu }}%">
                                                                {{ $grade->nilai_ketepatan_waktu }}
                                                            </div>
                                                        </div>
                                                        <span class="badge bg-info">5%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card bg-light mb-3">
                                                <div class="card-body">
                                                    <label class="form-label fw-bold">Produk Teknologi Tepat Guna atau Produk Kewirausahaan</label>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress flex-grow-1 me-3" style="height: 25px;">
                                                            <div class="progress-bar bg-{{ $grade->nilai_produk_teknologi >= 80 ? 'success' : ($grade->nilai_produk_teknologi >= 60 ? 'warning' : 'danger') }}" 
                                                                 style="width: {{ $grade->nilai_produk_teknologi }}%">
                                                                {{ $grade->nilai_produk_teknologi }}
                                                            </div>
                                                        </div>
                                                        <span class="badge bg-info">10%</span>
                                                    </div>
                                                </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($grade->catatan)
                        <div class="mt-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title text-warning mb-3">
                                                <i class="fas fa-sticky-note me-2"></i>Catatan
                                            </h6>
                                            <p class="mb-0">{{ $grade->catatan }}</p>
                    </div>
                </div>
            </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rumus Nilai Akhir -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                    <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-calculator me-2"></i>Rumus Nilai Akhir
                                </h5>
                    </div>
                    <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card bg-primary text-white">
                                            <div class="card-body">
                                                <h6 class="card-title">Tahap Pembekalan (10%)</h6>
                                                <ul class="list-unstyled mb-0">
                                                    <li>• Kehadiran Pembekalan (5%)</li>
                                                    <li>• Sikap Pembekalan (5%)</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-success text-white">
                                            <div class="card-body">
                                                <h6 class="card-title">Pelaksanaan (60%)</h6>
                                                <ul class="list-unstyled mb-0">
                                                    <li>• Kehadiran Lokasi (5%)</li>
                                                    <li>• Sikap Pelaksanaan (5%)</li>
                                                    <li>• Keterlibatan Kegiatan (15%)</li>
                                                    <li>• Relevansi Program (15%)</li>
                                                    <li>• Keberhasilan Program (20%)</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-info text-white">
                                            <div class="card-body">
                                                <h6 class="card-title">Laporan (30%)</h6>
                                                <ul class="list-unstyled mb-0">
                                                    <li>• Sistematika (3%)</li>
                                                    <li>• Konten Medsos (7%)</li>
                                                    <li>• Bahasa (2%)</li>
                                                    <li>• Analisis (3%)</li>
                                                    <li>• Ketepatan Waktu (5%)</li>
                                                    <li>• Produk Teknologi (10%)</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .nav-tabs .nav-link {
            border: none;
            border-radius: 0;
            transition: all 0.3s ease;
            padding: 12px 24px;
            font-size: 16px;
        }
        
        .nav-tabs .nav-link:hover {
            background-color: #e9ecef;
            color: #495057 !important;
        }
        
        .nav-tabs .nav-link.active {
            color: #0d6efd !important;
            background-color: #ffffff !important;
            border-bottom: 3px solid #0d6efd !important;
            font-weight: bold;
        }
        
        .nav-tabs .nav-link:not(.active) {
            color: #6c757d !important;
            background-color: #f8f9fa !important;
            border-bottom: 3px solid transparent !important;
        }
        
        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
            background-color: #f8f9fa;
            border-radius: 8px 8px 0 0;
            padding: 8px 8px 0 8px;
        }
        
        .tab-content {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 8px 8px;
            padding: 20px;
        }
    </style>
    @endpush
</x-app-layout> 
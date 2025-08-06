<x-guest-layout>
    <style>
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .hero {
                padding: 60px 0;
            }
            
            .py-5 {
                padding: 2rem 1rem;
            }
            
            .container {
                padding: 0 10px;
            }
            
            /* Text adjustments */
            h1 {
                font-size: 2rem;
            }
            
            h2.fw-bold {
                font-size: 1.5rem;
            }
            
            h3.fw-bold {
                font-size: 1.3rem;
            }
            
            h5.card-title {
                font-size: 1.1rem;
            }
            
            .lead {
                font-size: 1rem;
            }
            
            /* Card adjustments */
            .card {
                margin-bottom: 15px;
            }
            
            .card-body {
                padding: 15px;
            }
            
            .p-4.p-lg-5 {
                padding: 15px !important;
            }
            
            /* Button adjustments */
            .btn {
                font-size: 14px;
                padding: 8px 16px;
            }
            
            .btn-lg {
                font-size: 16px;
                padding: 10px 20px;
            }
            
            /* Grid adjustments */
            .row .col-md-6 {
                margin-bottom: 15px;
            }
            
            /* Icon adjustments */
            .fas {
                font-size: 12px;
            }
            
            /* Timeline adjustments */
            .timeline .card {
                margin-bottom: 10px;
            }
            
            .timeline .card-body {
                padding: 12px;
            }
        }
        
        /* Extra small devices */
        @media (max-width: 576px) {
            .hero {
                padding: 40px 0;
            }
            
            .py-5 {
                padding: 1.5rem 0.5rem;
            }
            
            .container {
                padding: 0 5px;
            }
            
            h1 {
                font-size: 1.8rem;
            }
            
            h2.fw-bold {
                font-size: 1.3rem;
            }
            
            h3.fw-bold {
                font-size: 1.1rem;
            }
            
            h5.card-title {
                font-size: 1rem;
            }
            
            .lead {
                font-size: 0.9rem;
            }
            
            .card-body {
                padding: 10px;
            }
            
            .p-4.p-lg-5 {
                padding: 10px !important;
            }
            
            .btn {
                font-size: 13px;
                padding: 6px 12px;
            }
            
            .btn-lg {
                font-size: 14px;
                padding: 8px 16px;
            }
            
            .fas {
                font-size: 11px;
            }
            
            .timeline .card-body {
                padding: 10px;
            }
            
            /* Stack elements vertically */
            .row .col-md-6 {
                margin-bottom: 10px;
            }
        }
    </style>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1>Tentang KKN</h1>
                    <p class="lead">Program Kuliah Kerja Nyata (KKN) Universitas Sugeng Hartono</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-body p-4 p-lg-5">
                            <h2 class="fw-bold mb-4">Apa itu KKN?</h2>
                            <p>Kuliah Kerja Nyata (KKN) adalah bentuk kegiatan pengabdian kepada masyarakat oleh mahasiswa dengan pendekatan lintas keilmuan dan sektoral pada waktu dan daerah tertentu di Indonesia.</p>
                            
                            <h3 class="fw-bold mt-5 mb-4">Tujuan KKN</h3>
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-primary me-2"></i>
                                    Meningkatkan empati dan kepedulian mahasiswa
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-primary me-2"></i>
                                    Melaksanakan terapan IPTEK secara teamwork dan interdisipliner
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-primary me-2"></i>
                                    Menanamkan nilai kepribadian, nasionalisme, dan jiwa kepemimpinan
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-primary me-2"></i>
                                    Menanamkan jiwa peneliti, eksploratif, dan analisis
                                </li>
                            </ul>

                            <h3 class="fw-bold mt-5 mb-4">Manfaat KKN</h3>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title">Bagi Mahasiswa</h5>
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-2">
                                                    <i class="fas fa-arrow-right text-primary me-2"></i>
                                                    Meningkatkan soft skill
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-arrow-right text-primary me-2"></i>
                                                    Mengembangkan jiwa kepemimpinan
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-arrow-right text-primary me-2"></i>
                                                    Menerapkan ilmu di masyarakat
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title">Bagi Masyarakat</h5>
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-2">
                                                    <i class="fas fa-arrow-right text-primary me-2"></i>
                                                    Memperoleh bantuan pemikiran dan tenaga
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-arrow-right text-primary me-2"></i>
                                                    Cara baru menyelesaikan masalah
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-arrow-right text-primary me-2"></i>
                                                    Terbentuknya kader pembangunan
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h3 class="fw-bold mt-5 mb-4">Pelaksanaan KKN</h3>
                            <p>KKN dilaksanakan selama 2 bulan dengan tahapan sebagai berikut:</p>
                            <div class="timeline">
                                <div class="row g-0">
                                    <div class="col-md-12 mb-4">
                                        <div class="card border-start border-4 border-primary">
                                            <div class="card-body">
                                                <h5 class="card-title">1. Persiapan</h5>
                                                <p class="card-text">Pembekalan, pembentukan kelompok, dan penentuan lokasi KKN.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <div class="card border-start border-4 border-primary">
                                            <div class="card-body">
                                                <h5 class="card-title">2. Pelaksanaan</h5>
                                                <p class="card-text">Observasi, program kerja, dan implementasi di masyarakat.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <div class="card border-start border-4 border-primary">
                                            <div class="card-body">
                                                <h5 class="card-title">3. Pelaporan</h5>
                                                <p class="card-text">Penyusunan laporan dan evaluasi program.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-5">
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Login Sistem KKN</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout> 
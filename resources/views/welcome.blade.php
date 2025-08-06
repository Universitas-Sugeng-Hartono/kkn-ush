<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/ush.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/ush.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ asset("assets/fonts/quicksand.css") }}" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap 5 CSS -->
    <link href="{{ asset("assets/css/bootstrap.min.css") }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset("assets/css/fontawesome.min.css") }}" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="{{ asset("assets/css/leaflet.min.css") }}">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0B1F3A;
            --accent-color: #f2b70d;
            --primary-hover: #163057;
            --accent-hover: #d9a404;
        }

        body {
            font-family: 'Quicksand', sans-serif;
        }

        .navbar-custom {
            background-color: var(--primary-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-custom .nav-link {
            color: rgba(255,255,255,0.8) !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .navbar-custom .nav-link:hover {
            color: var(--accent-color) !important;
        }

        .btn-accent {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: var(--primary-color);
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-accent:hover {
            background-color: var(--accent-hover);
            border-color: var(--accent-hover);
            color: var(--primary-color);
            transform: translateY(-1px);
        }

        /* Ensure login button is always visible */
        .btn-accent {
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* Ensure navbar menu is always visible */
        .navbar-nav {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        .navbar-nav .nav-item {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        .navbar-nav .nav-link {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* Ensure navbar collapse works properly */
        .navbar-collapse {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        @media (max-width: 991.98px) {
            .navbar-collapse:not(.show) {
                display: none !important;
            }
            
            .navbar-collapse.show {
                display: flex !important;
            }
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .hero-section {
            background: var(--primary-color);
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to right bottom, transparent 49%, white 50%);
        }

        .feature-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            background: white;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .feature-card i {
            color: var(--accent-color);
        }

        .carousel {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .carousel-caption {
            background: linear-gradient(to top, rgba(11,31,58,0.9), transparent);
            border-radius: 0 0 15px 15px;
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 50px;
            height: 50px;
            background-color: var(--primary-color);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.8;
        }

        .carousel-control-prev {
            left: 20px;
        }

        .carousel-control-next {
            right: 20px;
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            background-color: var(--accent-color);
            opacity: 1;
        }

        .carousel-indicators {
            margin-bottom: 2rem;
        }

        .carousel-indicators [data-bs-target] {
            background-color: var(--accent-color);
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin: 0 6px;
        }

        .news-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .news-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .news-card .card-footer {
            background-color: white;
            border-top: 1px solid rgba(0,0,0,0.05);
        }

        #map {
            height: 500px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .kelompok-list {
            max-height: 500px;
            overflow-y: auto;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .kelompok-item {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .kelompok-item:hover,
        .kelompok-item.active {
            background-color: #f8f9fa;
            border-left: 4px solid var(--accent-color);
        }

        .section-title {
            position: relative;
            margin-bottom: 3rem;
            padding-bottom: 1rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(242,183,13,0.25);
        }

        .document-btn {
            transition: all 0.3s ease;
        }

        .document-btn:hover {
            transform: translateY(-2px);
        }

        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background-color: #f2b70d;
            color: #0B1F3A;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            z-index: 9999;
        }

        .back-to-top:hover {
            background-color: #0B1F3A;
            color: #f2b70d;
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
        }

        .back-to-top.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .back-to-top {
                bottom: 20px;
                right: 20px;
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 0;
            }
            
            .hero-section::after {
                height: 60px;
            }
            
            /* Text adjustments */
            h1 {
                font-size: 2rem;
            }
            
            h2 {
                font-size: 1.5rem;
            }
            
            h3 {
                font-size: 1.3rem;
            }
            
            .display-4 {
                font-size: 2.5rem;
            }
            
            .lead {
                font-size: 1rem;
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
            
            /* Carousel adjustments */
            .carousel-control-prev,
            .carousel-control-next {
                width: 40px;
                height: 40px;
            }
            
            .carousel-control-prev {
                left: 10px;
            }
            
            .carousel-control-next {
                right: 10px;
            }
            
            /* Map adjustments */
            #map {
                height: 300px;
            }
            
            .kelompok-list {
                max-height: 300px;
            }
            
            /* Card adjustments */
            .feature-card,
            .news-card {
                margin-bottom: 15px;
            }
            
            /* Grid adjustments */
            .row .col-md-4,
            .row .col-md-6 {
                margin-bottom: 15px;
            }
            
            /* Navbar adjustments */
            .navbar-brand {
                font-size: 1.2rem;
            }
            
            .navbar-nav .nav-link {
                font-size: 14px;
                padding: 8px 12px;
            }
        }
        
        /* Extra small devices */
        @media (max-width: 576px) {
            .hero-section {
                padding: 40px 0;
            }
            
            .hero-section::after {
                height: 40px;
            }
            
            h1 {
                font-size: 1.8rem;
            }
            
            h2 {
                font-size: 1.3rem;
            }
            
            h3 {
                font-size: 1.1rem;
            }
            
            .display-4 {
                font-size: 2rem;
            }
            
            .lead {
                font-size: 0.9rem;
            }
            
            .btn {
                font-size: 13px;
                padding: 6px 12px;
            }
            
            .btn-lg {
                font-size: 14px;
                padding: 8px 16px;
            }
            
            .carousel-control-prev,
            .carousel-control-next {
                width: 35px;
                height: 35px;
            }
            
            .carousel-control-prev {
                left: 5px;
            }
            
            .carousel-control-next {
                right: 5px;
            }
            
            #map {
                height: 250px;
            }
            
            .kelompok-list {
                max-height: 250px;
            }
            
            .navbar-brand {
                font-size: 1rem;
            }
            
            .navbar-nav .nav-link {
                font-size: 13px;
                padding: 6px 8px;
            }
            
            /* Stack elements vertically */
            .d-flex {
                flex-direction: column;
                gap: 10px;
            }
            
            .d-flex > * {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}" style="padding: 5px;background-color: white;border-radius: 10px;">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 40px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang KKN</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#location">Lokasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#news">Berita</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#complaint">Pengaduan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#documents">Dokumen</a>
                    </li>
                </ul>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-accent">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-accent">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 mb-4">Kuliah Kerja Nyata</h1>
            <h2 class="h3 mb-4">Universitas Sugeng Hartono</h2>
            <p class="lead mb-4">Mengabdi untuk Negeri, Berkarya untuk Sesama</p>
            <a href="#about" class="btn btn-accent btn-lg">Pelajari Lebih Lanjut</a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="mb-4">Tentang Program KKN</h2>
                    <p class="lead">Program Kuliah Kerja Nyata (KKN) merupakan bentuk pengabdian mahasiswa kepada masyarakat yang bersifat interdisipliner, institusional, dan kemitraan.</p>
                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <div class="feature-card card p-3" style="height: 100%; min-height: 200px;">
                                <i class="fas fa-users fa-2x text-primary mb-3"></i>
                                <h5 class="mb-3">Pemberdayaan Masyarakat</h5>
                                <p class="mb-0" style="flex-grow: 1;">Mengembangkan potensi masyarakat melalui program-program inovatif</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="feature-card card p-3" style="height: 100%; min-height: 200px;">
                                <i class="fas fa-graduation-cap fa-2x text-primary mb-3"></i>
                                <h5 class="mb-3">Pengembangan Mahasiswa</h5>
                                <p class="mb-0" style="flex-grow: 1;">Meningkatkan soft skill dan pengalaman lapangan mahasiswa</p>
                            </div>
                        </div>
                    </div>

                    <style>
                        .feature-card {
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            text-align: center;
                        }
                        
                        .feature-card h5 {
                            font-size: 1.25rem;
                            font-weight: 600;
                            color: var(--primary-color);
                        }
                        
                        .feature-card p {
                            color: #6c757d;
                            line-height: 1.6;
                        }
                    </style>
                </div>
                <div class="col-md-6">
                    @if($galeri->isNotEmpty())
                        <div id="kknCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                @foreach($galeri as $key => $item)
                                    <button type="button" data-bs-target="#kknCarousel" data-bs-slide-to="{{ $key }}" 
                                        class="{{ $key == 0 ? 'active' : '' }}" aria-current="true" 
                                        aria-label="Slide {{ $key + 1 }}"></button>
                                @endforeach
                            </div>
                            <div class="carousel-inner rounded">
                                @foreach($galeri as $key => $item)
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <img src="{{ $item->gambar_url }}" class="d-block w-100" alt="{{ $item->judul }}" 
                                            style="height: 400px; object-fit: cover;">
                                        <div class="carousel-caption d-none d-md-block" 
                                            style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); 
                                                   bottom: 0; left: 0; right: 0; padding: 2rem;">
                                            <h5 class="fw-bold">{{ $item->judul }}</h5>
                                            @if($item->deskripsi)
                                                <p>{{ $item->deskripsi }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#kknCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#kknCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    @else
                        <div class="bg-light p-4 rounded">
                            <h4>Kegiatan KKN</h4>
                            <p>Program KKN USH bertujuan untuk mengembangkan kemampuan mahasiswa dalam pengabdian masyarakat.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Location Section -->
    <section id="location" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Lokasi KKN</h2>
            <div class="row">
                <div class="col-md-8">
                    <div id="map"></div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header text-white" style="background-color: #f2b70d;">
                            <h5 class="card-title mb-0">Daftar Kelompok KKN</h5>
                        </div>

                        <div class="card-body p-0">
                            <div class="kelompok-list list-group list-group-flush">
                                @foreach($kelompokData as $index => $kelompok)
                                    <a href="#" class="list-group-item list-group-item-action kelompok-item" 
                                       data-index="{{ $index }}"
                                       data-lat="{{ $kelompok['latitude'] }}"
                                       data-lng="{{ $kelompok['longitude'] }}">
                                        <h6 class="mb-1">{{ $kelompok['nama'] }}</h6>
                                        <p class="mb-1 text-muted small">{{ $kelompok['lokasi'] }}</p>
                                        <small>
                                            <i class="fas fa-users me-1"></i> {{ $kelompok['jumlah_mahasiswa'] }} Mahasiswa
                                        </small>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section id="news" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Berita Terkini</h2>
            <div class="row">
                @forelse($berita as $item)
                <div class="col-md-4 mb-4">
                    <div class="news-card card h-100">
                        <div class="position-relative">
                            <img src="{{ $item->thumbnail_url }}" 
                                class="card-img-top" 
                                alt="{{ $item->judul }}" 
                                style="height: 200px; object-fit: cover;">
                            <div class="position-absolute bottom-0 start-0 p-3" 
                                style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); width: 100%;">
                                <small class="text-white">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ $item->published_at->format('d M Y') }}
                                </small>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title" style="min-height: 50px;">{{ $item->judul }}</h5>
                            <p class="card-text text-muted" style="min-height: 75px;">
                                {{ Str::limit(strip_tags($item->konten), 100) }}
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <a href="{{ route('berita.public.show', $item) }}" 
                                class="btn w-100" 
                                style="background-color: #f2b70d; color: #0B1F3A; font-weight: 100;">
                                <i class="fas fa-arrow-right me-2"></i>Baca Selengkapnya
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Belum ada berita terbaru.
                    </div>
                </div>
                @endforelse
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('berita.public.index') }}" class="btn btn-lg" style="border: 2px solid #0B1F3A; color: #0B1F3A;">
                    <i class="fas fa-newspaper me-2"></i>Lihat Semua Berita
                </a>
            </div>
        </div>
    </section>

    <!-- Complaint Section -->
    <section id="complaint" class="py-5" style="background-color: #dce1e6;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center mb-5">
                    <h2 class="mb-4">Form Pengaduan Masyarakat</h2>
                    <p class="text-muted">Sampaikan pengaduan Anda terkait pelaksanaan KKN Universitas Sugeng Hartono</p>
                </div>
            </div>

            <div class="row justify-content-center mb-5">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Cek Status Pengaduan</h5>
                            <form action="{{ route('pengaduan.check') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control" 
                                           name="nomor_pengaduan" 
                                           placeholder="Masukkan nomor pengaduan" 
                                           required>
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search me-2"></i>Cek Status
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            @if(session('nomor_pengaduan'))
                                <hr>
                                <p class="mb-0">Nomor Pengaduan Anda: <strong>{{ session('nomor_pengaduan') }}</strong></p>
                                <small class="text-muted">Simpan nomor pengaduan ini untuk mengecek status pengaduan Anda</small>
                            @endif
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(isset($pengaduan))
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Detail Pengaduan</h5>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th width="200">Nomor Pengaduan</th>
                                            <td>{{ $pengaduan->nomor_pengaduan }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal</th>
                                            <td>{{ $pengaduan->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <span class="badge {{ $pengaduan->getStatusBadgeClass() }}">
                                                    {{ $pengaduan->getStatusOptions()[$pengaduan->status] }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Subjek</th>
                                            <td>{{ $pengaduan->subjek }}</td>
                                        </tr>
                                        @if($pengaduan->tanggapan)
                                            <tr>
                                                <th>Tanggapan Admin</th>
                                                <td>{{ $pengaduan->tanggapan }}</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="nama_pelapor" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama_pelapor" name="nama_pelapor" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email_pelapor" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email_pelapor" name="email_pelapor" required>
                                </div>
                                <div class="mb-3">
                                    <label for="no_hp_pelapor" class="form-label">Nomor HP</label>
                                    <input type="tel" class="form-control" id="no_hp_pelapor" name="no_hp_pelapor" required>
                                </div>
                                <div class="mb-3">
                                    <label for="subjek" class="form-label">Subjek Pengaduan</label>
                                    <input type="text" class="form-control" id="subjek" name="subjek" required>
                                </div>
                                <div class="mb-3">
                                    <label for="lokasi_id" class="form-label">Lokasi KKN</label>
                                    <select class="form-select" id="lokasi_id" name="lokasi_id" required>
                                        <option value="">Pilih Lokasi</option>
                                        @foreach($lokasi as $lok)
                                            <option value="{{ $lok->id }}">{{ $lok->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="isi_pengaduan" class="form-label">Isi Pengaduan</label>
                                    <textarea class="form-control" id="isi_pengaduan" name="isi_pengaduan" rows="5" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="bukti_pendukung" class="form-label">Bukti Pendukung (Opsional)</label>
                                    <input type="file" class="form-control" id="bukti_pendukung" name="bukti_pendukung">
                                    <div class="form-text">Format yang didukung: JPG, PNG, PDF (Max. 2MB)</div>
                                </div>
                                <button type="submit" class="btn btn-primary">Kirim Pengaduan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Documents Section -->
    <section id="documents" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Dokumen & Panduan</h2>
            <div class="row">
                <!-- Card Container -->
                <div class="d-flex flex-wrap justify-content-center">
                    <!-- Peraturan KKN -->
                    <div class="col-md-4 mb-4 px-3">
                        <div class="card shadow-sm h-100" style="min-height: 360px; border: none; background-color: #f8f9fa;">
                            <div class="card-body text-center d-flex flex-column">
                                <div class="mb-4">
                                    <i class="fas fa-file-alt fa-3x" style="color: #0B1F3A;"></i>
                                </div>
                                <h4 class="card-title mb-3" style="color: #0B1F3A;">Peraturan KKN</h4>
                                <p class="card-text text-muted flex-grow-1" style="min-height: 48px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                    Panduan lengkap mengenai aturan dan ketentuan KKN
                                </p>
                                @php
                                    $peraturanCount = App\Models\Dokumen::where('jenis', 'peraturan')->count();
                                @endphp
                                <div class="mb-3">
                                    <span class="badge" style="background-color: #0B1F3A;">{{ $peraturanCount }} Dokumen</span>
                                </div>
                                <a href="{{ route('dokumen.kategori', 'peraturan') }}" class="btn mt-auto w-100 document-btn" style="background-color: #0B1F3A; color: white;">
                                    <i class="fas fa-download me-2"></i>Download
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Panduan KKN -->
                    <div class="col-md-4 mb-4 px-3">
                        <div class="card shadow-sm h-100" style="min-height: 360px; border: none; background-color: #f8f9fa;">
                            <div class="card-body text-center d-flex flex-column">
                                <div class="mb-4">
                                    <i class="fas fa-book fa-3x" style="color: #F2B705;"></i>
                                </div>
                                <h4 class="card-title mb-3" style="color: #0B1F3A;">Panduan KKN</h4>
                                <p class="card-text text-muted flex-grow-1" style="min-height: 48px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                    Petunjuk teknis pelaksanaan program KKN
                                </p>
                                @php
                                    $panduanCount = App\Models\Dokumen::where('jenis', 'panduan')->count();
                                @endphp
                                <div class="mb-3">
                                    <span class="badge" style="background-color: #F2B705; color: #0B1F3A;">{{ $panduanCount }} Dokumen</span>
                                </div>
                                <a href="{{ route('dokumen.kategori', 'panduan') }}" class="btn mt-auto w-100 document-btn" style="background-color: #F2B705; color: #0B1F3A;">
                                    <i class="fas fa-download me-2"></i>Download
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Logo Universitas -->
                    <div class="col-md-4 mb-4 px-3">
                        <div class="card shadow-sm h-100" style="min-height: 360px; border: none; background-color: #f8f9fa;">
                            <div class="card-body text-center d-flex flex-column">
                                <div class="mb-4">
                                    <i class="fas fa-image fa-3x" style="color: #0B1F3A;"></i>
                                </div>
                                <h4 class="card-title mb-3" style="color: #0B1F3A;">Logo Universitas</h4>
                                <p class="card-text text-muted flex-grow-1" style="min-height: 48px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                    Download logo dalam berbagai format
                                </p>
                                @php
                                    $logoCount = App\Models\Dokumen::where('jenis', 'logo')->count();
                                @endphp
                                <div class="mb-3">
                                    <span class="badge" style="background-color: #0B1F3A;">{{ $logoCount }} Dokumen</span>
                                </div>
                                <a href="{{ route('dokumen.kategori', 'logo') }}" class="btn mt-auto w-100 document-btn" style="background-color: #0B1F3A; color: white;">
                                    <i class="fas fa-download me-2"></i>Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lihat Semua Dokumen -->
            <div class="text-center mt-4">
                <a href="{{ route('dokumen.kategori', 'all') }}" class="btn btn-lg" style="border: 2px solid #0B1F3A; color: #0B1F3A;">
                    <i class="fas fa-folder-open me-2"></i>Lihat Semua Dokumen
                </a>
            </div>
        </div>

        <style>
            /* Card hover effect */
            .card {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }
            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 .5rem 1rem rgba(0,0,0,.08)!important;
            }
            
            /* Ensure all cards have same width */
            @media (min-width: 768px) {
                .col-md-4 {
                    flex: 0 0 33.333333%;
                    max-width: 33.333333%;
                }
            }
            
            /* Ensure consistent spacing */
            .card-body {
                padding: 2rem;
            }
            
            /* Consistent icon sizes */
            .fa-3x {
                font-size: 3em;
                height: 1em;
                line-height: 1em;
                display: inline-block;
            }

            /* Document button hover effect */
            .document-btn {
                transition: all 0.3s ease;
            }
            .document-btn:hover {
                opacity: 0.9;
                transform: translateY(-2px);
            }

            /* Badge styles */
            .badge {
                padding: 0.5em 1em;
                font-weight: 500;
            }

            /* Soft background for section */
            #documents {
                background-color: #ffffff;
            }
        </style>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>Tentang USH</h5>
                    <p>Universitas Sugeng Hartono berkomitmen untuk menghasilkan lulusan yang berkualitas dan bermanfaat bagi masyarakat dengan motto: Adaptif, Kreatif, Mandiri.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Link Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="#about" class="text-white">Tentang KKN</a></li>
                        <li><a href="#location" class="text-white">Lokasi</a></li>
                        <li><a href="#news" class="text-white">Berita</a></li>
                        <li><a href="#complaint" class="text-white">Pengaduan</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Kontak</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i> Jl. Ir. Soekarno No. 69 Solo Baru, Sukoharjo, Jawa Tengah, Indonesia</li>
                        <li><i class="fas fa-phone me-2"></i> 0811-2674-670</li>
                        <li><i class="fas fa-envelope me-2"></i> ush@sugenghartono.ac.id</li>
                        <li><i class="fas fa-clock me-2"></i> Senin-Jumat: 08.00 - 16.30 WIB</li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Universitas Sugeng Hartono. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#" id="backToTop" class="back-to-top" aria-label="Back to Top" role="button">
        <i class="fas fa-chevron-up"></i>
    </a>

    <!-- Scripts -->
    <script src="{{ asset("assets/js/jquery.min.js") }}"></script>
    <script src="{{ asset("assets/js/bootstrap.bundle.min.js") }}"></script>
    <script src="{{ asset("assets/js/leaflet.min.js") }}"></script>
    <script src="{{ asset("assets/js/sweetalert2.min.js") }}"></script>

    <script>
        $(document).ready(function() {
            // Back to Top functionality
            var btn = $('#backToTop');
            
            $(window).scroll(function() {
                if ($(window).scrollTop() > 300) {
                    btn.addClass('show');
                } else {
                    btn.removeClass('show');
                }
            });
            
            btn.on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, {
                    duration: 800,
                    easing: 'swing'
                });
                return false;
            });

            // Initialize map
            var map = L.map('map').setView([-7.7956, 110.3695], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            // Data kelompok
            var kelompokData = @json($kelompokData);
            var markers = [];

            // Fungsi untuk membuat popup content
            function createPopupContent(kelompok) {
                return `
                    <div class="popup-content">
                        <h6 class="mb-2">${kelompok.nama}</h6>
                        <p class="mb-2"><strong>Lokasi:</strong> ${kelompok.lokasi}</p>
                        <p class="mb-2"><strong>Alamat:</strong> ${kelompok.alamat}</p>
                        <p class="mb-2"><strong>DPL:</strong> ${kelompok.dpl}</p>
                        <p class="mb-0"><strong>Jumlah Mahasiswa:</strong> ${kelompok.jumlah_mahasiswa}</p>
                    </div>
                `;
            }

            // Tambahkan marker untuk setiap kelompok
            kelompokData.forEach((kelompok, index) => {
                var marker = L.marker([kelompok.latitude, kelompok.longitude])
                    .bindPopup(createPopupContent(kelompok));
                
                markers.push(marker);
                marker.addTo(map);
            });

            // Event listener untuk item list kelompok
            document.querySelectorAll('.kelompok-item').forEach(item => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    const index = item.dataset.index;
                    const lat = parseFloat(item.dataset.lat);
                    const lng = parseFloat(item.dataset.lng);

                    // Zoom ke lokasi
                    map.setView([lat, lng], 15);
                    
                    // Buka popup
                    markers[index].openPopup();

                    // Tambahkan kelas active
                    document.querySelectorAll('.kelompok-item').forEach(el => {
                        el.classList.remove('active');
                    });
                    item.classList.add('active');
                });
            });

            // Smooth scroll
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
</body>
</html>


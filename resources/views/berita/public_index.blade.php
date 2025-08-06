<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Berita Terkini - {{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/ush.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/ush.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ asset("assets/fonts/quicksand.css") }}" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="{{ asset("assets/css/bootstrap.min.css") }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset("assets/css/fontawesome.min.css") }}" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0B1F3A;
            --accent-color: #F2B705;
            --soft-blue: #E8F0F8;
            --soft-yellow: #FFF8E7;
            --soft-green: #E8F5E9;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand, .display-4 {
            font-family: 'Quicksand', sans-serif;
            font-weight: 600;
        }

        .lead {
            font-family: 'Quicksand', sans-serif;
            font-weight: 400;
        }

        .card-title {
            font-family: 'Quicksand', sans-serif;
            font-weight: 500;
        }

        .btn {
            font-family: 'Quicksand', sans-serif;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .breadcrumb {
            font-family: 'Quicksand', sans-serif;
            font-weight: 500;
        }

        .badge {
            font-family: 'Quicksand', sans-serif;
            font-weight: 600;
            padding: 0.5em 1em;
        }

        footer {
            font-family: 'Quicksand', sans-serif;
        }

        footer h5 {
            font-weight: 600;
            margin-bottom: 1.2rem;
        }

        footer a {
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .navbar-custom {
            background-color: var(--primary-color);
        }

        .btn-accent {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: var(--primary-color);
        }

        .btn-accent:hover {
            background-color: #d9a504;
            border-color: #d9a504;
            color: var(--primary-color);
        }

        .page-header {
            background-color: var(--primary-color);
            color: white;
            padding: 60px 0;
        }

        .document-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .document-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            background-color: white;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-footer {
            background-color: transparent;
            border-top: 1px solid rgba(0,0,0,0.05);
            padding: 1rem 1.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #0d2847;
            border-color: #0d2847;
        }

        /* Warna soft untuk setiap jenis dokumen */
        .document-peraturan {
            background-color: var(--soft-blue);
        }

        .document-panduan {
            background-color: var(--soft-yellow);
        }

        .document-template, .document-logo {
            background-color: var(--soft-green);
        }

        .fa-file-pdf, .fa-file-word, .fa-file-excel {
            color: var(--primary-color);
            opacity: 0.8;
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
                        <a class="nav-link" href="{{ route('berita.public.index') }}">Berita</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dokumen.kategori', 'all') }}">Dokumen</a>
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

    <!-- Page Header -->
    <header class="page-header">
        <div class="container">
            <h1>Berita Terkini</h1>
            <p>Informasi terbaru seputar kegiatan KKN Universitas Sugeng Hartono</p>
        </div>
    </header>

    <!-- News List -->
    <main class="py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Berita</li>
                        </ol>
                    </nav>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h1 class="fw-bold">Semua Berita</h1>
                        <span class="badge bg-primary fs-6">{{ $berita->total() }} Berita</span>
                    </div>

                    <p class="text-muted">
                        Semua berita terkait KKN
                    </p>
                </div>
            </div>

            <div class="row">
                @forelse($berita as $item)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="position-relative">
                            <img src="{{ $item->thumbnail_url }}" 
                                class="card-img-top" 
                                alt="{{ $item->judul }}" 
                                style="height: 200px; object-fit: cover;">
                            <div class="position-absolute bottom-0 start-0 p-3" 
                                style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); width: 100%;">
                                <small class="text-white">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ $item->published_at ? $item->published_at->format('d/m/Y') : '-' }}
                                </small>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title mb-1">{{ $item->judul }}</h5>
                            <p class="card-text text-muted">
                                {{ Str::limit(strip_tags($item->konten), 100) }}
                            </p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="{{ route('berita.public.show', $item) }}" 
                               class="btn btn-primary w-100">
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

            <div class="d-flex justify-content-center mt-4">
                {{ $berita->links() }}
            </div>
        </div>
    </main>

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
                        <li><a href="{{ route('home') }}" class="text-white">Beranda</a></li>
                        <li><a href="{{ route('berita.public.index') }}" class="text-white">Berita</a></li>
                        <li><a href="#" class="text-white">Tentang Kami</a></li>
                        <li><a href="#" class="text-white">Kontak</a></li>
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

    <!-- Scripts -->
    <script src="{{ asset("assets/js/jquery.min.js") }}"></script>
    <script src="{{ asset("assets/js/bootstrap.bundle.min.js") }}"></script>
</body>
</html> 
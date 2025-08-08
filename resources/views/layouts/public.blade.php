<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Logbook KKN') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/ush.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/ush.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ asset("assets/fonts/quicksand.css") }}" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="{{ asset("assets/css/bootstrap.min.css") }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset("assets/css/fontawesome.min.css") }}" rel="stylesheet">

    <style>
        :root {
            --primary-color: #0B1F3A;
            --accent-color: #f2b70d;
        }

        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f8f9fa;
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
            background-color: #d9a404;
            border-color: #d9a404;
            color: var(--primary-color);
        }

        .page-header {
            background: var(--primary-color);
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
        }

        .page-header h1 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .page-header p {
            opacity: 0.8;
            margin-bottom: 0;
        }

        .breadcrumb-item a {
            color: var(--accent-color);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: rgba(255,255,255,0.8);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        footer {
            background: var(--primary-color);
            color: white;
            padding: 60px 0 30px;
            margin-top: 60px;
        }

        footer h5 {
            color: var(--accent-color);
            font-weight: 600;
            margin-bottom: 20px;
        }

        footer ul {
            list-style: none;
            padding: 0;
        }

        footer ul li {
            margin-bottom: 10px;
        }

        footer a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        footer a:hover {
            color: var(--accent-color);
        }

        .copyright {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 20px;
            margin-top: 40px;
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
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .back-to-top:hover {
            background-color: #0B1F3A;
            color: #f2b70d;
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
        }

        .back-to-top.show {
            display: flex;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
    </style>

    @stack('styles')
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

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>Tentang USH</h5>
                    <p>Universitas Sugeng Hartono berkomitmen untuk menghasilkan lulusan yang berkualitas dan bermanfaat bagi masyarakat dengan motto: Adaptif, Kreatif, Mandiri.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Link Cepat</h5>
                    <ul>
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('berita.public.index') }}">Berita</a></li>
                        <li><a href="#about">Tentang Kami</a></li>
                        <li><a href="#documents">Dokumen</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Kontak</h5>
                    <ul>
                        <li>
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Jl. Ir. Soekarno No. 69 Solo Baru, Sukoharjo, Jawa Tengah, Indonesia
                        </li>
                        <li>
                            <i class="fas fa-phone me-2"></i>
                            0811-2674-670
                        </li>
                        <li>
                            <i class="fas fa-envelope me-2"></i>
                            ush@sugenghartono.ac.id
                        </li>
                        <li>
                            <i class="fas fa-clock me-2"></i>
                            Senin-Jumat: 08.00 - 16.30 WIB
                        </li>
                    </ul>
                </div>
            </div>
            <div class="copyright text-center">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" class="back-to-top" aria-label="Back to Top">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- Scripts -->
    <script src="{{ asset("assets/js/jquery.min.js") }}"></script>
    <script src="{{ asset("assets/js/bootstrap.bundle.min.js") }}"></script>

    <script>
        // Back to Top functionality
        $(document).ready(function() {
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
                }, 800, 'easeInOutExpo');
            });

            // Add easing function if not available
            if (typeof $.easing.easeInOutExpo != 'function') {
                $.easing.easeInOutExpo = function (x) {
                    return x === 0
                        ? 0
                        : x === 1
                        ? 1
                        : x < 0.5 ?
                            Math.pow(2, 20 * x - 10) / 2
                            : (2 - Math.pow(2, -20 * x + 10)) / 2;
                }
            }
        });
    </script>

    @stack('scripts')
</body>
</html> 
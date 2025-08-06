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
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="{{ asset("assets/css/swiper.min.css") }}" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none;
        }

        .swiper {
            width: 100%;
            height: 400px;
            margin: 20px auto;
        }

        .swiper-slide {
            text-align: center;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: #fff;
            background: rgba(0, 0, 0, 0.5);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            --swiper-navigation-size: 20px;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        .swiper-pagination-bullet {
            background: #fff;
            opacity: 0.5;
        }

        .swiper-pagination-bullet-active {
            opacity: 1;
            background: #fff;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .py-5 {
                padding: 2rem 1rem;
            }
            
            .py-16 {
                padding: 3rem 1rem;
            }
            
            .container {
                padding: 0 10px;
            }
            
            /* Text adjustments */
            h2 {
                font-size: 1.5rem;
            }
            
            h5.card-title {
                font-size: 1.1rem;
            }
            
            .text-3xl {
                font-size: 1.8rem;
            }
            
            .text-lg {
                font-size: 1rem;
            }
            
            /* Card adjustments */
            .card {
                margin-bottom: 15px;
            }
            
            .card-body {
                padding: 15px;
            }
            
            /* Icon adjustments */
            .fas.fa-3x {
                font-size: 2rem !important;
            }
            
            /* Button adjustments */
            .btn {
                font-size: 14px;
                padding: 8px 12px;
            }
            
            .btn-lg {
                font-size: 16px;
                padding: 10px 16px;
            }
            
            /* Swiper adjustments */
            .swiper {
                height: 300px;
                margin: 15px auto;
            }
            
            .swiper-slide img {
                height: 300px !important;
            }
            
            .swiper-button-next,
            .swiper-button-prev {
                width: 35px;
                height: 35px;
                --swiper-navigation-size: 18px;
            }
            
            /* Grid adjustments */
            .row .col-md-4 {
                margin-bottom: 15px;
            }
        }
        
        /* Extra small devices */
        @media (max-width: 576px) {
            .py-5 {
                padding: 1.5rem 0.5rem;
            }
            
            .py-16 {
                padding: 2rem 0.5rem;
            }
            
            .container {
                padding: 0 5px;
            }
            
            h2 {
                font-size: 1.3rem;
            }
            
            h5.card-title {
                font-size: 1rem;
            }
            
            .text-3xl {
                font-size: 1.5rem;
            }
            
            .text-lg {
                font-size: 0.9rem;
            }
            
            .card-body {
                padding: 10px;
            }
            
            .fas.fa-3x {
                font-size: 1.5rem !important;
            }
            
            .btn {
                font-size: 13px;
                padding: 6px 10px;
            }
            
            .btn-lg {
                font-size: 14px;
                padding: 8px 12px;
            }
            
            .swiper {
                height: 250px;
                margin: 10px auto;
            }
            
            .swiper-slide img {
                height: 250px !important;
            }
            
            .swiper-button-next,
            .swiper-button-prev {
                width: 30px;
                height: 30px;
                --swiper-navigation-size: 16px;
            }
            
            /* Stack cards vertically */
            .row .col-md-4 {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Dokumen & Panduan -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Dokumen & Panduan</h2>

            <div class="row g-4">
                <!-- Peraturan KKN -->
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="mb-4">
                                <i class="fas fa-file-alt fa-3x text-primary"></i>
                            </div>
                            <h5 class="card-title">Peraturan KKN</h5>
                            <p class="card-text text-muted">Panduan lengkap mengenai aturan dan ketentuan KKN</p>
                            
                            @php
                                $peraturanCount = App\Models\Dokumen::where('jenis', 'peraturan')->count();
                            @endphp
                            <div class="mb-3">
                                <span class="badge bg-primary">{{ $peraturanCount }} Dokumen</span>
                            </div>
                            
                            <a href="{{ route('dokumen.kategori', 'peraturan') }}" class="btn btn-primary w-100">
                                <i class="fas fa-download me-2"></i>Download
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Panduan KKN -->
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="mb-4">
                                <i class="fas fa-book fa-3x text-info"></i>
                            </div>
                            <h5 class="card-title">Panduan KKN</h5>
                            <p class="card-text text-muted">Petunjuk teknis pelaksanaan program KKN</p>
                            
                            @php
                                $panduanCount = App\Models\Dokumen::where('jenis', 'panduan')->count();
                            @endphp
                            <div class="mb-3">
                                <span class="badge bg-info">{{ $panduanCount }} Dokumen</span>
                            </div>
                            
                            <a href="{{ route('dokumen.kategori', 'panduan') }}" class="btn btn-info text-white w-100">
                                <i class="fas fa-download me-2"></i>Download
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Logo Universitas -->
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="mb-4">
                                <i class="fas fa-image fa-3x text-success"></i>
                            </div>
                            <h5 class="card-title">Logo Universitas</h5>
                            <p class="card-text text-muted">Download logo dalam berbagai format</p>
                            
                            @php
                                $logoCount = App\Models\Dokumen::where('jenis', 'logo')->count();
                            @endphp
                            <div class="mb-3">
                                <span class="badge bg-success">{{ $logoCount }} Dokumen</span>
                            </div>
                            
                            <a href="{{ route('dokumen.kategori', 'logo') }}" class="btn btn-success w-100">
                                <i class="fas fa-download me-2"></i>Download
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lihat Semua Dokumen -->
            <div class="text-center mt-5">
                <a href="{{ route('dokumen.kategori', 'all') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-folder-open me-2"></i>Lihat Semua Dokumen
                </a>
            </div>
        </div>
    </section>

    <!-- Tentang Program KKN -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Kegiatan KKN</h2>
                <p class="text-lg text-gray-600">Dokumentasi kegiatan KKN Universitas Sugeng Hartono</p>
            </div>

            <!-- Swiper Slider -->
            <div class="swiper galeriSwiper">
                <div class="swiper-wrapper">
                    @foreach($galeri as $item)
                        <div class="swiper-slide">
                            <div class="relative group">
                                <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="w-full h-[400px] object-cover rounded-lg">
                                <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/80 to-transparent text-white rounded-b-lg">
                                    <h3 class="text-xl font-semibold mb-2">{{ $item->judul }}</h3>
                                    @if($item->deskripsi)
                                        <p class="text-sm opacity-90">{{ $item->deskripsi }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    <!-- Swiper JS -->
    <script src="{{ asset("assets/js/swiper.min.js") }}"></script>
    <script>
        var swiper = new Swiper(".galeriSwiper", {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });
    </script>
</body>
</html> 
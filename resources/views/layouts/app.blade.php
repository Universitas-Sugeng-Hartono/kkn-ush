<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Logbook KKN') }}</title>

    <!-- Device Detection CSS -->
    <style>
        /* Mobile Detection CSS */
        .mobile-only {
            display: none;
        }

        .desktop-only {
            display: block;
        }

        /* Mobile Detection - Screen width */
        @media (max-width: 768px) {
            .mobile-only {
                display: block;
            }

            .desktop-only {
                display: none;
            }
        }

        /* Mobile Detection - Touch capability */
        @media (hover: none) and (pointer: coarse) {
            .mobile-only {
                display: block;
            }

            .desktop-only {
                display: none;
            }
        }

        /* Mobile Detection - Orientation */
        @media (orientation: portrait) and (max-width: 768px) {
            .mobile-only {
                display: block;
            }

            .desktop-only {
                display: none;
            }
        }

        /* Mobile Detection - Device pixel ratio */
        @media (-webkit-min-device-pixel-ratio: 2),
        (min-resolution: 192dpi) {
            .mobile-only {
                display: block;
            }

            .desktop-only {
                display: none;
            }
        }

        /* Device-specific styles */
        .mobile-device .desktop-only {
            display: none !important;
        }

        .desktop-device .mobile-only {
            display: none !important;
        }

        .tablet-device .mobile-only {
            display: none !important;
        }
    </style>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/ush.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/ush.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ asset("assets/fonts/inter.css") }}" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="{{ asset("assets/css/bootstrap.min.css") }}" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="{{ asset("assets/css/fontawesome.min.css") }}" rel="stylesheet">

    <!-- DataTables -->
    <link href="{{ asset("assets/css/datatables.bootstrap5.min.css") }}" rel="stylesheet">
    <link href="{{ asset("assets/css/datatables.responsive.min.css") }}" rel="stylesheet">

    <!-- Leaflet -->
    <link href="{{ asset("assets/css/leaflet.min.css") }}" rel="stylesheet">

    <!-- SweetAlert2 -->
    <link href="{{ asset("assets/css/sweetalert2.min.css") }}" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0B1F3A;
            --accent-color: #F2B705;
            --sidebar-width: 250px;
            --sidebar-width-collapsed: 70px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        #sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: var(--primary-color);
            color: #fff;
            transition: all 0.3s;
            min-height: 100vh;
            position: fixed;
            z-index: 100;
        }

        #sidebar.active {
            min-width: var(--sidebar-width-collapsed);
            max-width: var(--sidebar-width-collapsed);
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: var(--primary-color);
        }

        #sidebar .sidebar-header h3 {
            color: #fff;
            font-size: 1.4em;
            margin: 0;
            text-align: center;
        }

        #sidebar.active .sidebar-header h3 {
            display: none;
        }

        #sidebar ul.components {
            padding: 20px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        #sidebar ul li a {
            padding: 10px 20px;
            font-size: 1.1em;
            display: block;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s;
            position: relative;
        }

        #sidebar ul li a:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        #sidebar ul li a.active {
            background: var(--accent-color);
            color: var(--primary-color);
        }

        #sidebar.active ul li a {
            padding: 15px;
            text-align: center;
        }

        #sidebar.active ul li a span {
            display: none;
        }

        #sidebar.active ul li a i {
            font-size: 1.4em;
            margin-right: 0;
        }

        /* Content Styles */
        #content {
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            transition: all 0.3s;
            position: absolute;
            right: 0;
            padding: 20px;
        }

        #content.active {
            width: calc(100% - var(--sidebar-width-collapsed));
        }

        /* Navbar Styles */
        .navbar {
            padding: 15px 10px;
            background: #fff;
            border: none;
            border-radius: 0;
            margin-bottom: 40px;
            box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
        }

        .navbar-btn {
            box-shadow: none;
            outline: none !important;
            border: none;
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #eee;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0;
        }

        .card-body {
            padding: 20px;
        }

        /* Button Styles */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #163057;
            border-color: #163057;
        }

        .btn-accent {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: var(--primary-color);
        }

        .btn-accent:hover {
            background-color: #d9a404;
            border-color: #d9a404;
            color: var(--primary-color);
        }

        /* DataTables Custom Styles */
        .dataTables_wrapper .dataTables_length select {
            padding-right: 25px;
        }

        .dataTables_wrapper .dataTables_filter input {
            margin-left: 0.5em;
        }

        /* Responsive */
        @media (max-width: 768px) {
            #sidebar {
                min-width: var(--sidebar-width-collapsed);
                max-width: var(--sidebar-width-collapsed);
                text-align: center;
            }

            #sidebar .sidebar-header h3 {
                display: none;
            }

            #sidebar ul li a span {
                display: none;
            }

            #content {
                width: calc(100% - var(--sidebar-width-collapsed));
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    @php
    $tahunAktifLayout = null;
    $semesterAktifLayout = null;
    
    $layoutUser = auth()->user();
    if ($layoutUser && $layoutUser->hasRole('mahasiswa')) {
        $tahunAktifLayout = $layoutUser->tahunAkademik;
        $semesterAktifLayout = $layoutUser->semester;
    } else {
        $tahun_akademik_id_layout = request()->query('tahun_akademik_id');
        $semester_id_layout = request()->query('semester_id');
        
        if ($tahun_akademik_id_layout) {
            $tahunAktifLayout = \App\Models\TahunAkademik::find($tahun_akademik_id_layout);
        }
        if ($semester_id_layout) {
            $semesterAktifLayout = \App\Models\Semester::find($semester_id_layout);
        }
    }
    
    if (!$tahunAktifLayout) {
        $tahunAktifLayout = \App\Models\TahunAkademik::getAktif();
    }
    if (!$semesterAktifLayout) {
        $semesterAktifLayout = \App\Models\Semester::getAktif();
    }
    @endphp
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="{{ request()->cookie('sidebar_collapsed') ? 'active' : '' }}">
            <div class="sidebar-header">
                <h3>{{ config('app.name') }}</h3>
            </div>

            <ul class="list-unstyled components">
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home me-2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                @role('mahasiswa')
                <li>
                    <a href="{{ route('logbooks.index') }}" class="{{ request()->routeIs('logbooks.*') ? 'active' : '' }}">
                        <i class="fas fa-book me-2"></i>
                        <span>Logbook</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('attendance.index') }}" class="{{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check me-2"></i>
                        <span>Absensi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('laporan-kelompok.index') }}" class="{{ request()->routeIs('laporan-kelompok.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt me-2"></i>
                        <span>Laporan Kelompok</span>
                    </a>
                </li>
                @endrole

                @role('dpl')
                <li>
                    <a href="{{ route('students.index') }}" class="{{ request()->routeIs('students.*') ? 'active' : '' }}">
                        <i class="fas fa-users me-2"></i>
                        <span>Mahasiswa</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('grades.index') }}" class="{{ request()->routeIs('grades.*') ? 'active' : '' }}">
                        <i class="fas fa-star me-2"></i>
                        <span>Penilaian</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('groups.monitoring') }}" class="{{ request()->routeIs('groups.monitoring') && !request()->routeIs('groups.monitoring.map') ? 'active' : '' }}">
                        <i class="fas fa-chart-line me-2"></i>
                        <span>Monitoring Kelompok</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('groups.monitoring.map') }}" class="{{ request()->routeIs('groups.monitoring.map') ? 'active' : '' }}">
                        <i class="fas fa-map-marked-alt me-2"></i>
                        <span>Peta Lokasi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dpl.laporan-kelompok.index') }}" class="{{ request()->routeIs('dpl.laporan-kelompok.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt me-2"></i>
                        <span>Laporan Kelompok</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('history.logbooks.index') }}" class="{{ request()->routeIs('history.logbooks.*') ? 'active' : '' }}">
                        <i class="fas fa-history me-2"></i>
                        <span>History Logbook</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('history.attendances.index') }}" class="{{ request()->routeIs('history.attendances.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <span>History Absensi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('monitoring.attendance-detail') }}" class="{{ request()->routeIs('monitoring.attendance-detail') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check me-2"></i>
                        <span>Monitoring Absensi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('monitoring.logbook-detail') }}" class="{{ request()->routeIs('monitoring.logbook-detail') ? 'active' : '' }}">
                        <i class="fas fa-book me-2"></i>
                        <span>Monitoring Logbook</span>
                    </a>
                </li>
                @endrole

                @role('admin')
                <li>
                    <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog me-2"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('locations.index') }}" class="{{ request()->routeIs('locations.*') ? 'active' : '' }}">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        <span>Lokasi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('groups.index') }}" class="{{ request()->routeIs('groups.*') ? 'active' : '' }}">
                        <i class="fas fa-users me-2"></i>
                        <span>Kelompok</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('berita.index') }}" class="{{ request()->routeIs('berita.*') ? 'active' : '' }}">
                        <i class="fas fa-newspaper me-2"></i>
                        <span>Berita</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dokumen.index') }}" class="{{ request()->routeIs('dokumen.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt me-2"></i>
                        <span>Dokumen</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('galeri.index') }}" class="{{ request()->routeIs('galeri.*') ? 'active' : '' }}">
                        <i class="fas fa-images me-2"></i>
                        <span>Galeri</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pengaduan.index') }}" class="{{ request()->routeIs('pengaduan.*') ? 'active' : '' }}">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <span>Pengaduan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('device.test') }}" class="{{ request()->routeIs('device.test') ? 'active' : '' }}">
                        <i class="fas fa-mobile-alt me-2"></i>
                        <span>Device Test</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('tahun-akademik.index') }}" class="{{ request()->routeIs('tahun-akademik.*') ? 'active' : '' }}">
                        <i class="fas fa-sliders-h me-2"></i>
                        <span>Pengaturan Akademik</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('monitoring.attendance-detail') }}" class="{{ request()->routeIs('monitoring.attendance-detail') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check me-2"></i>
                        <span>Monitoring Absensi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('monitoring.logbook-detail') }}" class="{{ request()->routeIs('monitoring.logbook-detail') ? 'active' : '' }}">
                        <i class="fas fa-book me-2"></i>
                        <span>Monitoring Logbook</span>
                    </a>
                </li>
                @endrole
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content" class="{{ request()->cookie('sidebar_collapsed') ? 'active' : '' }}">
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light" style="background-color:rgb(255, 255, 255) !important;">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-primary">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto">
                            @if($tahunAktifLayout && $semesterAktifLayout)
                            <li class="nav-item d-flex align-items-center me-3">
                                <span class="badge bg-success py-2 px-3">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ $tahunAktifLayout->nama }} - {{ $semesterAktifLayout->nama }}
                                </span>
                            </li>
                            @endif

                            @role('dpl')
                            <li class="nav-item dropdown me-3">
                                <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-bell"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notification-badge" style="display: none;">
                                        0
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown" style="width: 350px;">
                                    <li>
                                        <h6 class="dropdown-header">Notifikasi</h6>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <div id="notifications-list">
                                        <li class="px-3 py-2 text-muted">Memuat notifikasi...</li>
                                    </div>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item text-center" href="{{ route('dpl.notifications.index') }}">Lihat Semua</a></li>
                                </ul>
                            </li>
                            @endrole
                            @role('mahasiswa')
                            <li class="nav-item dropdown me-3">
                                <a class="nav-link position-relative" href="#" id="studentNotificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-bell"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="student-notification-badge" style="display: none;">
                                        0
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="studentNotificationsDropdown" style="width: 350px;">
                                    <li>
                                        <h6 class="dropdown-header">Notifikasi</h6>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <div id="student-notifications-list">
                                        <li class="px-3 py-2 text-muted">Memuat notifikasi...</li>
                                    </div>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item text-center" href="{{ route('notifications.index') }}">Lihat Semua</a></li>
                                </ul>
                            </li>
                            @endrole
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                            <i class="fas fa-user me-2"></i> Profile
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset("assets/js/jquery.min.js") }}"></script>
    <script src="{{ asset("assets/js/bootstrap.bundle.min.js") }}"></script>

    <!-- DataTables -->
    <script src="{{ asset("assets/js/datatables.min.js") }}"></script>
    <script src="{{ asset("assets/js/datatables.bootstrap5.min.js") }}"></script>
    <script src="{{ asset("assets/js/datatables.responsive.min.js") }}"></script>
    <script src="{{ asset("assets/js/datatables.responsive.bootstrap5.min.js") }}"></script>

    <script src="{{ asset("assets/js/chart.min.js") }}"></script>
    <script src="{{ asset("assets/js/leaflet.min.js") }}"></script>
    <script src="{{ asset("assets/js/sweetalert2.min.js") }}"></script>

    <script>
        // Real-time device detection
        class DeviceDetector {
            constructor() {
                this.isMobile = false;
                this.isTablet = false;
                this.isDesktop = false;
                this.init();
                this.bindEvents();
            }

            init() {
                this.detectDevice();
                this.updateSession();
            }

            detectDevice() {
                const width = window.innerWidth;
                const height = window.innerHeight;
                const aspectRatio = width / height;
                const hasTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
                const hasHover = window.matchMedia('(hover: hover)').matches;
                const isPortrait = window.matchMedia('(orientation: portrait)').matches;
                const pixelRatio = window.devicePixelRatio || 1;

                // Mobile detection criteria - lebih konservatif
                const isMobileBySize = width <= 768;
                const isMobileByTouch = hasTouch && !hasHover && width <= 1024;
                const isMobileByOrientation = isPortrait && width <= 768;

                // Tablet detection
                const isTabletBySize = width > 768 && width <= 1024;
                const isTabletByTouch = hasTouch && hasHover && width <= 1024;

                // Desktop detection - prioritas utama
                const isDesktopBySize = width > 1024;
                const isDesktopByHover = hasHover && width > 768;

                // Logic yang lebih akurat
                if (isDesktopBySize || isDesktopByHover) {
                    this.isDesktop = true;
                    this.isMobile = false;
                    this.isTablet = false;
                } else if (isTabletBySize || isTabletByTouch) {
                    this.isTablet = true;
                    this.isMobile = false;
                    this.isDesktop = false;
                } else if (isMobileBySize || isMobileByTouch || isMobileByOrientation) {
                    this.isMobile = true;
                    this.isTablet = false;
                    this.isDesktop = false;
                } else {
                    // Default ke desktop jika tidak jelas
                    this.isDesktop = true;
                    this.isMobile = false;
                    this.isTablet = false;
                }

                // Update CSS classes
                this.updateCSSClasses();

                // Debug info
                console.log('Device Detection:', {
                    width,
                    height,
                    hasTouch,
                    hasHover,
                    isPortrait,
                    pixelRatio,
                    isMobile: this.isMobile,
                    isTablet: this.isTablet,
                    isDesktop: this.isDesktop,
                    userAgent: navigator.userAgent
                });
            }

            updateCSSClasses() {
                const body = document.body;

                // Remove existing classes
                body.classList.remove('mobile-device', 'tablet-device', 'desktop-device');

                // Add appropriate class
                if (this.isMobile) {
                    body.classList.add('mobile-device');
                } else if (this.isTablet) {
                    body.classList.add('tablet-device');
                } else {
                    body.classList.add('desktop-device');
                }

                // Update mobile-only and desktop-only elements
                const mobileElements = document.querySelectorAll('.mobile-only');
                const desktopElements = document.querySelectorAll('.desktop-only');

                mobileElements.forEach(el => {
                    el.style.display = this.isMobile ? 'block' : 'none';
                });

                desktopElements.forEach(el => {
                    el.style.display = this.isMobile ? 'none' : 'block';
                });
            }

            updateSession() {
                // Send device info to server via AJAX
                fetch('{{ route("device.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        is_mobile: this.isMobile,
                        is_tablet: this.isTablet,
                        is_desktop: this.isDesktop,
                        screen_width: window.innerWidth,
                        screen_height: window.innerHeight,
                        user_agent: navigator.userAgent
                    })
                }).catch(error => {
                    console.log('Device info update failed:', error);
                });
            }

            bindEvents() {
                // Listen for resize events
                let resizeTimeout;
                window.addEventListener('resize', () => {
                    clearTimeout(resizeTimeout);
                    resizeTimeout = setTimeout(() => {
                        this.detectDevice();
                        this.updateSession();
                    }, 250);
                });

                // Listen for orientation change
                window.addEventListener('orientationchange', () => {
                    setTimeout(() => {
                        this.detectDevice();
                        this.updateSession();
                    }, 500);
                });

                // Listen for touch events to detect touch capability
                let touchDetected = false;
                const touchHandler = () => {
                    if (!touchDetected) {
                        touchDetected = true;
                        this.detectDevice();
                        this.updateSession();
                        document.removeEventListener('touchstart', touchHandler);
                    }
                };
                document.addEventListener('touchstart', touchHandler);
            }

            getDeviceInfo() {
                return {
                    isMobile: this.isMobile,
                    isTablet: this.isTablet,
                    isDesktop: this.isDesktop,
                    width: window.innerWidth,
                    height: window.innerHeight,
                    userAgent: navigator.userAgent
                };
            }
        }

        // Initialize device detector
        let deviceDetector;
        document.addEventListener('DOMContentLoaded', function() {
            deviceDetector = new DeviceDetector();
        });

        // Global function to get device info
        function getDeviceInfo() {
            return deviceDetector ? deviceDetector.getDeviceInfo() : null;
        }

        $(document).ready(function() {
            // Sidebar toggle
            $('#sidebarCollapse').on('click', function() {
                $('#sidebar, #content').toggleClass('active');
                // Save state to cookie
                document.cookie = 'sidebar_collapsed=' + ($('#sidebar').hasClass('active') ? '1' : '') + ';path=/';
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Flash messages
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            // Notifikasi Mahasiswa
            @if(auth()->user() && auth()->user()->hasRole('mahasiswa'))
            function loadStudentNotifications() {
                fetch('{{ route('notifications.get') }}')
                    .then(response => response.json())
                    .then(data => {
                        const notifications = data.notifications || data;
                        const unreadCount = data.unread_count !== undefined ? data.unread_count : notifications.length;
                        
                        const container = document.getElementById('student-notifications-list');
                        const badge = document.getElementById('student-notification-badge');
                        if (!container || !badge) return;
                        if (notifications.length === 0) {
                            container.innerHTML = '<li class="px-3 py-2 text-muted">Tidak ada notifikasi baru</li>';
                            badge.style.display = 'none';
                        } else {
                            container.innerHTML = '';
                            notifications.forEach(notification => {
                                const icon = getStudentNotificationIcon(notification.type);
                                const color = getStudentNotificationColor(notification.type);
                                const bgClass = notification.is_read ? '' : 'bg-light';
                                const fwClass = notification.is_read ? '' : 'fw-bold';
                                const isRead = notification.is_read;
                                const notificationHtml = `
                                        <li class="px-3 py-2 border-bottom ${bgClass}" style="cursor: pointer; transition: background-color 0.2s;" onmouseover="this.classList.add('bg-light')" onmouseout="if(!${isRead}) { this.classList.add('bg-light'); } else { this.classList.remove('bg-light'); }" onclick="markStudentNotificationAsRead('${notification.id}', '${notification.url || '#'}')">
                                            <div class="d-flex text-dark">
                                                <div class="flex-shrink-0 mt-1">
                                                    <i class="fas fa-${icon} text-${color}"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <div class="${fwClass} small">${notification.title}</div>
                                                    <div class="small text-muted">${notification.message}</div>
                                                    <div class="small text-muted mt-1" style="font-size: 0.75rem;"><i class="far fa-clock me-1"></i>${new Date(notification.created_at).toLocaleString('id-ID')}</div>
                                                </div>
                                            </div>
                                        </li>
                                    `;
                                container.innerHTML += notificationHtml;
                            });
                            
                            if (unreadCount > 0) {
                                badge.textContent = unreadCount;
                                badge.style.display = 'block';
                            } else {
                                badge.style.display = 'none';
                            }
                        }
                    });
            }

            window.markStudentNotificationAsRead = function(id, url) {
                if (!id || id === 'undefined') {
                    if (url && url !== '#') window.location.href = url;
                    return;
                }
                
                fetch(`/notifications/${id}/mark-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                }).then(() => {
                    if (url && url !== '#') {
                        window.location.href = url;
                    } else {
                        loadStudentNotifications();
                    }
                }).catch(err => {
                    console.error(err);
                    if (url && url !== '#') {
                        window.location.href = url;
                    }
                });
            };

            function getStudentNotificationIcon(type) {
                switch (type) {
                    case 'logbook_approved': return 'check-circle';
                    case 'logbook_rejected': return 'times-circle';
                    case 'attendance_approved': return 'check-circle';
                    case 'attendance_rejected': return 'times-circle';
                    case 'late_attendance': return 'exclamation-triangle';
                    default: return 'bell';
                }
            }

            function getStudentNotificationColor(type) {
                switch (type) {
                    case 'logbook_approved': return 'success';
                    case 'logbook_rejected': return 'danger';
                    case 'attendance_approved': return 'success';
                    case 'attendance_rejected': return 'danger';
                    case 'late_attendance': return 'warning';
                    default: return 'primary';
                }
            }

            loadStudentNotifications();
            setInterval(loadStudentNotifications, 60000);
            @endif

            // Notifikasi DPL
            @if(auth()->user() && auth()->user()->hasRole('dpl'))
            function loadNotifications() {
                fetch('{{ route('dpl.notifications.get') }}')
                    .then(response => response.json())
                    .then(notifications => {
                        const container = document.getElementById('notifications-list');
                        const badge = document.getElementById('notification-badge');
                        if (!container || !badge) return;
                        if (notifications.length === 0) {
                            container.innerHTML = '<li class="px-3 py-2 text-muted text-center"><i class="fas fa-bell-slash mb-2 d-block text-muted" style="font-size: 24px;"></i>Tidak ada notifikasi baru</li>';
                            badge.style.display = 'none';
                        } else {
                            container.innerHTML = '';
                            notifications.forEach(notification => {
                                const icon = getNotificationIcon(notification.type);
                                const color = getNotificationColor(notification.type);
                                const isRead = notification.is_read;
                                const bgClass = isRead ? '' : 'bg-light';
                                const fwClass = isRead ? '' : 'fw-bold';
                                
                                const notificationHtml = `
                                        <li class="px-3 py-2 ${bgClass} border-bottom" style="cursor: pointer; transition: background-color 0.2s;" onmouseover="this.classList.add('bg-light')" onmouseout="if(!${isRead}) { this.classList.add('bg-light'); } else { this.classList.remove('bg-light'); }" onclick="markDplNotificationRead('${notification.id}', '${notification.url || '#'}')">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 mt-1">
                                                    <i class="fas fa-${icon} text-${color}"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <div class="${fwClass} small text-dark">${notification.title}</div>
                                                    <div class="small text-muted">${notification.message}</div>
                                                    <div class="small text-muted mt-1"><i class="far fa-clock me-1"></i>${new Date(notification.created_at).toLocaleString('id-ID')}</div>
                                                </div>
                                            </div>
                                        </li>
                                    `;
                                container.innerHTML += notificationHtml;
                            });
                            
                            const unreadCount = notifications.filter(n => !n.is_read).length;
                            if (unreadCount > 0) {
                                badge.textContent = unreadCount;
                                badge.style.display = 'block';
                            } else {
                                badge.style.display = 'none';
                            }
                        }
                    });
            }

            window.markDplNotificationRead = function(id, url) {
                if (!id || id === 'undefined') {
                    if (url && url !== '#') window.location.href = url;
                    return;
                }
                
                fetch('{{ route('dpl.notifications.mark-read') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ id: id })
                }).then(() => {
                    if (url && url !== '#') {
                        window.location.href = url;
                    } else {
                        loadNotifications();
                    }
                }).catch(() => {
                    if (url && url !== '#') window.location.href = url;
                });
            };

            function getNotificationIcon(type) {
                switch (type) {
                    case 'logbook_pending': return 'book';
                    case 'absensi_pending': return 'calendar-check';
                    case 'no_logbook_today': return 'exclamation-circle';
                    case 'no_absensi_today': return 'exclamation-triangle';
                    default: return 'bell';
                }
            }

            function getNotificationColor(type) {
                switch (type) {
                    case 'logbook_pending': return 'primary';
                    case 'absensi_pending': return 'info';
                    case 'no_logbook_today': return 'danger';
                    case 'no_absensi_today': return 'warning';
                    default: return 'secondary';
                }
            }

            loadNotifications();
            setInterval(loadNotifications, 60000);
            @endif
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Override native form onsubmit with confirm()
            document.querySelectorAll('form[onsubmit*="return confirm"]').forEach(form => {
                const match = form.getAttribute('onsubmit').match(/return confirm\(['"`](.*?)['"`]\)/);
                if (match) {
                    const message = match[1];
                    form.removeAttribute('onsubmit');
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Konfirmasi',
                            text: message,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                }
            });

            // Override native onclick with confirm()
            document.querySelectorAll('[onclick*="return confirm"]').forEach(element => {
                const match = element.getAttribute('onclick').match(/return confirm\(['"`](.*?)['"`]\)/);
                if (match) {
                    const message = match[1];
                    element.removeAttribute('onclick');
                    element.addEventListener('click', function(e) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Konfirmasi',
                            text: message,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                if (element.tagName === 'A') {
                                    window.location.href = element.href;
                                } else if (element.type === 'submit') {
                                    element.closest('form').submit();
                                }
                            }
                        });
                    });
                }
            });
        });
    </script>
    <x-firebase-init />
    @stack('scripts')
</body>

</html>
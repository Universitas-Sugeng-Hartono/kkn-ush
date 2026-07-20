<x-app-layout>
    <style>
        /* Responsive Design untuk Mobile */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 10px;
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
            
            .table-responsive {
                font-size: 0.875rem;
            }
            
            .table th,
            .table td {
                padding: 0.5rem 0.25rem;
                vertical-align: middle;
            }
            
            .btn {
                font-size: 0.875rem;
                padding: 0.375rem 0.75rem;
            }
            
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
            
            .btn-lg {
                padding: 0.75rem 1.5rem;
                font-size: 1rem;
            }
            
            .badge {
                font-size: 0.75rem;
            }
            
            .col-md-8,
            .col-md-4 {
                margin-bottom: 1rem;
            }
            
            .row.g-3 {
                margin: 0;
            }
            
            .row.g-3 .col-6 {
                padding: 0 5px;
            }
            
            .border.rounded.p-3 {
                padding: 0.75rem !important;
            }
            
            .border.rounded.p-3 h3 {
                font-size: 1.5rem;
            }
            
            .border.rounded.p-3 small {
                font-size: 0.75rem;
            }
            
            .modal-dialog {
                margin: 0.5rem;
                max-width: calc(100% - 1rem);
            }
            
            .modal-body {
                padding: 1rem;
            }
            
            .modal-header {
                padding: 0.75rem 1rem;
            }
            
            .modal-title {
                font-size: 1rem;
            }
            
            .form-label {
                font-size: 0.875rem;
            }
            
            .form-control,
            .form-select {
                font-size: 0.875rem;
                padding: 0.375rem 0.75rem;
            }
            
            .text-center img {
                width: 120px !important;
            }
            
            .text-center h4 {
                font-size: 1.25rem;
            }
            
            .text-center p {
                font-size: 0.875rem;
            }
        }
        
        @media (max-width: 576px) {
            .container-fluid {
                padding: 5px;
            }
            
            .card-body {
                padding: 0.75rem;
            }
            
            .card-header {
                padding: 0.5rem 0.75rem;
            }
            
            .table-responsive {
                font-size: 0.8rem;
            }
            
            .table th,
            .table td {
                padding: 0.375rem 0.125rem;
            }
            
            .btn {
                font-size: 0.8rem;
                padding: 0.25rem 0.5rem;
            }
            
            .btn-sm {
                padding: 0.125rem 0.25rem;
                font-size: 0.7rem;
            }
            
            .btn-lg {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
            
            .modal-dialog {
                margin: 0.25rem;
                max-width: calc(100% - 0.5rem);
            }
            
            .modal-body {
                padding: 0.75rem;
            }
            
            .modal-header {
                padding: 0.5rem 0.75rem;
            }
            
            .form-control,
            .form-select {
                font-size: 0.8rem;
                padding: 0.25rem 0.5rem;
            }
            
            .row.g-3 .col-6 {
                padding: 0 2px;
            }
            
            .border.rounded.p-3 {
                padding: 0.5rem !important;
            }
            
            .border.rounded.p-3 h3 {
                font-size: 1.25rem;
            }
            
            .border.rounded.p-3 small {
                font-size: 0.7rem;
            }
            
            .text-center img {
                width: 100px !important;
            }
            
            .text-center h4 {
                font-size: 1.1rem;
            }
            
            .text-center p {
                font-size: 0.8rem;
            }
            
            .d-flex.justify-content-between.align-items-center {
                flex-direction: column;
                text-align: center;
            }
            
            .d-flex.justify-content-between.align-items-center > div:first-child {
                margin-bottom: 1rem;
            }
        }
    </style>
    
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">Absensi Harian</h2>
                        <p class="text-muted mb-0">
                            <i class="fas fa-clipboard-check me-2"></i>Catat kehadiran dan aktivitas harian Anda
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <!-- Kalender -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar me-2"></i>Kalender Aktivitas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>

                <!-- Riwayat Absensi -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-history me-2"></i>Riwayat Absensi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="attendanceTable">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Masuk</th>
                                        <th>Keluar</th>
                                        <th>Status</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendances as $attendance)
                                        <tr>
                                            <td>{{ $attendance->tanggal->isoFormat('dddd, D MMMM Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($attendance->waktu_masuk)->format('H:i') }}</td>
                                            <td>
                                                @if($attendance->waktu_keluar)
                                                    {{ \Carbon\Carbon::parse($attendance->waktu_keluar)->format('H:i') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $attendance->status === 'validated' ? 'bg-success' : 
                                                       ($attendance->status === 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                                                    {{ ucfirst($attendance->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" 
                                                        class="btn btn-sm btn-info"
                                                        onclick="showDetail({{ $attendance->id }})"
                                                        title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Panel Absen Hari Ini -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar-check me-2"></i>Absen Hari Ini
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="loadAttendanceStatus()" title="Refresh Status">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <div class="card-body text-center py-5">
                        <div id="attendance-status">
                            <!-- Status akan di-load via AJAX -->
                        </div>
                    </div>
                </div>

                <!-- Statistik Absensi -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-pie me-2"></i>Statistik Absensi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="border rounded p-3 text-center">
                                    <h3 class="fw-bold text-success mb-1">{{ $stats['validated'] }}</h3>
                                    <small class="text-muted">Divalidasi</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3 text-center">
                                    <h3 class="fw-bold text-warning mb-1">{{ $stats['pending'] }}</h3>
                                    <small class="text-muted">Pending</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3 text-center">
                                    <h3 class="fw-bold text-danger mb-1">{{ $stats['rejected'] }}</h3>
                                    <small class="text-muted">Ditolak</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3 text-center">
                                    <h3 class="fw-bold text-primary mb-1">{{ $stats['total'] }}</h3>
                                    <small class="text-muted">Total</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Absensi -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal</label>
                                <p id="detail-tanggal"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Waktu Masuk</label>
                                <p id="detail-waktu-masuk"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Waktu Keluar</label>
                                <p id="detail-waktu-keluar"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <p id="detail-status"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Lokasi Masuk</label>
                                <p id="detail-lokasi-masuk"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Lokasi Keluar</label>
                                <p id="detail-lokasi-keluar"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Foto Masuk</label>
                                <img id="detail-foto-masuk" src="" alt="Foto Absensi Masuk" class="img-fluid rounded mb-2">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Foto Keluar</label>
                                <img id="detail-foto-keluar" src="" alt="Foto Absensi Keluar" class="img-fluid rounded mb-2">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Absensi -->
    <div class="modal fade" id="attendanceModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="attendanceForm" action="{{ route('attendance.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="jenis_absen" id="jenis_absen">
                        
                        <div class="mb-3">
                            <label class="form-label">Foto Selfie</label>
                            <div class="text-center">
                                <video id="camera" autoplay style="display: none; width: 100%; max-width: 400px; height: auto;"></video>
                                <canvas id="canvas" style="display: none; width: 100%; max-width: 400px; height: auto;"></canvas>
                                <div id="capturePlaceholder" class="border rounded p-4 text-center">
                                    <i class="fas fa-camera fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Kamera akan aktif saat Anda mengklik "Ambil Foto"</p>
                                </div>
                            </div>
                            <input type="hidden" name="foto" id="foto">
                            <button type="button" class="btn btn-primary mt-3" id="captureBtn">
                                <i class="fas fa-camera me-2"></i>Ambil Foto
                            </button>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="text" class="form-control" value="{{ now()->format('d/m/Y') }}" disabled>
                            <input type="hidden" name="tanggal" value="{{ now()->format('Y-m-d') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Waktu</label>
                            <input type="text" class="form-control" value="{{ now()->format('H:i') }}" disabled>
                            <input type="hidden" name="waktu_masuk" value="{{ now()->format('H:i') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lokasi</label>
                            <div class="input-group">
                            <input type="text" class="form-control" id="lokasi" name="lokasi" readonly>
                                <button type="button" class="btn btn-outline-secondary" onclick="refreshLocation()">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>Lokasi akan terdeteksi otomatis
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                <i class="fas fa-check me-2"></i>Kirim Absensi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>

    @push('styles')
    <link href="{{ asset("assets/css/fullcalendar.min.css") }}" rel="stylesheet">
    <style>
        /* Kalender */
        .fc {
            --fc-border-color: #e5e7eb;
            --fc-button-text-color: #fff;
            --fc-button-bg-color: #0B1F3A;
            --fc-button-border-color: #0B1F3A;
            --fc-button-hover-bg-color: #0a1829;
            --fc-button-hover-border-color: #0a1829;
            --fc-button-active-bg-color: #0a1829;
            --fc-button-active-border-color: #0a1829;
            --fc-today-bg-color: rgba(11, 31, 58, 0.1);
            --fc-neutral-bg-color: #fff;
            --fc-neutral-text-color: #0B1F3A;
            --fc-event-bg-color: #0B1F3A;
            --fc-event-border-color: #0B1F3A;
        }

        .fc .fc-toolbar-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #0B1F3A;
        }

        .fc .fc-button {
            text-transform: capitalize;
            font-weight: 500;
        }

        .fc .fc-col-header-cell {
            background-color: #f8f9fa;
            padding: 1rem 0;
        }

        .fc .fc-col-header-cell-cushion {
            color: #0B1F3A;
            font-weight: 600;
            text-decoration: none;
            text-transform: capitalize;
            padding: 8px;
        }

        .fc .fc-daygrid-day-number {
            color: #0B1F3A;
            text-decoration: none;
            padding: 8px;
        }

        .fc .fc-daygrid-day.absent {
            background-color: rgba(220, 53, 69, 0.1);
        }

        .fc .fc-daygrid-day.present {
            background-color: rgba(40, 167, 69, 0.1);
        }

        .fc .fc-event {
            border: none;
            border-radius: 4px;
            padding: 2px 4px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .fc .fc-event-title {
            font-weight: 500;
        }

        .fc .fc-day:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        /* Camera */
        #camera-container {
            background: #000;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        #camera-overlay {
            border-radius: 0.5rem;
            pointer-events: none;
        }
        #canvas {
            width: 100% !important;
            height: auto !important;
            max-width: 100%;
            object-fit: contain !important;
            border-radius: 12px;
            background: #000;
            display: none;
        }
        #camera {
            width: 100% !important;
            height: auto !important;
            max-width: 100%;
            object-fit: contain !important;
            border-radius: 12px;
            background: #000;
        }

        /* Toast Styles */
        .toast-container {
            z-index: 1055;
        }

        .toast {
            min-width: 300px;
            background: white;
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            margin-bottom: 0.5rem;
        }

        .toast.alert-success {
            border-left: 4px solid #28a745;
        }

        .toast.alert-danger {
            border-left: 4px solid #dc3545;
        }

        .toast .d-flex {
            padding: 0.75rem 1rem;
        }

        .toast i {
            font-size: 1.125rem;
            margin-right: 0.5rem;
        }

        .toast.alert-success i {
            color: #28a745;
        }

        .toast.alert-danger i {
            color: #dc3545;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="{{ asset("assets/js/fullcalendar.min.js") }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Calendar
            const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'dayGridMonth',
                locale: 'id',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth'
                },
                buttonText: {
                    today: 'Hari Ini'
                },
                events: @json($events),
                dateClick: function(info) {
                    const date = info.date;
                    const today = new Date();
                    
                    date.setHours(0, 0, 0, 0);
                    today.setHours(0, 0, 0, 0);
                    
                    if (date.getTime() === today.getTime()) {
                        const hasMasuk = {{ $hasMasuk ? 'true' : 'false' }};
                        const hasKeluar = {{ $hasKeluar ? 'true' : 'false' }};
                        
                        if (!hasMasuk) {
                            takeAttendance('masuk');
                        } else if (!hasKeluar) {
                            takeAttendance('keluar');
                        } else {
                            Swal.fire('Info', 'Anda sudah menyelesaikan absensi untuk hari ini.', 'info');
                        }
                    } else if (date < today) {
                        Swal.fire('Perhatian', 'Tidak dapat melakukan absensi untuk hari yang sudah lewat.', 'warning');
                    } else {
                        Swal.fire('Perhatian', 'Tidak dapat melakukan absensi untuk hari yang belum tiba.', 'warning');
                    }
                },
                eventClick: function(info) {
                    if (info.event.id) {
                        showDetail(info.event.id);
                    }
                },
                dayCellDidMount: function(info) {
                    // Tandai hari yang sudah/belum absen
                    const event = info.el;
                    const date = info.date;
                    const today = new Date();
                    
                    // Reset jam agar bisa membandingkan tanggal saja
                    date.setHours(0, 0, 0, 0);
                    today.setHours(0, 0, 0, 0);

                    // Jika tanggal sudah lewat dan tidak ada absensi
                    if (date < today && !hasAttendance(date)) {
                        event.classList.add('absent');
                    }
                }
            });
            calendar.render();

            // Initialize DataTable
            $('#attendanceTable').DataTable({
                order: [[0, 'desc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                }
            });

            // Cek apakah ada absensi pada tanggal tertentu
            function hasAttendance(date) {
                const events = calendar.getEvents();
                return events.some(event => {
                    const eventDate = new Date(event.start);
                    return eventDate.toDateString() === date.toDateString();
                });
            }
        });

        // Fungsi untuk menampilkan detail absensi
        function showDetail(id) {
            fetch(`/attendance/${id}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
                .then(data => {
                    document.getElementById('detail-tanggal').textContent = data.tanggal;
                document.getElementById('detail-waktu-masuk').textContent = data.waktu_masuk;
                document.getElementById('detail-waktu-keluar').textContent = data.waktu_keluar || '-';
                    document.getElementById('detail-status').innerHTML = `
                        <span class="badge ${data.status === 'validated' ? 'bg-success' : 
                               (data.status === 'rejected' ? 'bg-danger' : 'bg-warning')}">
                            ${data.status}
                        </span>`;
                document.getElementById('detail-lokasi-masuk').textContent = data.lokasi_masuk;
                document.getElementById('detail-lokasi-keluar').textContent = data.lokasi_keluar || '-';

                if (data.foto_masuk_url) {
                    document.getElementById('detail-foto-masuk').src = data.foto_masuk_url;
                    document.getElementById('detail-foto-masuk').style.display = 'block';
                } else {
                    document.getElementById('detail-foto-masuk').style.display = 'none';
                }
                if (data.foto_keluar_url) {
                    document.getElementById('detail-foto-keluar').src = data.foto_keluar_url;
                    document.getElementById('detail-foto-keluar').style.display = 'block';
                } else {
                    document.getElementById('detail-foto-keluar').style.display = 'none';
                }
                    
                    new bootstrap.Modal(document.getElementById('detailModal')).show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat detail absensi. Silakan coba lagi.');
            });
        }

        // Load status absensi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Gunakan data server-side untuk performa lebih baik
            const serverData = {
                has_masuk: {{ $hasMasuk ? 'true' : 'false' }},
                has_keluar: {{ $hasKeluar ? 'true' : 'false' }},
                attendance: @json($todayAttendance)
            };
            
            updateAttendanceStatus(serverData);
            
            // Auto refresh setiap 30 detik
            setInterval(function() {
                loadAttendanceStatus();
            }, 30000);
        });

        // Update status absensi berdasarkan data
        function updateAttendanceStatus(data) {
            const statusDiv = document.getElementById('attendance-status');
            
            if (!data.has_masuk) {
                // Belum absen masuk
                statusDiv.innerHTML = `
                    <img src="{{ asset('images/attendance.svg') }}" alt="Absen" class="mb-4" style="width: 150px;">
                    <h4 class="fw-bold mb-3">Belum Absen Masuk</h4>
                    <p class="text-muted mb-4">Silakan lakukan absensi masuk untuk hari ini</p>
                    <button type="button" class="btn btn-primary btn-lg px-5" onclick="takeAttendance('masuk')">
                        <i class="fas fa-sign-in-alt me-2"></i>Absen Masuk
                    </button>
                `;
            } else if (!data.has_keluar) {
                // Sudah absen masuk, belum keluar
                statusDiv.innerHTML = `
                    <img src="{{ asset('images/attendance-done.svg') }}" alt="Sudah Absen" class="mb-4" style="width: 150px;">
                    <h4 class="fw-bold mb-3">Sudah Absen Masuk</h4>
                    <p class="text-muted mb-4">
                        Masuk: ${new Date(data.attendance.waktu_masuk).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}
                    </p>
                    <button type="button" class="btn btn-success btn-lg px-5" onclick="takeAttendance('keluar')">
                        <i class="fas fa-sign-out-alt me-2"></i>Absen Keluar
                    </button>
                `;
            } else {
                // Sudah absen masuk dan keluar
                statusDiv.innerHTML = `
                    <img src="{{ asset('images/attendance-done.svg') }}" alt="Sudah Absen" class="mb-4" style="width: 150px;">
                    <h4 class="fw-bold mb-3">Absensi Selesai</h4>
                    <p class="text-muted mb-4">
                        Masuk: ${new Date(data.attendance.waktu_masuk).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}<br>
                        Keluar: ${new Date(data.attendance.waktu_keluar).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}
                    </p>
                    <button type="button" class="btn btn-secondary btn-lg px-5" disabled>
                        <i class="fas fa-check-circle me-2"></i>Absensi Selesai
                    </button>
                `;
            }
        }

        // Load status absensi hari ini (untuk refresh manual)
        function loadAttendanceStatus() {
            fetch('{{ route("attendance.check-today") }}')
                .then(response => response.json())
                .then(data => {
                    updateAttendanceStatus(data); // Use the new function
                })
                .catch(error => {
                    console.error('Error loading attendance status:', error);
                });
        }

        // Fungsi untuk mengambil absensi
        function takeAttendance(jenis) {
            document.getElementById('jenis_absen').value = jenis;
            document.getElementById('modalTitle').textContent = jenis === 'masuk' ? 'Absensi Masuk' : 'Absensi Keluar';
            
            // Reset form
            document.getElementById('foto').value = '';
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('capturePlaceholder').style.display = 'block';
            document.getElementById('camera').style.display = 'none';
            document.getElementById('canvas').style.display = 'none';
            
            // Update waktu
            const now = new Date();
            document.querySelector('input[name="waktu_masuk"]').value = now.toTimeString().slice(0, 5);
            
            // Get location
            getLocation();

            // Show modal
            new bootstrap.Modal(document.getElementById('attendanceModal')).show();
        }

        // Refresh lokasi GPS
        function refreshLocation() {
            getLocation();
        }

        // Get location function
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        document.getElementById('latitude').value = lat;
                        document.getElementById('longitude').value = lng;

                        // Reverse geocoding
                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                            .then(response => response.json())
                            .then(data => {
                                const address = data.display_name || 'Lokasi tidak dapat ditentukan';
                                document.getElementById('lokasi').value = address;
                            })
                            .catch(error => {
                                document.getElementById('lokasi').value = `Lat: ${lat}, Lng: ${lng}`;
                            });
                    },
                    function(error) {
                        showToast('error', 'Tidak dapat mendapatkan lokasi: ' + error.message);
                    }
                );
            } else {
                showToast('error', 'Geolokasi tidak didukung oleh browser ini');
            }
        }

        // Capture foto
        document.getElementById('captureBtn').addEventListener('click', function() {
            const video = document.getElementById('camera');
            const canvas = document.getElementById('canvas');
            const context = canvas.getContext('2d');

            // Cek readiness video
            if (!video.videoWidth || !video.videoHeight) {
                showToast('error', 'Kamera belum siap. Mohon tunggu beberapa detik lalu coba lagi.');
                return;
            }

            // Set ukuran canvas sesuai video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Gambar frame dari video ke canvas
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Konversi ke base64 dengan quality 0.5
            const dataUrl = canvas.toDataURL('image/jpeg', 0.5);
            document.getElementById('foto').value = dataUrl;

            // Enable tombol submit
            document.getElementById('submitBtn').disabled = false;

            // Update preview
            video.style.display = 'none';
            canvas.classList.remove('d-none');
            canvas.style.display = 'block';
        });

        // Reset kamera saat modal ditutup
        document.getElementById('attendanceModal').addEventListener('hidden.bs.modal', function() {
            const video = document.getElementById('camera');
            const canvas = document.getElementById('canvas');
            const placeholder = document.getElementById('capturePlaceholder');
            
            if (video.srcObject) {
                video.srcObject.getTracks().forEach(track => track.stop());
                video.srcObject = null;
            }
            
            video.style.display = 'none';
            placeholder.style.display = 'block';
            canvas.style.display = 'none';
            document.getElementById('submitBtn').disabled = true;
        });

        // Inisialisasi kamera saat modal dibuka
        document.getElementById('attendanceModal').addEventListener('shown.bs.modal', function() {
            const video = document.getElementById('camera');
            const placeholder = document.getElementById('capturePlaceholder');
            
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        facingMode: "user",
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    } 
                })
                .then(stream => {
                    video.srcObject = stream;
                    video.style.display = 'block';
                    placeholder.style.display = 'none';
                    video.play();
                })
                .catch(err => {
                    console.error("Error accessing camera: ", err);
                    showToast('error', 'Gagal mengakses kamera. Pastikan izin kamera telah diberikan.');
                });
            } else {
                showToast('error', 'Browser Anda tidak mendukung akses kamera.');
            }
        });

        // Submit form absensi web pakai AJAX agar bisa toast & redirect
        const attendanceForm = document.getElementById('attendanceForm');
        if (attendanceForm) {
            attendanceForm.addEventListener('submit', function(e) {
                e.preventDefault();
                // Validasi field wajib
                const foto = document.getElementById('foto').value;
                const lat = document.getElementById('latitude').value;
                const lng = document.getElementById('longitude').value;
                const lokasi = document.getElementById('lokasi').value;
                if (!foto) {
                    showToast('error', 'Harap ambil foto terlebih dahulu!');
                    return;
                }
                if (!lat || !lng) {
                    showToast('error', 'Lokasi belum terdeteksi!');
                    return;
                }
                if (!lokasi) {
                    showToast('error', 'Alamat lokasi belum terisi!');
                    return;
                }
                const formData = new FormData(attendanceForm);
                fetch(attendanceForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(async response => {
                    let data = await response.json();
                    if (response.status === 422) {
                        // Tampilkan semua pesan error validasi
                        let msg = '';
                        if (data.errors) {
                            msg = Object.values(data.errors).map(arr => arr.join(', ')).join('\n');
                        } else {
                            msg = data.message || 'Data tidak valid';
                        }
                        showToast('error', msg);
                        return;
                    }
                    if (data.status === 'success') {
                        showToast('success', data.message || 'Absensi berhasil disimpan');
                        setTimeout(() => {
                            window.location.href = '/attendance';
                        }, 2000);
                    } else {
                        showToast('error', data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    showToast('error', 'Terjadi kesalahan saat menyimpan absensi: ' + error);
                });
            });
        }
    </script>
    @endpush

    @push('scripts')
    <script>
        // Tambahkan fungsi showToast di bawah semua script
        if (!window.showToast) {
        function showToast(type, message) {
            const container = document.getElementById('toastContainer');
            if (!container) return;
            const toast = document.createElement('div');
            toast.className = `toast alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show mt-2`;
            toast.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                    <div>${message}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            container.appendChild(toast);
            setTimeout(() => {
                if (container.contains(toast)) {
                    toast.classList.remove('show');
                    toast.classList.add('hide');
                    setTimeout(() => container.removeChild(toast), 300);
                }
            }, 2000);
        }
        }
    </script>
    @endpush
</x-app-layout> 
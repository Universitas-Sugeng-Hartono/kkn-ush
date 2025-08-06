@extends('layouts.mobile-app')

@section('content')
    <!-- Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h2>My Attendance</h2>
                <p class="date-info">{{ now()->format('l, d F Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-icon total">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $totalAttendance }}</div>
                    <div class="stat-label">Total</div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon approved">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $approvedAttendance }}</div>
                    <div class="stat-label">Disetujui</div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon pending">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $pendingAttendance }}</div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon rejected">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $rejectedAttendance }}</div>
                    <div class="stat-label">Ditolak</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Actions -->
    <div class="menu-section">
        <div class="section-header">
            <h3>Menu Absensi</h3>
        </div>
        <div class="menu-grid">
            <a href="{{ route('mobile.attendance.create') }}?jenis=masuk" 
               class="menu-item masuk {{ $hasMasuk ? 'disabled' : '' }}"
               @if($hasMasuk) onclick="return false;" @endif>
                <div class="menu-content">
                    <div class="menu-title">{{ $hasMasuk ? 'Sudah Absen Masuk' : 'Absen Masuk' }}</div>
                    <div class="menu-subtitle">{{ $hasMasuk ? 'Anda sudah absen hari ini' : 'Catat kehadiran pagi' }}</div>
                </div>
                <div class="menu-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>
            <a href="{{ route('mobile.attendance.create') }}?jenis=keluar" 
               class="menu-item keluar {{ $hasKeluar ? 'disabled' : '' }}"
               @if($hasKeluar) onclick="return false;" @endif>
                <div class="menu-content">
                    <div class="menu-title">{{ $hasKeluar ? 'Sudah Absen Keluar' : 'Absen Keluar' }}</div>
                    <div class="menu-subtitle">{{ $hasKeluar ? 'Anda sudah absen keluar hari ini' : 'Catat pulang sore' }}</div>
                </div>
                <div class="menu-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>
        </div>
    </div>

    <!-- Today's Attendance -->
    <div class="today-section">
        <div class="section-header">
            <h3>Today's Attendance</h3>
            <span class="today-date">{{ now()->format('d M Y') }}</span>
        </div>
        
        <div id="today-attendance-status">
            <!-- Status akan di-load via AJAX -->
        </div>
    </div>

    <!-- Recent Attendance -->
    <div class="recent-section">
        <div class="section-header">
            <h3>Recent Attendance</h3>
        </div>
        
        <div class="attendance-list">
            @forelse($recentAttendance as $attendance)
                <div class="attendance-item" onclick="window.location.href='{{ route('mobile.attendance.show', $attendance->id) }}'">
                    <div class="attendance-time">
                        <i class="fas fa-clock"></i>
                        <div class="time-details">
                            <div class="time-main">{{ $attendance->tanggal->format('d M Y') }}</div>
                            <div class="time-sub">
                                Masuk: {{ \Carbon\Carbon::parse($attendance->waktu_masuk)->format('H:i') }}
                                @if($attendance->waktu_keluar)
                                    | Keluar: {{ \Carbon\Carbon::parse($attendance->waktu_keluar)->format('H:i') }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="attendance-content">
                        <!-- <div class="attendance-type">
                            @if($attendance->waktu_keluar)
                                Masuk & Keluar
                            @else
                                Masuk
                            @endif
                        </div>
                        <div class="attendance-location">{{ $attendance->lokasi ?? 'Lokasi tidak tersedia' }}</div> -->
                    </div>
                    <div class="attendance-status {{ $attendance->status }}">
                        @if($attendance->status === 'approved')
                            <i class="fas fa-check"></i>
                        @elseif($attendance->status === 'rejected')
                            <i class="fas fa-times"></i>
                        @else
                            <i class="fas fa-clock"></i>
                        @endif
                    </div>
                    <div class="attendance-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-calendar-check"></i>
                    <p>No attendance records yet</p>
                    <a href="{{ route('mobile.attendance.create') }}" class="btn-mark-attendance">
                        <i class="fas fa-plus"></i>
                        Mark First Attendance
                    </a>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('styles')
<style>
    .stats-section {
        margin-bottom: 2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .stat-item {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f3f4;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
    }

    .stat-icon.total {
        background: linear-gradient(135deg, #0B1F3A 0%, #163057 100%);
    }

    .stat-icon.approved {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .stat-icon.pending {
        background: linear-gradient(135deg, #F2B705 0%, #d9a404 100%);
    }

    .stat-icon.rejected {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a202c;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.75rem;
        color: #6c757d;
        font-weight: 500;
        margin-top: 0.25rem;
    }

    .today-section {
        margin-bottom: 2rem;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .section-header h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1a202c;
        margin: 0;
    }

    .today-date {
        color: #6c757d;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .view-all {
        color: #0B1F3A;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .attendance-list {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f3f4;
    }

    .attendance-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #f1f3f4;
        transition: background-color 0.2s ease;
        cursor: pointer;
    }

    .attendance-item:last-child {
        border-bottom: none;
    }

    .attendance-item:hover {
        background: #f8f9fa;
    }

    .attendance-time {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        min-width: 120px;
    }

    .attendance-time i {
        color: #0B1F3A;
        font-size: 1rem;
    }

    .time-details {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .time-main {
        font-size: 0.875rem;
        font-weight: 600;
        color: #1a202c;
    }

    .time-sub {
        font-size: 0.75rem;
        color: #6c757d;
    }

    .attendance-content {
        flex: 1;
        margin: 0 1rem;
    }

    .attendance-title {
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
    }

    .attendance-type {
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
    }

    .attendance-date {
        font-size: 0.75rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }

    .attendance-location {
        font-size: 0.75rem;
        color: #6c757d;
    }

    .attendance-status {
        margin-right: 0.5rem;
    }

    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .attendance-status.approved .status-badge {
        background: #d4edda;
        color: #155724;
    }

    .attendance-status.rejected .status-badge {
        background: #f8d7da;
        color: #721c24;
    }

    .attendance-status.pending .status-badge {
        background: #fff3cd;
        color: #856404;
    }

    .attendance-status.approved {
        color: #28a745;
    }

    .attendance-status.rejected {
        color: #dc3545;
    }

    .attendance-status.pending {
        color: #F2B705;
    }

    .attendance-arrow {
        color: #cbd5e0;
        font-size: 0.875rem;
    }

    .no-attendance, .empty-state {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
    }

    .no-attendance i, .empty-state i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        opacity: 0.5;
    }

    .btn-mark-attendance {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #0B1F3A;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        margin-top: 1rem;
        transition: all 0.2s ease;
    }

    .btn-mark-attendance:hover {
        background: #163057;
        transform: translateY(-1px);
        text-decoration: none;
        color: white;
    }

    .btn-add {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(242, 183, 5, 0.2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-add:hover {
        background: rgba(242, 183, 5, 0.3);
        transform: scale(1.1);
        color: white;
    }

    .attendance-complete {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 12px;
        margin-top: 1rem;
    }

    .attendance-complete i {
        font-size: 2rem;
        color: #28a745;
        margin-bottom: 0.5rem;
    }

    .attendance-complete p {
        color: #6c757d;
        margin: 0;
        font-weight: 500;
    }

    .text-center {
        text-align: center;
    }

    .mt-3 {
        margin-top: 1rem;
    }

    /* New styles for menu section */
    .menu-section {
        margin-bottom: 2rem;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .menu-item {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f3f4;
        display: flex;
        align-items: center;
        gap: 1rem;
        text-decoration: none;
        color: #1a202c;
        transition: all 0.2s ease;
        text-align: center;
        justify-content: center;
    }

    .menu-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }

    .menu-item.masuk {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .menu-item.masuk::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        animation: shine-masuk 5s infinite;
    }

    .menu-item.masuk:hover {
        background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
    }

    .menu-item.keluar {
        background: linear-gradient(135deg, #F2B705 0%, #d9a404 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .menu-item.keluar::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        animation: shine-keluar 5s infinite;
        animation-delay: 2.5s;
    }

    .menu-item.keluar:hover {
        background: linear-gradient(135deg, #e0a800 0%, #c69500 100%);
    }

    @keyframes shine-masuk {
        0% { left: -100%; }
        20% { left: 100%; }
        100% { left: 100%; }
    }

    @keyframes shine-keluar {
        0% { left: -100%; }
        20% { left: 100%; }
        100% { left: 100%; }
    }

    .menu-content {
        flex: 1;
    }

    .menu-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .menu-subtitle {
        font-size: 0.75rem;
        opacity: 0.8;
    }

    .menu-arrow {
        opacity: 0.7;
        font-size: 0.875rem;
        transition: transform 0.2s ease;
    }

    .menu-item:hover .menu-arrow {
        transform: translateX(4px);
    }

    .menu-item.disabled {
        opacity: 0.6;
        cursor: not-allowed;
        pointer-events: none;
        background: #6c757d !important;
        color: #fff !important;
    }

    .menu-item.disabled::before {
        display: none;
    }

    .menu-item.disabled:hover {
        transform: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .menu-item.disabled .menu-arrow {
        opacity: 0.3;
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
        
        .stat-item {
            padding: 1rem;
        }
        
        .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
        
        .stat-number {
            font-size: 1.25rem;
        }
        
        .stat-label {
            font-size: 0.7rem;
        }

        .menu-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
        
        .menu-item {
            padding: 1.25rem;
        }
        
        .menu-title {
            font-size: 1rem;
        }
        
        .menu-subtitle {
            font-size: 0.7rem;
        }
        
        .attendance-item {
            padding: 1rem;
        }
        
        .attendance-time {
            min-width: 100px;
        }
        
        .time-main {
            font-size: 0.8rem;
        }
        
        .time-sub {
            font-size: 0.7rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Load status absensi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        loadAttendanceStatus();
        
        // Auto refresh setiap 30 detik untuk update status tombol
        setInterval(function() {
            location.reload();
        }, 30000);
    });

    // Load status absensi hari ini
    function loadAttendanceStatus() {
        fetch('{{ route("attendance.check-today") }}')
            .then(response => response.json())
            .then(data => {
                const statusDiv = document.getElementById('today-attendance-status');
                
                if (!data.has_masuk) {
                    // Belum absen masuk
                    statusDiv.innerHTML = `
                        <div class="no-attendance">
                            <i class="fas fa-calendar-day"></i>
                            <p>Belum absen masuk hari ini</p>
                            <a href="{{ route('mobile.attendance.create') }}?jenis=masuk" class="btn-mark-attendance">
                                <i class="fas fa-sign-in-alt"></i>
                                Absen Masuk
                            </a>
                        </div>
                    `;
                } else if (!data.has_keluar) {
                    // Sudah absen masuk, belum keluar
                    statusDiv.innerHTML = `
                        <div class="attendance-list">
                            <div class="attendance-item">
                                <div class="attendance-time">
                                    <i class="fas fa-clock"></i>
                                    ${new Date(data.attendance.waktu_masuk).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}
                                </div>
                                <div class="attendance-content">
                                    <div class="attendance-type">Masuk</div>
                                    <div class="attendance-location">Absensi masuk berhasil</div>
                                </div>
                                <div class="attendance-status approved">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('mobile.attendance.create') }}?jenis=keluar" class="btn-mark-attendance">
                                <i class="fas fa-sign-out-alt"></i>
                                Absen Keluar
                            </a>
                        </div>
                    `;
                } else {
                    // Sudah absen masuk dan keluar
                    statusDiv.innerHTML = `
                        <div class="attendance-list">
                            <div class="attendance-item">
                                <div class="attendance-time">
                                    <i class="fas fa-clock"></i>
                                    ${new Date(data.attendance.waktu_masuk).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}
                                </div>
                                <div class="attendance-content">
                                    <div class="attendance-type">Masuk</div>
                                    <div class="attendance-location">Absensi masuk berhasil</div>
                                </div>
                                <div class="attendance-status approved">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                            <div class="attendance-item">
                                <div class="attendance-time">
                                    <i class="fas fa-clock"></i>
                                    ${new Date(data.attendance.waktu_keluar).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}
                                </div>
                                <div class="attendance-content">
                                    <div class="attendance-type">Keluar</div>
                                    <div class="attendance-location">Absensi keluar berhasil</div>
                                </div>
                                <div class="attendance-status approved">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <div class="attendance-complete">
                                <i class="fas fa-check-circle"></i>
                                <p>Absensi hari ini selesai</p>
                            </div>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading attendance status:', error);
            });
    }
</script>
@endpush 
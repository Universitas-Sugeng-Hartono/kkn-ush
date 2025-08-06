@extends('layouts.mobile-app')

@section('content')
    <!-- Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h2>Detail Attendance</h2>
                <p class="date-info">{{ $attendance->created_at->format('l, d F Y') }}</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('mobile.attendance') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="status-section">
        <div class="status-badge {{ $attendance->status }}">
            @if($attendance->status === 'approved')
                <i class="fas fa-check-circle"></i>
                <span>Disetujui</span>
            @elseif($attendance->status === 'rejected')
                <i class="fas fa-times-circle"></i>
                <span>Ditolak</span>
            @else
                <i class="fas fa-clock"></i>
                <span>Menunggu Review</span>
            @endif
        </div>
    </div>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="summary-card">
            <div class="summary-header">
                <i class="fas fa-calendar-check"></i>
                <span>Ringkasan Absensi</span>
            </div>
            <div class="summary-content">
                <div class="summary-item">
                    <span class="summary-label">Tanggal:</span>
                    <span class="summary-value">{{ $attendance->tanggal->format('d F Y') }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Status:</span>
                    <span class="summary-value">
                        @if($attendance->waktu_keluar)
                            <span class="badge-complete">Lengkap (Masuk & Keluar)</span>
                        @else
                            <span class="badge-partial">Masuk Saja</span>
                        @endif
                    </span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Durasi:</span>
                    <span class="summary-value">
                        @if($attendance->waktu_keluar)
                            @php
                                $masuk = \Carbon\Carbon::parse($attendance->waktu_masuk);
                                $keluar = \Carbon\Carbon::parse($attendance->waktu_keluar);
                                $durasi = $masuk->diff($keluar);
                            @endphp
                            {{ $durasi->format('%H jam %i menit') }}
                        @else
                            -
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Basic Information -->
    <div class="info-section">
        <h3>Informasi Absensi</h3>
        
        <div class="info-item">
            <div class="info-label">
                <i class="fas fa-calendar"></i>
                <span>Tanggal</span>
            </div>
            <div class="info-value">{{ $attendance->tanggal->format('d F Y') }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">
                <i class="fas fa-sign-in-alt"></i>
                <span>Waktu Masuk</span>
            </div>
            <div class="info-value">{{ \Carbon\Carbon::parse($attendance->waktu_masuk)->format('H:i:s') }}</div>
        </div>

        @if($attendance->waktu_keluar)
        <div class="info-item">
            <div class="info-label">
                <i class="fas fa-sign-out-alt"></i>
                <span>Waktu Keluar</span>
            </div>
            <div class="info-value">{{ \Carbon\Carbon::parse($attendance->waktu_keluar)->format('H:i:s') }}</div>
        </div>
        @endif

        <div class="info-item">
            <div class="info-label">
                <i class="fas fa-map-marker-alt"></i>
                <span>Lokasi Masuk</span>
            </div>
            <div class="info-value" id="locationDisplay">
                @if($attendance->lokasi)
                    {{ $attendance->lokasi }}
                @else
                    <span class="loading-location">Mengambil informasi lokasi...</span>
                @endif
            </div>
        </div>

        @if($attendance->waktu_keluar && $attendance->lokasi_keluar)
        <div class="info-item">
            <div class="info-label">
                <i class="fas fa-map-marker-alt"></i>
                <span>Lokasi Keluar</span>
            </div>
            <div class="info-value" id="locationDisplayKeluar">
                {{ $attendance->lokasi_keluar }}
            </div>
        </div>
        @endif

        @if($attendance->keterangan)
        <div class="info-item">
            <div class="info-label">
                <i class="fas fa-align-left"></i>
                <span>Keterangan</span>
            </div>
            <div class="info-value description">{{ $attendance->keterangan }}</div>
        </div>
        @endif
    </div>

    <!-- Location Details -->
    <div class="info-section">
        <h3>Detail Lokasi Masuk</h3>
        
        <div class="info-item">
            <div class="info-label">
                <i class="fas fa-globe"></i>
                <span>Koordinat Masuk</span>
            </div>
            <div class="info-value">{{ $attendance->latitude }}, {{ $attendance->longitude }}</div>
        </div>

        <div class="map-container">
            <div id="map" class="map"></div>
            <div class="map-actions">
                <button type="button" class="btn-map-action" onclick="openInMaps({{ $attendance->latitude }}, {{ $attendance->longitude }})">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Buka di Maps</span>
                </button>
            </div>
        </div>
    </div>

    @if($attendance->waktu_keluar)
    <!-- Location Details Keluar -->
    <div class="info-section">
        <h3>Detail Lokasi Keluar</h3>
        
        <div class="info-item">
            <div class="info-label">
                <i class="fas fa-globe"></i>
                <span>Koordinat Keluar</span>
            </div>
            <div class="info-value">{{ $attendance->latitude_keluar }}, {{ $attendance->longitude_keluar }}</div>
        </div>

        <div class="map-container">
            <div id="mapKeluar" class="map"></div>
            <div class="map-actions">
                <button type="button" class="btn-map-action" onclick="openInMaps({{ $attendance->latitude_keluar }}, {{ $attendance->longitude_keluar }})">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Buka di Maps</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Photo -->
    <div class="info-section">
        <h3>Foto Bukti</h3>
        
        @if($attendance->foto_kegiatan)
        <div class="photo-section">
            <h4 class="photo-title">
                <i class="fas fa-sign-in-alt"></i>
                Foto Absen Masuk
            </h4>
            <div class="photo-container">
                <img src="{{ asset('storage/' . $attendance->foto_kegiatan) }}" alt="Foto Absen Masuk" onclick="openPhotoModal('{{ asset('storage/' . $attendance->foto_kegiatan) }}')">
            </div>
        </div>
        @endif

        @if($attendance->waktu_keluar && $attendance->foto_keluar)
        <div class="photo-section">
            <h4 class="photo-title">
                <i class="fas fa-sign-out-alt"></i>
                Foto Absen Keluar
            </h4>
            <div class="photo-container">
                <img src="{{ asset('storage/' . $attendance->foto_keluar) }}" alt="Foto Absen Keluar" onclick="openPhotoModal('{{ asset('storage/' . $attendance->foto_keluar) }}')">
            </div>
        </div>
        @endif
    </div>

    <!-- DPL Comments -->
    @if($attendance->komentar)
    <div class="info-section">
        <h3>Komentar DPL</h3>
        <div class="comment-box">
            <div class="comment-content">
                {{ $attendance->komentar }}
            </div>
            <div class="comment-meta">
                <i class="fas fa-user-tie"></i>
                <span>DPL</span>
                <span class="comment-date">{{ $attendance->updated_at->format('d M Y, H:i') }}</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Actions -->
    @if($attendance->status === 'pending')
    <div class="actions-section">
        <div class="action-buttons">
            <button type="button" class="btn-action delete" onclick="deleteAttendance({{ $attendance->id }})">
                <i class="fas fa-trash"></i>
                <span>Hapus</span>
            </button>
        </div>
    </div>
    @endif

    <!-- Photo Modal -->
    <div id="photoModal" class="photo-modal" onclick="closePhotoModal()">
        <div class="modal-content">
            <img id="modalImage" src="" alt="Attendance photo">
            <button class="modal-close" onclick="closePhotoModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/leaflet.min.css') }}" />
<style>
    .status-section {
        margin-bottom: 1rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .status-badge.approved {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.rejected {
        background: #f8d7da;
        color: #721c24;
    }

    .status-badge.pending {
        background: #fff3cd;
        color: #856404;
    }

    .info-section {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f3f4;
    }

    .info-section h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f3f4;
    }

    .info-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .info-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: #6c757d;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .info-value {
        color: #1a202c;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .info-value.description {
        white-space: pre-wrap;
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid #0B1F3A;
    }

    .loading-location {
        color: #6c757d;
        font-style: italic;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .loading-location::before {
        content: '';
        width: 12px;
        height: 12px;
        border: 2px solid #e2e8f0;
        border-top: 2px solid #0B1F3A;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .map-container {
        margin-top: 1rem;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        position: relative; /* Added for positioning map actions */
    }

    .map {
        width: 100%;
        height: 300px;
        border-radius: 12px;
    }

    .map-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 300px;
        background: #f8f9fa;
        color: #6c757d;
        font-size: 0.875rem;
    }

    .map-loading i {
        margin-right: 0.5rem;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .map-actions {
        position: absolute;
        top: 0;
        right: 0;
        background: rgba(255, 255, 255, 0.8);
        border-bottom-left-radius: 12px;
        padding: 0.5rem 0.75rem;
        display: flex;
        gap: 0.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-map-action {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #0B1F3A;
        color: white;
    }

    .btn-map-action:hover {
        background: #1a202c;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .photo-container {
        text-align: center;
    }

    .photo-container img {
        max-width: 100%;
        max-height: 300px;
        border-radius: 12px;
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .photo-container img:hover {
        transform: scale(1.02);
    }

    .comment-box {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1rem;
        border-left: 4px solid #0B1F3A;
    }

    .comment-content {
        color: #1a202c;
        font-size: 0.875rem;
        line-height: 1.5;
        margin-bottom: 0.75rem;
    }

    .comment-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        color: #6c757d;
    }

    .comment-date {
        margin-left: auto;
    }

    .actions-section {
        position: sticky;
        bottom: 0;
        background: white;
        padding: 1rem;
        margin: 1rem -1rem -1rem -1rem;
        border-top: 1px solid #f1f3f4;
        box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.1);
    }

    .action-buttons {
        display: flex;
        justify-content: center;
    }

    .btn-action {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-action.delete {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }

    .btn-action.delete:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }

    .btn-back {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
    }

    /* Photo Modal */
    .photo-modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        backdrop-filter: blur(5px);
    }

    .modal-content {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .modal-content img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 12px;
    }

    .modal-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.2rem;
        transition: all 0.2s ease;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .photo-section {
        margin-bottom: 1.5rem;
    }

    .photo-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .photo-title i {
        color: #0B1F3A;
    }

    .summary-section {
        margin-bottom: 1.5rem;
        background: #f8f9fa;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
    }

    .summary-card {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f3f4;
    }

    .summary-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.75rem;
    }

    .summary-header i {
        color: #0B1F3A;
        font-size: 1.125rem;
    }

    .summary-content {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .summary-label {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.875rem;
    }

    .summary-value {
        font-weight: 600;
        color: #1a202c;
        font-size: 0.875rem;
    }

    .badge-complete {
        background: #d4edda;
        color: #155724;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .badge-partial {
        background: #fff3cd;
        color: #856404;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    @media (max-width: 480px) {
        .info-section {
            padding: 1rem;
        }
        
        .summary-section {
            margin: 1rem;
            padding: 1rem;
        }
        
        .summary-card {
            padding: 0.75rem;
        }
        
        .summary-header {
            font-size: 0.875rem;
        }
        
        .summary-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }
        
        .summary-label,
        .summary-value {
            font-size: 0.8rem;
        }
        
        .info-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .info-label {
            font-size: 0.875rem;
        }
        
        .info-value {
            font-size: 0.875rem;
        }
        
        .map {
            height: 250px;
        }
        
        .map-actions {
            padding: 0.25rem 0.5rem;
        }
        
        .btn-map-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
        }
        
        .photo-container {
            grid-template-columns: 1fr;
        }
        
        .photo-item img {
            height: 200px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/js/leaflet.min.js') }}"></script>
<script>
function openPhotoModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('photoModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closePhotoModal() {
    document.getElementById('photoModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function deleteAttendance(attendanceId) {
    if (confirm('Apakah Anda yakin ingin menghapus absensi ini?')) {
        fetch(`/attendance/${attendanceId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("mobile.attendance") }}';
            } else {
                alert('Gagal menghapus absensi');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus absensi');
        });
    }
}

function openInMaps(latitude, longitude) {
    const baseUrl = 'https://www.google.com/maps/search/?api=1';
    const url = `${baseUrl}&query=${latitude},${longitude}`;
    window.open(url, '_blank');
}

// Close modal when clicking outside
document.getElementById('photoModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePhotoModal();
    }
});

// Close modal with escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePhotoModal();
    }
});

// Initialize map and location
document.addEventListener('DOMContentLoaded', function() {
    const map = document.getElementById('map');
    const mapKeluar = document.getElementById('mapKeluar');
    const locationDisplay = document.getElementById('locationDisplay');
    
    // Initialize map for masuk
    if (map) {
        const latitude = {{ $attendance->latitude }};
        const longitude = {{ $attendance->longitude }};
        
        initializeMap(map, latitude, longitude, 'Lokasi Absensi Masuk');
    }
    
    // Initialize map for keluar
    if (mapKeluar) {
        const latitudeKeluar = {{ $attendance->latitude_keluar ?? 0 }};
        const longitudeKeluar = {{ $attendance->longitude_keluar ?? 0 }};
        
        if (latitudeKeluar && longitudeKeluar) {
            initializeMap(mapKeluar, latitudeKeluar, longitudeKeluar, 'Lokasi Absensi Keluar');
        }
    }
    
    // Get location information if not available
    if (locationDisplay && locationDisplay.querySelector('.loading-location')) {
        const latitude = {{ $attendance->latitude }};
        const longitude = {{ $attendance->longitude }};
        
        // Get address from coordinates using Nominatim
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}&zoom=18&addressdetails=1`)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    locationDisplay.innerHTML = data.display_name;
                } else {
                    locationDisplay.innerHTML = '<span style="color: #6c757d;">Lokasi tidak dapat diidentifikasi</span>';
                }
            })
            .catch(error => {
                console.error('Error getting location:', error);
                locationDisplay.innerHTML = '<span style="color: #6c757d;">Gagal mengambil informasi lokasi</span>';
            });
    }
});

function initializeMap(mapElement, latitude, longitude, title) {
    // Show loading state
    mapElement.innerHTML = `
        <div class="map-loading">
            <i class="fas fa-spinner"></i>
            <span>Memuat peta...</span>
        </div>
    `;
    
    // Initialize Leaflet map
    setTimeout(() => {
        try {
            const leafletMap = L.map(mapElement.id).setView([latitude, longitude], 16);
            
            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(leafletMap);
            
            // Add marker
            const marker = L.marker([latitude, longitude]).addTo(leafletMap);
            
            // Add popup with coordinates
            marker.bindPopup(`
                <div style="text-align: center;">
                    <i class="fas fa-map-marker-alt" style="color: #0B1F3A; font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div style="font-weight: 600; color: #1a202c; margin-bottom: 0.25rem;">${title}</div>
                    <div style="font-size: 0.75rem; color: #6c757d;">
                        ${latitude.toFixed(6)}, ${longitude.toFixed(6)}
                    </div>
                </div>
            `);
            
            // Fit bounds to marker
            leafletMap.fitBounds(marker.getLatLng().toBounds(100));
            
        } catch (error) {
            console.error('Error initializing map:', error);
            mapElement.innerHTML = `
                <div class="map-loading">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Gagal memuat peta</span>
                </div>
            `;
        }
    }, 500);
}
</script>
@endpush 
@extends('layouts.mobile-app')

@section('content')
    <!-- Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h2 id="pageTitle">Mark Attendance</h2>
                <p class="date-info">{{ now()->format('l, d F Y') }}</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('mobile.attendance') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Current Time Display -->
    <div class="time-display">
        <div class="current-time" id="currentTime">
            <i class="fas fa-clock"></i>
            <span id="timeText">{{ now()->format('H:i:s') }}</span>
        </div>
        <div class="current-date">{{ now()->format('d F Y') }}</div>
    </div>

    <!-- Form -->
    <form id="attendanceForm" action="{{ route('attendance.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Hidden fields for automatic data -->
        <input type="hidden" name="tanggal" value="{{ now()->format('Y-m-d') }}">
        <input type="hidden" name="waktu_masuk" value="{{ now()->format('H:i') }}">
        <input type="hidden" name="jenis_absen" id="jenis_absen" value="{{ request('jenis', 'masuk') }}">
        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
        <input type="hidden" id="lokasi" name="lokasi" value="{{ old('lokasi') }}">
        <input type="hidden" id="foto" name="foto" value="{{ old('foto') }}">
        
        <div class="form-section">
            <h3>Attendance Information</h3>
            
            <div class="info-display">
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-calendar"></i>
                        <span>Tanggal</span>
                    </div>
                    <div class="info-value">{{ now()->format('d/m/Y') }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-clock"></i>
                        <span>Waktu</span>
                    </div>
                    <div class="info-value" id="timeDisplay">{{ now()->format('H:i') }}</div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>Location</h3>
            <p class="form-hint">Lokasi akan terdeteksi otomatis saat Anda menekan tombol "Get Location"</p>
            
            <div class="location-display" id="locationDisplay" style="display: none;">
                <div class="location-info">
                    <i class="fas fa-map-marker-alt"></i>
                    <div class="location-details">
                        <div class="location-address" id="locationAddress">Loading location...</div>
                    </div>
                </div>
            </div>
            
            <button type="button" class="btn-location" id="locationBtn">
                <i class="fas fa-location-arrow"></i>
                <span>Get Current Location</span>
            </button>
            
            @error('latitude')
                <span class="error-message">{{ $message }}</span>
            @enderror
            @error('longitude')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-section">
            <h3>Photo</h3>
            <p class="form-hint">Ambil foto untuk bukti kehadiran</p>
            
            <div class="photo-capture-area p-0 overflow-hidden" id="photoCaptureArea" style="position: relative; aspect-ratio: 3/4; background: #000; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                <video id="camera" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover; display: none; border-radius: 12px;"></video>
                <canvas id="canvas" style="display: none; width: 100%; height: 100%; object-fit: cover; border-radius: 12px;"></canvas>
                <div class="capture-placeholder" id="capturePlaceholder" style="position: absolute; z-index: 2; text-align: center;">
                    <i class="fas fa-camera text-white mb-2" style="font-size: 2rem;"></i>
                    <p class="text-white m-0" style="color: white !important;">Memuat kamera...</p>
                </div>
                <input type="hidden" id="photo">
            </div>
            
            <div class="d-flex gap-2" style="display: flex; gap: 0.5rem;">
                <button type="button" class="w-100" id="captureBtn" style="display: none; background: #0B1F3A; color: white; border: none; padding: 0.75rem; border-radius: 8px; flex: 1; font-weight: 600;">
                    <i class="fas fa-camera"></i> Ambil Foto
                </button>
                <button type="button" class="w-100" id="retakeBtn" style="display: none; background: #6c757d; color: white; border: none; padding: 0.75rem; border-radius: 8px; flex: 1; font-weight: 600;">
                    <i class="fas fa-redo"></i> Ulangi
                </button>
            </div>
            
            @error('foto')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit" id="submitBtn" disabled>
                <div class="icon-container">
                    <i class="fas fa-check" id="submitIcon"></i>
                </div>
                <span id="submitText">Mark Attendance</span>
            </button>
        </div>
    </form>

    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container"></div>
@endsection

@push('styles')
<style>
    .time-display {
        background: linear-gradient(135deg, #0B1F3A 0%, #163057 100%);
        color: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        text-align: center;
        box-shadow: 0 4px 12px rgba(11, 31, 58, 0.3);
    }

    .current-time {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .current-date {
        font-size: 1rem;
        opacity: 0.9;
    }

    .form-section {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f3f4;
    }

    .form-section h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background: #ffffff;
    }

    .form-control:focus {
        outline: none;
        border-color: #0B1F3A;
        box-shadow: 0 0 0 3px rgba(11, 31, 58, 0.1);
    }

    .error-message {
        color: #dc3545;
        font-size: 0.75rem;
        margin-top: 0.25rem;
        display: block;
    }

    .info-display {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 12px;
        border: 1px solid #e9ecef;
    }

    .info-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: #6c757d;
        font-size: 0.875rem;
    }

    .info-label i {
        color: #0B1F3A;
        width: 16px;
    }

    .info-value {
        font-weight: 600;
        color: #1a202c;
        font-size: 0.875rem;
    }

    .form-hint {
        color: #6c757d;
        font-size: 0.75rem;
        margin-bottom: 1rem;
    }

    .location-display {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 2px solid #e2e8f0;
    }

    .location-info {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .location-info i {
        color: #0B1F3A;
        font-size: 1.25rem;
        margin-top: 0.25rem;
    }

    .location-details {
        flex: 1;
    }

    .location-address {
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
    }

    .location-coords {
        font-size: 0.75rem;
        color: #6c757d;
    }

    .btn-location {
        width: 100%;
        background: linear-gradient(135deg, #F2B705 0%, #d9a404 100%);
        color: #0B1F3A;
        border: none;
        border-radius: 12px;
        padding: 1rem;
        font-weight: 600;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-location:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(242, 183, 5, 0.3);
    }

    .btn-location:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }



    .photo-capture-area {
        border: 2px dashed #cbd5e0;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #f8f9fa;
    }

    .photo-capture-area:hover {
        border-color: #0B1F3A;
        background: #f1f3f4;
    }

    .capture-placeholder i {
        font-size: 2rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }

    .capture-placeholder p {
        color: #6c757d;
        margin: 0;
        font-size: 0.875rem;
    }

    .photo-preview {
        margin-top: 1rem;
    }

    .photo-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        aspect-ratio: 1;
        max-width: 200px;
        margin: 0 auto;
    }

    .photo-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-remove {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .form-actions {
        position: sticky;
        bottom: 0;
        background: white;
        padding: 1rem;
        margin: 1rem -1rem -1rem -1rem;
        border-top: 1px solid #f1f3f4;
        box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-submit {
        width: 100% !important;
        background: linear-gradient(135deg, #0B1F3A 0%, #163057 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 1rem;
        font-weight: 600;
        font-size: 1rem;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
        min-height: 56px !important; /* Memastikan tinggi tombol konsisten */
        flex-direction: row !important;
        box-sizing: border-box !important;
    }

    .btn-submit:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(11, 31, 58, 0.3);
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
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

    .icon-container {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }

    .icon-spinner {
        display: inline-block;
        width: 18px;
        height: 18px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
    }

    .loading {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
    }

    /* Memastikan icon dan text tetap konsisten */
    .btn-submit .icon-container {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0; /* Mencegah icon container mengecil */
    }

    .btn-submit i {
        font-size: 1rem;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0; /* Mencegah icon mengecil */
    }

    .btn-submit span {
        font-size: 1rem;
        font-weight: 600;
        white-space: nowrap;
        flex-shrink: 0; /* Mencegah text mengecil */
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Toast Styles */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 300px;
    }

    .toast {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border-left: 4px solid;
        animation: slideIn 0.3s ease-out;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .toast.success {
        border-left-color: #28a745;
    }

    .toast.error {
        border-left-color: #dc3545;
    }

    .toast-icon {
        font-size: 1.25rem;
    }

    .toast.success .toast-icon {
        color: #28a745;
    }

    .toast.error .toast-icon {
        color: #dc3545;
    }

    .toast-message {
        flex: 1;
        font-size: 0.875rem;
        color: #1a202c;
        font-weight: 500;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
</style>
@endpush

@push('scripts')
<script>
function compressImage(file, callback) {
    console.log('Compressing image:', file.name, file.size);
    const maxWidth = 800;
    const quality = 0.5;
    const reader = new FileReader();
    reader.onload = function(event) {
        console.log('File read, creating image');
        const img = new Image();
        img.onload = function() {
            console.log('Image loaded, dimensions:', img.width, 'x', img.height);
            let scale = Math.min(maxWidth / img.width, 1);
            let canvas = document.createElement('canvas');
            canvas.width = img.width * scale;
            canvas.height = img.height * scale;
            let ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            canvas.toBlob(function(blob) {
                console.log('Image compressed, size:', blob.size);
                callback(new File([blob], file.name, {type: 'image/jpeg'}));
            }, 'image/jpeg', quality);
        };
        img.src = event.target.result;
    };
    reader.readAsDataURL(file);
}

let currentLocation = null;
let photoTaken = false;
let isSubmitting = false;

function getLocation() {
    console.log('Getting location...');
    const locationBtn = document.getElementById('locationBtn');
    const locationDisplay = document.getElementById('locationDisplay');
    const locationAddress = document.getElementById('locationAddress');
    
    locationBtn.innerHTML = '<div class="loading"></div><span>Getting location...</span>';
    locationBtn.disabled = true;

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                console.log('Geolocation success');
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                
                console.log('Coordinates:', { latitude, longitude });
                currentLocation = { latitude, longitude };
                
                // Update hidden inputs
                document.getElementById('latitude').value = latitude;
                document.getElementById('longitude').value = longitude;
                console.log('Hidden inputs updated with coordinates');
                
                // Get address from coordinates
                getAddressFromCoords(latitude, longitude, locationAddress);
                
                // Show location display
                locationDisplay.style.display = 'block';
                console.log('Location display shown');
                
                // Reset button
                locationBtn.innerHTML = '<i class="fas fa-check"></i><span>Location Captured</span>';
                locationBtn.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';
                locationBtn.style.color = 'white';
                console.log('Location button updated');
                
                checkFormValidity();
            },
            function(error) {
                console.error('Geolocation error:', error);
                console.log('Using default location due to GPS failure');
                
                // Use default location when GPS fails
                useDefaultLocation();
                
                // Reset button
                locationBtn.innerHTML = '<i class="fas fa-check"></i><span>Location Captured</span>';
                locationBtn.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';
                locationBtn.style.color = 'white';
                locationBtn.disabled = false;
                console.log('Location button reset after error');
                
                let errorMessage = 'GPS gagal, menggunakan lokasi default KKN';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = 'Izin lokasi ditolak. Menggunakan lokasi default KKN.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = 'GPS tidak tersedia. Menggunakan lokasi default KKN.';
                        break;
                    case error.TIMEOUT:
                        errorMessage = 'GPS timeout. Menggunakan lokasi default KKN.';
                        break;
                    default:
                        errorMessage = 'GPS error. Menggunakan lokasi default KKN.';
                }
                
                showToast('info', errorMessage);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 300000 // 5 minutes
            }
        );
    } else {
        console.log('Geolocation not supported, using default location');
        useDefaultLocation();
        showToast('info', 'Geolocation tidak didukung. Menggunakan lokasi default KKN.');
        locationBtn.innerHTML = '<i class="fas fa-check"></i><span>Location Captured</span>';
        locationBtn.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';
        locationBtn.style.color = 'white';
        locationBtn.disabled = false;
        console.log('Geolocation not supported, using default location');
    }
}

// Function to use default KKN location
function useDefaultLocation() {
    console.log('Using default KKN location');
    
    // Default coordinates for KKN location (Sukoharjo, Central Java)
    const defaultLat = -7.6833;
    const defaultLng = 110.8167;
    
    // Update hidden inputs
    document.getElementById('latitude').value = defaultLat;
    document.getElementById('longitude').value = defaultLng;
    
    // Update location display
    const locationDisplay = document.getElementById('locationDisplay');
    const locationAddress = document.getElementById('locationAddress');
    
    locationAddress.textContent = 'Lokasi KKN Default: Jalan Insinyur Soekarno, Grogol, Sukoharjo, Central Java, Indonesia';
    
    // Update hidden location input
    document.getElementById('lokasi').value = 'Lokasi KKN Default: Jalan Insinyur Soekarno, Grogol, Sukoharjo, Central Java, Indonesia';
    
    // Store current location
    currentLocation = { latitude: defaultLat, longitude: defaultLng };
    console.log('Default location stored:', currentLocation);
    
    // Show location display
    locationDisplay.style.display = 'block';
    
    checkFormValidity();
}

document.addEventListener('DOMContentLoaded', function() {
        // Check CSRF token availability
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            console.error('CSRF token not found');
            showToast('error', 'CSRF token tidak ditemukan. Silakan refresh halaman.');
        }
        
        // Check required elements
        const requiredElements = [
            'jenis_absen',
            'pageTitle',
            'timeText',
            'timeDisplay',
            'latitude',
            'longitude',
            'lokasi',
            'foto',
            'photoCaptureArea',
            'photo',
            'photoPreview',
            'capturePlaceholder',
            'submitBtn',
            'submitText',
            'locationBtn',
            'locationDisplay',
            'locationAddress'
        ];
        
        const missingElements = requiredElements.filter(id => !document.getElementById(id));
        if (missingElements.length > 0) {
            console.error('Missing elements:', missingElements);
            showToast('error', 'Beberapa elemen tidak ditemukan. Silakan refresh halaman.');
        }
        
        // Check form availability
        const attendanceForm = document.getElementById('attendanceForm');
        if (!attendanceForm) {
            console.error('Form not found');
            showToast('error', 'Form tidak ditemukan. Silakan refresh halaman.');
            return;
        }
        
        // Check form action
        if (!attendanceForm.action) {
            console.error('Form action not found');
            showToast('error', 'Form action tidak ditemukan. Silakan refresh halaman.');
            return;
        }
        
        console.log('All required elements found, initializing attendance form...');
        
    // Set page title and jenis absen based on URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const jenis = urlParams.get('jenis') || 'masuk';
    
        console.log('Setting jenis absen:', jenis);
    document.getElementById('jenis_absen').value = jenis;
    
    if (jenis === 'masuk') {
        document.getElementById('pageTitle').textContent = 'Absen Masuk';
        document.getElementById('submitText').textContent = 'Absen Masuk';
    } else {
        document.getElementById('pageTitle').textContent = 'Absen Keluar';
        document.getElementById('submitText').textContent = 'Absen Keluar';
    }
    
    // Update time every second
        console.log('Setting up time update interval');
    setInterval(updateTime, 1000);
    
    // Get location automatically
        console.log('Getting location automatically');
    getLocation();
    
    // Location button functionality
        console.log('Setting up location button functionality');
            const locationBtn = document.getElementById('locationBtn');
        locationBtn.addEventListener('click', function() {
            console.log('Location button clicked');
            getLocation();
        });


    // Camera functionality
    console.log('Setting up camera functionality');
    const video = document.getElementById('camera');
    const canvas = document.getElementById('canvas');
    const captureBtn = document.getElementById('captureBtn');
    const retakeBtn = document.getElementById('retakeBtn');
    const placeholder = document.getElementById('capturePlaceholder');
    let stream = null;

    function startCamera() {
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            placeholder.querySelector('p').textContent = "Memuat kamera...";
            navigator.mediaDevices.getUserMedia({ 
                video: { 
                    facingMode: "user"
                } 
            })
            .then(s => {
                stream = s;
                video.srcObject = stream;
                video.style.display = 'block';
                placeholder.style.display = 'none';
                captureBtn.style.display = 'block';
                retakeBtn.style.display = 'none';
                canvas.style.display = 'none';
                document.getElementById('photoCaptureArea').style.cursor = 'default';
                video.play();
            })
            .catch(err => {
                console.error("Error accessing camera: ", err);
                placeholder.innerHTML = '<i class="fas fa-exclamation-triangle text-warning mb-2" style="font-size: 2rem;"></i><p class="text-white m-0" style="color: white !important;">Kamera tidak dapat diakses</p>';
                showToast('error', 'Gagal mengakses kamera. Pastikan izin kamera telah diberikan.');
            });
        } else {
            placeholder.innerHTML = '<i class="fas fa-times-circle text-danger mb-2" style="font-size: 2rem;"></i><p class="text-white m-0" style="color: white !important;">Browser tidak mendukung kamera</p>';
            showToast('error', 'Browser Anda tidak mendukung akses kamera.');
        }
    }

    startCamera();

    captureBtn.addEventListener('click', function() {
        const context = canvas.getContext('2d');
        if (!video.videoWidth || !video.videoHeight) {
            showToast('error', 'Kamera belum siap. Mohon tunggu beberapa detik.');
            return;
        }

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const dataUrl = canvas.toDataURL('image/jpeg', 0.5);
        document.getElementById('foto').value = dataUrl;
        document.getElementById('photo').value = dataUrl;
        
        video.style.display = 'none';
        canvas.style.display = 'block';
        captureBtn.style.display = 'none';
        retakeBtn.style.display = 'block';
        
        photoTaken = true;
        checkFormValidity();
    });

    retakeBtn.addEventListener('click', function() {
        document.getElementById('foto').value = '';
        document.getElementById('photo').value = '';
        photoTaken = false;
        checkFormValidity();
        
        canvas.style.display = 'none';
        video.style.display = 'block';
        captureBtn.style.display = 'block';
        retakeBtn.style.display = 'none';
    });
    
    // Form submission
        console.log('Setting up form submission handler');
    attendanceForm.addEventListener('submit', function(e) {
            console.log('Form submitted');
        e.preventDefault();
        
        if (isSubmitting) {
            console.log('Already submitting, ignoring request');
            return;
        }
        
        if (!currentLocation) {
            console.log('No current location');
            showToast('error', 'Harap dapatkan lokasi terlebih dahulu');
            return;
        }
        
        // Validate location coordinates
        const lat = parseFloat(currentLocation.latitude);
        const lng = parseFloat(currentLocation.longitude);
        console.log('Location coordinates:', { lat, lng });
        if (isNaN(lat) || isNaN(lng) || lat === 0 || lng === 0) {
            console.log('Invalid location coordinates');
            showToast('error', 'Koordinat lokasi tidak valid. Silakan coba lagi.');
            return;
        }
        
        if (!currentLocation || !currentLocation.latitude || !currentLocation.longitude) {
            console.log('Invalid current location data');
            showToast('error', 'Data lokasi tidak valid. Silakan coba lagi.');
            return;
        }
        
        if (!photoTaken) {
            console.log('No photo taken');
            showToast('error', 'Harap ambil foto terlebih dahulu');
            return;
        }
        
        // Validate photo data
        const photoInput = document.querySelector('input[name="foto"]');
        if (!photoInput || !photoInput.value) {
            console.log('No photo input or value');
            showToast('error', 'Data foto tidak ditemukan. Silakan ambil foto lagi.');
            return;
        }
        
        const fotoValue = document.querySelector('input[name="foto"]')?.value;
        console.log('Photo value length:', fotoValue?.length);
        if (!fotoValue || fotoValue === 'data:,' || fotoValue.length < 1000 || !fotoValue.startsWith('data:image/')) {
            console.log('Invalid photo value');
            showToast('error', 'Foto tidak valid. Silakan ambil foto lagi.');
            return;
        }
        
        // Validate photo max size
        if (fotoValue.length > 30000000) { // 20MB dalam base64
            console.log('Photo too large');
            showToast('error', 'Foto terlalu besar. Maksimal 20MB.');
            return;
        }
        

        
        // Show loading state
        console.log('Setting loading state');
        isSubmitting = true;
        const submitBtn = document.getElementById('submitBtn');
        const submitIcon = document.getElementById('submitIcon');
        const submitText = document.getElementById('submitText');
        const originalIconClass = 'fas fa-check';

        // Saat loading
        submitIcon.className = 'icon-spinner'; // spinner khusus untuk icon
        submitText.textContent = 'Menyimpan...';
        submitBtn.disabled = true;
        
        // Update waktu sebelum submit
        const now = new Date();
        const waktuString = now.toTimeString().slice(0, 5);
        console.log('Updated waktu_masuk:', waktuString);
        document.querySelector('input[name="waktu_masuk"]').value = waktuString;
        
        // Prepare data for submission
        console.log('Preparing form data for submission');
        const formData = new FormData();
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            console.log('No CSRF token found');
            showToast('error', 'CSRF token tidak ditemukan. Silakan refresh halaman.');
            isSubmitting = false;
            submitIcon.className = originalIconClass;
            submitText.textContent = jenisAbsen === 'masuk' ? 'Absen Masuk' : 'Absen Keluar';
            submitBtn.disabled = false;
            return;
        }
        formData.append('_token', csrfToken);
        const tanggal = document.querySelector('input[name="tanggal"]')?.value;
        const waktuMasuk = document.querySelector('input[name="waktu_masuk"]')?.value;
        const jenisAbsen = document.querySelector('input[name="jenis_absen"]')?.value;
        const latitude = document.querySelector('input[name="latitude"]')?.value;
        const longitude = document.querySelector('input[name="longitude"]')?.value;
        const lokasi = document.querySelector('input[name="lokasi"]')?.value;
        const foto = document.querySelector('input[name="foto"]')?.value;
        
        formData.append('tanggal', tanggal);
        formData.append('waktu_masuk', waktuMasuk);
        formData.append('jenis_absen', jenisAbsen);
        formData.append('latitude', latitude);
        formData.append('longitude', longitude);
        formData.append('lokasi', lokasi);
        formData.append('foto', foto);
        
        // Validate required data
        console.log('Validating required data...');
        if (!tanggal || !waktuMasuk || !jenisAbsen || !latitude || !longitude || !lokasi || !foto || foto === 'data:,' || foto.length < 1000 || !foto.startsWith('data:image/')) {
            console.log('Required data validation failed');
            showToast('error', 'Data tidak lengkap. Silakan coba lagi.');
            isSubmitting = false;
            submitIcon.className = originalIconClass;
            submitText.textContent = jenisAbsen === 'masuk' ? 'Absen Masuk' : 'Absen Keluar';
            submitBtn.disabled = false;
            return;
        }
        

        
        // Validate coordinates
        console.log('Validating coordinates...');
        const latCoord = parseFloat(latitude);
        const lngCoord = parseFloat(longitude);
        if (isNaN(latCoord) || isNaN(lngCoord) || latCoord === 0 || lngCoord === 0) {
            console.log('Coordinate validation failed');
            showToast('error', 'Koordinat lokasi tidak valid. Silakan coba lagi.');
            isSubmitting = false;
            submitIcon.className = originalIconClass;
            submitText.textContent = jenisAbsen === 'masuk' ? 'Absen Masuk' : 'Absen Keluar';
            submitBtn.disabled = false;
            return;
        }
        
        // Validate time
        console.log('Validating time...');
        if (!waktuMasuk || waktuMasuk.trim() === '') {
            console.log('Time validation failed');
            showToast('error', 'Waktu tidak valid. Silakan coba lagi.');
            isSubmitting = false;
            submitIcon.className = originalIconClass;
            submitText.textContent = jenisAbsen === 'masuk' ? 'Absen Masuk' : 'Absen Keluar';
            submitBtn.disabled = false;
            return;
        }
        
        // Validate date
        console.log('Validating date...');
        if (!tanggal || tanggal.trim() === '') {
            console.log('Date validation failed');
            showToast('error', 'Tanggal tidak valid. Silakan coba lagi.');
            isSubmitting = false;
            submitIcon.className = originalIconClass;
            submitText.textContent = jenisAbsen === 'masuk' ? 'Absen Masuk' : 'Absen Keluar';
            submitBtn.disabled = false;
            return;
        }
        
        // Validate jenis absen
        console.log('Validating jenis absen...');
        if (!jenisAbsen || !['masuk', 'keluar'].includes(jenisAbsen)) {
            console.log('Jenis absen validation failed');
            showToast('error', 'Jenis absen tidak valid. Silakan coba lagi.');
            isSubmitting = false;
            submitIcon.className = originalIconClass;
            submitText.textContent = jenisAbsen === 'masuk' ? 'Absen Masuk' : 'Absen Keluar';
            submitBtn.disabled = false;
            return;
        }
        
        // Validate location
        console.log('Validating location...');
        if (!lokasi || lokasi.trim() === '') {
            console.log('Location validation failed');
            showToast('error', 'Lokasi tidak valid. Silakan coba lagi.');
            isSubmitting = false;
            submitIcon.className = originalIconClass;
            submitText.textContent = jenisAbsen === 'masuk' ? 'Absen Masuk' : 'Absen Keluar';
            submitBtn.disabled = false;
            return;
        }
        
        // Validate photo
        console.log('Validating photo...');
        if (!foto || foto.trim() === '') {
            console.log('Photo validation failed - empty');
            showToast('error', 'Foto tidak valid. Silakan ambil foto lagi.');
            isSubmitting = false;
            submitIcon.className = originalIconClass;
            submitText.textContent = jenisAbsen === 'masuk' ? 'Absen Masuk' : 'Absen Keluar';
            submitBtn.disabled = false;
            return;
        }
        
        // Validate photo data URL
        console.log('Validating photo data URL...');
        if (!foto.startsWith('data:image/')) {
            console.log('Photo data URL validation failed');
            showToast('error', 'Format foto tidak valid. Silakan ambil foto lagi.');
            isSubmitting = false;
            submitIcon.className = originalIconClass;
            submitText.textContent = jenisAbsen === 'masuk' ? 'Absen Masuk' : 'Absen Keluar';
            submitBtn.disabled = false;
            return;
        }
        
        // Validate photo size
        console.log('Validating photo size...');
        if (foto.length < 1000) { // Minimal 1KB dalam base64
            console.log('Photo size validation failed');
            showToast('error', 'Foto terlalu kecil. Silakan ambil foto lagi.');
            isSubmitting = false;
            submitIcon.className = originalIconClass;
            submitText.textContent = jenisAbsen === 'masuk' ? 'Absen Masuk' : 'Absen Keluar';
            submitBtn.disabled = false;
            return;
        }
        
        // Validate photo max size
        if (foto.length > 30000000) { // 20MB dalam base64
            console.log('Photo max size validation failed');
            showToast('error', 'Foto terlalu besar. Maksimal 20MB.');
            isSubmitting = false;
            submitIcon.className = originalIconClass;
            submitText.textContent = jenisAbsen === 'masuk' ? 'Absen Masuk' : 'Absen Keluar';
            submitBtn.disabled = false;
            return;
        }
        

        
        // Validate photo is not empty data URL
        console.log('Validating photo is not empty data URL...');
        if (foto === 'data:,' || foto === 'data:image/,') {
            console.log('Photo empty data URL validation failed');
            showToast('error', 'Foto kosong. Silakan ambil foto lagi.');
            isSubmitting = false;
            submitIcon.className = originalIconClass;
            submitText.textContent = jenisAbsen === 'masuk' ? 'Absen Masuk' : 'Absen Keluar';
            submitBtn.disabled = false;
            return;
        }
        

        

        
        // All validations passed, proceed with submission
        console.log('All validations passed, submitting attendance...');
        console.log('Data to submit:', {
            tanggal,
            waktuMasuk,
            jenisAbsen,
            latitude,
            longitude,
            lokasi,
            fotoLength: foto.length
        });
        
        // Create AbortController for timeout
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 60000); // 60 seconds timeout
        
        console.log('Sending fetch request to:', attendanceForm.action);
        
        // Tambahkan delay minimum untuk memastikan loading animation terlihat
        const minLoadingTime = 3000; // 3 detik minimum loading
        const startTime = Date.now();
        
        fetch(attendanceForm.action, {
            method: 'POST',
            body: formData,
            signal: controller.signal,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            clearTimeout(timeoutId);
            console.log('Response received');
            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);
            
            if (!response.ok) {
                console.log('Response not ok, parsing error');
                return response.json().then(data => {
                    console.error('Server error:', data);
                    throw new Error(data.message || 'Terjadi kesalahan saat menyimpan absensi');
                });
            }
            console.log('Response ok, parsing JSON');
            return response.json();
        })
        .then(data => {
            console.log('Response data received:', data);
            
            if (data.status === 'success') {
                console.log('Success response, showing success toast');
                showToast('success', data.message);
                
                // Pastikan loading animation berjalan minimal 3 detik sebelum redirect
                const elapsedTime = Date.now() - startTime;
                const remainingTime = Math.max(0, minLoadingTime - elapsedTime);
                
                setTimeout(() => {
                    console.log('Redirecting to attendance page');
                    window.location.href = '{{ route("mobile.attendance") }}';
                }, remainingTime + 1000); // Tambah 1 detik untuk toast
            } else {
                console.log('Error response, showing error toast');
                showToast('error', data.message || 'Terjadi kesalahan saat menyimpan absensi');
            }
        })
        .catch(error => {
            clearTimeout(timeoutId);
            console.error('Fetch error occurred');
            console.error('Error:', error);
            console.error('Error name:', error.name);
            console.error('Error message:', error.message);
            
            // Pastikan loading animation berjalan minimal 3 detik sebelum reset
            const elapsedTime = Date.now() - startTime;
            const remainingTime = Math.max(0, minLoadingTime - elapsedTime);
            
            setTimeout(() => {
                if (error.name === 'AbortError') {
                    console.log('AbortError - request timeout');
                    showToast('error', 'Request timeout. Silakan coba lagi.');
                } else if (error.name === 'TypeError' && error.message.includes('fetch')) {
                    console.log('TypeError - network error');
                    showToast('error', 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
                } else {
                    console.log('Other error');
                    showToast('error', error.message || 'Terjadi kesalahan saat menyimpan absensi');
                }
            }, remainingTime);
        })
        .finally(() => {
            console.log('Request completed, resetting loading state');
            
            // Pastikan loading animation berjalan minimal 3 detik
            const elapsedTime = Date.now() - startTime;
            const remainingTime = Math.max(0, minLoadingTime - elapsedTime);
            
            setTimeout(() => {
                // Reset loading state
                isSubmitting = false;
                            submitIcon.className = originalIconClass;
            submitText.textContent = jenisAbsen === 'masuk' ? 'Absen Masuk' : 'Absen Keluar';
            submitBtn.disabled = false;
            console.log('Loading state reset complete');
            }, remainingTime);
        });
    });
});

function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('id-ID');
    const timeDisplayString = now.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});
    
    document.getElementById('timeText').textContent = timeString;
    document.getElementById('timeDisplay').textContent = timeDisplayString;
}

function getAddressFromCoords(latitude, longitude, element) {
    console.log('Getting address from coordinates:', { latitude, longitude });
    // Using OpenStreetMap Nominatim API (free)
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}&zoom=18&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            console.log('Address data received:', data);
            if (data.display_name) {
                element.textContent = data.display_name;
                document.getElementById('lokasi').value = data.display_name;
                console.log('Address set:', data.display_name);
            } else {
                element.textContent = 'Lokasi tidak dapat diidentifikasi';
                console.log('No display name in address data');
            }
        })
        .catch(error => {
            console.error('Error getting address:', error);
            element.textContent = 'Lokasi tidak dapat diidentifikasi';
        });
}

function removePhoto() {
    console.log('Removing photo');
    document.getElementById('photo').value = '';
    document.getElementById('foto').value = '';
    document.getElementById('photoPreview').innerHTML = '';
    document.getElementById('capturePlaceholder').style.display = 'block';
    photoTaken = false;
    checkFormValidity();
}

function checkFormValidity() {
    console.log('Checking form validity...');
    console.log('currentLocation:', currentLocation);
    console.log('photoTaken:', photoTaken);
    console.log('isSubmitting:', isSubmitting);
    
    const submitBtn = document.getElementById('submitBtn');
    
    if (currentLocation && photoTaken && !isSubmitting) {
        console.log('Form is valid, enabling submit button');
        submitBtn.disabled = false;
    } else {
        console.log('Form is invalid, disabling submit button');
        submitBtn.disabled = true;
    }
}

function showToast(type, message) {
    console.log('Showing toast:', type, message);
    const container = document.getElementById('toastContainer');
    
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
    
    toast.innerHTML = `
        <i class="${icon} toast-icon"></i>
        <div class="toast-message">${message}</div>
    `;
    
    container.appendChild(toast);
    console.log('Toast added to container');
    
    // Remove toast after 3 seconds
    setTimeout(() => {
        console.log('Removing toast');
        toast.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => {
            if (container.contains(toast)) {
                container.removeChild(toast);
                console.log('Toast removed from container');
            }
        }, 300);
    }, 3000);
}

</script>
@endpush 
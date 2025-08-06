<x-app-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-mobile-alt me-2"></i>
                        Device Detection Test
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Device Info Display -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0">Current Device Info</h6>
                                </div>
                                <div class="card-body">
                                    <div id="deviceInfo">
                                        <div class="text-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <p class="mt-2">Detecting device...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0">Session Info</h6>
                                </div>
                                <div class="card-body">
                                    <div id="sessionInfo">
                                        <div class="text-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <p class="mt-2">Loading session info...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Test Controls -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Test Controls</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button class="btn btn-primary" onclick="refreshDeviceInfo()">
                                            <i class="fas fa-sync-alt me-2"></i>
                                            Refresh Device Info
                                        </button>
                                        <button class="btn btn-success" onclick="forceMobileView()">
                                            <i class="fas fa-mobile-alt me-2"></i>
                                            Force Mobile View
                                        </button>
                                        <button class="btn btn-info" onclick="forceDesktopView()">
                                            <i class="fas fa-desktop me-2"></i>
                                            Force Desktop View
                                        </button>
                                        <button class="btn btn-warning" onclick="resetDetection()">
                                            <i class="fas fa-undo me-2"></i>
                                            Reset to Auto
                                        </button>
                                        <button class="btn btn-secondary" onclick="testResponsive()">
                                            <i class="fas fa-expand-arrows-alt me-2"></i>
                                            Test Responsive
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Responsive Test -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Responsive Test</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 mb-3">
                                            <div class="card border-primary">
                                                <div class="card-header bg-primary text-white">
                                                    <h6 class="mb-0">Desktop Only</h6>
                                                </div>
                                                <div class="card-body desktop-only">
                                                    <p class="mb-0 text-success">
                                                        <i class="fas fa-check-circle me-2"></i>
                                                        This content is visible on desktop
                                                    </p>
                                                </div>
                                                <div class="card-body mobile-only" style="display: none;">
                                                    <p class="mb-0 text-danger">
                                                        <i class="fas fa-times-circle me-2"></i>
                                                        This content is hidden on mobile
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 mb-3">
                                            <div class="card border-success">
                                                <div class="card-header bg-success text-white">
                                                    <h6 class="mb-0">Mobile Only</h6>
                                                </div>
                                                <div class="card-body mobile-only">
                                                    <p class="mb-0 text-success">
                                                        <i class="fas fa-check-circle me-2"></i>
                                                        This content is visible on mobile
                                                    </p>
                                                </div>
                                                <div class="card-body desktop-only" style="display: none;">
                                                    <p class="mb-0 text-danger">
                                                        <i class="fas fa-times-circle me-2"></i>
                                                        This content is hidden on desktop
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 mb-3">
                                            <div class="card border-info">
                                                <div class="card-header bg-info text-white">
                                                    <h6 class="mb-0">Always Visible</h6>
                                                </div>
                                                <div class="card-body">
                                                    <p class="mb-0 text-info">
                                                        <i class="fas fa-eye me-2"></i>
                                                        This content is always visible
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 mb-3">
                                            <div class="card border-warning">
                                                <div class="card-header bg-warning text-dark">
                                                    <h6 class="mb-0">Device Class</h6>
                                                </div>
                                                <div class="card-body">
                                                    <p class="mb-0">
                                                        <strong>Body Class:</strong>
                                                        <span id="bodyClass" class="badge bg-secondary">detecting...</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Screen Size Info -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Screen Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <div class="text-center">
                                                <h4 id="screenWidth">-</h4>
                                                <small class="text-muted">Width (px)</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="text-center">
                                                <h4 id="screenHeight">-</h4>
                                                <small class="text-muted">Height (px)</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="text-center">
                                                <h4 id="aspectRatio">-</h4>
                                                <small class="text-muted">Aspect Ratio</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="text-center">
                                                <h4 id="pixelRatio">-</h4>
                                                <small class="text-muted">Pixel Ratio</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>

@push('scripts')
<script>
    // Load device info on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadDeviceInfo();
        loadSessionInfo();
        updateScreenInfo();
        
        // Update screen info on resize
        window.addEventListener('resize', function() {
            updateScreenInfo();
        });
    });

    function loadDeviceInfo() {
        const deviceInfo = getDeviceInfo();
        if (deviceInfo) {
            displayDeviceInfo(deviceInfo);
        } else {
            setTimeout(loadDeviceInfo, 1000);
        }
    }

    function displayDeviceInfo(info) {
        const container = document.getElementById('deviceInfo');
        container.innerHTML = `
            <table class="table table-sm">
                <tbody>
                    <tr>
                        <td><strong>Device Type:</strong></td>
                        <td>
                            ${info.isMobile ? '<span class="badge bg-success">Mobile</span>' : ''}
                            ${info.isTablet ? '<span class="badge bg-warning">Tablet</span>' : ''}
                            ${info.isDesktop ? '<span class="badge bg-info">Desktop</span>' : ''}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Screen Size:</strong></td>
                        <td>${info.width} × ${info.height}</td>
                    </tr>
                    <tr>
                        <td><strong>User Agent:</strong></td>
                        <td><small class="text-muted">${info.userAgent.substring(0, 100)}...</small></td>
                    </tr>
                </tbody>
            </table>
        `;
    }

    function loadSessionInfo() {
        fetch('{{ route("device.info") }}')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('sessionInfo');
                container.innerHTML = `
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td><strong>Session Mobile:</strong></td>
                                <td>
                                    ${data.is_mobile_device ? 
                                        '<span class="badge bg-success">Yes</span>' : 
                                        '<span class="badge bg-secondary">No</span>'
                                    }
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Device Info:</strong></td>
                                <td>
                                    ${data.device_info ? 
                                        `<small class="text-muted">${JSON.stringify(data.device_info, null, 2)}</small>` : 
                                        '<span class="text-muted">No device info stored</span>'
                                    }
                                </td>
                            </tr>
                        </tbody>
                    </table>
                `;
            })
            .catch(error => {
                console.error('Error loading session info:', error);
                document.getElementById('sessionInfo').innerHTML = 
                    '<p class="text-danger">Error loading session info</p>';
            });
    }

    function updateScreenInfo() {
        document.getElementById('screenWidth').textContent = window.innerWidth;
        document.getElementById('screenHeight').textContent = window.innerHeight;
        document.getElementById('aspectRatio').textContent = (window.innerWidth / window.innerHeight).toFixed(2);
        document.getElementById('pixelRatio').textContent = window.devicePixelRatio || 1;
        
        // Update body class display
        const bodyClass = document.body.className;
        document.getElementById('bodyClass').textContent = bodyClass || 'none';
    }

    function refreshDeviceInfo() {
        if (deviceDetector) {
            deviceDetector.detectDevice();
            deviceDetector.updateSession();
            loadDeviceInfo();
            loadSessionInfo();
            updateScreenInfo();
        }
    }

    function forceMobileView() {
        fetch('{{ route("device.force-mobile") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Mobile View Forced',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });
            loadSessionInfo();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to force mobile view'
            });
        });
    }

    function forceDesktopView() {
        fetch('{{ route("device.force-desktop") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Desktop View Forced',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });
            loadSessionInfo();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to force desktop view'
            });
        });
    }

    function resetDetection() {
        fetch('{{ route("device.reset") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Detection Reset',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });
            loadSessionInfo();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to reset detection'
            });
        });
    }

    function testResponsive() {
        Swal.fire({
            title: 'Responsive Test',
            html: `
                <p>Resize your browser window to test responsive behavior:</p>
                <ul class="text-left">
                    <li><strong>Desktop:</strong> Width > 768px</li>
                    <li><strong>Tablet:</strong> Width 768px - 1024px</li>
                    <li><strong>Mobile:</strong> Width < 768px</li>
                </ul>
                <p class="mt-3">Watch the "Device Class" badge and content visibility change in real-time.</p>
            `,
            icon: 'info',
            confirmButtonText: 'Got it!'
        });
    }
</script>
@endpush 
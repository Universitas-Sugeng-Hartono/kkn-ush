<x-app-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-bug me-2"></i>
                        Device Detection Debug
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Browser Info</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>User Agent:</strong></td>
                                    <td><small id="userAgent"></small></td>
                                </tr>
                                <tr>
                                    <td><strong>Screen Size:</strong></td>
                                    <td id="screenSize"></td>
                                </tr>
                                <tr>
                                    <td><strong>Window Size:</strong></td>
                                    <td id="windowSize"></td>
                                </tr>
                                <tr>
                                    <td><strong>Pixel Ratio:</strong></td>
                                    <td id="pixelRatio"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Device Detection</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Has Touch:</strong></td>
                                    <td id="hasTouch"></td>
                                </tr>
                                <tr>
                                    <td><strong>Has Hover:</strong></td>
                                    <td id="hasHover"></td>
                                </tr>
                                <tr>
                                    <td><strong>Is Portrait:</strong></td>
                                    <td id="isPortrait"></td>
                                </tr>
                                <tr>
                                    <td><strong>Detected Device:</strong></td>
                                    <td id="detectedDevice"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Session Info</h5>
                            <div id="sessionInfo">Loading...</div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Actions</h5>
                            <button class="btn btn-primary" onclick="refreshDetection()">
                                <i class="fas fa-sync-alt me-2"></i>
                                Refresh Detection
                            </button>
                            <button class="btn btn-success" onclick="forceMobile()">
                                <i class="fas fa-mobile-alt me-2"></i>
                                Force Mobile
                            </button>
                            <button class="btn btn-info" onclick="forceDesktop()">
                                <i class="fas fa-desktop me-2"></i>
                                Force Desktop
                            </button>
                            <button class="btn btn-warning" onclick="resetDetection()">
                                <i class="fas fa-undo me-2"></i>
                                Reset
                            </button>
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
    function updateDebugInfo() {
        // Browser info
        document.getElementById('userAgent').textContent = navigator.userAgent;
        document.getElementById('screenSize').textContent = `${screen.width} × ${screen.height}`;
        document.getElementById('windowSize').textContent = `${window.innerWidth} × ${window.innerHeight}`;
        document.getElementById('pixelRatio').textContent = window.devicePixelRatio || 1;
        
        // Device capabilities
        document.getElementById('hasTouch').textContent = ('ontouchstart' in window || navigator.maxTouchPoints > 0) ? 'Yes' : 'No';
        document.getElementById('hasHover').textContent = window.matchMedia('(hover: hover)').matches ? 'Yes' : 'No';
        document.getElementById('isPortrait').textContent = window.matchMedia('(orientation: portrait)').matches ? 'Yes' : 'No';
        
        // Detected device
        const deviceInfo = getDeviceInfo();
        if (deviceInfo) {
            let deviceType = '';
            if (deviceInfo.isMobile) deviceType = 'Mobile';
            else if (deviceInfo.isTablet) deviceType = 'Tablet';
            else deviceType = 'Desktop';
            
            document.getElementById('detectedDevice').innerHTML = `
                <span class="badge bg-${deviceInfo.isMobile ? 'success' : deviceInfo.isTablet ? 'warning' : 'info'}">${deviceType}</span>
            `;
        }
        
        // Session info
        fetch('{{ route("device.info") }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('sessionInfo').innerHTML = `
                    <pre class="bg-light p-3 rounded">${JSON.stringify(data, null, 2)}</pre>
                `;
            })
            .catch(error => {
                document.getElementById('sessionInfo').innerHTML = `<p class="text-danger">Error: ${error.message}</p>`;
            });
    }
    
    function refreshDetection() {
        if (deviceDetector) {
            deviceDetector.detectDevice();
            deviceDetector.updateSession();
        }
        updateDebugInfo();
    }
    
    function forceMobile() {
        fetch('{{ route("device.force-mobile") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(() => {
            updateDebugInfo();
            Swal.fire('Success', 'Forced mobile view', 'success');
        });
    }
    
    function forceDesktop() {
        fetch('{{ route("device.force-desktop") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(() => {
            updateDebugInfo();
            Swal.fire('Success', 'Forced desktop view', 'success');
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
        .then(() => {
            location.reload();
        });
    }
    
    // Update on page load
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(updateDebugInfo, 1000);
    });
    
    // Update on resize
    window.addEventListener('resize', function() {
        setTimeout(updateDebugInfo, 250);
    });
</script>
@endpush 
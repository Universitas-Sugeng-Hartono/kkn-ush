<x-app-layout>
    <style>
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 10px;
            }
            
            .card {
                margin-bottom: 15px;
            }
            
            .card-body {
                padding: 15px;
            }
            
            /* Map and sidebar - Stack vertically */
            .row .col-md-8,
            .row .col-md-4 {
                margin-bottom: 20px;
            }
            
            /* Map height smaller on mobile */
            #map {
                height: 400px !important;
            }
            
            /* Text adjustments */
            h2.fw-bold {
                font-size: 1.5rem;
            }
            
            h5.card-title {
                font-size: 1.1rem;
            }
            
            /* Button adjustments */
            .btn-sm {
                font-size: 12px;
                padding: 6px 10px;
            }
            
            /* Group item adjustments */
            .group-item {
                padding: 10px !important;
                margin-bottom: 10px !important;
            }
            
            .group-item h6 {
                font-size: 1rem;
            }
            
            .group-item small {
                font-size: 11px;
            }
            
            /* Popup adjustments */
            .leaflet-popup-content {
                font-size: 14px;
            }
            
            .leaflet-popup-content h6 {
                font-size: 1rem;
            }
            
            .leaflet-popup-content p {
                font-size: 12px;
                margin-bottom: 5px;
            }
        }
        
        /* Extra small devices */
        @media (max-width: 576px) {
            .container-fluid {
                padding: 5px;
            }
            
            .card-body {
                padding: 10px;
            }
            
            h2.fw-bold {
                font-size: 1.3rem;
            }
            
            h5.card-title {
                font-size: 1rem;
            }
            
            /* Map height even smaller */
            #map {
                height: 300px !important;
            }
            
            .btn-sm {
                font-size: 11px;
                padding: 5px 8px;
            }
            
            .group-item {
                padding: 8px !important;
            }
            
            .group-item h6 {
                font-size: 0.9rem;
            }
            
            .group-item small {
                font-size: 10px;
            }
            
            /* Popup adjustments */
            .leaflet-popup-content {
                font-size: 13px;
                min-width: 200px !important;
            }
            
            .leaflet-popup-content h6 {
                font-size: 0.9rem;
            }
            
            .leaflet-popup-content p {
                font-size: 11px;
            }
        }
        
        /* Existing styles */
        .group-item {
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .group-item:hover {
            background-color: #f8f9fa;
        }
        
        .group-item.active {
            background-color: #e3f2fd;
            border-color: #2196f3 !important;
        }
    </style>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <h2 class="fw-bold">Peta Lokasi Kelompok KKN</h2>
                <p class="text-muted">Pantau lokasi dan aktivitas kelompok bimbingan Anda</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-map-marked-alt me-2"></i>
                            Peta Lokasi Kelompok
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="map" style="height: 600px; width: 100%;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Informasi Kelompok
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="group-info">
                            <p class="text-muted">Pilih marker pada peta untuk melihat detail kelompok</p>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>
                            Daftar Kelompok
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="groups-list">
                            <!-- Groups will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let map;
        let markers = [];

        // Initialize map
        function initMap() {
            map = L.map('map').setView([-7.797068, 110.370529], 10); // Default to Yogyakarta

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            loadGroupsData();
        }

        // Load groups data
        function loadGroupsData() {
            fetch('{{ route("groups.monitoring.data") }}')
                .then(response => response.json())
                .then(data => {
                    displayGroups(data);
                    addMarkersToMap(data);
                })
                .catch(error => {
                    console.error('Error loading groups data:', error);
                });
        }

        // Display groups in sidebar
        function displayGroups(groups) {
            const container = document.getElementById('groups-list');
            container.innerHTML = '';

            groups.forEach(group => {
                const groupHtml = `
                    <div class="group-item mb-3 p-3 border rounded" data-group-id="${group.id}">
                        <h6 class="mb-2">
                            <i class="fas fa-users me-2"></i>
                            ${group.nama}
                        </h6>
                        <p class="mb-1"><small><strong>Lokasi:</strong> ${group.lokasi}</small></p>
                        <p class="mb-1"><small><strong>Mahasiswa:</strong> ${group.jumlah_mahasiswa} orang</small></p>
                        <div class="row text-center">
                            <div class="col-6">
                                <small class="text-warning">
                                    <i class="fas fa-clock me-1"></i>
                                    ${group.logbook_pending} pending
                                </small>
                            </div>
                            <div class="col-6">
                                <small class="text-info">
                                    <i class="fas fa-calendar me-1"></i>
                                    ${group.absensi_pending} pending
                                </small>
                            </div>
                        </div>
                    </div>
                `;
                container.innerHTML += groupHtml;
            });

            // Add click event to group items
            document.querySelectorAll('.group-item').forEach(item => {
                item.addEventListener('click', function() {
                    const groupId = this.dataset.groupId;
                    const marker = markers.find(m => m.groupId == groupId);
                    if (marker) {
                        map.setView(marker.getLatLng(), 15);
                        marker.openPopup();
                    }
                });
            });
        }

        // Add markers to map
        function addMarkersToMap(groups) {
            // Clear existing markers
            markers.forEach(marker => map.removeLayer(marker));
            markers = [];

            groups.forEach(group => {
                const marker = L.marker([group.latitude, group.longitude])
                    .addTo(map)
                    .bindPopup(createPopupContent(group));

                marker.groupId = group.id;
                markers.push(marker);
            });

            // Fit map to show all markers
            if (markers.length > 0) {
                const group = L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.1));
            }
        }

        // Create popup content
        function createPopupContent(group) {
            const mahasiswaList = group.mahasiswa.map(mhs => 
                `<li>${mhs.nama} (${mhs.nim}) - ${mhs.logbook_count} logbook, ${mhs.absensi_count} absensi</li>`
            ).join('');

            return `
                <div style="min-width: 250px;">
                    <h6><i class="fas fa-users me-2"></i>${group.nama}</h6>
                    <p><strong>Lokasi:</strong> ${group.lokasi}</p>
                    <p><strong>Alamat:</strong> ${group.alamat}</p>
                    <p><strong>Mahasiswa:</strong> ${group.jumlah_mahasiswa} orang</p>
                    <div class="row text-center mb-2">
                        <div class="col-6">
                            <small class="text-warning">
                                <i class="fas fa-clock me-1"></i>
                                ${group.logbook_pending} logbook pending
                            </small>
                        </div>
                        <div class="col-6">
                            <small class="text-info">
                                <i class="fas fa-calendar me-1"></i>
                                ${group.absensi_pending} absensi pending
                            </small>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('students.index') }}?group=${group.id}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye me-1"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            `;
        }

        // Initialize map when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
        });
    </script>
    @endpush

    @push('styles')
    <link rel="stylesheet" href="{{ asset("assets/css/leaflet.min.css") }}" />
    @endpush
</x-app-layout> 
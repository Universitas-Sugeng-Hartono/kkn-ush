<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Peta Lokasi KKN</h1>
            <a href="{{ route('locations.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body p-0">
                        <div id="map" style="height: 700px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Daftar Lokasi</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush" style="max-height: 655px; overflow-y: auto;">
                            @foreach($locations as $location)
                            <a href="#" class="list-group-item list-group-item-action location-item" 
                                data-lat="{{ $location->latitude }}" 
                                data-lng="{{ $location->longitude }}"
                                data-id="{{ $location->id }}">
                                <h6 class="mb-1">{{ $location->nama_desa }}</h6>
                                <p class="mb-1 text-muted small">
                                    Kec. {{ $location->nama_kecamatan }}, 
                                    Kab. {{ $location->nama_kabupaten }}, 
                                    {{ $location->nama_provinsi }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small>
                                        <i class="fas fa-users me-1"></i>
                                        {{ $location->kelompok->count() }} Kelompok
                                    </small>
                                    <small>
                                        <i class="fas fa-user-graduate me-1"></i>
                                        {{ $location->kelompok->sum(function($kelompok) { return $kelompok->mahasiswa->count(); }) }} Mahasiswa
                                    </small>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Initialize map
        const map = L.map('map').setView([-7.7956, 110.3695], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add markers
        const markers = {};
        @foreach($locations as $location)
            markers[{{ $location->id }}] = L.marker([{{ $location->latitude }}, {{ $location->longitude }}])
                .addTo(map)
                .bindPopup(`
                    <h6>{{ $location->nama_desa }}</h6>
                    <p class="mb-1">
                        Kec. {{ $location->nama_kecamatan }}, <br>
                        Kab. {{ $location->nama_kabupaten }}, <br>
                        {{ $location->nama_provinsi }}
                    </p>
                    <div class="d-flex justify-content-between">
                        <small>
                            <i class="fas fa-users me-1"></i>
                            {{ $location->kelompok->count() }} Kelompok
                        </small>
                        <small>
                            <i class="fas fa-user-graduate me-1"></i>
                            {{ $location->kelompok->sum(function($kelompok) { return $kelompok->mahasiswa->count(); }) }} Mahasiswa
                        </small>
                    </div>
                `);
        @endforeach

        // Handle location item click
        $('.location-item').on('click', function(e) {
            e.preventDefault();
            const lat = $(this).data('lat');
            const lng = $(this).data('lng');
            const id = $(this).data('id');
            
            map.setView([lat, lng], 14);
            markers[id].openPopup();

            // Highlight active item
            $('.location-item').removeClass('active');
            $(this).addClass('active');
        });

        // Fit bounds to all markers
        const bounds = [];
        Object.values(markers).forEach(marker => {
            bounds.push(marker.getLatLng());
        });
        if (bounds.length > 0) {
            map.fitBounds(bounds);
        }
    </script>
    @endpush
</x-app-layout> 
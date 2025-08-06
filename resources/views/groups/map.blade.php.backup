<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Peta Kelompok KKN</h1>
            <a href="{{ route('groups.index') }}" class="btn btn-secondary">
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
                        <h5 class="card-title mb-0">Daftar Kelompok</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush" style="max-height: 655px; overflow-y: auto;">
                            @foreach($groups as $group)
                            <a href="#" class="list-group-item list-group-item-action group-item" 
                                data-lat="{{ $group->lokasi->latitude }}" 
                                data-lng="{{ $group->lokasi->longitude }}"
                                data-id="{{ $group->id }}">
                                <h6 class="mb-1">{{ $group->nama_kelompok }}</h6>
                                <p class="mb-1 text-muted small">
                                    {{ $group->lokasi->nama_desa }}, 
                                    {{ $group->lokasi->nama_kecamatan }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small>
                                        <i class="fas fa-users me-1"></i>
                                        {{ $group->mahasiswa->count() }} Mahasiswa
                                    </small>
                                    <small>
                                        <i class="fas fa-user-tie me-1"></i>
                                        {{ $group->dpl ? $group->dpl->name : 'Belum ada DPL' }}
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
        @foreach($groups as $group)
            markers[{{ $group->id }}] = L.marker([{{ $group->lokasi->latitude }}, {{ $group->lokasi->longitude }}])
                .addTo(map)
                .bindPopup(`
                    <h6>{{ $group->nama_kelompok }}</h6>
                    <p class="mb-1">
                        {{ $group->lokasi->nama_desa }}, <br>
                        {{ $group->lokasi->nama_kecamatan }}
                    </p>
                    <div class="d-flex justify-content-between">
                        <small>
                            <i class="fas fa-users me-1"></i>
                            {{ $group->mahasiswa->count() }} Mahasiswa
                        </small>
                    </div>
                    <div class="mt-2">
                        <small class="d-block">
                            <i class="fas fa-user-tie me-1"></i>
                            DPL: {{ $group->dpl ? $group->dpl->name : 'Belum ada' }}
                        </small>
                    </div>
                `);
        @endforeach

        // Handle group item click
        $('.group-item').on('click', function(e) {
            e.preventDefault();
            const lat = $(this).data('lat');
            const lng = $(this).data('lng');
            const id = $(this).data('id');
            
            map.setView([lat, lng], 14);
            markers[id].openPopup();

            // Highlight active item
            $('.group-item').removeClass('active');
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
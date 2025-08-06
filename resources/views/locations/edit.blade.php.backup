<x-app-layout>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">Edit Lokasi KKN</h2>
                        <p class="text-muted">Edit data lokasi {{ $location->nama_desa }}</p>
                    </div>
                    <div>
                        <a href="{{ route('locations.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('locations.update', $location) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_desa" class="form-label">Nama Desa <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('nama_desa') is-invalid @enderror" 
                                               id="nama_desa" 
                                               name="nama_desa" 
                                               value="{{ old('nama_desa', $location->nama_desa) }}" 
                                               required>
                                        @error('nama_desa')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="nama_kecamatan" class="form-label">Nama Kecamatan <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('nama_kecamatan') is-invalid @enderror" 
                                               id="nama_kecamatan" 
                                               name="nama_kecamatan" 
                                               value="{{ old('nama_kecamatan', $location->nama_kecamatan) }}" 
                                               required>
                                        @error('nama_kecamatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="nama_kabupaten" class="form-label">Nama Kabupaten <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('nama_kabupaten') is-invalid @enderror" 
                                               id="nama_kabupaten" 
                                               name="nama_kabupaten" 
                                               value="{{ old('nama_kabupaten', $location->nama_kabupaten) }}" 
                                               required>
                                        @error('nama_kabupaten')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="nama_provinsi" class="form-label">Nama Provinsi <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('nama_provinsi') is-invalid @enderror" 
                                               id="nama_provinsi" 
                                               name="nama_provinsi" 
                                               value="{{ old('nama_provinsi', $location->nama_provinsi) }}" 
                                               required>
                                        @error('nama_provinsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                                  id="deskripsi" 
                                                  name="deskripsi" 
                                                  rows="3">{{ old('deskripsi', $location->deskripsi) }}</textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Klik pada peta untuk mengubah lokasi atau isi koordinat secara manual.
                                    </div>

                                    <div id="map" style="height: 300px;" class="mb-3 rounded"></div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="latitude" class="form-label">Latitude <span class="text-danger">*</span></label>
                                                <input type="text" 
                                                       class="form-control @error('latitude') is-invalid @enderror" 
                                                       id="latitude" 
                                                       name="latitude" 
                                                       value="{{ old('latitude', $location->latitude) }}" 
                                                       required>
                                                @error('latitude')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="longitude" class="form-label">Longitude <span class="text-danger">*</span></label>
                                                <input type="text" 
                                                       class="form-control @error('longitude') is-invalid @enderror" 
                                                       id="longitude" 
                                                       name="longitude" 
                                                       value="{{ old('longitude', $location->longitude) }}" 
                                                       required>
                                                @error('longitude')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="input-group">
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="searchLocation" 
                                                   placeholder="Cari lokasi...">
                                            <button class="btn btn-primary" 
                                                    type="button"
                                                    onclick="searchLocation()">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">
                                            Cari lokasi berdasarkan nama tempat atau alamat
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Initialize map
        const map = L.map('map').setView([{{ $location->latitude }}, {{ $location->longitude }}], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        let marker = L.marker([{{ $location->latitude }}, {{ $location->longitude }}]).addTo(map);

        // Handle map click
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            marker.setLatLng([lat, lng]);
        });

        // Handle manual coordinate input
        document.getElementById('latitude').addEventListener('change', updateMarker);
        document.getElementById('longitude').addEventListener('change', updateMarker);

        function updateMarker() {
            const lat = document.getElementById('latitude').value;
            const lng = document.getElementById('longitude').value;

            if (lat && lng) {
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], 13);
            }
        }

        // Location search using Nominatim
        function searchLocation() {
            const query = document.getElementById('searchLocation').value;
            if (!query) return;

            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lng = parseFloat(data[0].lon);

                        document.getElementById('latitude').value = lat;
                        document.getElementById('longitude').value = lng;
                        marker.setLatLng([lat, lng]);
                        map.setView([lat, lng], 13);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lokasi Tidak Ditemukan',
                            text: 'Coba kata kunci pencarian yang lain',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat mencari lokasi',
                        timer: 3000,
                        showConfirmButton: false
                    });
                });
        }

        // Handle enter key on search input
        document.getElementById('searchLocation').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchLocation();
            }
        });
    </script>
    @endpush
</x-app-layout> 
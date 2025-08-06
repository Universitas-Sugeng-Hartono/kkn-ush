<x-app-layout>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">Tambah Kelompok KKN</h2>
                        <p class="text-muted">Tambah data kelompok KKN baru</p>
                    </div>
                    <div>
                        <a href="{{ route('groups.index') }}" class="btn btn-secondary">
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
                        <form action="{{ route('groups.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_kelompok" class="form-label">Nama Kelompok <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('nama_kelompok') is-invalid @enderror" 
                                               id="nama_kelompok" 
                                               name="nama_kelompok" 
                                               value="{{ old('nama_kelompok') }}" 
                                               required>
                                        @error('nama_kelompok')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="angkatan_id" class="form-label">Angkatan <span class="text-danger">*</span></label>
                                        <select class="form-select @error('angkatan_id') is-invalid @enderror" 
                                                id="angkatan_id" 
                                                name="angkatan_id" 
                                                required>
                                            <option value="">Pilih Angkatan</option>
                                            @foreach($angkatan as $a)
                                                <option value="{{ $a->id }}" {{ old('angkatan_id') == $a->id ? 'selected' : '' }}>
                                                    {{ $a->nama_angkatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('angkatan_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="lokasi_id" class="form-label">Lokasi <span class="text-danger">*</span></label>
                                        <select class="form-select @error('lokasi_id') is-invalid @enderror" 
                                                id="lokasi_id" 
                                                name="lokasi_id" 
                                                required>
                                            <option value="">Pilih Lokasi</option>
                                            @foreach($lokasi as $l)
                                                <option value="{{ $l->id }}" 
                                                        data-lat="{{ $l->latitude }}"
                                                        data-lng="{{ $l->longitude }}"
                                                        {{ old('lokasi_id') == $l->id ? 'selected' : '' }}>
                                                    {{ $l->nama_desa }}, {{ $l->nama_kecamatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('lokasi_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="dpl_id" class="form-label">Dosen Pembimbing Lapangan <span class="text-danger">*</span></label>
                                        <select class="form-select @error('dpl_id') is-invalid @enderror" 
                                                id="dpl_id" 
                                                name="dpl_id" 
                                                required>
                                            <option value="">Pilih DPL</option>
                                            @foreach($dpl as $d)
                                                <option value="{{ $d->id }}" {{ old('dpl_id') == $d->id ? 'selected' : '' }}>
                                                    {{ $d->name }} ({{ $d->nip }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('dpl_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                                  id="deskripsi" 
                                                  name="deskripsi" 
                                                  rows="3">{{ old('deskripsi') }}</textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Lokasi kelompok KKN akan ditampilkan pada peta.
                                    </div>

                                    <div id="map" style="height: 300px;" class="mb-3 rounded"></div>

                                    <div class="mb-3">
                                        <label class="form-label">Anggota Kelompok</label>
                                        <div class="card">
                                            <div class="card-body p-0">
                                                <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                                                    @foreach($mahasiswa as $m)
                                                        <label class="list-group-item">
                                                            <input class="form-check-input me-2" 
                                                                   type="checkbox" 
                                                                   name="mahasiswa_ids[]" 
                                                                   value="{{ $m->id }}"
                                                                   {{ in_array($m->id, old('mahasiswa_ids', [])) ? 'checked' : '' }}>
                                                            <div>
                                                                <div class="fw-bold">{{ $m->name }}</div>
                                                                <small class="text-muted">NIM: {{ $m->nim }}</small>
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @error('mahasiswa_ids')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Simpan
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
        const map = L.map('map').setView([-7.7956, 110.3695], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        let marker;

        // Update map when location is selected
        document.getElementById('lokasi_id').addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            if (option.value) {
                const lat = parseFloat(option.dataset.lat);
                const lng = parseFloat(option.dataset.lng);
                
                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng]).addTo(map);
                }
                
                map.setView([lat, lng], 13);
            } else if (marker) {
                map.removeLayer(marker);
                marker = null;
                map.setView([-7.7956, 110.3695], 10);
            }
        });

        // Initialize map if location is selected
        window.addEventListener('load', function() {
            const lokasiSelect = document.getElementById('lokasi_id');
            if (lokasiSelect.value) {
                const option = lokasiSelect.options[lokasiSelect.selectedIndex];
                const lat = parseFloat(option.dataset.lat);
                const lng = parseFloat(option.dataset.lng);
                
                marker = L.marker([lat, lng]).addTo(map);
                map.setView([lat, lng], 13);
            }
        });
    </script>
    @endpush
</x-app-layout> 
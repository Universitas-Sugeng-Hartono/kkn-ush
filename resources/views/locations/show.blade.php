<x-app-layout>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">Detail Lokasi KKN</h2>
                        <p class="text-muted">Informasi lengkap lokasi {{ $location->nama_desa }}</p>
                    </div>
                    <div>
                        <a href="{{ route('locations.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <a href="{{ route('locations.edit', $location) }}" class="btn btn-warning text-white">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informasi Lokasi</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td class="fw-bold">Desa</td>
                                <td>{{ $location->nama_desa }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Kecamatan</td>
                                <td>{{ $location->nama_kecamatan }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Kabupaten</td>
                                <td>{{ $location->nama_kabupaten }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Provinsi</td>
                                <td>{{ $location->nama_provinsi }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Koordinat</td>
                                <td>{{ $location->latitude }}, {{ $location->longitude }}</td>
                            </tr>
                        </table>

                        @if($location->deskripsi)
                            <div class="mt-3">
                                <h6 class="fw-bold">Deskripsi</h6>
                                <p class="text-muted">{{ $location->deskripsi }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Statistik</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="border rounded p-3 text-center">
                                    <h3 class="mb-1">{{ $location->kelompok ? $location->kelompok->count() : 0 }}</h3>
                                    <p class="text-muted mb-0">Kelompok KKN</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3 text-center">
                                    <h3 class="mb-1">{{ $location->kelompok ? $location->kelompok->sum(function($k) { return $k->mahasiswa ? $k->mahasiswa->count() : 0; }) : 0 }}</h3>
                                    <p class="text-muted mb-0">Mahasiswa</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Lokasi di Peta</h5>
                    </div>
                    <div class="card-body p-0">
                        <div id="map" style="height: 400px;"></div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Daftar Kelompok KKN</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="groupsTable">
                                <thead>
                                    <tr>
                                        <th>Nama Kelompok</th>
                                        <th>DPL</th>
                                        <th>Jumlah Mahasiswa</th>
                                        <th>Total Logbook</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($location->kelompok ?? [] as $kelompok)
                                        <tr>
                                            <td>{{ $kelompok->nama_kelompok }}</td>
                                            <td>
                                                @if($kelompok->dpl)
                                                    <div class="d-flex align-items-center">
                                                        @if($kelompok->dpl->foto_profil)
                                                            <img src="{{ asset('storage/'.$kelompok->dpl->foto_profil) }}" 
                                                                 alt="{{ $kelompok->dpl->name }}" 
                                                                 class="rounded-circle me-2"
                                                                 width="30" height="30">
                                                        @else
                                                            <div class="bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                                 style="width: 30px; height: 30px;">
                                                                <span class="text-white" style="font-size: 12px;">
                                                                    {{ substr($kelompok->dpl->name, 0, 1) }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                        {{ $kelompok->dpl->name }}
                                                    </div>
                                                @else
                                                    <span class="text-muted">Belum ditentukan</span>
                                                @endif
                                            </td>
                                            <td>{{ $kelompok->mahasiswa ? $kelompok->mahasiswa->count() : 0 }}</td>
                                            <td>{{ $kelompok->logbooks ? $kelompok->logbooks->count() : 0 }}</td>
                                            <td>
                                                <a href="{{ route('groups.show', $kelompok) }}" 
                                                   class="btn btn-sm btn-info text-white">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Belum ada kelompok KKN</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
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

        // Add marker
        L.marker([{{ $location->latitude }}, {{ $location->longitude }}])
            .addTo(map)
            .bindPopup(`
                <h6>{{ $location->nama_desa }}</h6>
                <p class="mb-1">{{ $location->nama_kecamatan }}, {{ $location->nama_kabupaten }}</p>
                <div class="mt-2">
                    <span class="badge bg-primary">{{ $location->kelompok ? $location->kelompok->count() : 0 }} Kelompok</span>
                    <span class="badge bg-info">{{ $location->kelompok ? $location->kelompok->sum(function($k) { return $k->mahasiswa ? $k->mahasiswa->count() : 0; }) : 0 }} Mahasiswa</span>
                </div>
            `)
            .openPopup();

        // Initialize DataTable
        $(document).ready(function() {
            $('#groupsTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                },
            });
        });
    </script>
    @endpush
</x-app-layout> 
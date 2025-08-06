<x-app-layout>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">Lokasi KKN</h2>
                        <p class="text-muted">Kelola data lokasi KKN</p>
                    </div>
                    <div>
                        <a href="{{ route('locations.map') }}" class="btn btn-info text-white me-2">
                            <i class="fas fa-map-marked-alt me-2"></i>Lihat Peta
                        </a>
                        <a href="{{ route('locations.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Lokasi
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="locationsTable">
                                <thead>
                                    <tr>
                                        <th>Desa</th>
                                        <th>Kecamatan</th>
                                        <th>Kabupaten</th>
                                        <th>Provinsi</th>
                                        <th>Koordinat</th>
                                        <th>Total Kelompok</th>
                                        <th>Total Mahasiswa</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($locations as $location)
                                    <tr>
                                        <td>{{ $location->nama_desa }}</td>
                                        <td>{{ $location->nama_kecamatan }}</td>
                                        <td>{{ $location->nama_kabupaten }}</td>
                                        <td>{{ $location->nama_provinsi }}</td>
                                        <td>
                                            <span class="d-inline-block text-truncate" style="max-width: 150px;">
                                                {{ $location->latitude }}, {{ $location->longitude }}
                                            </span>
                                            <button type="button" 
                                                    class="btn btn-sm btn-info text-white ms-2"
                                                    onclick="showMap({{ $location->latitude }}, {{ $location->longitude }}, '{{ $location->nama_desa }}')">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </button>
                                        </td>
                                        <td>{{ $location->kelompok->count() }}</td>
                                        <td>{{ $location->kelompok->sum(function($kelompok) { return $kelompok->mahasiswa->count(); }) }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('locations.show', $location) }}" 
                                                   class="btn btn-sm btn-info text-white"
                                                   data-bs-toggle="tooltip"
                                                   title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('locations.edit', $location) }}" 
                                                   class="btn btn-sm btn-warning text-white"
                                                   data-bs-toggle="tooltip"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger"
                                                        data-bs-toggle="tooltip"
                                                        title="Hapus"
                                                        onclick="deleteLocation({{ $location->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>

                                            <form id="delete-form-{{ $location->id }}" 
                                                  action="{{ route('locations.destroy', $location) }}" 
                                                  method="POST" 
                                                  class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Map -->
    <div class="modal fade" id="mapModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lokasi di Peta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="map" style="height: 400px;"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // DataTables
        $(document).ready(function() {
            $('#locationsTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                },
            });
        });

        // Delete confirmation
        function deleteLocation(locationId) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data lokasi akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + locationId).submit();
                }
            });
        }

        // Leaflet Map in Modal
        let map;
        function showMap(lat, lng, title) {
            $('#mapModal').modal('show');
            
            $('#mapModal').on('shown.bs.modal', function() {
                if (map) {
                    map.remove();
                }

                map = L.map('map').setView([lat, lng], 13);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                L.marker([lat, lng])
                    .addTo(map)
                    .bindPopup(title)
                    .openPopup();

                setTimeout(() => {
                    map.invalidateSize();
                }, 100);
            });
        }
    </script>
    @endpush
</x-app-layout> 
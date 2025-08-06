<x-app-layout>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">Kelompok KKN</h2>
                        <p class="text-muted">Kelola data kelompok KKN</p>
                    </div>
                    <div>
                        <a href="{{ route('groups.map') }}" class="btn btn-info text-white me-2">
                            <i class="fas fa-map-marked-alt me-2"></i>Lihat Peta
                        </a>
                        <a href="{{ route('groups.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Kelompok
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
                            <table class="table table-hover" id="groupsTable">
                                <thead>
                                    <tr>
                                        <th>Nama Kelompok</th>
                                        <th>Angkatan</th>
                                        <th>Lokasi</th>
                                        <th>DPL</th>
                                        <th>Jumlah Mahasiswa</th>
                                        <th>Total Logbook</th>
                                        <th>Total Absensi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($groups as $group)
                                    <tr>
                                        <td>{{ $group->nama_kelompok }}</td>
                                        <td>{{ $group->angkatan->nama_angkatan }}</td>
                                        <td>
                                            {{ $group->lokasi->nama_desa }}, 
                                            {{ $group->lokasi->nama_kecamatan }}
                                            <button type="button" 
                                                    class="btn btn-sm btn-info text-white ms-2"
                                                    onclick="showMap({{ $group->lokasi->latitude }}, {{ $group->lokasi->longitude }}, '{{ $group->nama_kelompok }}')">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </button>
                                        </td>
                                        <td>
                                            @if($group->dpl)
                                                <div class="d-flex align-items-center">
                                                    @if($group->dpl->foto_profil)
                                                        <img src="{{ asset('storage/'.$group->dpl->foto_profil) }}" 
                                                             alt="{{ $group->dpl->name }}" 
                                                             class="rounded-circle me-2"
                                                             width="30" height="30">
                                                    @else
                                                        <div class="bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                             style="width: 30px; height: 30px;">
                                                            <span class="text-white" style="font-size: 12px;">
                                                                {{ substr($group->dpl->name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    {{ $group->dpl->name }}
                                                </div>
                                            @else
                                                <span class="text-muted">Belum ditentukan</span>
                                            @endif
                                        </td>
                                        <td>{{ $group->mahasiswa->count() }}</td>
                                        <td>{{ $group->logbook_count }}</td>
                                        <td>{{ $group->absensi_count }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('groups.show', $group) }}" 
                                                   class="btn btn-sm btn-info text-white"
                                                   data-bs-toggle="tooltip"
                                                   title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('groups.edit', $group) }}" 
                                                   class="btn btn-sm btn-warning text-white"
                                                   data-bs-toggle="tooltip"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger"
                                                        data-bs-toggle="tooltip"
                                                        title="Hapus"
                                                        onclick="deleteGroup({{ $group->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>

                                            <form id="delete-form-{{ $group->id }}" 
                                                  action="{{ route('groups.destroy', $group) }}" 
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
            $('#groupsTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                },
            });
        });

        // Delete confirmation
        function deleteGroup(groupId) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data kelompok akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + groupId).submit();
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
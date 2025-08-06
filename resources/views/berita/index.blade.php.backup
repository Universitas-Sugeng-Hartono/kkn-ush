<x-app-layout>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">Manajemen Berita</h2>
                        <p class="text-muted">Kelola berita dan informasi</p>
                    </div>
                    <div>
                        <a href="{{ route('berita.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Berita
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover" id="beritaTable">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Penulis</th>
                                        <th>Status</th>
                                        <th>Tanggal Publikasi</th>
                                        <th style="width: 150px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($berita as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3" style="width: 80px; height: 60px;">
                                                    <img src="{{ $item->thumbnail_url }}" 
                                                         alt="{{ $item->judul }}" 
                                                         class="rounded w-100 h-100"
                                                         style="object-fit: cover;">
                                                </div>
                                                <div>
                                                    <strong>{{ $item->judul }}</strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ Str::limit(strip_tags($item->konten), 100) }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->user->foto_profil)
                                                    <img src="{{ Storage::url($item->user->foto_profil) }}" 
                                                         alt="{{ $item->user->name }}" 
                                                         class="rounded-circle me-2"
                                                         width="40" height="40"
                                                         style="object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-primary text-white me-2 d-flex align-items-center justify-content-center"
                                                         style="width: 40px; height: 40px;">
                                                        {{ substr($item->user->name, 0, 1) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $item->user->name }}</div>
                                                    @if($item->user->nim)
                                                        <small class="text-muted">NIM: {{ $item->user->nim }}</small>
                                                        @if($item->user->jurusan)
                                                            <br><small class="text-muted">Jurusan: {{ ucfirst($item->user->jurusan) }}</small>
                                                        @endif
                                                    @elseif($item->user->nip)
                                                        <small class="text-muted">NIP: {{ $item->user->nip }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge {{ $item->status['class'] }} me-2">
                                                    <i class="fas {{ $item->status['icon'] }} me-1"></i>
                                                    {{ $item->status['text'] }}
                                                </span>
                                                @if($item->status['text'] === 'Terjadwal')
                                                    <small class="text-muted">
                                                        {{ Carbon\Carbon::parse($item->published_at)->format('d/m/Y H:i') }}
                                                    </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($item->published_at)
                                                @if(Carbon\Carbon::parse($item->published_at)->isFuture())
                                                    <span class="text-primary">
                                                        {{ Carbon\Carbon::parse($item->published_at)->format('d/m/Y H:i') }}
                                                    </span>
                                                @else
                                                    {{ Carbon\Carbon::parse($item->published_at)->format('d/m/Y H:i') }}
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('berita.show', $item) }}" 
                                                   class="btn btn-sm btn-info text-white"
                                                   data-bs-toggle="tooltip"
                                                   title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('berita.edit', $item) }}" 
                                                   class="btn btn-sm btn-warning text-white"
                                                   data-bs-toggle="tooltip"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger"
                                                        data-bs-toggle="tooltip"
                                                        title="Hapus"
                                                        onclick="deleteBerita({{ $item->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>

                                            <form id="delete-form-{{ $item->id }}" 
                                                  action="{{ route('berita.destroy', $item) }}" 
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

    @push('scripts')
    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#beritaTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                },
                order: [[3, 'desc']],
                columnDefs: [
                    { orderable: false, targets: 4 }
                ]
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });

        // Delete confirmation
        function deleteBerita(beritaId) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Berita akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + beritaId).submit();
                }
            });
        }
    </script>
    @endpush
</x-app-layout> 
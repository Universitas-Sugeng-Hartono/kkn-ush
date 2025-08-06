<x-app-layout>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">Manajemen Dokumen</h2>
                        <p class="text-muted">Kelola dokumen dan berkas KKN</p>
                    </div>
                    <div>
                        <a href="{{ route('dokumen.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-2"></i>Upload Dokumen
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
                            <table class="table table-hover" id="dokumenTable">
                                <thead>
                                    <tr>
                                        <th>Nama Dokumen</th>
                                        <th>Jenis</th>
                                        <th>Ukuran</th>
                                        <th>Diupload Oleh</th>
                                        <th>Tanggal Upload</th>
                                        <th style="width: 150px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dokumen as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    @php
                                                        $iconClass = match($item->jenis) {
                                                            'panduan' => 'fa-book text-info',
                                                            'template' => 'fa-file-alt text-primary',
                                                            'laporan' => 'fa-file-pdf text-danger',
                                                            'lainnya' => 'fa-file text-secondary',
                                                            default => 'fa-file text-secondary'
                                                        };
                                                    @endphp
                                                    <i class="fas {{ $iconClass }} fa-2x"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $item->nama }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $item->keterangan }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $badgeClass = match($item->jenis) {
                                                    'panduan' => 'bg-info',
                                                    'template' => 'bg-primary',
                                                    'laporan' => 'bg-danger',
                                                    'lainnya' => 'bg-secondary',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">
                                                {{ ucfirst($item->jenis) }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($item->ukuran / 1024, 2) }} KB</td>
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
                                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('dokumen.download', $item) }}" 
                                                   class="btn btn-sm btn-success"
                                                   data-bs-toggle="tooltip"
                                                   title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <a href="{{ route('dokumen.edit', $item) }}" 
                                                   class="btn btn-sm btn-warning text-white"
                                                   data-bs-toggle="tooltip"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger"
                                                        data-bs-toggle="tooltip"
                                                        title="Hapus"
                                                        onclick="deleteDokumen({{ $item->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>

                                            <form id="delete-form-{{ $item->id }}" 
                                                  action="{{ route('dokumen.destroy', $item) }}" 
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
            $('#dokumenTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                },
                order: [[4, 'desc']],
                columnDefs: [
                    { orderable: false, targets: 5 }
                ]
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });

        // Delete confirmation
        function deleteDokumen(dokumenId) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Dokumen akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + dokumenId).submit();
                }
            });
        }
    </script>
    @endpush
</x-app-layout> 
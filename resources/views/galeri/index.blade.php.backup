<x-app-layout>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <i class="fas fa-images me-2"></i>{{ __('Galeri Kegiatan') }}
                </h2>
                <p class="text-gray-600 text-sm">Kelola foto-foto kegiatan KKN</p>
            </div>
            <a href="{{ route('galeri.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Foto
            </a>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $galeri->count() }}</h4>
                                <p class="mb-0">Total Foto</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-images fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $galeri->where('aktif', true)->count() }}</h4>
                                <p class="mb-0">Foto Aktif</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-eye fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $galeri->where('aktif', false)->count() }}</h4>
                                <p class="mb-0">Foto Non-aktif</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-eye-slash fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $galeri->unique('user_id')->count() }}</h4>
                                <p class="mb-0">Uploader</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" id="searchInput" placeholder="Cari foto berdasarkan judul...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="non-aktif">Non-aktif</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="sortFilter">
                            <option value="urutan">Urutan (Terlama)</option>
                            <option value="created_desc">Terbaru</option>
                            <option value="created_asc">Terlama</option>
                            <option value="judul">Judul A-Z</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gallery Grid -->
        <div class="row g-4" id="galleryGrid">
            @forelse($galeri as $item)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 gallery-item" 
                     data-judul="{{ strtolower($item->judul) }}"
                     data-status="{{ $item->aktif ? 'aktif' : 'non-aktif' }}"
                     data-urutan="{{ $item->urutan }}"
                     data-created="{{ $item->created_at->timestamp }}">
                    <div class="card h-100 shadow-sm hover-shadow">
                        <!-- Image -->
                        <div class="position-relative">
                            <img src="{{ $item->gambar_url }}" 
                                alt="{{ $item->judul }}" 
                                class="card-img-top"
                                style="height: 200px; object-fit: cover;"
                                loading="lazy">
                            
                            <!-- Status Badge -->
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge {{ $item->aktif ? 'bg-success' : 'bg-danger' }}">
                                    <i class="fas {{ $item->aktif ? 'fa-eye' : 'fa-eye-slash' }} me-1"></i>
                                    {{ $item->aktif ? 'Aktif' : 'Non-aktif' }}
                                </span>
                            </div>

                            <!-- Overlay Actions -->
                            <div class="position-absolute top-0 start-0 m-2">
                                <div class="btn-group-vertical">
                                    <button type="button" 
                                        class="btn btn-sm btn-light"
                                        data-bs-toggle="modal"
                                        data-bs-target="#previewModal{{ $item->id }}"
                                        title="Preview">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('galeri.edit', $item) }}" 
                                        class="btn btn-sm btn-light"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                        class="btn btn-sm btn-light"
                                        onclick="confirmDelete({{ $item->id }}, '{{ $item->judul }}')"
                                        title="Hapus">
                                        <i class="fas fa-trash text-danger"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body">
                            <h5 class="card-title text-truncate" title="{{ $item->judul }}">
                                {{ $item->judul }}
                            </h5>
                            @if($item->deskripsi)
                                <p class="card-text small text-muted" style="height: 3em; overflow: hidden;">
                                    {{ Str::limit($item->deskripsi, 100) }}
                                </p>
                            @endif
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-sort me-1"></i>Urutan: {{ $item->urutan }}
                                </small>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>{{ $item->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>{{ $item->user->name }}
                                </small>
                            </div>
                        </div>

                        <!-- Hidden Delete Form -->
                        <form action="{{ route('galeri.destroy', $item) }}" 
                            method="POST" 
                            id="deleteForm{{ $item->id }}"
                            style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>

                    <!-- Preview Modal -->
                    <div class="modal fade" id="previewModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-image me-2"></i>{{ $item->judul }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-0">
                                    <img src="{{ $item->gambar_url }}" 
                                        alt="{{ $item->judul }}" 
                                        class="img-fluid w-100">
                                    @if($item->deskripsi)
                                        <div class="p-3">
                                            <h6 class="fw-bold">Deskripsi:</h6>
                                            <p class="mb-0">{{ $item->deskripsi }}</p>
                                        </div>
                                    @endif
                                    <div class="p-3 border-top">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <i class="fas fa-user me-1"></i>Uploader: {{ $item->user->name }}
                                                </small>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>{{ $item->created_at->format('d M Y H:i') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ route('galeri.edit', $item) }}" class="btn btn-primary">
                                        <i class="fas fa-edit me-2"></i>Edit
                                    </a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-images fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada foto</h5>
                            <p class="text-muted mb-3">Mulai tambahkan foto-foto kegiatan KKN untuk mengisi galeri</p>
                            <a href="{{ route('galeri.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Foto Pertama
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    @push('styles')
    <style>
        .hover-shadow:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
            transition: all 0.3s ease;
        }
        
        .gallery-item {
            transition: all 0.3s ease;
        }
        
        .gallery-item.hidden {
            display: none !important;
        }
        
        .btn-group-vertical .btn {
            margin-bottom: 2px;
        }
        
        .btn-group-vertical .btn:last-child {
            margin-bottom: 0;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Search and filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const sortFilter = document.getElementById('sortFilter');
            const galleryItems = document.querySelectorAll('.gallery-item');

            function filterGallery() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value;
                const sortValue = sortFilter.value;

                galleryItems.forEach(item => {
                    const judul = item.dataset.judul;
                    const status = item.dataset.status;
                    
                    let show = true;

                    // Search filter
                    if (searchTerm && !judul.includes(searchTerm)) {
                        show = false;
                    }

                    // Status filter
                    if (statusValue && status != statusValue) {
                        show = false;
                    }

                    if (show) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });

                // Sort items
                const visibleItems = Array.from(galleryItems).filter(item => !item.classList.contains('hidden'));
                const container = document.getElementById('galleryGrid');

                visibleItems.sort((a, b) => {
                    switch(sortValue) {
                        case 'created_desc':
                            return b.dataset.created - a.dataset.created;
                        case 'created_asc':
                            return a.dataset.created - b.dataset.created;
                        case 'judul':
                            return a.dataset.judul.localeCompare(b.dataset.judul);
                        default: // urutan
                            return a.dataset.urutan - b.dataset.urutan;
                    }
                });

                visibleItems.forEach(item => {
                    container.appendChild(item);
                });
            }

            searchInput.addEventListener('input', filterGallery);
            statusFilter.addEventListener('change', filterGallery);
            sortFilter.addEventListener('change', filterGallery);

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });

        // Delete confirmation
        function confirmDelete(id, judul) {
            if (confirm(`Apakah Anda yakin ingin menghapus foto "${judul}"?`)) {
                document.getElementById(`deleteForm${id}`).submit();
            }
        }
    </script>
    @endpush
</x-app-layout> 
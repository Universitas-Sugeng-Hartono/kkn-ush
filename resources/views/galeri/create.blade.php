<x-app-layout>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <i class="fas fa-plus-circle me-2"></i>{{ __('Tambah Foto Galeri') }}
                </h2>
                <p class="text-gray-600 text-sm">Upload foto baru ke galeri kegiatan KKN</p>
            </div>
            <a href="{{ route('galeri.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <!-- Flash Messages -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-upload me-2"></i>Form Upload Foto
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('galeri.store') }}" method="POST" enctype="multipart/form-data" id="galeriForm">
                            @csrf

                            <!-- Judul -->
                            <div class="mb-4">
                                <label for="judul" class="form-label fw-bold">
                                    <i class="fas fa-heading me-1"></i>Judul Foto <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('judul') is-invalid @enderror" 
                                       id="judul" 
                                       name="judul" 
                                       value="{{ old('judul') }}"
                                       required
                                       placeholder="Masukkan judul foto yang menarik">
                                @error('judul')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Judul yang menarik akan membuat foto lebih mudah ditemukan</small>
                            </div>

                            <!-- Upload Foto -->
                            <div class="mb-4">
                                <label for="gambar" class="form-label fw-bold">
                                    <i class="fas fa-image me-1"></i>Upload Foto <span class="text-danger">*</span>
                                </label>
                                <div class="upload-area @error('gambar') is-invalid @enderror" id="uploadArea">
                                    <input type="file" 
                                           class="form-control d-none" 
                                           id="gambar" 
                                           name="gambar"
                                           accept="image/*"
                                           onchange="previewImage(this)"
                                           required>
                                    <div class="upload-content text-center py-5">
                                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Drag & Drop foto di sini</h5>
                                        <p class="text-muted mb-3">atau</p>
                                        <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('gambar').click()">
                                            <i class="fas fa-folder-open me-2"></i>Pilih Foto
                                        </button>
                                        <p class="text-muted mt-2 mb-0">Format: JPEG, PNG, JPG. Maksimal 2MB</p>
                                    </div>
                                </div>
                                @error('gambar')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                
                                <!-- Image Preview -->
                                <div id="imagePreview" class="mt-3 d-none">
                                    <div class="card">
                                        <div class="card-body p-0">
                                            <img src="" alt="Preview" class="img-fluid rounded w-100" style="max-height: 300px; object-fit: cover;">
                                            <div class="p-3">
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeImage()">
                                                    <i class="fas fa-trash me-1"></i>Hapus Foto
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="mb-4">
                                <label for="deskripsi" class="form-label fw-bold">
                                    <i class="fas fa-align-left me-1"></i>Deskripsi
                                </label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                          id="deskripsi" 
                                          name="deskripsi" 
                                          rows="4"
                                          placeholder="Jelaskan kegiatan atau momen dalam foto ini (opsional)">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Deskripsi akan membantu pengunjung memahami konteks foto</small>
                            </div>

                            <!-- Urutan -->
                            <div class="mb-4">
                                <label for="urutan" class="form-label fw-bold">
                                    <i class="fas fa-sort me-1"></i>Urutan Tampilan
                                </label>
                                <input type="number" 
                                       class="form-control @error('urutan') is-invalid @enderror" 
                                       id="urutan" 
                                       name="urutan" 
                                       value="{{ old('urutan', 0) }}"
                                       min="0"
                                       placeholder="0">
                                @error('urutan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Angka yang lebih kecil akan ditampilkan terlebih dahulu</small>
                            </div>

                            <!-- Status Aktif -->
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input type="checkbox" 
                                           class="form-check-input @error('aktif') is-invalid @enderror" 
                                           id="aktif" 
                                           name="aktif" 
                                           value="1"
                                           {{ old('aktif', true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="aktif">
                                        <i class="fas fa-eye me-1"></i>Foto Aktif
                                    </label>
                                    <small class="text-muted d-block">Foto aktif akan ditampilkan di galeri publik</small>
                                </div>
                                @error('aktif')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('galeri.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-2"></i>Simpan Foto
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Informasi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6 class="fw-bold text-primary">
                                <i class="fas fa-file-image me-2"></i>Format yang Didukung
                            </h6>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="far fa-file-image text-success me-2"></i>JPEG Image
                                </li>
                                <li class="mb-2">
                                    <i class="far fa-file-image text-info me-2"></i>PNG Image
                                </li>
                                <li>
                                    <i class="far fa-file-image text-warning me-2"></i>JPG Image
                                </li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold text-primary">
                                <i class="fas fa-weight-hanging me-2"></i>Ukuran Maksimal
                            </h6>
                            <p class="mb-0 text-muted">2 MB per foto</p>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold text-primary">
                                <i class="fas fa-user me-2"></i>Uploader
                            </h6>
                            <p class="mb-0 fw-bold">{{ Auth::user()->name }}</p>
                            <small class="text-muted">{{ now()->format('d M Y H:i') }}</small>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Tips:</strong> Gunakan foto berkualitas tinggi untuk hasil terbaik
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .upload-area:hover {
            border-color: #0d6efd;
            background-color: #f8f9fa;
        }
        
        .upload-area.dragover {
            border-color: #0d6efd;
            background-color: #e7f3ff;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Drag and drop functionality
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('gambar');

        uploadArea.addEventListener('click', () => fileInput.click());

        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                previewImage(fileInput);
            }
        });

        // Image preview functionality
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const image = preview.querySelector('img');
            const submitBtn = document.getElementById('submitBtn');
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                // Validate file size
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran foto maksimal 2MB!');
                    input.value = '';
                    return;
                }
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    alert('Format foto harus JPEG, PNG, atau JPG!');
                    input.value = '';
                    return;
                }
                // Kompresi dan preview hasil kompresi
                compressImage(file, function(compressed) {
                    compressedFile = compressed;
                const reader = new FileReader();
                reader.onload = function(e) {
                    image.src = e.target.result;
                    preview.classList.remove('d-none');
                    submitBtn.disabled = false;
                }
                    reader.readAsDataURL(compressed);
                });
            } else {
                image.src = '';
                preview.classList.add('d-none');
                submitBtn.disabled = true;
            }
        }

        function removeImage() {
            const fileInput = document.getElementById('gambar');
            const preview = document.getElementById('imagePreview');
            const submitBtn = document.getElementById('submitBtn');
            
            fileInput.value = '';
            preview.classList.add('d-none');
            submitBtn.disabled = true;
        }

        function compressImage(file, callback) {
            const maxWidth = 800;
            const quality = 0.5;
            const reader = new FileReader();
            reader.onload = function(event) {
                const img = new Image();
                img.onload = function() {
                    let scale = Math.min(maxWidth / img.width, 1);
                    let canvas = document.createElement('canvas');
                    canvas.width = img.width * scale;
                    canvas.height = img.height * scale;
                    let ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    canvas.toBlob(function(blob) {
                        callback(new File([blob], file.name, {type: 'image/jpeg'}));
                    }, 'image/jpeg', quality);
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }

        const fileInput = document.getElementById('gambar');
        const form = document.getElementById('galeriForm');
        let compressedFile = null;

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.size > 2 * 1024 * 1024) {
                alert('Ukuran file tidak boleh lebih dari 2MB!');
                fileInput.value = '';
                return;
            }
            if (file && file.type.startsWith('image/')) {
                compressImage(file, function(compressed) {
                    compressedFile = compressed;
                    // Preview tetap jalan
                    const preview = document.getElementById('imagePreview');
                    const image = preview.querySelector('img');
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        image.src = e.target.result;
                        preview.classList.remove('d-none');
                        document.getElementById('submitBtn').disabled = false;
                    }
                    reader.readAsDataURL(compressed);
                });
            }
        });

        form.addEventListener('submit', function(e) {
            if (compressedFile) {
                e.preventDefault();
                const formData = new FormData(form);
                formData.delete('gambar');
                formData.append('gambar', compressedFile);
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(res => res.redirected ? window.location.href = res.url : res.json().then(data => alert(data.message)));
            }
        });

        // Form validation
        document.getElementById('galeriForm').addEventListener('submit', function(e) {
            const judul = document.getElementById('judul').value.trim();
            const gambar = document.getElementById('gambar').files[0];
            
            if (!judul) {
                e.preventDefault();
                alert('Judul foto harus diisi!');
                document.getElementById('judul').focus();
                return;
            }
            
            if (!gambar) {
                e.preventDefault();
                alert('Pilih foto terlebih dahulu!');
                document.getElementById('gambar').focus();
                return;
            }
            
            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
        });
    </script>
    @endpush
</x-app-layout> 
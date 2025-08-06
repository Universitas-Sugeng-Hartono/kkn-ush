<x-app-layout>
    <style>
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .py-12 {
                padding: 1rem;
            }
            
            .max-w-7xl {
                max-width: 100%;
            }
            
            .card {
                margin-bottom: 15px;
            }
            
            .card-body {
                padding: 15px;
            }
            
            /* Text adjustments */
            h2.font-semibold {
                font-size: 1.5rem;
            }
            
            h5.card-title {
                font-size: 1.1rem;
            }
            
            h4 {
                font-size: 1.2rem;
            }
            
            /* Profile image adjustments */
            .rounded-circle {
                width: 120px !important;
                height: 120px !important;
            }
            
            .bg-primary.rounded-circle {
                width: 120px !important;
                height: 120px !important;
                font-size: 36px !important;
            }
            
            /* Form controls */
            .form-control, .form-select {
                font-size: 14px;
                padding: 8px 12px;
            }
            
            /* Button adjustments */
            .btn {
                font-size: 14px;
                padding: 8px 12px;
            }
            
            .btn-sm {
                font-size: 12px;
                padding: 6px 10px;
            }
            
            /* Grid adjustments */
            .row .col-md-4,
            .row .col-md-8 {
                margin-bottom: 15px;
            }
            
            /* Stack buttons vertically */
            .d-flex.align-items-center.gap-4 {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .d-flex.align-items-center.gap-4 > * {
                width: 100%;
            }
            
            /* Modal adjustments */
            .modal-dialog {
                margin: 0.5rem;
                max-width: calc(100% - 1rem);
            }
            
            .modal-body {
                padding: 1rem;
            }
            
            .modal-header {
                padding: 0.75rem 1rem;
            }
            
            .modal-footer {
                padding: 0.75rem 1rem;
            }
        }
        
        /* Extra small devices */
        @media (max-width: 576px) {
            .py-12 {
                padding: 0.5rem;
            }
            
            .card-body {
                padding: 10px;
            }
            
            h2.font-semibold {
                font-size: 1.3rem;
            }
            
            h5.card-title {
                font-size: 1rem;
            }
            
            h4 {
                font-size: 1.1rem;
            }
            
            .rounded-circle {
                width: 100px !important;
                height: 100px !important;
            }
            
            .bg-primary.rounded-circle {
                width: 100px !important;
                height: 100px !important;
                font-size: 32px !important;
            }
            
            .form-control, .form-select {
                font-size: 13px;
                padding: 6px 10px;
            }
            
            .btn {
                font-size: 13px;
                padding: 6px 10px;
            }
            
            .btn-sm {
                font-size: 11px;
                padding: 4px 8px;
            }
            
            .modal-dialog {
                margin: 0.25rem;
                max-width: calc(100% - 0.5rem);
            }
            
            .modal-body {
                padding: 0.75rem;
            }
            
            .modal-header {
                padding: 0.5rem 0.75rem;
            }
            
            .modal-footer {
                padding: 0.5rem 0.75rem;
            }
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="mb-4">
                                @if(auth()->user()->photo)
                                    <img src="{{ Storage::url(auth()->user()->photo) }}" 
                                         alt="{{ auth()->user()->name }}" 
                                         class="rounded-circle mx-auto"
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center bg-primary text-white"
                                         style="width: 150px; height: 150px; font-size: 48px;">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <h4 class="mb-1">{{ auth()->user()->name }}</h4>
                            <p class="text-muted">{{ auth()->user()->roles->first()->name ?? 'User' }}</p>
                            @if(auth()->user()->nim)
                                <p class="text-muted mb-2">NIM: {{ auth()->user()->nim }}</p>
                            @endif
                            @if(auth()->user()->nip)
                                <p class="text-muted mb-2">NIP: {{ auth()->user()->nip }}</p>
                            @endif
                            @if(auth()->user()->jurusan)
                                <p class="text-muted mb-2">Jurusan: {{ ucfirst(auth()->user()->jurusan) }}</p>
                            @endif
                            <button type="button" 
                                    class="btn btn-primary btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#updatePhotoModal">
                                <i class="fas fa-camera me-2"></i>Ubah Foto
                            </button>
                        </div>
                    </div>

                    <!-- Modal Update Photo -->
                    <div class="modal fade" id="updatePhotoModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('profile.update-photo') }}" 
                                      method="POST" 
                                      enctype="multipart/form-data">
                                    @csrf
                                    @method('patch')
                                    
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ubah Foto Profil</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="photo" class="form-label">Pilih Foto</label>
                                            <input type="file" 
                                                   class="form-control @error('photo') is-invalid @enderror" 
                                                   id="photo" 
                                                   name="photo"
                                                   accept="image/*"
                                                   required>
                                            @error('photo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Format: JPG, JPEG, PNG (Max. 2MB)</div>
                                            
                                            <!-- Image Preview -->
                                            <div id="photoPreview" class="mt-3 d-none">
                                                <img id="previewImage" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Informasi Profil</h5>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                                @csrf
                                @method('patch')

                                <div class="mb-3">
                                    <x-input-label for="name" :value="__('Nama')" />
                                    <x-text-input id="name" name="name" type="text" class="form-control" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <div class="mb-3">
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" name="email" type="email" class="form-control" :value="old('email', $user->email)" required autocomplete="username" />
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                </div>

                                @if(auth()->user()->hasRole('mahasiswa'))
                                <div class="mb-3">
                                    <x-input-label for="nim" :value="__('NIM')" />
                                    <x-text-input id="nim" name="nim" type="text" class="form-control" :value="old('nim', $user->nim)" readonly />
                                    <div class="form-text">NIM tidak dapat diubah</div>
                                </div>

                                <div class="mb-3">
                                    <x-input-label for="jurusan" :value="__('Jurusan')" />
                                    <select id="jurusan" name="jurusan" class="form-control @error('jurusan') is-invalid @enderror">
                                        <option value="">Pilih Jurusan</option>
                                        <option value="informatika" {{ old('jurusan', $user->jurusan) == 'informatika' ? 'selected' : '' }}>Informatika</option>
                                        <option value="bisnis digital" {{ old('jurusan', $user->jurusan) == 'bisnis digital' ? 'selected' : '' }}>Bisnis Digital</option>
                                        <option value="gizi" {{ old('jurusan', $user->jurusan) == 'gizi' ? 'selected' : '' }}>Gizi</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('jurusan')" />
                                </div>
                                @endif

                                @if(auth()->user()->hasRole('dpl'))
                                <div class="mb-3">
                                    <x-input-label for="nip" :value="__('NIP')" />
                                    <x-text-input id="nip" name="nip" type="text" class="form-control" :value="old('nip', $user->nip)" readonly />
                                    <div class="form-text">NIP tidak dapat diubah</div>
                                </div>
                                @endif

                                <div class="mb-3">
                                    <x-input-label for="no_hp" :value="__('No HP')" />
                                    <x-text-input id="no_hp" name="no_hp" type="text" class="form-control" :value="old('no_hp', $user->no_hp)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('no_hp')" />
                                </div>

                                <div class="mb-3">
                                    <x-input-label for="alamat" :value="__('Alamat')" />
                                    <textarea id="alamat" name="alamat" class="form-control" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
                                </div>

                                <div class="d-flex align-items-center gap-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>{{ __('Simpan') }}
                                    </button>

                                    @if (session('status') === 'profile-updated')
                                        <p class="text-success">{{ __('Tersimpan.') }}</p>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Update Password</h5>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                                @csrf
                                @method('put')

                                <div class="mb-3">
                                    <x-input-label for="current_password" :value="__('Password Saat Ini')" />
                                    <x-text-input id="current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" />
                                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                                </div>

                                <div class="mb-3">
                                    <x-input-label for="password" :value="__('Password Baru')" />
                                    <x-text-input id="password" name="password" type="password" class="form-control" autocomplete="new-password" />
                                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                                </div>

                                <div class="mb-3">
                                    <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />
                                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                                </div>

                                <div class="d-flex align-items-center gap-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-key me-2"></i>{{ __('Simpan') }}
                                    </button>

                                    @if (session('status') === 'password-updated')
                                        <p class="text-success">{{ __('Tersimpan.') }}</p>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Hapus Akun</h5>
                        </div>
                        <div class="card-body">
                            <div class="max-w-xl">
                                {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.') }}
                            </div>

                            <button type="button" 
                                    class="btn btn-danger mt-3" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#confirmUserDeletionModal">
                                <i class="fas fa-trash me-2"></i>{{ __('Hapus Akun') }}
                            </button>

                            <!-- Modal Konfirmasi Hapus -->
                            <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="post" action="{{ route('profile.destroy') }}">
                                            @csrf
                                            @method('delete')
                                            
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{ __('Apakah Anda yakin ingin menghapus akun?') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            
                                            <div class="modal-body">
                                                <p>
                                                    {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Silakan masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}
                                                </p>

                                                <div class="mt-3">
                                                    <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                                                    <x-text-input
                                                        id="password"
                                                        name="password"
                                                        type="password"
                                                        class="form-control"
                                                        placeholder="{{ __('Password') }}"
                                                    />
                                                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                                                </div>
                                            </div>
                                            
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    {{ __('Batal') }}
                                                </button>
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash me-2"></i>{{ __('Hapus Akun') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Kompresi gambar sebelum upload
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

        // Handle photo upload dengan kompresi
        const photoInput = document.getElementById('photo');
        const photoForm = document.querySelector('#updatePhotoModal form');
        let compressedPhoto = null;

        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.size > 2 * 1024 * 1024) {
                alert('Ukuran file tidak boleh lebih dari 2MB!');
                photoInput.value = '';
                return;
            }
            if (file && file.type.startsWith('image/')) {
                compressImage(file, function(compressed) {
                    compressedPhoto = compressed;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('photoPreview');
                        const previewImage = document.getElementById('previewImage');
                        previewImage.src = e.target.result;
                        preview.classList.remove('d-none');
                    };
                    reader.readAsDataURL(compressed);
                });
            }
        });

        // Handle form submission dengan file yang sudah dikompresi
        photoForm.addEventListener('submit', function(e) {
            if (compressedPhoto) {
                e.preventDefault();
                const formData = new FormData(photoForm);
                formData.delete('photo');
                formData.append('photo', compressedPhoto);
                
                fetch(photoForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(res => {
                    if (res.redirected) {
                        window.location.href = res.url;
                    } else {
                        res.json().then(data => {
                            if (data.message) {
                                alert(data.message);
                            }
                        });
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>

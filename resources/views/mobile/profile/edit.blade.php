@extends('layouts.mobile-app')

@section('content')
    <!-- Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h2>Edit Profile</h2>
                <p class="date-info">{{ now()->format('l, d F Y') }}</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('mobile.profile') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('status'))
        <div class="alert alert-success">
            @if(session('status') === 'profile-updated')
                <i class="fas fa-check-circle"></i>
                <span>Profile berhasil diperbarui!</span>
            @elseif(session('status') === 'photo-updated')
                <i class="fas fa-check-circle"></i>
                <span>Foto profil berhasil diperbarui!</span>
            @endif
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Profile Photo -->
    <div class="photo-section">
        <div class="photo-container">
            @if(Auth::user()->photo)
                <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Profile Photo" id="profilePhoto">
            @else
                <div class="photo-placeholder" id="photoPlaceholder">
                    <i class="fas fa-user"></i>
                </div>
            @endif
            <button type="button" class="photo-edit-btn" onclick="document.getElementById('photoInput').click()">
                <i class="fas fa-camera"></i>
            </button>
        </div>
        <input type="file" id="photoInput" name="photo" accept="image/*" style="display: none;">
    </div>

    <!-- Form -->
    <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        
        <!-- Hidden photo field for main form -->
        <input type="file" name="photo" id="profilePhotoInput" accept="image/*" style="display: none;">
        
        <div class="form-section">
            <h3>Personal Information</h3>
            
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}" required>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" required>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            @if(Auth::user()->nim)
            <div class="form-group">
                <label for="nim">NIM</label>
                <input type="text" id="nim" name="nim" class="form-control" value="{{ old('nim', Auth::user()->nim) }}" readonly>
                <small class="form-hint">NIM tidak dapat diubah</small>
            </div>

            <div class="form-group">
                <label for="jurusan">Jurusan</label>
                <select id="jurusan" name="jurusan" class="form-control">
                    <option value="">Pilih Jurusan</option>
                    <option value="informatika" {{ old('jurusan', Auth::user()->jurusan) == 'informatika' ? 'selected' : '' }}>Informatika</option>
                    <option value="bisnis digital" {{ old('jurusan', Auth::user()->jurusan) == 'bisnis digital' ? 'selected' : '' }}>Bisnis Digital</option>
                    <option value="gizi" {{ old('jurusan', Auth::user()->jurusan) == 'gizi' ? 'selected' : '' }}>Gizi</option>
                </select>
                @error('jurusan')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            @endif

            @if(Auth::user()->nip)
            <div class="form-group">
                <label for="nip">NIP</label>
                <input type="text" id="nip" name="nip" class="form-control" value="{{ old('nip', Auth::user()->nip) }}" readonly>
                <small class="form-hint">NIP tidak dapat diubah</small>
            </div>
            @endif
        </div>

        <div class="form-section">
            <h3>Contact Information</h3>
            
            <div class="form-group">
                <label for="no_hp">Phone Number</label>
                <input type="tel" id="no_hp" name="no_hp" class="form-control" value="{{ old('no_hp', Auth::user()->no_hp) }}" placeholder="Enter your phone number">
                @error('no_hp')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="alamat">Address</label>
                <textarea id="alamat" name="alamat" class="form-control" rows="3" placeholder="Enter your address">{{ old('alamat', Auth::user()->alamat) }}</textarea>
                @error('alamat')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i>
                <span id="submitText">Update Profile</span>
            </button>
        </div>
    </form>
@endsection

@push('styles')
<style>
    .photo-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f3f4;
        text-align: center;
    }

    .photo-container {
        position: relative;
        display: inline-block;
    }

    .photo-container img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #f1f3f4;
    }

    .photo-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0B1F3A 0%, #163057 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 4px solid #f1f3f4;
    }

    .photo-placeholder i {
        font-size: 3rem;
        color: white;
    }

    .photo-edit-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #F2B705;
        color: #0B1F3A;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(242, 183, 5, 0.3);
    }

    .photo-edit-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(242, 183, 5, 0.4);
    }

    .photo-edit-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .form-section {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f3f4;
    }

    .form-section h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background: #ffffff;
    }

    .form-control:focus {
        outline: none;
        border-color: #0B1F3A;
        box-shadow: 0 0 0 3px rgba(11, 31, 58, 0.1);
    }

    .form-control[readonly] {
        background: #f8f9fa;
        color: #6c757d;
    }

    .error-message {
        color: #dc3545;
        font-size: 0.75rem;
        margin-top: 0.25rem;
        display: block;
    }

    .form-hint {
        color: #6c757d;
        font-size: 0.75rem;
        margin-top: 0.25rem;
        display: block;
    }

    .form-actions {
        position: sticky;
        bottom: 0;
        background: white;
        padding: 1rem;
        margin: 1rem -1rem -1rem -1rem;
        border-top: 1px solid #f1f3f4;
        box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-submit {
        width: 100%;
        background: linear-gradient(135deg, #0B1F3A 0%, #163057 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 1rem;
        font-weight: 600;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(11, 31, 58, 0.3);
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .btn-back {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
    }

    /* Alert Styles */
    .alert {
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-success i {
        color: #28a745;
        font-size: 1rem;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert-danger i {
        color: #dc3545;
        font-size: 1rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.getElementById('photoInput');
    const profileForm = document.getElementById('profileForm');
    const profilePhotoInput = document.getElementById('profilePhotoInput');
    const submitBtn = document.getElementById('submitText');

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

    // Handle photo selection dengan kompresi
    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file && file.size > 2 * 1024 * 1024) {
            alert('Ukuran file tidak boleh lebih dari 2MB!');
            photoInput.value = '';
            return;
        }
        
        if (file && file.type.startsWith('image/')) {
            console.log('File selected:', file.name, file.size, file.type);
            
            // Kompresi gambar
            compressImage(file, function(compressed) {
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const photoPlaceholder = document.getElementById('photoPlaceholder');
                    const profilePhoto = document.getElementById('profilePhoto');
                    
                    if (photoPlaceholder) {
                        photoPlaceholder.style.display = 'none';
                    }
                    
                    if (profilePhoto) {
                        profilePhoto.src = e.target.result;
                    } else {
                        const newPhoto = document.createElement('img');
                        newPhoto.id = 'profilePhoto';
                        newPhoto.src = e.target.result;
                        newPhoto.alt = 'Profile Photo';
                        newPhoto.style.width = '120px';
                        newPhoto.style.height = '120px';
                        newPhoto.style.borderRadius = '50%';
                        newPhoto.style.objectFit = 'cover';
                        newPhoto.style.border = '4px solid #f1f3f4';
                        
                        const photoContainer = document.querySelector('.photo-container');
                        photoContainer.insertBefore(newPhoto, photoContainer.firstChild);
                    }
                };
                reader.readAsDataURL(compressed);
                
                // Set photo yang sudah dikompresi untuk main form
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(compressed);
                profilePhotoInput.files = dataTransfer.files;
                console.log('Compressed photo set for main form');
                
                // Update button text to indicate photo will be saved
                if (submitBtn) {
                    submitBtn.textContent = 'Update Profile (with photo)';
                }
            });
        } else {
            console.error('Invalid file type:', file ? file.type : 'no file');
            alert('Please select a valid image file (JPG, PNG, GIF)');
        }
    });

    // Form validation
    profileForm.addEventListener('submit', function(e) {
        submitBtn.textContent = 'Updating...';
        submitBtn.disabled = true;
    });

    // Reset button text when page loads
    window.addEventListener('load', function() {
        const submitBtn = document.getElementById('submitText');
        if (submitBtn) {
            submitBtn.textContent = 'Update Profile';
        }
    });
});
</script>
@endpush 
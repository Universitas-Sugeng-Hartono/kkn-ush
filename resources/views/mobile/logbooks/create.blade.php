@extends('layouts.mobile-app')

@section('content')
    <!-- Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h2>Add Logbook</h2>
                <p class="date-info">{{ now()->format('l, d F Y') }}</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('mobile.logbooks') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form id="logbookForm" action="{{ route('logbooks.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-section">
            <h3>Basic Information</h3>
            
            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" class="form-control" value="{{ old('tanggal', now()->format('Y-m-d')) }}" required>
                @error('tanggal')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="waktu_mulai">Waktu Mulai</label>
                    <input type="time" id="waktu_mulai" name="waktu_mulai" class="form-control" value="{{ old('waktu_mulai') }}" required>
                    @error('waktu_mulai')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="waktu_selesai">Waktu Selesai</label>
                    <input type="time" id="waktu_selesai" name="waktu_selesai" class="form-control" value="{{ old('waktu_selesai') }}" required>
                    @error('waktu_selesai')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="judul">Judul Kegiatan</label>
                <input type="text" id="judul" name="judul" class="form-control" value="{{ old('judul') }}" placeholder="Masukkan judul kegiatan" required>
                @error('judul')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="is_kelompok">Tipe Logbook</label>
                <select id="is_kelompok" name="is_kelompok" class="form-control" required>
                    <option value="0" {{ old('is_kelompok', request('tipe') === 'kelompok' ? '1' : '0') == '0' ? 'selected' : '' }}>Logbook Individu</option>
                    <option value="1" {{ old('is_kelompok', request('tipe') === 'kelompok' ? '1' : '0') == '1' ? 'selected' : '' }}>Logbook Kelompok (Sharing)</option>
                </select>
                @error('is_kelompok')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="jenis">Jenis Kegiatan</label>
                <select id="jenis" name="jenis" class="form-control" required>
                    <option value="">Pilih jenis kegiatan</option>
                    <option value="individu" {{ old('jenis') == 'individu' ? 'selected' : '' }}>Individu</option>
                    <option value="desa" {{ old('jenis') == 'desa' ? 'selected' : '' }}>Desa</option>
                    <option value="kecamatan" {{ old('jenis') == 'kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                </select>
                @error('jenis')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="lokasi">Lokasi</label>
                <input type="text" id="lokasi" name="lokasi" class="form-control" value="{{ old('lokasi') }}" placeholder="Masukkan lokasi kegiatan" required>
                @error('lokasi')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="keterangan">Deskripsi Kegiatan</label>
                <textarea id="keterangan" name="keterangan" class="form-control" rows="4" placeholder="Jelaskan detail kegiatan yang dilakukan" required>{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-section">
            <h3>Foto Kegiatan</h3>
            <p class="form-hint">Upload minimal 1 foto kegiatan (format: jpg, jpeg, png, max: 20MB per foto)</p>
            
            <div class="photo-upload-area" id="photoUploadArea">
                <div class="upload-placeholder" id="uploadPlaceholder">
                    <i class="fas fa-camera"></i>
                    <p>Tap untuk mengambil foto atau pilih dari galeri</p>
                </div>
                <input type="file" id="photos" name="photos[]" accept="image/*" multiple style="display: none;">
            </div>
            
            <div id="photoPreview" class="photo-preview"></div>
            
            @error('photos')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-section">
            <h3>File Lampiran (Opsional)</h3>
            <p class="form-hint">Upload file pendukung seperti PDF, DOC, DOCX, Excel, PowerPoint, atau gambar (max: 20MB per file)</p>
            
            <div class="attachment-upload-area" id="attachmentUploadArea">
                <div class="upload-placeholder" id="attachmentUploadPlaceholder">
                    <i class="fas fa-file-upload"></i>
                    <p>Tap untuk memilih file lampiran</p>
                    <small>Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, JPG, PNG, GIF</small>
                </div>
                <input type="file" id="attachments" name="attachments[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif" multiple style="display: none;">
            </div>
            
            <div id="attachmentPreview" class="attachment-preview"></div>
            
            @error('attachments')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-section">
            <h3>Status</h3>
            <div class="form-group">
                <label for="status">Status Logbook</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Simpan sementara)</option>
                    <option value="submitted" {{ old('status', 'submitted') == 'submitted' ? 'selected' : '' }}>Submit (Kirim ke DPL)</option>
                </select>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i>
                <span id="submitText">Simpan Logbook</span>
            </button>
        </div>
    </form>
@endsection

@push('styles')
<style>
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

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
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

    .form-control.error {
        border-color: #dc3545;
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
        margin-bottom: 1rem;
    }

    .photo-upload-area {
        border: 2px dashed #cbd5e0;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #f8f9fa;
    }

    .photo-upload-area:hover {
        border-color: #0B1F3A;
        background: #f1f3f4;
    }

    .upload-placeholder i {
        font-size: 2rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }

    .upload-placeholder p {
        color: #6c757d;
        margin: 0;
        font-size: 0.875rem;
    }

    .photo-preview {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .photo-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        aspect-ratio: 1;
    }

    .photo-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-remove {
        position: absolute;
        top: 0.25rem;
        right: 0.25rem;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.75rem;
    }

    .attachment-upload-area {
        border: 2px dashed #cbd5e0;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #f8f9fa;
    }

    .attachment-upload-area:hover {
        border-color: #0B1F3A;
        background: #f1f3f4;
    }

    .attachment-upload-placeholder i {
        font-size: 2rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }

    .attachment-upload-placeholder p {
        color: #6c757d;
        margin: 0;
        font-size: 0.875rem;
    }

    .attachment-preview {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .attachment-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem;
        background: #f8f9fa;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .attachment-item:hover {
        background: #f1f3f4;
        border-color: #cbd5e0;
    }

    .attachment-item .attachment-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex: 1;
        min-width: 0;
    }

    .attachment-item .attachment-content i {
        font-size: 1.5rem;
        color: #6c757d;
        flex-shrink: 0;
    }

    .attachment-item .attachment-name {
        font-size: 0.875rem;
        color: #1a202c;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        flex: 1;
        min-width: 0;
    }

    .attachment-item .attachment-size {
        font-size: 0.75rem;
        color: #6c757d;
        flex-shrink: 0;
    }

    .attachment-remove {
        position: absolute;
        top: 0.25rem;
        right: 0.25rem;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.75rem;
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

    @media (max-width: 480px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .photo-preview {
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        }
    }
</style>
@endpush

@push('scripts')
<script>
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

const photoInput = document.getElementById('photos');
const attachmentInput = document.getElementById('attachments');
const form = document.getElementById('logbookForm');
let compressedFiles = [];
let compressedAttachments = [];

photoInput.addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    
    // Validasi ukuran file
    for (const file of files) {
        if (file.size > 20 * 1024 * 1024) {
            alert('Ukuran file tidak boleh lebih dari 20MB!');
            photoInput.value = '';
            return;
        }
    }
    
    const photoPreview = document.getElementById('photoPreview');
    const startIndex = compressedFiles.length; // Mulai dari index foto yang sudah ada
    
    files.forEach((file, idx) => {
        const actualIndex = startIndex + idx;
        compressImage(file, function(compressed) {
            compressedFiles[actualIndex] = compressed;
            const reader = new FileReader();
            reader.onload = function(e) {
                const photoItem = document.createElement('div');
                photoItem.className = 'photo-item';
                photoItem.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="photo-remove" onclick="removePhoto(${actualIndex})">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                photoPreview.appendChild(photoItem);
            };
            reader.readAsDataURL(compressed);
        });
    });
    
    // Reset input untuk memungkinkan upload file yang sama lagi
    photoInput.value = '';
});

attachmentInput.addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    
    // Validasi ukuran file
    for (const file of files) {
        if (file.size > 20 * 1024 * 1024) { // 20MB
            alert('Ukuran file tidak boleh lebih dari 20MB!');
            attachmentInput.value = '';
            return;
        }
    }

    const attachmentPreview = document.getElementById('attachmentPreview');
    const startIndex = compressedAttachments.length; // Mulai dari index file yang sudah ada
    
    files.forEach((file, idx) => {
        const actualIndex = startIndex + idx;
        compressedAttachments[actualIndex] = file;
        
        const attachmentItem = document.createElement('div');
        attachmentItem.className = 'attachment-item';
        
        // Tentukan icon berdasarkan tipe file
        const fileExtension = file.name.split('.').pop().toLowerCase();
        let iconClass = 'fas fa-file';
        
        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
            iconClass = 'fas fa-image';
        } else if (['pdf'].includes(fileExtension)) {
            iconClass = 'fas fa-file-pdf';
        } else if (['doc', 'docx'].includes(fileExtension)) {
            iconClass = 'fas fa-file-word';
        } else if (['xls', 'xlsx'].includes(fileExtension)) {
            iconClass = 'fas fa-file-excel';
        } else if (['ppt', 'pptx'].includes(fileExtension)) {
            iconClass = 'fas fa-file-powerpoint';
        } else if (['txt'].includes(fileExtension)) {
            iconClass = 'fas fa-file-alt';
        }
        
        attachmentItem.innerHTML = `
            <div class="attachment-content">
                <i class="${iconClass}"></i>
                <span class="attachment-name">${file.name}</span>
                <span class="attachment-size">${formatFileSize(file.size)}</span>
            </div>
            <button type="button" class="attachment-remove" onclick="removeAttachment(${actualIndex})">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        attachmentPreview.appendChild(attachmentItem);
    });
    
    // Reset input untuk memungkinkan upload file yang sama lagi
    attachmentInput.value = '';
});

form.addEventListener('submit', function(e) {
    if (compressedFiles.length > 0) {
        e.preventDefault();
        const formData = new FormData(form);
        formData.delete('photos[]');
        compressedFiles.forEach(f => formData.append('photos[]', f));
        formData.delete('attachments[]');
        compressedAttachments.forEach(f => formData.append('attachments[]', f));
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(res => res.redirected ? window.location.href = res.url : res.json().then(data => alert(data.message)));
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const photoUploadArea = document.getElementById('photoUploadArea');
    const photoPreview = document.getElementById('photoPreview');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const attachmentUploadArea = document.getElementById('attachmentUploadArea');
    const attachmentPreview = document.getElementById('attachmentPreview');
    const attachmentUploadPlaceholder = document.getElementById('attachmentUploadPlaceholder');
    const form = document.getElementById('logbookForm');
    const submitBtn = document.getElementById('submitText');

    // Handle photo upload
    photoUploadArea.addEventListener('click', function() {
        photoInput.click();
    });

    // Handle attachment upload
    attachmentUploadArea.addEventListener('click', function() {
        attachmentInput.click();
    });

    // Form validation
    form.addEventListener('submit', function(e) {
        if (compressedFiles.length === 0) {
            e.preventDefault();
            alert('Harap upload minimal 1 foto kegiatan');
            return;
        }

        submitBtn.textContent = 'Menyimpan...';
        submitBtn.disabled = true;
    });

    // Time validation
    const waktuMulai = document.getElementById('waktu_mulai');
    const waktuSelesai = document.getElementById('waktu_selesai');

    waktuSelesai.addEventListener('change', function() {
        if (waktuMulai.value && waktuSelesai.value) {
            if (waktuSelesai.value <= waktuMulai.value) {
                alert('Waktu selesai harus lebih besar dari waktu mulai');
                waktuSelesai.value = '';
            }
        }
    });

    // Set tanggal dari query string jika ada
    const urlParams = new URLSearchParams(window.location.search);
    const tgl = urlParams.get('tanggal');
    console.log('URL params:', window.location.search, 'tanggal:', tgl);
    if (tgl) {
        document.getElementById('tanggal').value = tgl;
        console.log('Set tanggal input to:', tgl);
    }
});

function removePhoto(index) {
    // Hapus dari array compressedFiles
    compressedFiles.splice(index, 1);
    
    // Re-render semua preview
    const photoPreview = document.getElementById('photoPreview');
    photoPreview.innerHTML = '';
    
    compressedFiles.forEach((file, idx) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const photoItem = document.createElement('div');
            photoItem.className = 'photo-item';
            photoItem.innerHTML = `
                <img src="${e.target.result}" alt="Preview">
                <button type="button" class="photo-remove" onclick="removePhoto(${idx})">
                    <i class="fas fa-times"></i>
                </button>
            `;
            photoPreview.appendChild(photoItem);
        };
        reader.readAsDataURL(file);
    });
    
    // Show placeholder if no photos
    if (compressedFiles.length === 0) {
        document.getElementById('uploadPlaceholder').style.display = 'block';
    }
}

function removeAttachment(index) {
    // Hapus dari array compressedAttachments
    compressedAttachments.splice(index, 1);
    
    // Re-render semua preview
    const attachmentPreview = document.getElementById('attachmentPreview');
    attachmentPreview.innerHTML = '';
    
    compressedAttachments.forEach((file, idx) => {
        const attachmentItem = document.createElement('div');
        attachmentItem.className = 'attachment-item';
        
        // Tentukan icon berdasarkan tipe file
        const fileExtension = file.name.split('.').pop().toLowerCase();
        let iconClass = 'fas fa-file';
        
        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
            iconClass = 'fas fa-image';
        } else if (['pdf'].includes(fileExtension)) {
            iconClass = 'fas fa-file-pdf';
        } else if (['doc', 'docx'].includes(fileExtension)) {
            iconClass = 'fas fa-file-word';
        } else if (['xls', 'xlsx'].includes(fileExtension)) {
            iconClass = 'fas fa-file-excel';
        } else if (['ppt', 'pptx'].includes(fileExtension)) {
            iconClass = 'fas fa-file-powerpoint';
        } else if (['txt'].includes(fileExtension)) {
            iconClass = 'fas fa-file-alt';
        }
        
        attachmentItem.innerHTML = `
            <div class="attachment-content">
                <i class="${iconClass}"></i>
                <span class="attachment-name">${file.name}</span>
                <span class="attachment-size">${formatFileSize(file.size)}</span>
            </div>
            <button type="button" class="attachment-remove" onclick="removeAttachment(${idx})">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        attachmentPreview.appendChild(attachmentItem);
    });
    
    // Show placeholder if no attachments
    if (compressedAttachments.length === 0) {
        document.getElementById('attachmentUploadPlaceholder').style.display = 'block';
    }
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Dynamic adjust jenis dropdown options based on is_kelompok select list
const isKelompokSelect = document.getElementById('is_kelompok');
const jenisSelect = document.getElementById('jenis');

function adjustJenisOptions() {
    const isKelompok = isKelompokSelect.value;
    const options = jenisSelect.options;
    
    if (isKelompok === '1') {
        for (let i = 0; i < options.length; i++) {
            if (options[i].value === 'individu') {
                options[i].style.display = 'none';
                options[i].disabled = true;
            } else if (options[i].value !== '') {
                options[i].style.display = 'block';
                options[i].disabled = false;
            }
        }
        if (jenisSelect.value === 'individu') {
            jenisSelect.value = 'desa';
        }
    } else {
        for (let i = 0; i < options.length; i++) {
            if (options[i].value === 'desa' || options[i].value === 'kecamatan') {
                options[i].style.display = 'none';
                options[i].disabled = true;
            } else if (options[i].value !== '') {
                options[i].style.display = 'block';
                options[i].disabled = false;
            }
        }
        jenisSelect.value = 'individu';
    }
}

if (isKelompokSelect && jenisSelect) {
    isKelompokSelect.addEventListener('change', adjustJenisOptions);
    adjustJenisOptions();
}
</script>
@endpush 
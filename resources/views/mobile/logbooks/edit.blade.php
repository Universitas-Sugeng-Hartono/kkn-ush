@extends('layouts.mobile-app')

@section('content')
    <!-- Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h2>Edit Logbook</h2>
                <p class="date-info">{{ $logbook->created_at->format('l, d F Y') }}</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('mobile.logbooks') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="form-container">
        <form action="{{ route('logbooks.update', $logbook) }}" method="POST" enctype="multipart/form-data" id="editLogbookForm">
            <input type="hidden" name="redirect_to" value="mobile">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="judul">Judul Kegiatan *</label>
                <input type="text" id="judul" name="judul" value="{{ $logbook->judul }}" required>
            </div>

            <div class="form-group">
                <label for="jenis">Jenis Kegiatan *</label>
                <select id="jenis" name="jenis" required>
                    <option value="">Pilih jenis kegiatan</option>
                    <option value="individu" {{ $logbook->jenis === 'individu' ? 'selected' : '' }}>Individu</option>
                    <option value="desa" {{ $logbook->jenis === 'desa' ? 'selected' : '' }}>Desa</option>
                    <option value="kecamatan" {{ $logbook->jenis === 'kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                </select>
            </div>

            <div class="form-group">
                <label for="tanggal">Tanggal *</label>
                <input type="date" id="tanggal" name="tanggal" value="{{ $logbook->tanggal->format('Y-m-d') }}" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="waktu_mulai">Waktu Mulai *</label>
                    <input type="time" id="waktu_mulai" name="waktu_mulai" value="{{ $logbook->waktu_mulai }}" required>
                </div>
                <div class="form-group">
                    <label for="waktu_selesai">Waktu Selesai *</label>
                    <input type="time" id="waktu_selesai" name="waktu_selesai" value="{{ $logbook->waktu_selesai }}" required>
                </div>
            </div>

            <div class="form-group">
                <label for="lokasi">Lokasi *</label>
                <input type="text" id="lokasi" name="lokasi" value="{{ $logbook->lokasi }}" required>
            </div>

            <div class="form-group">
                <label for="keterangan">Deskripsi Kegiatan *</label>
                <textarea id="keterangan" name="keterangan" rows="6" required>{{ $logbook->keterangan }}</textarea>
            </div>

            <div class="form-group">
                <label for="photos">Foto Kegiatan (Opsional)</label>
                <div class="photo-upload-area" id="photoUploadArea">
                    <div class="upload-placeholder" id="uploadPlaceholder">
                        <i class="fas fa-camera"></i>
                        <p>Tap untuk mengambil foto atau pilih dari galeri</p>
                    </div>
                    <input type="file" id="photos" name="photos[]" accept="image/*" multiple style="display: none;">
                </div>
                <small class="form-text">Pilih satu atau lebih foto kegiatan. Format: JPG, PNG, GIF. Maksimal 10MB per foto (akan dikompresi otomatis).</small>
                <div id="photoPreview" class="photo-preview"></div>
            </div>

            <!-- Existing Photos -->
            @if($logbook->photos->count() > 0)
            <div class="existing-photos">
                <label>Foto Saat Ini:</label>
                <div class="photo-grid">
                    @foreach($logbook->photos as $photo)
                    <div class="photo-item">
                        <img src="{{ asset('storage/' . $photo->path) }}" alt="Foto kegiatan">
                        <div class="photo-overlay">
                            <button type="button" class="btn-delete-photo" onclick="deletePhoto({{ $photo->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="form-group">
                <label for="attachments">File Lampiran (Opsional)</label>
                <div class="attachment-upload-area" id="attachmentUploadArea">
                    <div class="upload-placeholder" id="attachmentUploadPlaceholder">
                        <i class="fas fa-file-upload"></i>
                        <p>Tap untuk memilih file lampiran</p>
                        <small>Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, JPG, PNG, GIF</small>
                    </div>
                    <input type="file" id="attachments" name="attachments[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif" multiple style="display: none;">
                </div>
                <small class="form-text">Upload file pendukung seperti PDF, DOC, DOCX, Excel, PowerPoint, atau gambar (max: 10MB per file)</small>
                <div id="attachmentPreview" class="attachment-preview"></div>
            </div>

            <!-- Existing Attachments -->
            @if($logbook->attachments && count($logbook->attachments) > 0)
            <div class="existing-attachments">
                <label>File Lampiran Saat Ini:</label>
                <div class="attachment-list">
                    @foreach($logbook->attachments as $index => $attachment)
                        <div class="attachment-item">
                            <div class="attachment-content">
                                @php
                                    $extension = strtolower(pathinfo($attachment['name'], PATHINFO_EXTENSION));
                                    $iconClass = 'fas fa-file';
                                    if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                        $iconClass = 'fas fa-image';
                                    } elseif ($extension === 'pdf') {
                                        $iconClass = 'fas fa-file-pdf';
                                    } elseif (in_array($extension, ['doc', 'docx'])) {
                                        $iconClass = 'fas fa-file-word';
                                    } elseif (in_array($extension, ['xls', 'xlsx'])) {
                                        $iconClass = 'fas fa-file-excel';
                                    } elseif (in_array($extension, ['ppt', 'pptx'])) {
                                        $iconClass = 'fas fa-file-powerpoint';
                                    } elseif ($extension === 'txt') {
                                        $iconClass = 'fas fa-file-alt';
                                    }
                                @endphp
                                <i class="{{ $iconClass }}"></i>
                                <div class="attachment-info">
                                    <div class="attachment-name">{{ $attachment['name'] }}</div>
                                    <div class="attachment-size">{{ number_format($attachment['size'] / 1024, 1) }} KB</div>
                                </div>
                            </div>
                            <div class="attachment-actions">
                                <a href="{{ Storage::url($attachment['path']) }}" 
                                   target="_blank" 
                                   class="btn-download">
                                    <i class="fas fa-download"></i>
                                </a>
                                <button type="button" 
                                        class="btn-delete"
                                        onclick="deleteAttachment({{ $logbook->id }}, {{ $index }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="form-group">
                <label for="status">Status Logbook *</label>
                <select id="status" name="status" required>
                    <option value="draft" {{ $logbook->status === 'draft' ? 'selected' : '' }}>Draft (Simpan sementara)</option>
                    <option value="submitted" {{ $logbook->status === 'submitted' ? 'selected' : '' }}>Submit (Kirim ke DPL)</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i>
                    <span>Update Logbook</span>
                </button>
                <a href="{{ route('mobile.logbooks.show', $logbook) }}" class="btn-secondary">
                    <i class="fas fa-times"></i>
                    <span>Batal</span>
                </a>
            </div>
        </form>
    </div>

    <script>
        // Form submission handling akan ditangani di bagian scripts
    </script>
@endsection

@push('styles')
<style>
    .form-container {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f3f4;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background: #f8f9fa;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #0B1F3A;
        background: white;
        box-shadow: 0 0 0 3px rgba(11, 31, 58, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .form-text {
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    .photo-upload-area {
        border: 2px dashed #cbd5e0;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #f8f9fa;
        margin-bottom: 1rem;
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

    .preview-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        aspect-ratio: 1;
    }

    .preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .preview-remove {
        position: absolute;
        top: 0.25rem;
        right: 0.25rem;
        background: #dc3545;
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

    .existing-photos {
        margin-bottom: 1.5rem;
    }

    .existing-photos label {
        display: block;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.75rem;
        font-size: 0.875rem;
    }

    .photo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 0.75rem;
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

    .photo-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .photo-item:hover .photo-overlay {
        opacity: 1;
    }

    .btn-delete-photo {
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.75rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-primary,
    .btn-secondary {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #0B1F3A 0%, #163057 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(11, 31, 58, 0.3);
    }

    .btn-secondary {
        background: #f8f9fa;
        color: #6c757d;
        border: 2px solid #e2e8f0;
    }

    .btn-secondary:hover {
        background: #e9ecef;
        color: #495057;
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

    .page-header {
        background: linear-gradient(135deg, #0B1F3A 0%, #163057 100%);
        color: white;
        padding: 1.5rem;
        margin: -1rem -1rem 1rem -1rem;
        border-radius: 0 0 16px 16px;
    }

    .header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-left h2 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .date-info {
        font-size: 0.875rem;
        opacity: 0.8;
    }

    @media (max-width: 480px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .photo-grid {
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        }
    }

    /* Attachment Styles */
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
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .attachment-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1rem;
        border: 1px solid #e2e3e5;
    }

    .attachment-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex: 1;
        min-width: 0;
    }

    .attachment-content i {
        font-size: 1.5rem;
        color: #6c757d;
        flex-shrink: 0;
    }

    .attachment-info {
        display: flex;
        flex-direction: column;
    }

    .attachment-name {
        font-weight: 600;
        color: #1a202c;
        font-size: 0.875rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .attachment-size {
        font-size: 0.75rem;
        color: #6c757d;
    }

    .attachment-actions {
        display: flex;
        gap: 0.75rem;
    }

    .btn-download,
    .btn-delete {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-download {
        background: linear-gradient(135deg, #0B1F3A 0%, #163057 100%);
        color: white;
    }

    .btn-download:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(11, 31, 58, 0.3);
    }

    .btn-delete {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }

    .btn-delete:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }

    .existing-attachments {
        margin-top: 1rem;
    }

    .existing-attachments label {
        display: block;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.75rem;
        font-size: 0.875rem;
    }

    .attachment-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
function deletePhoto(photoId) {
    if (confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
        fetch(`/logbooks/photos/${photoId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Network response was not ok');
            }
        })
        .then(data => {
            if (data.status === 'success') {
                // Remove photo element from DOM
                const photoElement = document.querySelector(`[onclick="deletePhoto(${photoId})"]`).closest('.photo-item');
                if (photoElement) {
                    photoElement.remove();
                } else {
                    // Jika tidak bisa menemukan element, reload halaman
                    location.reload();
                }
            } else {
                // Reload halaman jika ada error
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Reload halaman jika ada error
            location.reload();
        });
    }
}

// Kompresi gambar dengan maksimal 50%
function compressImage(file, callback) {
    const maxWidth = 1200;
    const maxHeight = 1200;
    const quality = 0.5; // 50% kualitas
    
    const reader = new FileReader();
    reader.onload = function(event) {
        const img = new Image();
        img.onload = function() {
            // Hitung skala untuk mempertahankan aspect ratio
            let scale = 1;
            if (img.width > maxWidth || img.height > maxHeight) {
                scale = Math.min(maxWidth / img.width, maxHeight / img.height);
            }
            
            const canvas = document.createElement('canvas');
            canvas.width = img.width * scale;
            canvas.height = img.height * scale;
            
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            
            canvas.toBlob(function(blob) {
                const compressedFile = new File([blob], file.name, {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                });
                callback(compressedFile);
            }, 'image/jpeg', quality);
        };
        img.src = event.target.result;
    };
    reader.readAsDataURL(file);
}

// Photo upload handling dengan kompresi
const photoInput = document.getElementById('photos');
const photoPreview = document.getElementById('photoPreview');
const uploadPlaceholder = document.getElementById('uploadPlaceholder');
const photoUploadArea = document.getElementById('photoUploadArea');
let compressedFiles = [];

photoUploadArea.addEventListener('click', function() {
    photoInput.click();
});

photoInput.addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    
    // Validasi ukuran file
    for (const file of files) {
        if (file.size > 10 * 1024 * 1024) { // 10MB sebelum kompresi
            alert('Ukuran file tidak boleh lebih dari 10MB!');
            photoInput.value = '';
            return;
        }
    }

    const startIndex = compressedFiles.length;
    let processedCount = 0;
    
    files.forEach((file, idx) => {
        const actualIndex = startIndex + idx;
        
        // Kompresi gambar
        compressImage(file, function(compressed) {
            compressedFiles[actualIndex] = compressed;
            
            // Buat preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const photoItem = document.createElement('div');
                photoItem.className = 'photo-item';
                photoItem.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <div class="photo-overlay">
                        <button type="button" class="btn-delete-photo" onclick="removePhoto(${actualIndex})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                photoPreview.appendChild(photoItem);
            };
            reader.readAsDataURL(compressed);
            
            processedCount++;
            
            // Sembunyikan placeholder jika ada foto
            if (processedCount > 0) {
                uploadPlaceholder.style.display = 'none';
            }
        });
    });
    
    // Reset input untuk memungkinkan upload file yang sama lagi
    photoInput.value = '';
});

function removePhoto(index) {
    // Hapus dari array compressedFiles
    compressedFiles.splice(index, 1);
    
    // Re-render semua preview
    photoPreview.innerHTML = '';
    
    compressedFiles.forEach((file, idx) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const photoItem = document.createElement('div');
            photoItem.className = 'photo-item';
            photoItem.innerHTML = `
                <img src="${e.target.result}" alt="Preview">
                <div class="photo-overlay">
                    <button type="button" class="btn-delete-photo" onclick="removePhoto(${idx})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            photoPreview.appendChild(photoItem);
        };
        reader.readAsDataURL(file);
    });
    
    // Show placeholder if no photos
    if (compressedFiles.length === 0) {
        uploadPlaceholder.style.display = 'block';
    }
}

// Attachment handling
const attachmentInput = document.getElementById('attachments');
const attachmentPreview = document.getElementById('attachmentPreview');
const attachmentUploadArea = document.getElementById('attachmentUploadArea');
const attachmentUploadPlaceholder = document.getElementById('attachmentUploadPlaceholder');
let selectedAttachments = [];

attachmentUploadArea.addEventListener('click', function() {
    attachmentInput.click();
});

attachmentInput.addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    
    // Validasi ukuran file
    for (const file of files) {
        if (file.size > 10 * 1024 * 1024) { // 10MB
            alert('Ukuran file tidak boleh lebih dari 10MB!');
            attachmentInput.value = '';
            return;
        }
    }

    const startIndex = selectedAttachments.length;
    
    files.forEach((file, idx) => {
        const actualIndex = startIndex + idx;
        selectedAttachments[actualIndex] = file;
        
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
                <div class="attachment-info">
                    <div class="attachment-name">${file.name}</div>
                    <div class="attachment-size">${formatFileSize(file.size)}</div>
                </div>
            </div>
            <div class="attachment-actions">
                <button type="button" class="btn-delete" onclick="removeAttachment(${actualIndex})">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        attachmentPreview.appendChild(attachmentItem);
        
        // Sembunyikan placeholder jika ada attachment
        attachmentUploadPlaceholder.style.display = 'none';
    });
    
    // Reset input untuk memungkinkan upload file yang sama lagi
    attachmentInput.value = '';
});

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function removeAttachment(index) {
    // Hapus dari array selectedAttachments
    selectedAttachments.splice(index, 1);
    
    // Re-render semua preview
    attachmentPreview.innerHTML = '';
    
    selectedAttachments.forEach((file, idx) => {
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
                <div class="attachment-info">
                    <div class="attachment-name">${file.name}</div>
                    <div class="attachment-size">${formatFileSize(file.size)}</div>
                </div>
            </div>
            <div class="attachment-actions">
                <button type="button" class="btn-delete" onclick="removeAttachment(${idx})">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        attachmentPreview.appendChild(attachmentItem);
    });
    
    // Show placeholder if no attachments
    if (selectedAttachments.length === 0) {
        attachmentUploadPlaceholder.style.display = 'block';
    }
}

// Handle form submission dengan file yang sudah dikompresi
const form = document.getElementById('editLogbookForm');
form.addEventListener('submit', function(e) {
    // Hanya intervensi jika ada foto baru yang diupload
    if (compressedFiles.length > 0 || selectedAttachments.length > 0) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        // Hapus input file asli
        formData.delete('photos[]');
        formData.delete('attachments[]');
        
        // Tambahkan foto yang sudah dikompresi
        compressedFiles.forEach(f => formData.append('photos[]', f));
        
        // Tambahkan attachment yang dipilih
        selectedAttachments.forEach(f => formData.append('attachments[]', f));
        
        // Submit form dengan fetch
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            // Cek apakah response adalah JSON
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json().then(data => {
                    // Redirect ke halaman show logbook
                    window.location.href = '{{ route("mobile.logbooks.show", $logbook) }}';
                });
            } else {
                // Jika bukan JSON, kemungkinan redirect atau response HTML
                // Redirect ke halaman show logbook
                window.location.href = '{{ route("mobile.logbooks.show", $logbook) }}';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Redirect ke halaman show logbook meskipun ada error
            window.location.href = '{{ route("mobile.logbooks.show", $logbook) }}';
        });
    } else {
        // Jika tidak ada file baru, submit form normal
        console.log('No new files, submitting form normally');
        // Form akan di-submit secara normal dengan field redirect_to yang sudah ada
        return true; // Biarkan form submit normal
    }
});

function deleteAttachment(logbookId, index) {
    if (confirm('Apakah Anda yakin ingin menghapus file ini?')) {
        fetch(`/logbooks/${logbookId}/attachments`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ index: index })
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Network response was not ok');
            }
        })
        .then(data => {
            if (data.status === 'success') {
                // Reload halaman untuk memperbarui tampilan
                location.reload();
            } else {
                // Reload halaman jika ada error
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Reload halaman jika ada error
            location.reload();
        });
    }
}
</script>
@endpush 
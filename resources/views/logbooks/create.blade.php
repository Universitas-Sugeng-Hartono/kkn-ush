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
            
            .p-6 {
                padding: 1rem;
            }
            
            /* Grid adjustments */
            .grid.grid-cols-2 {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .grid.grid-cols-3 {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
            }
            
            /* Form controls */
            input, select, textarea {
                font-size: 14px;
                padding: 8px 12px;
            }
            
            /* Button adjustments */
            .inline-flex {
                font-size: 14px;
                padding: 8px 12px;
            }
            
            /* Stack buttons vertically */
            .flex.items-center.gap-4 {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .flex.items-center.gap-4 > * {
                width: 100%;
            }
            
            /* Text adjustments */
            .text-sm {
                font-size: 12px;
            }
            
            /* Preview image adjustments */
            .w-full.h-32 {
                height: 80px;
            }
        }
        
        /* Extra small devices */
        @media (max-width: 576px) {
            .py-12 {
                padding: 0.5rem;
            }
            
            .p-6 {
                padding: 0.75rem;
            }
            
            .grid.grid-cols-3 {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
            
            input, select, textarea {
                font-size: 13px;
                padding: 6px 10px;
            }
            
            .inline-flex {
                font-size: 13px;
                padding: 6px 10px;
            }
            
            .text-sm {
                font-size: 11px;
            }
            
            .w-full.h-32 {
                height: 60px;
            }
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Logbook Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form id="logbookForm" action="{{ route('logbooks.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="tanggal" value="Tanggal" />
                            <x-text-input id="tanggal" name="tanggal" type="date" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="waktu_mulai" value="Waktu Mulai" />
                                <x-text-input id="waktu_mulai" name="waktu_mulai" type="time" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('waktu_mulai')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="waktu_selesai" value="Waktu Selesai" />
                                <x-text-input id="waktu_selesai" name="waktu_selesai" type="time" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('waktu_selesai')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="judul" value="Judul Kegiatan" />
                            <x-text-input id="judul" name="judul" type="text" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('judul')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="jenis" value="Jenis Kegiatan" />
                            <select id="jenis" name="jenis" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Pilih Jenis Kegiatan</option>
                                <option value="individu">Individu</option>
                                <option value="desa">Desa</option>
                                <option value="kecamatan">Kecamatan</option>
                            </select>
                            <x-input-error :messages="$errors->get('jenis')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="keterangan" value="Keterangan Kegiatan" />
                            <textarea id="keterangan" name="keterangan" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required></textarea>
                            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="lokasi" value="Lokasi Kegiatan" />
                            <x-text-input id="lokasi" name="lokasi" type="text" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="photos" value="Foto Kegiatan" />
                            <input type="file" id="photos" name="photos[]" class="mt-1 block w-full" accept="image/*" multiple required />
                            <p class="text-sm text-gray-500 mt-1">Upload minimal 1 foto (format: jpg, jpeg, png, max: 20MB per foto)</p>
                            <x-input-error :messages="$errors->get('photos')" class="mt-2" />
                            <div id="preview" class="grid grid-cols-3 gap-4 mt-4"></div>
                        </div>

                        <div>
                            <x-input-label for="attachments" value="File Lampiran (Opsional)" />
                            <input type="file" id="attachments" name="attachments[]" class="mt-1 block w-full" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif" multiple />
                            <p class="text-sm text-gray-500 mt-1">Upload file pendukung seperti PDF, DOC, DOCX, Excel, PowerPoint, atau gambar (max: 20MB per file)</p>
                            <x-input-error :messages="$errors->get('attachments')" class="mt-2" />
                            <div id="attachmentPreview" class="mt-4 space-y-2"></div>
                        </div>

                        <input type="hidden" name="status" value="submitted">

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Simpan') }}</x-primary-button>
                            <a href="{{ route('logbooks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                {{ __('Batal') }}
                            </a>
                        </div>
                    </form>
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

        // Preview foto & kompresi sebelum upload
        const photosInput = document.getElementById('photos');
            const preview = document.getElementById('preview');
        const form = document.getElementById('logbookForm');
        let compressedFiles = [];

        // Attachment handling
        const attachmentsInput = document.getElementById('attachments');
        const attachmentPreview = document.getElementById('attachmentPreview');
        let attachmentFiles = [];

        photosInput.addEventListener('change', function(e) {
            preview.innerHTML = '';
            compressedFiles = [];
            const files = [...e.target.files];
            for (const file of files) {
                if (file.size > 20 * 1024 * 1024) {
                    alert('Ukuran file tidak boleh lebih dari 20MB!');
                    photosInput.value = '';
                    return;
                }
            }
            let processed = 0;
            files.forEach((file, idx) => {
                compressImage(file, function(compressed) {
                    compressedFiles[idx] = compressed;
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative';
                        div.innerHTML = `<img src="${e.target.result}" class="w-full h-32 object-cover rounded">`;
                    preview.appendChild(div);
                }
                    reader.readAsDataURL(compressed);
                    processed++;
                });
            });
        });

        // Handle attachment upload
        attachmentsInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            
            // Validasi ukuran file
            for (const file of files) {
                if (file.size > 20 * 1024 * 1024) { // 20MB
                    alert('Ukuran file tidak boleh lebih dari 20MB!');
                    attachmentsInput.value = '';
                    return;
                }
            }

            const startIndex = attachmentFiles.length;
            
            files.forEach((file, idx) => {
                const actualIndex = startIndex + idx;
                attachmentFiles[actualIndex] = file;
                
                const attachmentItem = document.createElement('div');
                attachmentItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg border';
                
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
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <i class="${iconClass} text-gray-500 text-lg"></i>
                        <span class="text-sm text-gray-900 truncate">${file.name}</span>
                        <span class="text-xs text-gray-500">${formatFileSize(file.size)}</span>
                    </div>
                    <button type="button" class="text-red-500 hover:text-red-700 ml-2" onclick="removeAttachment(${actualIndex})">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                
                attachmentPreview.appendChild(attachmentItem);
            });
            
            // Reset input untuk memungkinkan upload file yang sama lagi
            attachmentsInput.value = '';
        });

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function removeAttachment(index) {
            // Hapus dari array attachmentFiles
            attachmentFiles.splice(index, 1);
            
            // Re-render semua preview
            attachmentPreview.innerHTML = '';
            
            attachmentFiles.forEach((file, idx) => {
                const attachmentItem = document.createElement('div');
                attachmentItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg border';
                
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
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <i class="${iconClass} text-gray-500 text-lg"></i>
                        <span class="text-sm text-gray-900 truncate">${file.name}</span>
                        <span class="text-xs text-gray-500">${formatFileSize(file.size)}</span>
                    </div>
                    <button type="button" class="text-red-500 hover:text-red-700 ml-2" onclick="removeAttachment(${idx})">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                
                attachmentPreview.appendChild(attachmentItem);
            });
        }

        form.addEventListener('submit', function(e) {
            if (compressedFiles.length > 0) {
                e.preventDefault();
                // Buat FormData baru
                const formData = new FormData(form);
                formData.delete('photos[]');
                compressedFiles.forEach(f => formData.append('photos[]', f));
                
                // Tambahkan attachments
                formData.delete('attachments[]');
                attachmentFiles.forEach(f => formData.append('attachments[]', f));
                
                // Submit via fetch agar file yang dikirim hasil kompresi
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(res => res.redirected ? window.location.href = res.url : res.json().then(data => alert(data.message)));
            }
        });
    </script>
    @endpush
</x-app-layout> 
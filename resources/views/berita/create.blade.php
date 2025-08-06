<x-app-layout>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">Tambah Berita</h2>
                        <p class="text-muted">Buat berita atau informasi baru</p>
                    </div>
                    <div>
                        <a href="{{ route('berita.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <!-- Form Utama -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('berita.store') }}" method="POST" enctype="multipart/form-data" id="formBerita">
                            @csrf

                            <div class="mb-4">
                                <label for="judul" class="form-label">Judul Berita</label>
                                <input type="text" 
                                       class="form-control form-control-lg @error('judul') is-invalid @enderror" 
                                       id="judul" 
                                       name="judul" 
                                       value="{{ old('judul') }}"
                                       required>
                                @error('judul')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="gambar" class="form-label">Gambar Berita</label>
                                <input type="file" 
                                       class="form-control @error('gambar') is-invalid @enderror" 
                                       id="gambar" 
                                       name="gambar"
                                       accept="image/*"
                                       onchange="previewImage(this)">
                                <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB.</small>
                                @error('gambar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="imagePreview" class="mt-2 d-none">
                                    <img src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="konten" class="form-label">Konten Berita</label>
                                <textarea class="form-control @error('konten') is-invalid @enderror" 
                                          id="konten" 
                                          name="konten" 
                                          rows="10">{{ old('konten') }}</textarea>
                                @error('konten')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Pengaturan Publikasi -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Pengaturan Publikasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label">Penulis</label>
                            <input type="text" 
                                   class="form-control" 
                                   value="{{ Auth::user()->name }}" 
                                   readonly>
                        </div>

                        <div class="mb-4">
                            <label for="is_published" class="form-label">Status</label>
                            <select class="form-select @error('is_published') is-invalid @enderror" 
                                    id="is_published" 
                                    name="is_published">
                                <option value="0" {{ old('is_published') == '0' ? 'selected' : '' }}>Draft</option>
                                <option value="1" {{ old('is_published') == '1' ? 'selected' : '' }}>Publikasikan</option>
                            </select>
                            @error('is_published')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="published_at" class="form-label">Tanggal Publikasi</label>
                            <input type="datetime-local" 
                                   class="form-control @error('published_at') is-invalid @enderror" 
                                   id="published_at" 
                                   name="published_at"
                                   value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}">
                            @error('published_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Berita
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset("assets/js/ckeditor.min.js") }}"></script>
    <script>
        // Initialize CKEditor
        ClassicEditor
            .create(document.querySelector('#konten'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'blockQuote', 'insertTable', 'undo', 'redo'],
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                    ]
                }
            })
            .then(editor => {
                // Set editor height
                editor.editing.view.change(writer => {
                    writer.setStyle(
                        'min-height',
                        '500px',
                        editor.editing.view.document.getRoot()
                    );
                });
            })
            .catch(error => {
                console.error(error);
            });

        // Image Preview
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const image = preview.querySelector('img');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    image.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                image.src = '';
                preview.classList.add('d-none');
            }
        }

        // Form Validation
        document.getElementById('formBerita').addEventListener('submit', function(e) {
            const editorData = document.querySelector('.ck-editor__editable').ckeditorInstance.getData();
            if (!editorData) {
                e.preventDefault();
                Swal.fire({
                    title: 'Error!',
                    text: 'Konten berita tidak boleh kosong!',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }
        });

        // Handle Status Change
        document.getElementById('is_published').addEventListener('change', function() {
            const publishedAtInput = document.getElementById('published_at');
            if (this.value === '1' && !publishedAtInput.value) {
                publishedAtInput.value = new Date().toISOString().slice(0, 16);
            } else if (this.value === '0') {
                publishedAtInput.value = '';
            }
        });
    </script>
    @endpush
</x-app-layout> 
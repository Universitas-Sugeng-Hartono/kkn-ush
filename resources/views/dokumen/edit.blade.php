<x-app-layout>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">Edit Dokumen</h2>
                        <p class="text-muted">Edit informasi dokumen</p>
                    </div>
                    <div>
                        <a href="{{ route('dokumen.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('dokumen.update', $dokumen) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="nama" class="form-label">Nama Dokumen</label>
                                <input type="text" 
                                       class="form-control form-control-lg @error('nama') is-invalid @enderror" 
                                       id="nama" 
                                       name="nama" 
                                       value="{{ old('nama', $dokumen->nama) }}"
                                       required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="jenis" class="form-label">Jenis Dokumen</label>
                                <select class="form-select @error('jenis') is-invalid @enderror" 
                                        id="jenis" 
                                        name="jenis"
                                        required>
                                    <option value="">Pilih Jenis Dokumen</option>
                                    <option value="panduan" {{ old('jenis', $dokumen->jenis) == 'panduan' ? 'selected' : '' }}>Panduan/Pedoman</option>
                                    <option value="peraturan" {{ old('jenis', $dokumen->jenis) == 'peraturan' ? 'selected' : '' }}>Peraturan</option>
                                    <option value="template" {{ old('jenis', $dokumen->jenis) == 'template' ? 'selected' : '' }}>Template/Form</option>
                                    <option value="logo" {{ old('jenis', $dokumen->jenis) == 'logo' ? 'selected' : '' }}>Logo</option>
                                    <option value="lainnya" {{ old('jenis', $dokumen->jenis) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('jenis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                          id="keterangan" 
                                          name="keterangan" 
                                          rows="3">{{ old('keterangan', $dokumen->keterangan) }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="file" class="form-label">File Dokumen</label>
                                <input type="file" 
                                       class="form-control @error('file') is-invalid @enderror" 
                                       id="file" 
                                       name="file">
                                <small class="text-muted">Format: PDF, DOC, DOCX, XLS, XLSX. Maksimal 10MB. Biarkan kosong jika tidak ingin mengubah file.</small>
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('dokumen.index') }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Informasi File Saat Ini -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">File Saat Ini</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <i class="fas {{ $dokumen->icon_class }} fa-3x"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ basename($dokumen->file_path) }}</h6>
                                <p class="text-muted mb-0">{{ $dokumen->formatted_size }}</p>
                            </div>
                        </div>

                        <a href="{{ route('dokumen.download', $dokumen) }}" 
                           class="btn btn-outline-primary w-100">
                            <i class="fas fa-download me-2"></i>Download File Saat Ini
                        </a>
                    </div>
                </div>

                <!-- Panduan -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Panduan</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6 class="fw-bold">Jenis Dokumen</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <span class="badge bg-info me-2">Panduan</span>
                                    Dokumen panduan atau pedoman KKN
                                </li>
                                <li class="mb-2">
                                    <span class="badge bg-primary me-2">Peraturan</span>
                                    Peraturan dan ketentuan KKN
                                </li>
                                <li class="mb-2">
                                    <span class="badge bg-warning me-2">Template</span>
                                    Form atau template yang digunakan
                                </li>
                                <li class="mb-2">
                                    <span class="badge bg-success me-2">Logo</span>
                                    Logo universitas
                                </li>
                                <li>
                                    <span class="badge bg-secondary me-2">Lainnya</span>
                                    Dokumen pendukung lainnya
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h6 class="fw-bold">Format yang Didukung</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="far fa-file-pdf text-danger me-2"></i>PDF Document
                                </li>
                                <li class="mb-2">
                                    <i class="far fa-file-word text-primary me-2"></i>Word Document (DOC, DOCX)
                                </li>
                                <li>
                                    <i class="far fa-file-excel text-success me-2"></i>Excel Spreadsheet (XLS, XLSX)
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
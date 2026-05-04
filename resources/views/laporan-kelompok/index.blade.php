<x-app-layout>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">Laporan Kelompok KKN</h2>
                        <p class="text-muted mb-0">Upload sekali, otomatis bisa dilihat oleh seluruh anggota kelompok Anda.</p>
                    </div>
                    @if($kelompok)
                    <div>
                        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="fas fa-upload me-2"></i>Upload Laporan
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @if(!$kelompok)
        <div class="alert alert-warning shadow-sm border-0">
            <i class="fas fa-exclamation-triangle me-2"></i>Anda belum tergabung ke kelompok. Silakan hubungi Admin untuk penugasan kelompok.
        </div>
        @else
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">
                        <h6 class="mb-3 text-uppercase fw-bold text-primary"><i class="fas fa-users me-2"></i>Informasi Kelompok</h6>
                        <div class="row g-4">
                            <div class="col-md-3">
                                <div class="text-muted small mb-1">Kelompok</div>
                                <div class="fw-semibold fs-5">{{ $kelompok->nama_kelompok }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-muted small mb-1">Periode</div>
                                <div class="fw-semibold">
                                    {{ $kelompok->tahunAkademik ? $kelompok->tahunAkademik->nama : '-' }}
                                    <span class="text-muted">({{ $kelompok->semester ? $kelompok->semester->nama : '-' }})</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-muted small mb-1">Lokasi</div>
                                <div class="fw-semibold">
                                    {{ $kelompok->lokasi ? $kelompok->lokasi->nama_desa . ', ' . $kelompok->lokasi->nama_kecamatan : '-' }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-muted small mb-1">Dosen Pembimbing Lapangan (DPL)</div>
                                <div class="fw-semibold">{{ $kelompok->dpl ? $kelompok->dpl->name : '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center py-3 px-4 mt-2">
                        <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-folder-open me-2 text-warning"></i>Daftar Laporan</h5>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fs-6">{{ $laporan->count() }} File</span>
                    </div>
                    <div class="card-body p-0">
                        @if($laporan->isEmpty())
                        <div class="text-center py-5">
                            <img src="{{ asset('assets/images/empty.svg') }}" alt="Empty" class="img-fluid mb-3" style="max-height: 120px; opacity: 0.5;">
                            <h5 class="text-muted fw-semibold">Belum ada laporan</h5>
                            <p class="text-muted mb-0">Laporan yang diupload akan muncul di sini.</p>
                        </div>
                        @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-muted">
                                    <tr>
                                        <th class="ps-4 border-0 py-3">Detail Laporan</th>
                                        <th class="border-0 py-3">Diupload Oleh</th>
                                        <th class="border-0 py-3">Tanggal Upload</th>
                                        <th class="text-end pe-4 border-0 py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($laporan as $item)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-danger-subtle text-danger rounded p-3 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                    <i class="fas fa-file-pdf fa-lg"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark fs-6">{{ $item->judul ?: $item->file_original_name }}</div>
                                                    @if($item->keterangan)
                                                    <div class="text-muted small mb-1">{{ Str::limit($item->keterangan, 60) }}</div>
                                                    @endif
                                                    <div class="text-muted small">
                                                        <i class="fas fa-paperclip me-1"></i>{{ $item->file_original_name }} • <span class="badge bg-secondary-subtle text-secondary">{{ number_format(($item->file_size ?? 0) / 1024 / 1024, 2) }} MB</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <div class="avatar-title rounded-circle bg-primary text-white" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                                        {{ strtoupper(substr($item->user?->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                </div>
                                                <span class="fw-medium">{{ $item->user?->name ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 text-muted">{{ $item->created_at?->format('d M Y, H:i') }}</td>
                                        <td class="text-end pe-4 py-3">
                                            <a class="btn btn-sm btn-light text-primary border me-1 rounded-pill px-3" href="{{ route('laporan-kelompok.download', $item) }}" data-bs-toggle="tooltip" title="Download Laporan">
                                                <i class="fas fa-download me-1"></i> Unduh
                                            </a>
                                            @if((int) $item->user_id === (int) auth()->id())
                                            <form action="{{ route('laporan-kelompok.destroy', $item) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-light text-danger border rounded-pill px-3" onclick="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')" data-bs-toggle="tooltip" title="Hapus Laporan">
                                                    <i class="fas fa-trash-alt me-1"></i> Hapus
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Upload Laporan -->
        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-primary text-white rounded-top-4 py-3 px-4">
                        <h5 class="modal-title fw-bold" id="uploadModalLabel"><i class="fas fa-cloud-upload-alt me-2"></i>Upload Laporan Kelompok</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('laporan-kelompok.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark">Judul Laporan <span class="text-muted fw-normal ms-1">(Opsional)</span></label>
                                <input type="text" name="judul" value="{{ old('judul') }}" class="form-control form-control-lg fs-6 @error('judul') is-invalid @enderror" placeholder="Contoh: Laporan Akhir KKN">
                                @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark">Keterangan <span class="text-muted fw-normal ms-1">(Opsional)</span></label>
                                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3" placeholder="Tambahkan catatan singkat mengenai isi laporan ini...">{{ old('keterangan') }}</textarea>
                                @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-dark">File Laporan <span class="text-danger">*</span></label>
                                <input type="file" name="file" class="form-control form-control-lg fs-6 @error('file') is-invalid @enderror" required id="fileInput">
                                @error('file') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                <div class="form-text mt-2"><i class="fas fa-info-circle me-1 text-primary"></i>Format yang didukung: <strong>PDF, DOC, DOCX</strong>. Ukuran maksimal <strong>20MB</strong>.</div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light rounded-bottom-4 px-4 py-3 border-top-0">
                            <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary px-4 rounded-pill"><i class="fas fa-paper-plane me-2"></i>Upload Laporan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi Tooltip Bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            
            // Tampilkan kembali modal jika ada error validasi
            @if($errors->any())
                var uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
                uploadModal.show();
            @endif
        });
    </script>
    @endpush
</x-app-layout>
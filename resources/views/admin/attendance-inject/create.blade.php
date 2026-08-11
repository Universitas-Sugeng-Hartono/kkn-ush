<x-app-layout>
    <style>
        @media (max-width: 768px) {
            .container-fluid { padding: 10px; }
            .card { margin-bottom: 15px; }
            .card-body { padding: 15px; }
            h2.fw-bold { font-size: 1.5rem; }
            .form-control, .form-select { font-size: 14px; padding: 8px 12px; }
            .form-label { font-size: 14px; font-weight: 500; }
            .btn { font-size: 14px; padding: 8px 12px; }
        }
        @media (max-width: 576px) {
            .container-fluid { padding: 5px; }
            .card-body { padding: 10px; }
            h2.fw-bold { font-size: 1.3rem; }
            .form-control, .form-select { font-size: 13px; padding: 6px 10px; }
            .btn { font-size: 13px; padding: 6px 10px; }
        }
        .select-mahasiswa-item {
            cursor: pointer;
            transition: background-color 0.15s;
        }
        .select-mahasiswa-item:hover {
            background-color: #f0f4ff;
        }
        .select-mahasiswa-item.selected {
            background-color: #e8f0fe;
            border-color: #4285f4 !important;
        }
        #mahasiswaSearch {
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }
        #mahasiswaDropdown {
            max-height: 300px;
            overflow-y: auto;
            border-top: none;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>

    <div class="container-fluid">
        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h2 class="fw-bold"><i class="fas fa-syringe me-2 text-primary"></i>Inject Absensi Baru</h2>
                        <p class="text-muted mb-0">Tambahkan data absensi manual untuk mahasiswa</p>
                    </div>
                    <a href="{{ route('admin.attendance-inject.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Terdapat kesalahan:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Info Alert --}}
        <div class="alert alert-info border-0" style="background-color: #e8f4fd;">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Catatan:</strong> Absensi yang di-inject akan langsung berstatus <span class="badge bg-success">Validated</span>
            tanpa perlu approval DPL. Data inject akan ditandai dengan label <span class="badge bg-warning text-dark"><i class="fas fa-syringe me-1"></i>Injected</span>.
        </div>

        {{-- Form --}}
        <div class="card">
            <div class="card-header" style="background-color: #f2b70d;">
                <h5 class="card-title mb-0" style="color: #0B1F3A;">
                    <i class="fas fa-edit me-2"></i>Form Inject Absensi
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.attendance-inject.store') }}" method="POST" enctype="multipart/form-data" id="injectForm">
                    @csrf

                    <div class="row g-4">
                        {{-- Pilih Mahasiswa --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user me-1"></i>Mahasiswa <span class="text-danger">*</span>
                            </label>
                            <input type="hidden" name="user_id" id="selectedUserId" value="{{ old('user_id') }}">
                            <input type="text" class="form-control @error('user_id') is-invalid @enderror"
                                   id="mahasiswaSearch" placeholder="Ketik nama atau NIM mahasiswa..."
                                   autocomplete="off">
                            <div id="mahasiswaDropdown" class="border rounded-bottom shadow-sm" style="display:none;">
                                {{-- Populated by JS --}}
                            </div>
                            <div id="selectedMahasiswaInfo" class="mt-2" style="display:none;">
                                <div class="alert alert-success py-2 px-3 mb-0 d-flex align-items-center justify-content-between">
                                    <div>
                                        <i class="fas fa-check-circle me-2"></i>
                                        <strong id="selectedName"></strong>
                                        <span class="text-muted ms-2" id="selectedNim"></span>
                                        <span class="badge bg-secondary ms-2" id="selectedKelompok"></span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger" id="clearMahasiswa">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Filter by Kelompok --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-users me-1"></i>Filter Kelompok
                            </label>
                            <select class="form-select" id="filterKelompok">
                                <option value="">Semua Kelompok</option>
                                @foreach($kelompokList as $kelompok)
                                    <option value="{{ $kelompok->id }}">
                                        {{ $kelompok->nama_kelompok }}
                                        @if($kelompok->lokasi)
                                            — {{ $kelompok->lokasi->nama_lokasi }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Filter daftar mahasiswa berdasarkan kelompok</small>
                        </div>

                        <hr>

                        {{-- Tanggal --}}
                        <div class="col-md-4">
                            <label for="tanggal" class="form-label fw-semibold">
                                <i class="fas fa-calendar me-1"></i>Tanggal <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                                   id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Waktu Masuk --}}
                        <div class="col-md-4">
                            <label for="waktu_masuk" class="form-label fw-semibold">
                                <i class="fas fa-sign-in-alt me-1"></i>Waktu Masuk <span class="text-danger">*</span>
                            </label>
                            <input type="time" class="form-control @error('waktu_masuk') is-invalid @enderror"
                                   id="waktu_masuk" name="waktu_masuk" value="{{ old('waktu_masuk', '08:00') }}" required>
                            @error('waktu_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Waktu Keluar --}}
                        <div class="col-md-4">
                            <label for="waktu_keluar" class="form-label fw-semibold">
                                <i class="fas fa-sign-out-alt me-1"></i>Waktu Keluar
                                <span class="text-muted fw-normal">(opsional)</span>
                            </label>
                            <input type="time" class="form-control @error('waktu_keluar') is-invalid @enderror"
                                   id="waktu_keluar" name="waktu_keluar" value="{{ old('waktu_keluar') }}">
                            @error('waktu_keluar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Lokasi --}}
                        <div class="col-md-6">
                            <label for="lokasi" class="form-label fw-semibold">
                                <i class="fas fa-map-marker-alt me-1"></i>Lokasi
                                <span class="text-muted fw-normal">(opsional)</span>
                            </label>
                            <input type="text" class="form-control @error('lokasi') is-invalid @enderror"
                                   id="lokasi" name="lokasi" value="{{ old('lokasi') }}"
                                   placeholder="Nama lokasi kegiatan...">
                            <small class="text-muted" id="lokasiHint" style="display:none;">
                                <i class="fas fa-info-circle me-1"></i>Lokasi kelompok: <span id="lokasiKelompok"></span>
                            </small>
                            @error('lokasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Foto Kegiatan --}}
                        <div class="col-md-6">
                            <label for="foto_kegiatan" class="form-label fw-semibold">
                                <i class="fas fa-camera me-1"></i>Foto Kegiatan
                                <span class="text-muted fw-normal">(opsional, maks 5MB)</span>
                            </label>
                            <input type="file" class="form-control @error('foto_kegiatan') is-invalid @enderror"
                                   id="foto_kegiatan" name="foto_kegiatan" accept="image/jpeg,image/jpg,image/png">
                            @error('foto_kegiatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="fotoPreview" class="mt-2" style="display:none;">
                                <img id="fotoPreviewImg" src="#" alt="Preview" class="rounded" style="max-height:150px; max-width:100%;">
                            </div>
                        </div>

                        {{-- Catatan Inject --}}
                        <div class="col-12">
                            <label for="catatan_inject" class="form-label fw-semibold">
                                <i class="fas fa-sticky-note me-1"></i>Catatan / Alasan Inject <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('catatan_inject') is-invalid @enderror"
                                      id="catatan_inject" name="catatan_inject" rows="3"
                                      placeholder="Tuliskan alasan inject absensi ini, contoh: Mahasiswa lupa absen tapi sudah hadir dibuktikan dengan foto kegiatan..."
                                      required maxlength="1000">{{ old('catatan_inject') }}</textarea>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Wajib diisi sebagai bukti audit</small>
                                <small class="text-muted"><span id="charCount">0</span>/1000</small>
                            </div>
                            @error('catatan_inject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    {{-- Submit Buttons --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.attendance-inject.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-syringe me-2"></i>Inject Absensi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data mahasiswa dari server
            const mahasiswaList = @json($mahasiswaList);
            const searchInput = document.getElementById('mahasiswaSearch');
            const dropdown = document.getElementById('mahasiswaDropdown');
            const selectedUserIdInput = document.getElementById('selectedUserId');
            const selectedInfo = document.getElementById('selectedMahasiswaInfo');
            const selectedName = document.getElementById('selectedName');
            const selectedNim = document.getElementById('selectedNim');
            const selectedKelompok = document.getElementById('selectedKelompok');
            const clearBtn = document.getElementById('clearMahasiswa');
            const filterKelompok = document.getElementById('filterKelompok');
            const lokasiInput = document.getElementById('lokasi');
            const lokasiHint = document.getElementById('lokasiHint');
            const lokasiKelompokSpan = document.getElementById('lokasiKelompok');

            // Char counter for catatan
            const catatanInput = document.getElementById('catatan_inject');
            const charCount = document.getElementById('charCount');
            catatanInput.addEventListener('input', function() {
                charCount.textContent = this.value.length;
            });
            charCount.textContent = catatanInput.value.length;

            // Foto preview
            const fotoInput = document.getElementById('foto_kegiatan');
            const fotoPreview = document.getElementById('fotoPreview');
            const fotoPreviewImg = document.getElementById('fotoPreviewImg');
            fotoInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        fotoPreviewImg.src = e.target.result;
                        fotoPreview.style.display = 'block';
                    };
                    reader.readAsDataURL(this.files[0]);
                } else {
                    fotoPreview.style.display = 'none';
                }
            });

            // Filter & search mahasiswa
            function renderDropdown(query = '', kelompokId = '') {
                let filtered = mahasiswaList.filter(m => {
                    const matchSearch = !query ||
                        m.name.toLowerCase().includes(query.toLowerCase()) ||
                        (m.nim && m.nim.toLowerCase().includes(query.toLowerCase()));
                    const matchKelompok = !kelompokId || m.kelompok_id == kelompokId;
                    return matchSearch && matchKelompok;
                });

                if (filtered.length === 0) {
                    dropdown.innerHTML = '<div class="p-3 text-center text-muted"><i class="fas fa-search me-2"></i>Tidak ditemukan</div>';
                } else {
                    dropdown.innerHTML = filtered.map(m => `
                        <div class="select-mahasiswa-item p-2 border-bottom d-flex align-items-center"
                             data-id="${m.id}"
                             data-name="${m.name}"
                             data-nim="${m.nim || '-'}"
                             data-kelompok="${m.kelompok ? m.kelompok.nama_kelompok : '-'}"
                             data-lokasi="${m.kelompok && m.kelompok.lokasi ? m.kelompok.lokasi.nama_lokasi : ''}">
                            <div class="bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px;height:32px;">
                                <span class="text-white fw-bold" style="font-size:0.8rem;">${m.name.charAt(0)}</span>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:0.9rem;">${m.name}</div>
                                <small class="text-muted">NIM: ${m.nim || '-'} | ${m.kelompok ? m.kelompok.nama_kelompok : 'Tanpa Kelompok'}</small>
                            </div>
                        </div>
                    `).join('');
                }
                dropdown.style.display = 'block';

                // Bind click events
                dropdown.querySelectorAll('.select-mahasiswa-item').forEach(item => {
                    item.addEventListener('click', function() {
                        selectMahasiswa(
                            this.dataset.id,
                            this.dataset.name,
                            this.dataset.nim,
                            this.dataset.kelompok,
                            this.dataset.lokasi
                        );
                    });
                });
            }

            function selectMahasiswa(id, name, nim, kelompok, lokasi) {
                selectedUserIdInput.value = id;
                selectedName.textContent = name;
                selectedNim.textContent = 'NIM: ' + nim;
                selectedKelompok.textContent = kelompok;
                selectedInfo.style.display = 'block';
                searchInput.style.display = 'none';
                dropdown.style.display = 'none';

                // Autofill lokasi
                if (lokasi && !lokasiInput.value) {
                    lokasiInput.value = lokasi;
                }
                if (lokasi) {
                    lokasiKelompokSpan.textContent = lokasi;
                    lokasiHint.style.display = 'block';
                }
            }

            clearBtn.addEventListener('click', function() {
                selectedUserIdInput.value = '';
                selectedInfo.style.display = 'none';
                searchInput.style.display = 'block';
                searchInput.value = '';
                searchInput.focus();
                lokasiHint.style.display = 'none';
            });

            searchInput.addEventListener('focus', function() {
                renderDropdown(this.value, filterKelompok.value);
            });

            searchInput.addEventListener('input', function() {
                renderDropdown(this.value, filterKelompok.value);
            });

            filterKelompok.addEventListener('change', function() {
                renderDropdown(searchInput.value, this.value);
                if (searchInput.style.display !== 'none') {
                    dropdown.style.display = 'block';
                }
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });

            // Restore old value if exists
            const oldUserId = '{{ old('user_id') }}';
            if (oldUserId) {
                const oldMahasiswa = mahasiswaList.find(m => m.id == oldUserId);
                if (oldMahasiswa) {
                    selectMahasiswa(
                        oldMahasiswa.id,
                        oldMahasiswa.name,
                        oldMahasiswa.nim || '-',
                        oldMahasiswa.kelompok ? oldMahasiswa.kelompok.nama_kelompok : '-',
                        oldMahasiswa.kelompok && oldMahasiswa.kelompok.lokasi ? oldMahasiswa.kelompok.lokasi.nama_lokasi : ''
                    );
                }
            }

            // Form validation
            document.getElementById('injectForm').addEventListener('submit', function(e) {
                if (!selectedUserIdInput.value) {
                    e.preventDefault();
                    alert('Silakan pilih mahasiswa terlebih dahulu.');
                    searchInput.focus();
                    return false;
                }
            });
        });
    </script>
    @endpush

    @push('styles')
    <style>
        .card { border-radius: 15px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.07); }
        .card-header { border-bottom: none; }
        .btn { border-radius: 8px; }
    </style>
    @endpush
</x-app-layout>

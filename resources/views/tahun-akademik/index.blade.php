<x-app-layout>
    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold">Pengaturan Akademik</h1>
                <p class="text-muted mb-0 small">Kelola Tahun Akademik dan Semester yang digunakan di seluruh sistem.</p>
            </div>
            <div class="text-end">
                <span class="text-muted small d-block mb-1">Periode Aktif Saat Ini</span>
                <div class="d-flex align-items-center gap-2 justify-content-end">
                    {{-- Tahun Akademik: tampil ringkas --}}
                    @if($tahunAktifList->count() === 0)
                        <span class="text-muted small">Tidak ada tahun aktif</span>
                    @elseif($tahunAktifList->count() === 1)
                        <span class="badge bg-primary rounded-pill px-3 py-2">{{ $tahunAktifList->first()->nama }}</span>
                    @else
                        <span class="badge bg-primary rounded-pill px-3 py-2"
                              role="button"
                              data-bs-toggle="popover"
                              data-bs-trigger="hover focus"
                              data-bs-placement="bottom"
                              data-bs-html="true"
                              data-bs-content="{{ $tahunAktifList->pluck('nama')->map(fn($n) => '<span class=\'badge bg-primary me-1\'>'.$n.'</span>')->implode(' ') }}">
                            {{ $tahunAktifList->count() }} Tahun Aktif
                            <i class="fas fa-info-circle ms-1 opacity-75"></i>
                        </span>
                    @endif
                    <span class="text-muted">/</span>
                    {{-- Semester: tampil ringkas --}}
                    @if($semesterAktifList->count() === 0)
                        <span class="text-muted small">Tidak ada sem aktif</span>
                    @elseif($semesterAktifList->count() === 1)
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2">{{ $semesterAktifList->first()->nama }}</span>
                    @else
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2"
                              role="button"
                              data-bs-toggle="popover"
                              data-bs-trigger="hover focus"
                              data-bs-placement="bottom"
                              data-bs-html="true"
                              data-bs-content="{{ $semesterAktifList->pluck('nama')->map(fn($n) => '<span class=\'badge bg-warning text-dark me-1\'>'.$n.'</span>')->implode(' ') }}">
                            {{ $semesterAktifList->count() }} Sem Aktif
                            <i class="fas fa-info-circle ms-1 opacity-75"></i>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="row g-3 mb-4">
            {{-- Total Tahun --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3 bg-primary bg-opacity-10 text-primary fs-4">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Tahun Akademik</div>
                            <div class="fw-bold fs-4">{{ $tahunAkademikList->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tahun Aktif --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body gap-3" style="display:grid; grid-template-columns: auto 1fr;">
                        <div class="rounded-3 p-3 bg-success bg-opacity-10 text-success fs-4 align-self-start">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-muted small mb-1">Tahun Aktif
                                <span class="badge bg-success rounded-pill ms-1">{{ $tahunAktifList->count() }}</span>
                            </div>
                            @if($tahunAktifList->isEmpty())
                                <span class="text-muted small">Belum ada</span>
                            @else
                                <div style="max-height: 72px; overflow-y: auto;" class="d-flex flex-column gap-1 pe-1">
                                    @foreach($tahunAktifList as $ta)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 text-start">
                                            <i class="fas fa-circle fa-xs me-1"></i>{{ $ta->nama }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Semester --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3 bg-warning bg-opacity-10 text-warning fs-4">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Semester</div>
                            <div class="fw-bold fs-4">{{ $semesterList->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Semester Aktif --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body gap-3" style="display:grid; grid-template-columns: auto 1fr;">
                        <div class="rounded-3 p-3 bg-info bg-opacity-10 text-info fs-4 align-self-start">
                            <i class="fas fa-toggle-on"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-muted small mb-1">Semester Aktif
                                <span class="badge bg-info text-dark rounded-pill ms-1">{{ $semesterAktifList->count() }}</span>
                            </div>
                            @if($semesterAktifList->isEmpty())
                                <span class="text-muted small">Belum ada</span>
                            @else
                                <div style="max-height: 72px; overflow-y: auto;" class="d-flex flex-column gap-1 pe-1">
                                    @foreach($semesterAktifList as $sem)
                                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2 text-start">
                                            <i class="fas fa-circle fa-xs me-1"></i>{{ $sem->nama }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Tables --}}
        <div class="row g-4">

            {{-- Tahun Akademik --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                        <div>
                            <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-alt text-primary me-2"></i>Tahun Akademik</h5>
                        </div>
                        <button class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalTambahTahun">
                            <i class="fas fa-plus me-1"></i>Tambah
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-muted">
                                    <tr>
                                        <th class="ps-4 py-3 border-0">Nama Tahun Akademik</th>
                                        <th class="py-3 border-0">Status</th>
                                        <th class="text-end pe-4 py-3 border-0">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tahunAkademikList as $ta)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                                                    <i class="fas fa-calendar fa-sm"></i>
                                                </div>
                                                <span class="fw-semibold">{{ $ta->nama }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            @if($ta->is_aktif)
                                            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-2">
                                                <i class="fas fa-circle fa-xs me-1"></i>Aktif
                                            </span>
                                            @else
                                            <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2">
                                                Nonaktif
                                            </span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4 py-3">
                                            <div class="d-inline-flex gap-1">
                                                @if(!$ta->is_aktif)
                                                <form method="POST" action="{{ route('tahun-akademik.set-aktif', $ta) }}">
                                                    @csrf
                                                    <button class="btn btn-sm btn-light border text-success rounded-pill px-3" type="submit" title="Aktifkan">
                                                        <i class="fas fa-toggle-on me-1"></i>Aktifkan
                                                    </button>
                                                </form>
                                                @else
                                                <form method="POST" action="{{ route('tahun-akademik.set-nonaktif', $ta) }}">
                                                    @csrf
                                                    <button class="btn btn-sm btn-light border text-danger rounded-pill px-3" type="submit" title="Nonaktifkan">
                                                        <i class="fas fa-toggle-off me-1"></i>Nonaktifkan
                                                    </button>
                                                </form>
                                                @endif
                                                <button class="btn btn-sm btn-light border text-primary rounded-pill px-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditTahun"
                                                    data-id="{{ $ta->id }}"
                                                    data-nama="{{ $ta->nama }}">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </button>
                                                @if(!$ta->is_aktif)
                                                <form method="POST" action="{{ route('tahun-akademik.destroy', $ta) }}" onsubmit="return confirm('Hapus tahun akademik ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-light border text-danger rounded-pill px-2" type="submit" title="Hapus">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-5">
                                            <i class="fas fa-calendar-times fa-2x mb-2 d-block text-secondary opacity-50"></i>
                                            Belum ada data tahun akademik.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Semester --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                        <div>
                            <h5 class="mb-0 fw-bold"><i class="fas fa-layer-group text-warning me-2"></i>Semester</h5>
                        </div>
                        @php
                            $hasGanjil = $semesterList->contains('nama', 'Ganjil');
                            $hasGenap = $semesterList->contains('nama', 'Genap');
                        @endphp

                        @if($hasGanjil && $hasGenap)
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-2 rounded-pill" data-bs-toggle="tooltip" title="Semester Ganjil & Genap sudah terdaftar">
                                <i class="fas fa-info-circle me-1"></i>Periode Lengkap
                            </span>
                        @else
                            <button class="btn btn-warning btn-sm rounded-pill px-3 text-white" data-bs-toggle="modal" data-bs-target="#modalTambahSemester">
                                <i class="fas fa-plus me-1"></i>Tambah
                            </button>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-muted">
                                    <tr>
                                        <th class="ps-4 py-3 border-0">Nama Semester</th>
                                        <th class="py-3 border-0">Status</th>
                                        <th class="text-end pe-4 py-3 border-0">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($semesterList as $sem)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                                                    <i class="fas fa-layer-group fa-sm"></i>
                                                </div>
                                                <span class="fw-semibold">{{ $sem->nama }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            @if($sem->is_aktif)
                                            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-2">
                                                <i class="fas fa-circle fa-xs me-1"></i>Aktif
                                            </span>
                                            @else
                                            <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2">
                                                Nonaktif
                                            </span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4 py-3">
                                            <div class="d-inline-flex gap-1">
                                                @if(!$sem->is_aktif)
                                                <form method="POST" action="{{ route('semester.set-aktif', $sem) }}">
                                                    @csrf
                                                    <button class="btn btn-sm btn-light border text-success rounded-pill px-3" type="submit" title="Aktifkan">
                                                        <i class="fas fa-toggle-on me-1"></i>Aktifkan
                                                    </button>
                                                </form>
                                                @else
                                                <form method="POST" action="{{ route('semester.set-nonaktif', $sem) }}">
                                                    @csrf
                                                    <button class="btn btn-sm btn-light border text-danger rounded-pill px-3" type="submit" title="Nonaktifkan">
                                                        <i class="fas fa-toggle-off me-1"></i>Nonaktifkan
                                                    </button>
                                                </form>
                                                @endif
                                                <button class="btn btn-sm btn-light border text-primary rounded-pill px-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditSemester"
                                                    data-id="{{ $sem->id }}"
                                                    data-nama="{{ $sem->nama }}">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </button>
                                                @if(!$sem->is_aktif)
                                                <form method="POST" action="{{ route('semester.destroy', $sem) }}" onsubmit="return confirm('Hapus semester ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-light border text-danger rounded-pill px-2" type="submit" title="Hapus">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-5">
                                            <i class="fas fa-list-alt fa-2x mb-2 d-block text-secondary opacity-50"></i>
                                            Belum ada data semester.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Periode Tanggal KKN (Angkatan) --}}
        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                        <div>
                            <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-check text-success me-2"></i>Periode Tanggal KKN (Angkatan)</h5>
                        </div>
                        <button class="btn btn-success text-white btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalTambahAngkatan">
                            <i class="fas fa-plus me-1"></i>Atur Tanggal Baru
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-muted">
                                    <tr>
                                        <th class="ps-4 py-3 border-0">Tahun Akademik</th>
                                        <th class="py-3 border-0">Semester</th>
                                        <th class="py-3 border-0">Tanggal Mulai</th>
                                        <th class="py-3 border-0">Tanggal Selesai</th>
                                        <th class="py-3 border-0">Nama Angkatan</th>
                                        <th class="text-end pe-4 py-3 border-0">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($angkatanList as $angkatan)
                                    <tr>
                                        <td class="ps-4 py-3 fw-semibold">{{ $angkatan->tahunAkademik?->nama ?? '-' }}</td>
                                        <td class="py-3">{{ $angkatan->semester?->nama ?? '-' }}</td>
                                        <td class="py-3 fw-semibold text-primary">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ $angkatan->tanggal_mulai ? $angkatan->tanggal_mulai->format('d M Y') : '-' }}
                                        </td>
                                        <td class="py-3 fw-semibold text-danger">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ $angkatan->tanggal_selesai ? $angkatan->tanggal_selesai->format('d M Y') : '-' }}
                                        </td>
                                        <td class="py-3 text-muted small">{{ $angkatan->nama_angkatan }}</td>
                                        <td class="text-end pe-4 py-3">
                                            <div class="d-inline-flex gap-1">
                                                <button class="btn btn-sm btn-light border text-primary rounded-pill px-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditAngkatan"
                                                    data-id="{{ $angkatan->id }}"
                                                    data-ta-id="{{ $angkatan->tahun_akademik_id }}"
                                                    data-sem-id="{{ $angkatan->semester_id }}"
                                                    data-mulai="{{ $angkatan->tanggal_mulai ? $angkatan->tanggal_mulai->format('Y-m-d') : '' }}"
                                                    data-selesai="{{ $angkatan->tanggal_selesai ? $angkatan->tanggal_selesai->format('Y-m-d') : '' }}">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </button>
                                                <form method="POST" action="{{ route('angkatan.destroy', $angkatan) }}" onsubmit="return confirm('Hapus pengaturan tanggal KKN untuk angkatan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-light border text-danger rounded-pill px-2" type="submit" title="Hapus">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <i class="fas fa-calendar-times fa-2x mb-2 d-block text-secondary opacity-50"></i>
                                            Belum ada tanggal KKN yang dikonfigurasi. Sistem akan menggunakan periode default (04-26 Agt 2025).
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Tambah Tahun Akademik --}}
    <div class="modal fade" id="modalTambahTahun" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <form method="POST" action="{{ route('tahun-akademik.store') }}">
                    @csrf
                    <div class="modal-header bg-primary text-white rounded-top-4 py-3 px-4">
                        <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2"></i>Tambah Tahun Akademik</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <label class="form-label fw-semibold">Nama Tahun Akademik <span class="text-danger">*</span></label>
                        <input class="form-control form-control-lg fs-6 @error('nama') is-invalid @enderror"
                            name="nama" placeholder="contoh: 2025/2026" required value="{{ old('nama') }}">
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text mt-2"><i class="fas fa-info-circle me-1 text-primary"></i>Format: <strong>YYYY/YYYY</strong>, contoh: 2024/2025</div>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom-4 border-top-0 px-4 py-3">
                        <button class="btn btn-secondary rounded-pill px-4" type="button" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-primary rounded-pill px-4" type="submit"><i class="fas fa-save me-2"></i>Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Edit Tahun Akademik --}}
    <div class="modal fade" id="modalEditTahun" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <form method="POST" id="formEditTahun">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-primary text-white rounded-top-4 py-3 px-4">
                        <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i>Edit Tahun Akademik</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <label class="form-label fw-semibold">Nama Tahun Akademik <span class="text-danger">*</span></label>
                        <input class="form-control form-control-lg fs-6" id="edit_tahun_nama" name="nama" required>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom-4 border-top-0 px-4 py-3">
                        <button class="btn btn-secondary rounded-pill px-4" type="button" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-primary rounded-pill px-4" type="submit"><i class="fas fa-save me-2"></i>Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Tambah Semester --}}
    <div class="modal fade" id="modalTambahSemester" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <form method="POST" action="{{ route('semester.store') }}">
                    @csrf
                    <div class="modal-header bg-warning text-white rounded-top-4 py-3 px-4">
                        <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2"></i>Tambah Semester</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <label class="form-label fw-semibold">Nama Semester <span class="text-danger">*</span></label>
                        <input class="form-control form-control-lg fs-6 @error('nama') is-invalid @enderror"
                            name="nama" placeholder="contoh: Ganjil / Genap" required value="{{ old('nama') }}">
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text mt-2"><i class="fas fa-info-circle me-1 text-warning"></i>Contoh: <strong>Ganjil</strong> atau <strong>Genap</strong></div>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom-4 border-top-0 px-4 py-3">
                        <button class="btn btn-secondary rounded-pill px-4" type="button" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-warning text-white rounded-pill px-4" type="submit"><i class="fas fa-save me-2"></i>Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Edit Semester --}}
    <div class="modal fade" id="modalEditSemester" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <form method="POST" id="formEditSemester">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning text-white rounded-top-4 py-3 px-4">
                        <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i>Edit Semester</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <label class="form-label fw-semibold">Nama Semester <span class="text-danger">*</span></label>
                        <input class="form-control form-control-lg fs-6" id="edit_semester_nama" name="nama" required>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom-4 border-top-0 px-4 py-3">
                        <button class="btn btn-secondary rounded-pill px-4" type="button" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-warning text-white rounded-pill px-4" type="submit"><i class="fas fa-save me-2"></i>Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Tambah/Atur Periode KKN (Angkatan) --}}
    <div class="modal fade" id="modalTambahAngkatan" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <form method="POST" action="{{ route('angkatan.store-or-update') }}">
                    @csrf
                    <div class="modal-header bg-success text-white rounded-top-4 py-3 px-4">
                        <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2"></i>Atur Tanggal KKN Baru</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tahun Akademik <span class="text-danger">*</span></label>
                            <select class="form-select @error('tahun_akademik_id') is-invalid @enderror" name="tahun_akademik_id" required>
                                <option value="">Pilih Tahun Akademik</option>
                                @foreach($tahunAkademikList as $ta)
                                    <option value="{{ $ta->id }}">{{ $ta->nama }}</option>
                                @endforeach
                            </select>
                            @error('tahun_akademik_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
                            <select class="form-select @error('semester_id') is-invalid @enderror" name="semester_id" required>
                                <option value="">Pilih Semester</option>
                                @foreach($semesterList as $sem)
                                    <option value="{{ $sem->id }}">{{ $sem->nama }}</option>
                                @endforeach
                            </select>
                            @error('semester_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" name="tanggal_mulai" required value="{{ old('tanggal_mulai') }}">
                            @error('tanggal_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" name="tanggal_selesai" required value="{{ old('tanggal_selesai') }}">
                            @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom-4 border-top-0 px-4 py-3">
                        <button class="btn btn-secondary rounded-pill px-4" type="button" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-success text-white rounded-pill px-4" type="submit"><i class="fas fa-save me-2"></i>Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Edit Periode KKN (Angkatan) --}}
    <div class="modal fade" id="modalEditAngkatan" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <form method="POST" action="{{ route('angkatan.store-or-update') }}">
                    @csrf
                    <div class="modal-header bg-success text-white rounded-top-4 py-3 px-4">
                        <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i>Edit Periode Tanggal KKN</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tahun Akademik</label>
                            <select class="form-select" id="edit_angkatan_ta_id" disabled>
                                @foreach($tahunAkademikList as $ta)
                                    <option value="{{ $ta->id }}">{{ $ta->nama }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="tahun_akademik_id" id="edit_angkatan_ta_id_hidden">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Semester</label>
                            <select class="form-select" id="edit_angkatan_sem_id" disabled>
                                @foreach($semesterList as $sem)
                                    <option value="{{ $sem->id }}">{{ $sem->nama }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="semester_id" id="edit_angkatan_sem_id_hidden">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_mulai" id="edit_angkatan_mulai" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_selesai" id="edit_angkatan_selesai" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom-4 border-top-0 px-4 py-3">
                        <button class="btn btn-secondary rounded-pill px-4" type="button" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-success text-white rounded-pill px-4" type="submit"><i class="fas fa-save me-2"></i>Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Inisialisasi Bootstrap Popover
        document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el => {
            new bootstrap.Popover(el);
        });

        // Edit Tahun Akademik
        document.getElementById('modalEditTahun')?.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            document.getElementById('edit_tahun_nama').value = button.getAttribute('data-nama') || '';
            document.getElementById('formEditTahun').action = `{{ url('pengaturan/tahun-akademik') }}/${button.getAttribute('data-id')}`;
        });

        // Edit Semester
        document.getElementById('modalEditSemester')?.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            document.getElementById('edit_semester_nama').value = button.getAttribute('data-nama') || '';
            document.getElementById('formEditSemester').action = `{{ url('pengaturan/semester') }}/${button.getAttribute('data-id')}`;
        });

        // Edit Angkatan / Tanggal KKN
        document.getElementById('modalEditAngkatan')?.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const taId = button.getAttribute('data-ta-id');
            const semId = button.getAttribute('data-sem-id');
            const mulai = button.getAttribute('data-mulai');
            const selesai = button.getAttribute('data-selesai');

            document.getElementById('edit_angkatan_ta_id').value = taId;
            document.getElementById('edit_angkatan_ta_id_hidden').value = taId;
            
            document.getElementById('edit_angkatan_sem_id').value = semId;
            document.getElementById('edit_angkatan_sem_id_hidden').value = semId;

            document.getElementById('edit_angkatan_mulai').value = mulai;
            document.getElementById('edit_angkatan_selesai').value = selesai;
        });

        // Buka modal jika ada error validasi
        @if($errors->any())
            @if(old('_method') === 'PUT')
                // error saat edit — buka modal edit yg sesuai
            @else
                // Cek jika error dari form tambah tahun
                @if(session()->has('tambah_tahun'))
                new bootstrap.Modal(document.getElementById('modalTambahTahun')).show();
                @elseif(session()->has('tambah_semester'))
                new bootstrap.Modal(document.getElementById('modalTambahSemester')).show();
                @endif
            @endif
        @endif
    </script>
    @endpush
</x-app-layout>
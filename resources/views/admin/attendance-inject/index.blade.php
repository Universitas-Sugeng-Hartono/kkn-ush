<x-app-layout>
    <style>
        @media (max-width: 768px) {
            .container-fluid { padding: 10px; }
            .card { margin-bottom: 15px; }
            .card-body { padding: 15px; }
            .row .col-md-3, .row .col-md-4 { margin-bottom: 15px; }
            h2.fw-bold { font-size: 1.5rem; }
            h5.card-title { font-size: 1.1rem; }
            .form-control, .form-select { font-size: 14px; padding: 8px 12px; }
            .form-label { font-size: 14px; font-weight: 500; }
            .btn { font-size: 14px; padding: 8px 12px; }
            .table { font-size: 14px; }
            .table th, .table td { padding: 8px 4px; }
        }
        @media (max-width: 576px) {
            .container-fluid { padding: 5px; }
            .card-body { padding: 10px; }
            h2.fw-bold { font-size: 1.3rem; }
            .form-control, .form-select { font-size: 13px; padding: 6px 10px; }
            .btn { font-size: 13px; padding: 6px 10px; }
            .table { font-size: 13px; }
            .table th, .table td { padding: 6px 2px; }
        }
    </style>

    <div class="container-fluid">
        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h2 class="fw-bold"><i class="fas fa-syringe me-2 text-primary"></i>Inject Absensi</h2>
                        <p class="text-muted mb-0">Kelola absensi yang di-inject oleh admin</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.attendance-inject.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Inject Absensi Baru
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Filter Section --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-filter me-2"></i>Filter Data
                    @if($attendances->total() > 0)
                        <span class="badge bg-primary ms-2">{{ $attendances->total() }} data inject</span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.attendance-inject.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="nama" class="form-label fw-semibold small text-muted mb-1">Nama Mahasiswa</label>
                            <input type="text" class="form-control form-control-sm" id="nama" name="nama"
                                   value="{{ request('nama') }}" placeholder="Cari nama..." onchange="this.form.submit()">
                        </div>
                        <div class="col-md-3">
                            <label for="tahun_akademik_id" class="form-label fw-semibold small text-muted mb-1">Tahun Akademik</label>
                            <select class="form-select form-select-sm" id="tahun_akademik_id" name="tahun_akademik_id" onchange="this.form.submit()">
                                <option value="">Semua Tahun</option>
                                @foreach($tahunAkademikList as $ta)
                                    <option value="{{ $ta->id }}" {{ $tahun_akademik_id == $ta->id ? 'selected' : '' }}>
                                        {{ $ta->nama }} {{ $ta->is_aktif ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="semester_id" class="form-label fw-semibold small text-muted mb-1">Semester</label>
                            <select class="form-select form-select-sm" id="semester_id" name="semester_id" onchange="this.form.submit()">
                                <option value="">Semua Semester</option>
                                @foreach($semesterList as $sem)
                                    <option value="{{ $sem->id }}" {{ $semester_id == $sem->id ? 'selected' : '' }}>
                                        {{ $sem->nama }} {{ $sem->is_aktif ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-muted mb-1">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <input type="date" class="form-control form-control-sm" name="tanggal_mulai"
                                       value="{{ request('tanggal_mulai') }}" placeholder="Dari" onchange="this.form.submit()">
                                <input type="date" class="form-control form-control-sm" name="tanggal_akhir"
                                       value="{{ request('tanggal_akhir') }}" placeholder="Sampai" onchange="this.form.submit()">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="card">
            <div class="card-header" style="background-color: #f2b70d;">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0" style="color: #0B1F3A;">
                        <i class="fas fa-syringe me-2"></i>Data Absensi Inject
                    </h5>
                    @if($attendances->count() > 0)
                        <span class="badge bg-info">{{ $attendances->total() }} total</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if($attendances->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Kelompok</th>
                                    <th>Tanggal</th>
                                    <th>Waktu Masuk</th>
                                    <th>Waktu Keluar</th>
                                    <th>Status</th>
                                    <th>Di-inject oleh</th>
                                    <th>Catatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $attendance)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($attendance->user->foto_profil)
                                                    <img src="{{ asset('storage/'.$attendance->user->foto_profil) }}" class="rounded-circle me-2" width="36" height="36" style="object-fit:cover;">
                                                @else
                                                    <div class="bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                                                        <span class="text-white fw-bold">{{ substr($attendance->user->name ?? '?', 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $attendance->user->name ?? '-' }}</div>
                                                    <small class="text-muted">{{ $attendance->user->jurusan ? ucfirst($attendance->user->jurusan) : '-' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $attendance->user->nim ?? '-' }}</td>
                                        <td>
                                            @if($attendance->kelompok)
                                                <span class="badge bg-secondary">{{ $attendance->kelompok->nama_kelompok }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $attendance->tanggal ? $attendance->tanggal->format('d M Y') : '-' }}</td>
                                        <td>
                                            @if($attendance->waktu_masuk)
                                                <span class="badge bg-success">{{ \Carbon\Carbon::parse($attendance->waktu_masuk)->format('H:i') }}</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attendance->waktu_keluar)
                                                <span class="badge bg-info">{{ \Carbon\Carbon::parse($attendance->waktu_keluar)->format('H:i') }}</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Validated
                                            </span>
                                            <br>
                                            <span class="badge bg-warning text-dark mt-1" style="font-size: 0.7rem;">
                                                <i class="fas fa-syringe me-1"></i>Injected
                                            </span>
                                        </td>
                                        <td>
                                            <small class="fw-semibold">{{ $attendance->injectedBy?->name ?? '-' }}</small>
                                            <br>
                                            <small class="text-muted">{{ $attendance->created_at ? $attendance->created_at->format('d/m/Y H:i') : '-' }}</small>
                                        </td>
                                        <td>
                                            <small class="text-muted" title="{{ $attendance->catatan_inject }}">
                                                {{ \Illuminate\Support\Str::limit($attendance->catatan_inject, 40) }}
                                            </small>
                                        </td>
                                        <td>
                                            <a href="{{ route('history.attendances.show', $attendance) }}" class="btn btn-sm btn-info text-white mb-1" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.attendance-inject.destroy', $attendance) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger mb-1" title="Hapus"
                                                        onclick="return confirm('Hapus absensi inject ini? Data tidak dapat dikembalikan.')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-4">
                        {{ $attendances->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-syringe fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada absensi yang di-inject</h5>
                        <p class="text-muted">Klik tombol "Inject Absensi Baru" untuk menambahkan data absensi manual.</p>
                        <a href="{{ route('admin.attendance-inject.create') }}" class="btn btn-primary mt-2">
                            <i class="fas fa-plus me-2"></i>Inject Absensi Baru
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .card { border-radius: 15px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.07); }
        .card-header { border-bottom: none; }
        .table th { border-top: none; font-weight: 600; color: #0B1F3A; }
        .badge { font-size: 0.85rem; }
        .btn { border-radius: 8px; }
    </style>
    @endpush
</x-app-layout>

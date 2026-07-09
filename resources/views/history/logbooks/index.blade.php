<x-app-layout>
    <style>
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 10px;
            }
            
            .card {
                margin-bottom: 15px;
            }
            
            .card-body {
                padding: 15px;
            }
            
            /* Text adjustments */
            h2.fw-bold {
                font-size: 1.5rem;
            }
            
            h5.card-title {
                font-size: 1.1rem;
            }
            
            h4 {
                font-size: 1.2rem;
            }
            
            /* Table adjustments */
            .table-responsive {
                font-size: 14px;
            }
            
            .table th,
            .table td {
                padding: 8px 4px;
                vertical-align: middle;
            }
            
            /* Profile image adjustments */
            .rounded-circle {
                width: 30px !important;
                height: 30px !important;
            }
            
            .bg-primary.rounded-circle {
                width: 30px !important;
                height: 30px !important;
            }
            
            .bg-primary.rounded-circle span {
                font-size: 12px;
            }
            
            /* Button adjustments */
            .btn {
                font-size: 14px;
                padding: 8px 12px;
            }
            
            .btn-sm {
                font-size: 12px;
                padding: 4px 6px;
            }
            
            /* Badge adjustments */
            .badge {
                font-size: 11px;
            }
            
            /* Stack header buttons vertically */
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 10px;
            }
            
            .d-flex.justify-content-between .btn {
                width: 100%;
            }
            
            /* Stack action buttons vertically */
            .d-flex.gap-2 {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .d-flex.gap-2 .btn {
                width: 100%;
            }
            
            /* Icon adjustments */
            .fas {
                font-size: 12px;
            }
            
            /* Empty state adjustments */
            .fas[style*="font-size: 4rem"] {
                font-size: 3rem !important;
            }
        }
        
        /* Extra small devices */
        @media (max-width: 576px) {
            .container-fluid {
                padding: 5px;
            }
            
            .card-body {
                padding: 10px;
            }
            
            h2.fw-bold {
                font-size: 1.3rem;
            }
            
            h5.card-title {
                font-size: 1rem;
            }
            
            h4 {
                font-size: 1.1rem;
            }
            
            .table-responsive {
                font-size: 13px;
            }
            
            .table th,
            .table td {
                padding: 6px 2px;
            }
            
            .rounded-circle {
                width: 25px !important;
                height: 25px !important;
            }
            
            .bg-primary.rounded-circle {
                width: 25px !important;
                height: 25px !important;
            }
            
            .bg-primary.rounded-circle span {
                font-size: 10px;
            }
            
            .btn {
                font-size: 13px;
                padding: 6px 10px;
            }
            
            .btn-sm {
                font-size: 11px;
                padding: 3px 5px;
            }
            
            .badge {
                font-size: 10px;
            }
            
            .fas {
                font-size: 11px;
            }
            
            .fas[style*="font-size: 4rem"] {
                font-size: 2.5rem !important;
            }
            
            /* Stack profile info vertically on very small screens */
            .d-flex.align-items-center {
                flex-direction: column;
                text-align: center;
            }
            
            .d-flex.align-items-center .me-2 {
                margin-right: 0 !important;
                margin-bottom: 5px;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">History Logbook</h2>
                        <p class="text-muted">Riwayat logbook mahasiswa bimbingan</p>
                    </div>
                    <div>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-filter me-2"></i>Filter Data
                    @if(request()->hasAny(['nama', 'jenis', 'jurusan', 'status', 'tanggal_mulai', 'tanggal_akhir']))
                        <span class="badge bg-primary ms-2">({{ $logbooks->total() }} hasil)</span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('history.logbooks.index') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div>
                                <label for="nama" class="form-label fw-semibold small text-muted mb-1">Nama Mahasiswa</label>
                                <input type="text" class="form-control form-control-sm" id="nama" name="nama" 
                                       value="{{ request('nama') }}" placeholder="Cari nama..." onchange="this.form.submit()">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div>
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
                        </div>
                        <div class="col-md-3">
                            <div>
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
                        </div>
                        <div class="col-md-3">
                            <div>
                                <label for="jenis" class="form-label fw-semibold small text-muted mb-1">Jenis Kegiatan</label>
                                <select class="form-select form-select-sm" id="jenis" name="jenis" onchange="this.form.submit()">
                                    <option value="">Semua Jenis</option>
                                    @foreach($jenisKegiatan as $key => $value)
                                        <option value="{{ $key }}" {{ request('jenis') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-md-3">
                            <div>
                                <label for="jurusan" class="form-label fw-semibold small text-muted mb-1">Jurusan</label>
                                <select class="form-select form-select-sm" id="jurusan" name="jurusan" onchange="this.form.submit()">
                                    <option value="">Semua Jurusan</option>
                                    @foreach($jurusanList as $jurusan)
                                        <option value="{{ $jurusan }}" {{ request('jurusan') == $jurusan ? 'selected' : '' }}>
                                            {{ ucfirst($jurusan) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div>
                                <label for="status" class="form-label fw-semibold small text-muted mb-1">Status</label>
                                <select class="form-select form-select-sm" id="status" name="status" onchange="this.form.submit()">
                                    <option value="">Semua Status</option>
                                    @foreach($statusList as $key => $value)
                                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div>
                                <label for="tanggal_mulai" class="form-label fw-semibold small text-muted mb-1">Tanggal Mulai</label>
                                <input type="date" class="form-control form-control-sm" id="tanggal_mulai" name="tanggal_mulai" 
                                       value="{{ request('tanggal_mulai') }}" onchange="this.form.submit()">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div>
                                <label for="tanggal_akhir" class="form-label fw-semibold small text-muted mb-1">Tanggal Akhir</label>
                                <input type="date" class="form-control form-control-sm" id="tanggal_akhir" name="tanggal_akhir" 
                                       value="{{ request('tanggal_akhir') }}" onchange="this.form.submit()">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card">
            <div class="card-header" style="background-color: #f2b70d;">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0" style="color: #0B1F3A;">
                        <i class="fas fa-book me-2"></i>History Logbook
                        @if(request('nama') || request('jenis') || request('jurusan') || request('status') || request('tanggal_mulai') || request('tanggal_akhir'))
                            <small class="text-muted">({{ $logbooks->total() }} hasil)</small>
                        @endif
                    </h5>
                </div>
            </div>
            <div class="card-body">
                @if($logbooks->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Jurusan</th>
                                    <th>Judul</th>
                                    <th>Jenis Kegiatan</th>
                                    <th>Tanggal</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logbooks as $logbook)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($logbook->user->foto_profil)
                                                    <img src="{{ asset('storage/'.$logbook->user->foto_profil) }}" class="rounded-circle me-2" width="36" height="36" style="object-fit:cover;">
                                                @else
                                                    <div class="bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                                                        <span class="text-white">{{ substr($logbook->user->name,0,1) }}</span>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $logbook->user->name }}</div>
                                                    <small class="text-muted">{{ $logbook->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $logbook->user->nim ?? '-' }}</td>
                                        <td>{{ $logbook->user->jurusan ? ucfirst($logbook->user->jurusan) : '-' }}</td>
                                        <td>{{ $logbook->judul }}</td>
                                        <td>
                                            @php
                                                $jenisLabels = [
                                                    'individu' => 'Individu',
                                                    'desa' => 'Desa',
                                                    'kecamatan' => 'Kecamatan'
                                                ];
                                                $jenisColors = [
                                                    'individu' => 'bg-info',
                                                    'desa' => 'bg-success',
                                                    'kecamatan' => 'bg-warning'
                                                ];
                                            @endphp
                                            <span class="badge {{ $jenisColors[$logbook->jenis] ?? 'bg-secondary' }} text-white">
                                                {{ $jenisLabels[$logbook->jenis] ?? ucfirst($logbook->jenis) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ \Carbon\Carbon::parse($logbook->tanggal)->format('d M Y') }}</div>
                                            <small class="text-muted">{{ $logbook->waktu_mulai ? \Carbon\Carbon::parse($logbook->waktu_mulai)->format('H:i') : '-' }} - {{ $logbook->waktu_selesai ? \Carbon\Carbon::parse($logbook->waktu_selesai)->format('H:i') : '-' }}</small>
                                        </td>
                                        <td>{{ $logbook->lokasi ?? '-' }}</td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'draft' => 'bg-warning',
                                                    'submitted' => 'bg-info',
                                                    'approved' => 'bg-success',
                                                    'rejected' => 'bg-danger'
                                                ];
                                                $statusColor = $statusColors[$logbook->status] ?? 'bg-secondary';
                                            @endphp
                                            <span class="badge {{ $statusColor }}">
                                                {{ $statusList[$logbook->status] ?? $logbook->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('history.logbooks.show', $logbook) }}" class="btn btn-sm btn-info text-white mb-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($logbook->status == 'submitted')
                                                <form action="{{ route('logbooks.approve', $logbook) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success mb-1" title="Setujui" onclick="return confirm('Setujui logbook ini?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('logbooks.reject', $logbook) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-danger mb-1" title="Tolak" onclick="return confirm('Tolak logbook ini?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $logbooks->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Tidak Ada Logbook</h4>
                        <p class="text-muted">Belum ada logbook yang ditemukan sesuai filter yang dipilih</p>
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
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Logbook Mahasiswa') }}
        </h2>
    </x-slot>

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-12">
                <h2 class="fw-bold">Monitoring Logbook Mahasiswa</h2>
                <p class="text-muted">Detail logbook mahasiswa bimbingan untuk periode KKN</p>
            </div>
        </div>

        <!-- Statistik Cards - Menggunakan style dashboard -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white shadow-sm h-100" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0 fw-bold">{{ $stats['total'] }}</h4>
                                <p class="mb-0">Total Logbook</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-book fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white shadow-sm h-100" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0 fw-bold">{{ $stats['approved'] }}</h4>
                                <p class="mb-0">Disetujui</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white shadow-sm h-100" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0 fw-bold">{{ $stats['pending'] }}</h4>
                                <p class="mb-0">Menunggu Review</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-clock fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white shadow-sm h-100" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0 fw-bold">{{ $stats['rejected'] }}</h4>
                                <p class="mb-0">Ditolak</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-times-circle fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Control Panel -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold"><i class="fas fa-sliders-h me-2"></i>Filter & Pencarian</h5>
                        <div class="d-flex align-items-center text-muted small fw-semibold">
                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                            <span>{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('monitoring.logbook-detail') }}" id="filterForm">
                            <!-- Retain 'tipe' query parameter if it exists -->
                            @if(request()->has('tipe'))
                                <input type="hidden" name="tipe" value="{{ request('tipe') }}">
                            @endif
                            <div class="row g-3 align-items-center">
                                @if($dplList)
                                    <div class="col-md-4">
                                        <select class="form-select" name="dpl_id" onchange="document.getElementById('filterForm').submit()">
                                            <option value="">Semua DPL Pendamping</option>
                                            @foreach($dplList as $dplOption)
                                                <option value="{{ $dplOption->id }}" {{ $dpl_id == $dplOption->id ? 'selected' : '' }}>
                                                    DPL: {{ $dplOption->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-select" name="tahun_akademik_id" onchange="document.getElementById('filterForm').submit()">
                                            <option value="">Semua Tahun Akademik</option>
                                            @foreach($tahunAkademikList as $ta)
                                                <option value="{{ $ta->id }}" {{ $tahun_akademik_id == $ta->id ? 'selected' : '' }}>
                                                    {{ $ta->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-select" name="semester_id" onchange="document.getElementById('filterForm').submit()">
                                            <option value="">Semua Semester</option>
                                            @foreach($semesterList as $sem)
                                                <option value="{{ $sem->id }}" {{ $semester_id == $sem->id ? 'selected' : '' }}>
                                                    {{ $sem->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <div class="col-md-6">
                                        <select class="form-select" name="tahun_akademik_id" onchange="document.getElementById('filterForm').submit()">
                                            <option value="">Semua Tahun Akademik</option>
                                            @foreach($tahunAkademikList as $ta)
                                                <option value="{{ $ta->id }}" {{ $tahun_akademik_id == $ta->id ? 'selected' : '' }}>
                                                    {{ $ta->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-select" name="semester_id" onchange="document.getElementById('filterForm').submit()">
                                            <option value="">Semua Semester</option>
                                            @foreach($semesterList as $sem)
                                                <option value="{{ $sem->id }}" {{ $semester_id == $sem->id ? 'selected' : '' }}>
                                                    {{ $sem->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logbook Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fas fa-table me-2 text-primary"></i>
                            Data Logbook Mahasiswa
                            <span class="badge {{ $tipe === 'kelompok' ? 'bg-success' : 'bg-primary' }} ms-2 fs-6">
                                {{ $tipe === 'kelompok' ? 'Kelompok' : 'Individu' }}
                            </span>
                        </h5>
                        <div class="btn-group shadow-sm" role="group" aria-label="Tipe Logbook">
                            <a href="{{ request()->fullUrlWithQuery(['tipe' => 'individu']) }}"
                               class="btn btn-sm {{ $tipe === 'individu' ? 'btn-primary' : 'btn-outline-primary' }} fw-semibold px-3">
                                <i class="fas fa-user me-1"></i>Individu
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['tipe' => 'kelompok']) }}"
                               class="btn btn-sm {{ $tipe === 'kelompok' ? 'btn-success' : 'btn-outline-success' }} fw-semibold px-3">
                                <i class="fas fa-users me-1"></i>Kelompok
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <span class="me-2 text-muted small">Tampilkan</span>
                                <select form="filterForm" name="per_page" class="form-select form-select-sm w-auto" onchange="document.getElementById('filterForm').submit()">
                                    <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                                <span class="ms-2 text-muted small">entri</span>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 text-center">Periode Logbook KKN ({{ $stats['hari_kkn'] ?? 0 }} Hari) — Menampilkan logbook <strong>{{ $tipe === 'kelompok' ? 'kelompok' : 'individu' }}</strong></h6>
                            </div>
                            <div>
                                <div class="input-group input-group-sm" style="width: 250px;">
                                    <input type="text" form="filterForm" name="search" class="form-control" placeholder="Cari nama/kelompok..." value="{{ request('search') }}">
                                    <button form="filterForm" class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th style="width: 15%">Tanggal</th>
                                        <th style="width: 25%">Pembuat</th>
                                        <th style="width: 35%">Kegiatan</th>
                                        <th style="width: 10%" class="text-center">Status</th>
                                        <th style="width: 10%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logbooks as $index => $logbook)
                                        <tr>
                                            <td>{{ $logbooks->firstItem() + $index }}</td>
                                            <td>
                                                <div class="fw-bold">{{ \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('d M Y') }}</div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2 flex-shrink-0" style="width: 32px; height: 32px; font-size: 12px; font-weight: bold;">
                                                        {{ strtoupper(substr($logbook->user->name ?? 'M', 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-truncate" style="max-width: 200px;">{{ $logbook->user->name ?? 'Mahasiswa' }}</div>
                                                        <small class="text-muted">{{ $logbook->user->nim ?? '-' }}</small>
                                                        @if($tipe === 'kelompok' && $logbook->kelompok)
                                                            <div class="badge bg-info mt-1 text-truncate" style="max-width: 200px;">{{ $logbook->kelompok->nama_kelompok }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-truncate" style="max-width: 300px;">{{ $logbook->judul }}</div>
                                                <div class="text-muted small text-truncate" style="max-width: 300px;">
                                                    {{ Str::limit(strip_tags($logbook->keterangan), 60) }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($logbook->status === 'approved')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @elseif($logbook->status === 'rejected')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @elseif($logbook->status === 'submitted')
                                                    <span class="badge bg-warning">Pending Review</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($logbook->status) }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('logbooks.show', $logbook->id) }}" class="btn btn-sm btn-outline-primary shadow-sm" target="_blank">
                                                    <i class="fas fa-eye me-1"></i>Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">Tidak ada data logbook</h5>
                                                    <p class="text-muted">Belum ada logbook {{ $tipe }} yang disubmit untuk filter ini.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($logbooks->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $logbooks->withQueryString()->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .sticky-col {
            position: sticky !important;
            background-color: #f8f9fa !important;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }
        td.sticky-col {
            background-color: #ffffff !important;
        }
        .table-responsive {
            overflow-x: auto;
            position: relative;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');

            // Prevent form submit on Enter press in search input
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                }
            });
        });
    </script>
    @endpush
</x-app-layout> 
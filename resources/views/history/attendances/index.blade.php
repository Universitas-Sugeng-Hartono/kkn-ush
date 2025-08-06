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
            
            /* Form layout - Stack vertically */
            .row .col-md-3,
            .row .col-md-4 {
                margin-bottom: 15px;
            }
            
            /* Text adjustments */
            h2.fw-bold {
                font-size: 1.5rem;
            }
            
            h5.card-title {
                font-size: 1.1rem;
            }
            
            /* Form controls */
            .form-control, .form-select {
                font-size: 14px;
                padding: 8px 12px;
            }
            
            .form-label {
                font-size: 14px;
                font-weight: 500;
            }
            
            /* Button adjustments */
            .btn {
                font-size: 14px;
                padding: 8px 12px;
            }
            
            /* Table adjustments */
            .table {
                font-size: 14px;
            }
            
            .table th,
            .table td {
                padding: 8px 4px;
            }
            
            /* Stack filter buttons */
            .d-flex.gap-2 {
                flex-direction: column;
                gap: 10px !important;
            }
            
            .d-flex.gap-2 .btn {
                width: 100%;
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
            
            .form-control, .form-select {
                font-size: 13px;
                padding: 6px 10px;
            }
            
            .form-label {
                font-size: 13px;
            }
            
            .btn {
                font-size: 13px;
                padding: 6px 10px;
            }
            
            .table {
                font-size: 13px;
            }
            
            .table th,
            .table td {
                padding: 6px 2px;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">History Absensi</h2>
                        <p class="text-muted">Riwayat absensi mahasiswa bimbingan</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-filter me-2"></i>Filter Data
                    @if(request()->hasAny(['nama', 'jurusan', 'status', 'tanggal_mulai', 'tanggal_akhir', 'waktu_mulai', 'waktu_akhir']))
                        <span class="badge bg-primary ms-2">({{ $attendances->total() }} hasil)</span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('history.attendances.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Mahasiswa</label>
                                <input type="text" class="form-control" id="nama" name="nama" 
                                       value="{{ request('nama') }}" placeholder="Cari nama mahasiswa...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="jurusan" class="form-label">Jurusan</label>
                                <select class="form-select" id="jurusan" name="jurusan">
                                    <option value="">Semua Jurusan</option>
                                    @foreach($jurusanList as $jurusan)
                                        <option value="{{ $jurusan }}" {{ request('jurusan') == $jurusan ? 'selected' : '' }}>
                                            {{ $jurusan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
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
                            <div class="mb-3">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" 
                                       value="{{ request('tanggal_mulai') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" 
                                       value="{{ request('tanggal_akhir') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
                                <input type="time" class="form-control" id="waktu_mulai" name="waktu_mulai" 
                                       value="{{ request('waktu_mulai') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="waktu_akhir" class="form-label">Waktu Akhir</label>
                                <input type="time" class="form-control" id="waktu_akhir" name="waktu_akhir" 
                                       value="{{ request('waktu_akhir') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3 d-flex align-items-end h-100">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i>Filter
                                    </button>
                                    <a href="{{ route('history.attendances.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-refresh me-2"></i>Reset
                                    </a>
                                </div>
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
                        <i class="fas fa-calendar-check me-2"></i>History Absensi
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
                                            <th>Jurusan</th>
                                            <th>Tanggal</th>
                                            <th>Waktu Masuk</th>
                                            <th>Waktu Keluar</th>
                                            <th>Lokasi</th>
                                            <th>Status</th>
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
                                                                <span class="text-white">{{ substr($attendance->user->name,0,1) }}</span>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="fw-bold">{{ $attendance->user->name }}</div>
                                                            <small class="text-muted">{{ $attendance->user->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $attendance->user->nim ?? '-' }}</td>
                                                <td>{{ $attendance->user->jurusan ? ucfirst($attendance->user->jurusan) : '-' }}</td>
                                                <td>{{ $attendance->tanggal ? \Carbon\Carbon::parse($attendance->tanggal)->format('d M Y') : '-' }}</td>
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
                                                    @if($attendance->latitude && $attendance->longitude)
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $attendance->latitude }}, {{ $attendance->longitude }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak ada</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $statusColors = [
                                                            'hadir' => 'bg-success',
                                                            'terlambat' => 'bg-warning',
                                                            'izin' => 'bg-info',
                                                            'sakit' => 'bg-primary',
                                                            'alpha' => 'bg-danger',
                                                            'pending' => 'bg-warning text-dark'
                                                        ];
                                                        $statusColor = $statusColors[$attendance->status] ?? 'bg-secondary';
                                                        $statusLabels = [
                                                            'hadir' => 'Hadir', 
                                                            'terlambat' => 'Terlambat', 
                                                            'izin' => 'Izin', 
                                                            'sakit' => 'Sakit', 
                                                            'alpha' => 'Alpha',
                                                            'pending' => 'Pending'
                                                        ];
                                                    @endphp
                                                    <span class="badge {{ $statusColor }}">
                                                        {{ $statusLabels[$attendance->status] ?? $attendance->status }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('history.attendances.show', $attendance) }}" class="btn btn-sm btn-info text-white mb-1">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($attendance->status == 'pending')
                                                        <form action="{{ route('attendance.approve', $attendance) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-success mb-1" title="Setujui" onclick="return confirm('Setujui absensi ini?')">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('attendance.reject', $attendance) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-danger mb-1" title="Tolak" onclick="return confirm('Tolak absensi ini?')">
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

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $attendances->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada data absensi</h5>
                        <p class="text-muted">Belum ada absensi yang ditemukan sesuai filter yang dipilih.</p>
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
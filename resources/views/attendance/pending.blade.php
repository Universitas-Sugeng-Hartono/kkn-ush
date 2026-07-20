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
                        <h2 class="fw-bold">Validasi Absensi Pending</h2>
                        <p class="text-muted">Daftar absensi mahasiswa bimbingan yang menunggu validasi</p>
                    </div>
                    <div>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header" style="background-color: #f2b70d;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0" style="color: #0B1F3A;">
                                <i class="fas fa-calendar-check me-2"></i>Absensi Pending
                            </h5>
                            @if($attendances->count() > 0)
                                <div class="d-flex gap-2">
                                    <form action="{{ route('attendance.approveAll') }}" method="POST" onsubmit="return confirm('Setujui semua absensi pending?')">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check-double me-1"></i>Setujui Semua
                                        </button>
                                    </form>
                                    <form action="{{ route('attendance.rejectAll') }}" method="POST" onsubmit="return confirm('Tolak semua absensi pending?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-times-double me-1"></i>Tolak Semua
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success mb-4">{{ session('success') }}</div>
                        @endif
                        @if($attendances->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle datatable w-100">
                                    <thead>
                                        <tr>
                                            <th>Mahasiswa</th>
                                            <th>NIM</th>
                                            <th>Jurusan</th>
                                            <th>Tanggal</th>
                                            <th>Waktu Masuk</th>
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
                                                <td>{{ $attendance->waktu_masuk ? \Carbon\Carbon::parse($attendance->waktu_masuk)->format('H:i') : '-' }}</td>
                                                <td>{{ $attendance->latitude }}, {{ $attendance->longitude }}</td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('attendance.show', $attendance) }}" class="btn btn-sm btn-info text-white mb-1">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
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
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <!-- DataTables handles pagination -->
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                                <h4 class="mt-3">Tidak Ada Absensi Pending</h4>
                                <p class="text-muted">Semua absensi mahasiswa bimbingan Anda sudah divalidasi</p>
                            </div>
                        @endif
                    </div>
                </div>
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
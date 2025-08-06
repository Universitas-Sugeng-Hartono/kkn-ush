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
            
            h4.fw-bold {
                font-size: 1.2rem;
            }
            
            /* Profile image adjustments */
            .rounded-circle {
                width: 80px !important;
                height: 80px !important;
            }
            
            .bg-primary.rounded-circle {
                width: 80px !important;
                height: 80px !important;
            }
            
            .bg-primary.rounded-circle span {
                font-size: 32px !important;
            }
            
            /* Table adjustments */
            .table {
                font-size: 14px;
            }
            
            .table td {
                padding: 8px 4px;
            }
            
            /* Button adjustments */
            .btn {
                font-size: 14px;
                padding: 8px 12px;
            }
            
            /* Badge adjustments */
            .badge {
                font-size: 12px;
            }
            
            .badge[style*="font-size: 1rem"] {
                font-size: 14px !important;
            }
            
            /* Stack header buttons vertically */
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 10px;
            }
            
            .d-flex.justify-content-between .btn {
                width: 100%;
            }
            
            /* Grid adjustments */
            .row .col-md-6,
            .row .col-md-8,
            .row .col-md-4 {
                margin-bottom: 15px;
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
            
            h4.fw-bold {
                font-size: 1.1rem;
            }
            
            .rounded-circle {
                width: 60px !important;
                height: 60px !important;
            }
            
            .bg-primary.rounded-circle {
                width: 60px !important;
                height: 60px !important;
            }
            
            .bg-primary.rounded-circle span {
                font-size: 24px !important;
            }
            
            .table {
                font-size: 13px;
            }
            
            .table td {
                padding: 6px 2px;
            }
            
            .btn {
                font-size: 13px;
                padding: 6px 10px;
            }
            
            .badge {
                font-size: 11px;
            }
            
            .badge[style*="font-size: 1rem"] {
                font-size: 12px !important;
            }
            
            /* Stack profile info vertically */
            .text-center {
                text-align: center;
            }
            
            .text-center .mb-4 {
                margin-bottom: 1rem !important;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">Detail Absensi</h2>
                        <p class="text-muted">Informasi lengkap absensi {{ $attendance->user->name }}</p>
                    </div>
                    <div>
                        @if(auth()->user()->hasRole('dpl'))
                            <a href="{{ route('attendance.pending') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Pending
                            </a>
                        @else
                            <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Absensi
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Informasi Mahasiswa -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header" style="background-color: #f2b70d;">
                        <h5 class="card-title mb-0" style="color: #0B1F3A;">
                            <i class="fas fa-user-graduate me-2"></i>Informasi Mahasiswa
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            @if($attendance->user->foto_profil)
                                <img src="{{ asset('storage/'.$attendance->user->foto_profil) }}" 
                                     alt="{{ $attendance->user->name }}" 
                                     class="rounded-circle mb-3"
                                     width="120" height="120"
                                     style="object-fit: cover;">
                            @else
                                <div class="bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                                     style="width: 120px; height: 120px;">
                                    <span class="text-white" style="font-size: 48px;">
                                        {{ substr($attendance->user->name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            <h4 class="fw-bold mb-1">{{ $attendance->user->name }}</h4>
                            <p class="text-muted mb-0">{{ $attendance->user->nim ?? 'NIM belum diisi' }}</p>
                        </div>

                        <table class="table">
                            <tr>
                                <td class="fw-bold">Jurusan</td>
                                <td>
                                    @if($attendance->user->jurusan)
                                        <span class="badge" style="background-color: #0B1F3A; color: white;">
                                            {{ ucfirst($attendance->user->jurusan) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Email</td>
                                <td>{{ $attendance->user->email }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Kelompok</td>
                                <td>
                                    @if($attendance->user->kelompok)
                                        <span class="badge bg-info">{{ $attendance->user->kelompok->nama_kelompok }}</span>
                                    @else
                                        <span class="text-muted">Belum ditentukan</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Lokasi KKN</td>
                                <td>
                                    @if($attendance->user->kelompok && $attendance->user->kelompok->lokasi)
                                        <span class="badge bg-success">{{ $attendance->user->kelompok->lokasi->nama_desa }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Status Absensi -->
                <div class="card">
                    <div class="card-header" style="background-color: #f2b70d;">
                        <h5 class="card-title mb-0" style="color: #0B1F3A;">
                            <i class="fas fa-info-circle me-2"></i>Status Absensi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            @if($attendance->status === 'validated')
                                <span class="badge bg-success" style="font-size: 1rem;">Validated</span>
                            @elseif($attendance->status === 'rejected')
                                <span class="badge bg-danger" style="font-size: 1rem;">Rejected</span>
                            @else
                                <span class="badge bg-warning text-dark" style="font-size: 1rem;">Pending</span>
                            @endif
                        </div>
                        
                        @if(auth()->user()->hasRole('dpl') && $attendance->status === 'pending')
                            <div class="d-grid gap-2">
                                <form action="{{ route('attendance.approve', $attendance) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Setujui absensi ini?')">
                                        <i class="fas fa-check me-2"></i>Setujui
                                    </button>
                                </form>
                                <form action="{{ route('attendance.reject', $attendance) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak absensi ini?')">
                                        <i class="fas fa-times me-2"></i>Tolak
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Detail Absensi -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header" style="background-color: #f2b70d;">
                        <h5 class="card-title mb-0" style="color: #0B1F3A;">
                            <i class="fas fa-calendar-check me-2"></i>Detail Absensi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td class="fw-bold">Tanggal</td>
                                        <td>{{ $attendance->tanggal ? \Carbon\Carbon::parse($attendance->tanggal)->format('d M Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Waktu Masuk</td>
                                        <td>{{ $attendance->waktu_masuk ? \Carbon\Carbon::parse($attendance->waktu_masuk)->format('H:i') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Waktu Keluar</td>
                                        <td>{{ $attendance->waktu_keluar ? \Carbon\Carbon::parse($attendance->waktu_keluar)->format('H:i') : 'Belum keluar' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Status</td>
                                        <td>
                                            @if($attendance->status === 'validated')
                                                <span class="badge bg-success">Validated</span>
                                            @elseif($attendance->status === 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td class="fw-bold">Latitude</td>
                                        <td>{{ $attendance->latitude }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Longitude</td>
                                        <td>{{ $attendance->longitude }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Kelompok</td>
                                        <td>{{ $attendance->kelompok->nama_kelompok ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Dibuat</td>
                                        <td>{{ $attendance->created_at->format('d M Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if($attendance->keterangan)
                            <div class="mt-4">
                                <h6 class="fw-bold">Keterangan:</h6>
                                <p class="text-muted">{{ $attendance->keterangan }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Foto Selfie -->
                @if($attendance->foto_kegiatan)
                    <div class="card">
                        <div class="card-header" style="background-color: #f2b70d;">
                            <h5 class="card-title mb-0" style="color: #0B1F3A;">
                                <i class="fas fa-camera me-2"></i>Foto Selfie
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ asset('storage/' . $attendance->foto_kegiatan) }}" 
                                 alt="Foto selfie absensi" 
                                 class="img-fluid rounded"
                                 style="max-height: 400px;">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            border-bottom: none;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: #0B1F3A;
        }

        .badge {
            font-size: 0.75rem;
        }

        .btn {
            border-radius: 8px;
        }
    </style>
    @endpush
</x-app-layout> 
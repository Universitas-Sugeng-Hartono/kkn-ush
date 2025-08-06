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
            
            /* Sidebar and main content - Stack vertically */
            .row .col-md-4,
            .row .col-md-8 {
                margin-bottom: 20px;
            }
            
            /* Text adjustments */
            h2.fw-bold {
                font-size: 1.5rem;
            }
            
            h4.fw-bold {
                font-size: 1.2rem;
            }
            
            h5.card-title {
                font-size: 1.1rem;
            }
            
            /* Profile image smaller on mobile */
            .rounded-circle {
                width: 100px !important;
                height: 100px !important;
            }
            
            .bg-primary.rounded-circle {
                font-size: 36px !important;
            }
            
            /* Table adjustments */
            .table {
                font-size: 14px;
            }
            
            .table td, .table th {
                padding: 8px 5px;
            }
            
            /* Stats adjustments */
            .border.rounded h3 {
                font-size: 1.5rem;
            }
            
            .border.rounded p {
                font-size: 12px;
            }
            
            /* Progress bar adjustments */
            .progress {
                height: 6px !important;
            }
            
            .progress + small {
                font-size: 11px;
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
            
            /* Display adjustments */
            .display-4 {
                font-size: 2rem;
            }
            
            .h5 {
                font-size: 1.1rem;
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
            
            h4.fw-bold {
                font-size: 1.1rem;
            }
            
            h5.card-title {
                font-size: 1rem;
            }
            
            /* Profile image even smaller */
            .rounded-circle {
                width: 80px !important;
                height: 80px !important;
            }
            
            .bg-primary.rounded-circle {
                font-size: 28px !important;
            }
            
            .table {
                font-size: 13px;
            }
            
            .table td, .table th {
                padding: 6px 3px;
            }
            
            .border.rounded h3 {
                font-size: 1.3rem;
            }
            
            .border.rounded p {
                font-size: 11px;
            }
            
            .btn {
                font-size: 13px;
                padding: 6px 10px;
            }
            
            .badge {
                font-size: 11px;
            }
            
            .display-4 {
                font-size: 1.8rem;
            }
            
            .h5 {
                font-size: 1rem;
            }
            
            /* Stack buttons vertically */
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 10px;
            }
            
            .d-flex.justify-content-between .btn {
                width: 100%;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">Detail Mahasiswa</h2>
                        <p class="text-muted">Informasi lengkap mahasiswa {{ $user->name }}</p>
                    </div>
                    <div>
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
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
                            @if($user->foto_profil)
                                <img src="{{ asset('storage/'.$user->foto_profil) }}" 
                                     alt="{{ $user->name }}" 
                                     class="rounded-circle mb-3"
                                     width="120" height="120"
                                     style="object-fit: cover;">
                            @else
                                <div class="bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                                     style="width: 120px; height: 120px;">
                                    <span class="text-white" style="font-size: 48px;">
                                        {{ substr($user->name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                            <p class="text-muted mb-0">{{ $user->nim ?? 'NIM belum diisi' }}</p>
                        </div>

                        <table class="table">
                            <tr>
                                <td class="fw-bold">Jurusan</td>
                                <td>
                                    @if($user->jurusan)
                                        <span class="badge" style="background-color: #0B1F3A; color: white;">
                                            {{ ucfirst($user->jurusan) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Email</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">No HP</td>
                                <td>{{ $user->no_hp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Alamat</td>
                                <td>{{ $user->alamat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Kelompok</td>
                                <td>
                                    @if($user->kelompok)
                                        <span class="badge bg-info">{{ $user->kelompok->nama_kelompok }}</span>
                                    @else
                                        <span class="text-muted">Belum ditentukan</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Lokasi KKN</td>
                                <td>
                                    @if($user->kelompok && $user->kelompok->lokasi)
                                        <span class="badge bg-success">{{ $user->kelompok->lokasi->nama_desa }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Statistik -->
                <div class="card mb-4">
                    <div class="card-header" style="background-color: #f2b70d;">
                        <h5 class="card-title mb-0" style="color: #0B1F3A;">
                            <i class="fas fa-chart-bar me-2"></i>Statistik
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="border rounded p-3 text-center">
                                    <h3 class="mb-1 text-primary">{{ $user->logbooks->count() }}</h3>
                                    <p class="text-muted mb-0">Logbook</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3 text-center">
                                    <h3 class="mb-1 text-success">{{ $user->absensi->count() }}</h3>
                                    <p class="text-muted mb-0">Absensi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nilai KKN Tematik -->
                <div class="card">
                    <div class="card-header" style="background-color: #f2b70d;">
                        <h5 class="card-title mb-0" style="color: #0B1F3A;">
                            <i class="fas fa-star me-2"></i>Nilai KKN Tematik
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($user->nilai->count() > 0)
                            @php
                                $nilai = $user->nilai->first();
                            @endphp
                            <div class="text-center mb-3">
                                <div class="display-4 fw-bold text-primary mb-1">{{ number_format($nilai->nilai_akhir, 1) }}</div>
                                <div class="h5 mb-0">
                                    <span class="badge" style="background-color: #28a745; color: white; font-size: 1rem;">
                                        Grade {{ $nilai->grade }}
                                    </span>
                                </div>
                            </div>

                            <!-- Progress Bars -->
                            <div class="space-y-3">
                                <div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="fw-bold">Pembekalan (10%)</small>
                                        <small>{{ number_format(($nilai->nilai_kehadiran_pembekalan + $nilai->nilai_sikap_pembekalan) / 2, 1) }}</small>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-warning" style="width: {{ ($nilai->nilai_kehadiran_pembekalan + $nilai->nilai_sikap_pembekalan) / 2 }}%"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="fw-bold">Pelaksanaan (60%)</small>
                                        <small>{{ number_format(($nilai->nilai_kehadiran_lokasi + $nilai->nilai_sikap_pelaksanaan + $nilai->nilai_keterlibatan_kegiatan + $nilai->nilai_relevansi_program + $nilai->nilai_keberhasilan_program) / 5, 1) }}</small>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success" style="width: {{ ($nilai->nilai_kehadiran_lokasi + $nilai->nilai_sikap_pelaksanaan + $nilai->nilai_keterlibatan_kegiatan + $nilai->nilai_relevansi_program + $nilai->nilai_keberhasilan_program) / 5 }}%"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="fw-bold">Laporan (30%)</small>
                                        <small>{{ number_format(($nilai->nilai_sistematika_laporan + $nilai->nilai_konten_medsos + $nilai->nilai_bahasa + $nilai->nilai_analisis + $nilai->nilai_ketepatan_waktu + $nilai->nilai_produk_teknologi) / 6, 1) }}</small>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-info" style="width: {{ ($nilai->nilai_sistematika_laporan + $nilai->nilai_konten_medsos + $nilai->nilai_bahasa + $nilai->nilai_analisis + $nilai->nilai_ketepatan_waktu + $nilai->nilai_produk_teknologi) / 6 }}%"></div>
                                    </div>
                                </div>
                            </div>

                            @if($nilai->catatan)
                                <div class="mt-3">
                                    <h6 class="fw-bold text-muted">Catatan DPL:</h6>
                                    <p class="text-muted bg-light p-2 rounded">{{ $nilai->catatan }}</p>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-graduation-cap text-4xl text-muted mb-3"></i>
                                <p class="text-muted mb-1">Belum ada nilai</p>
                                <p class="text-sm text-muted">Nilai akan ditampilkan setelah DPL memberikan penilaian</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Konten Utama -->
            <div class="col-md-8">
                <!-- Logbook Terbaru -->
                <div class="card mb-4">
                    <div class="card-header" style="background-color: #f2b70d;">
                        <h5 class="card-title mb-0" style="color: #0B1F3A;">
                            <i class="fas fa-book me-2"></i>Logbook Terbaru
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($user->logbooks->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Kegiatan</th>
                                            <th>Lokasi</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->logbooks->take(10) as $logbook)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold">{{ $logbook->tanggal->format('d M Y') }}</div>
                                                    <small class="text-muted">{{ $logbook->tanggal->format('H:i') }}</small>
                                                </td>
                                                <td>
                                                    <div class="fw-bold">{{ Str::limit($logbook->kegiatan, 50) }}</div>
                                                    <small class="text-muted">{{ Str::limit($logbook->deskripsi, 80) }}</small>
                                                </td>
                                                <td>
                                                    @if($logbook->lokasi)
                                                        <span class="badge bg-info">{{ $logbook->lokasi }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($logbook->is_submitted)
                                                        <span class="badge bg-success">Submitted</span>
                                                    @else
                                                        <span class="badge bg-warning">Draft</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('logbooks.show', $logbook) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-book text-4xl text-muted mb-3"></i>
                                <p class="text-muted">Belum ada logbook</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Absensi Terbaru -->
                <div class="card">
                    <div class="card-header" style="background-color: #f2b70d;">
                        <h5 class="card-title mb-0" style="color: #0B1F3A;">
                            <i class="fas fa-calendar-check me-2"></i>Absensi Terbaru
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($user->absensi->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Waktu</th>
                                            <th>Status</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->absensi->take(10) as $absen)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold">{{ $absen->tanggal->format('d M Y') }}</div>
                                                </td>
                                                <td>
                                                    <div class="fw-bold">{{ $absen->waktu_masuk ? $absen->waktu_masuk->format('H:i') : '-' }}</div>
                                                    @if($absen->waktu_keluar)
                                                        <small class="text-muted">{{ $absen->waktu_keluar->format('H:i') }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($absen->status == 'hadir')
                                                        <span class="badge bg-success">Hadir</span>
                                                    @elseif($absen->status == 'izin')
                                                        <span class="badge bg-warning">Izin</span>
                                                    @elseif($absen->status == 'sakit')
                                                        <span class="badge bg-info">Sakit</span>
                                                    @else
                                                        <span class="badge bg-danger">Tidak Hadir</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ $absen->keterangan ?? '-' }}</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-check text-4xl text-muted mb-3"></i>
                                <p class="text-muted">Belum ada data absensi</p>
                            </div>
                        @endif
                    </div>
                </div>
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

        .progress {
            border-radius: 10px;
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
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
            
            /* Button adjustments */
            .btn {
                font-size: 14px;
                padding: 8px 12px;
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
            
            .btn {
                font-size: 13px;
                padding: 6px 10px;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">Detail History Absensi</h2>
                        <p class="text-muted">Informasi lengkap absensi {{ $attendance->user->name }}</p>
                    </div>
                    <div>
                        <a href="{{ route('history.attendances.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke History
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
                            <span class="badge {{ $statusColor }}" style="font-size: 1rem;">
                                {{ $statusLabels[$attendance->status] ?? $attendance->status }}
                            </span>
                            @if($attendance->isInjected())
                                <br>
                                <span class="badge bg-warning text-dark mt-2" style="font-size: 0.9rem;" title="Di-inject oleh: {{ $attendance->injectedBy?->name ?? '-' }}">
                                    <i class="fas fa-syringe me-1"></i>Injected
                                </span>
                            @endif
                        </div>
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
                                        <td>{{ $attendance->tanggal ? \Carbon\Carbon::parse($attendance->tanggal)->format('d F Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Waktu Masuk</td>
                                        <td>
                                            @if($attendance->waktu_masuk)
                                                <span class="badge bg-success">{{ \Carbon\Carbon::parse($attendance->waktu_masuk)->format('H:i') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Waktu Keluar</td>
                                        <td>
                                            @if($attendance->waktu_keluar)
                                                <span class="badge bg-info">{{ \Carbon\Carbon::parse($attendance->waktu_keluar)->format('H:i') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>

                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td class="fw-bold">Latitude</td>
                                        <td>{{ $attendance->latitude ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Longitude</td>
                                        <td>{{ $attendance->longitude ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Alamat Check-in</td>
                                        <td>{{ $attendance->lokasi ?? ($attendance->latitude && $attendance->longitude ? $attendance->latitude . ', ' . $attendance->longitude : '-') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Alamat Check-out</td>
                                        <td>{{ $attendance->lokasi_keluar ?? ($attendance->latitude_keluar && $attendance->longitude_keluar ? $attendance->latitude_keluar . ', ' . $attendance->longitude_keluar : '-') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Dibuat Pada</td>
                                        <td>{{ $attendance->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @if($attendance->isInjected())
                                    <tr>
                                        <td class="fw-bold" style="color: #f2b70d;"><i class="fas fa-user-shield me-1"></i>Di-inject Oleh</td>
                                        <td>{{ $attendance->injectedBy?->name ?? 'Admin' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold" style="color: #f2b70d;"><i class="fas fa-sticky-note me-1"></i>Catatan Inject</td>
                                        <td>
                                            <div class="p-2 border rounded bg-light small">
                                                {{ $attendance->catatan_inject ?? '-' }}
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lokasi -->
                @if($attendance->latitude && $attendance->longitude)
                    <div class="card mb-4">
                        <div class="card-header" style="background-color: #f2b70d;">
                            <h5 class="card-title mb-0" style="color: #0B1F3A;">
                                <i class="fas fa-map-marker-alt me-2"></i>Lokasi Check-in
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Koordinat</label>
                                <p class="form-control-plaintext">{{ $attendance->latitude && $attendance->longitude ? $attendance->latitude . ', ' . $attendance->longitude : 'Koordinat tidak tersedia' }}</p>
                            </div>
                            <div id="map" style="height: 300px; width: 100%; border-radius: 8px;"></div>
                        </div>
                    </div>
                @endif

                <!-- Lokasi Check-out -->
                @if($attendance->latitude_keluar && $attendance->longitude_keluar)
                    <div class="card mb-4">
                        <div class="card-header" style="background-color: #f2b70d;">
                            <h5 class="card-title mb-0" style="color: #0B1F3A;">
                                <i class="fas fa-map-marker-alt me-2"></i>Lokasi Check-out
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Koordinat Keluar</label>
                                <p class="form-control-plaintext">{{ $attendance->latitude_keluar && $attendance->longitude_keluar ? $attendance->latitude_keluar . ', ' . $attendance->longitude_keluar : 'Koordinat tidak tersedia' }}</p>
                            </div>
                            <div id="map-keluar" style="height: 300px; width: 100%; border-radius: 8px;"></div>
                        </div>
                    </div>
                @endif

                <!-- Foto Bukti -->
                @if($attendance->foto_kegiatan)
                    <div class="card mb-4">
                        <div class="card-header" style="background-color: #f2b70d;">
                            <h5 class="card-title mb-0" style="color: #0B1F3A;">
                                <i class="fas fa-camera me-2"></i>Foto Bukti
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ asset('storage/' . $attendance->foto_kegiatan) }}" 
                                 class="img-fluid rounded" alt="Foto Bukti"
                                 style="max-height: 400px; cursor: pointer;"
                                 onclick="openImageModal('{{ asset('storage/' . $attendance->foto_kegiatan) }}')">
                        </div>
                    </div>
                @endif

                <!-- Foto Bukti Keluar -->
                @if($attendance->foto_keluar)
                    <div class="card mb-4">
                        <div class="card-header" style="background-color: #f2b70d;">
                            <h5 class="card-title mb-0" style="color: #0B1F3A;">
                                <i class="fas fa-camera me-2"></i>Foto Bukti Keluar
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ asset('storage/' . $attendance->foto_keluar) }}" 
                                 class="img-fluid rounded" alt="Foto Bukti Keluar"
                                 style="max-height: 400px; cursor: pointer;"
                                 onclick="openImageModal('{{ asset('storage/' . $attendance->foto_keluar) }}')">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Foto Bukti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid" alt="Foto Bukti">
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openImageModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }

        // Initialize maps if coordinates exist
        document.addEventListener('DOMContentLoaded', function() {
            // Map Check-in
            @if($attendance->latitude && $attendance->longitude)
                var mapMasuk = L.map('map').setView([{{ $attendance->latitude }}, {{ $attendance->longitude }}], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(mapMasuk);
                L.marker([{{ $attendance->latitude }}, {{ $attendance->longitude }}])
                 .addTo(mapMasuk)
                 .bindPopup('Lokasi Check-in<br>Alamat tidak tersedia');
            @endif

            // Map Check-out
            @if($attendance->latitude_keluar && $attendance->longitude_keluar)
                var mapKeluar = L.map('map-keluar').setView([{{ $attendance->latitude_keluar }}, {{ $attendance->longitude_keluar }}], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(mapKeluar);
                L.marker([{{ $attendance->latitude_keluar }}, {{ $attendance->longitude_keluar }}])
                 .addTo(mapKeluar)
                 .bindPopup('Lokasi Check-out<br>Alamat tidak tersedia');
            @endif
        });
    </script>
    @endpush
</x-app-layout>
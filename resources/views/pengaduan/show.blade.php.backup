<x-app-layout>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">Detail Pengaduan #{{ $pengaduan->nomor_pengaduan }}</h2>
                        <p class="text-muted mb-0">
                            <i class="fas fa-calendar me-2"></i>{{ $pengaduan->created_at->format('d/m/Y H:i') }} · 
                            <span class="badge {{ $pengaduan->getStatusBadgeClass() }}">
                                {{ $pengaduan->getStatusOptions()[$pengaduan->status] }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('pengaduan.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <!-- Detail Pengaduan -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-alt me-2"></i>Detail Pengaduan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6 class="fw-bold">Subjek</h6>
                            <p>{{ $pengaduan->subjek }}</p>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold">Isi Pengaduan</h6>
                            <p>{{ $pengaduan->isi_pengaduan }}</p>
                        </div>

                        @if($pengaduan->bukti_pendukung)
                            <div class="mb-4">
                                <h6 class="fw-bold">Bukti Pendukung</h6>
                                @php
                                    $extension = pathinfo($pengaduan->bukti_pendukung, PATHINFO_EXTENSION);
                                @endphp

                                @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                                    <img src="{{ Storage::url($pengaduan->bukti_pendukung) }}" 
                                         alt="Bukti Pendukung" 
                                         class="img-fluid rounded">
                                @else
                                    <a href="{{ Storage::url($pengaduan->bukti_pendukung) }}" 
                                       class="btn btn-primary" 
                                       target="_blank">
                                        <i class="fas fa-file-pdf me-2"></i>Lihat Dokumen
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tanggapan Admin -->
                @if($pengaduan->tanggapan)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-comment-alt me-2"></i>Tanggapan Admin
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="fw-bold">Admin</h6>
                                <p>{{ $pengaduan->admin->name }}</p>
                            </div>
                            <div>
                                <h6 class="fw-bold">Tanggapan</h6>
                                <p>{{ $pengaduan->tanggapan }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form Proses Pengaduan -->
                @if($pengaduan->status === 'pending' || $pengaduan->status === 'process')
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ $pengaduan->status === 'pending' ? 'Proses Pengaduan' : 'Update Status' }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('pengaduan.process', $pengaduan) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" name="status" required>
                                        @if($pengaduan->status === 'pending')
                                            <option value="process">Diproses</option>
                                        @endif
                                        <option value="resolved">Selesai</option>
                                        <option value="rejected">Ditolak</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="tanggapan" class="form-label">
                                        {{ $pengaduan->tanggapan ? 'Update Tanggapan' : 'Tanggapan' }}
                                    </label>
                                    <textarea class="form-control" 
                                              name="tanggapan" 
                                              rows="4" 
                                              required>{{ $pengaduan->tanggapan }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Simpan Tanggapan
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                <!-- Informasi Pelapor -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user me-2"></i>Informasi Pelapor
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Nama</th>
                                <td>{{ $pengaduan->nama_pelapor }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $pengaduan->email_pelapor }}</td>
                            </tr>
                            <tr>
                                <th>No. HP</th>
                                <td>{{ $pengaduan->no_hp_pelapor }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Lokasi KKN -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-map-marker-alt me-2"></i>Lokasi KKN
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Nama Lokasi</th>
                                <td>{{ $pengaduan->lokasi->nama }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $pengaduan->lokasi->alamat }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
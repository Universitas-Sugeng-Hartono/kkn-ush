<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Detail User</h1>
            <div>
                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning text-white me-2">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        @if($user->foto_profil)
                            <img src="{{ Storage::url($user->foto_profil) }}" 
                                alt="{{ $user->name }}" 
                                class="rounded-circle mb-3"
                                width="150" height="150"
                                style="object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white mb-3 d-flex align-items-center justify-content-center mx-auto"
                                style="width: 150px; height: 150px; font-size: 48px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <h4>{{ $user->name }}</h4>
                        <p class="text-muted mb-2">{{ $user->email }}</p>
                        @foreach($user->roles as $role)
                            <span class="badge bg-primary">{{ ucfirst($role->name) }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informasi User</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <th>NIM</th>
                                        <td>{{ $user->nim ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jurusan</th>
                                        <td>{{ $user->jurusan ? ucfirst($user->jurusan) : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>NIP</th>
                                        <td>{{ $user->nip ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>No HP</th>
                                        <td>{{ $user->no_hp }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <th>Alamat</th>
                                        <td>{{ $user->alamat }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kelompok</th>
                                        <td>{{ $user->kelompok->nama_kelompok ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Lokasi</th>
                                        <td>{{ $user->kelompok->lokasi->nama ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                @if($user->hasRole('mahasiswa'))
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Statistik</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">Total Logbook</h6>
                                        <h2 class="mb-0">{{ $user->logbooks->count() }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">Total Absensi</h6>
                                        <h2 class="mb-0">{{ $user->absensi->count() }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">Total Nilai</h6>
                                        <h2 class="mb-0">{{ $user->nilai->count() }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> 
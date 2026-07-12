<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Edit User</h1>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                    id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                    id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                    id="password" name="password">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" 
                                    id="password_confirmation" name="password_confirmation">
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                    id="role" name="role" required>
                                    <option value="">Pilih Role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" 
                                            {{ old('role', $user->roles->first()->name ?? '') == $role->name ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nim" class="form-label">NIM</label>
                                <input type="text" class="form-control @error('nim') is-invalid @enderror" 
                                    id="nim" name="nim" value="{{ old('nim', $user->nim) }}">
                                @error('nim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="jurusan" class="form-label">Jurusan</label>
                                <select class="form-select @error('jurusan') is-invalid @enderror" 
                                    id="jurusan" name="jurusan">
                                    <option value="">Pilih Jurusan</option>
                                    <option value="ilmu komputer" {{ old('jurusan', $user->jurusan) == 'ilmu komputer' ? 'selected' : '' }}>Ilmu Komputer</option>
                                    <option value="bisnis digital" {{ old('jurusan', $user->jurusan) == 'bisnis digital' ? 'selected' : '' }}>Bisnis Digital</option>
                                    <option value="gizi" {{ old('jurusan', $user->jurusan) == 'gizi' ? 'selected' : '' }}>Gizi</option>
                                    <option value="manajemen bisnis internasional" {{ old('jurusan', $user->jurusan) == 'manajemen bisnis internasional' ? 'selected' : '' }}>Manajemen Bisnis Internasional</option>
                                    <option value="teknologi pangan" {{ old('jurusan', $user->jurusan) == 'teknologi pangan' ? 'selected' : '' }}>Teknologi Pangan</option>
                                    <option value="hukum bisnis" {{ old('jurusan', $user->jurusan) == 'hukum bisnis' ? 'selected' : '' }}>Hukum Bisnis</option>
                                </select>
                                @error('jurusan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" class="form-control @error('nip') is-invalid @enderror" 
                                    id="nip" name="nip" value="{{ old('nip', $user->nip) }}">
                                @error('nip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="no_hp" class="form-label">No HP</label>
                                <input type="text" class="form-control @error('no_hp') is-invalid @enderror" 
                                    id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" required>
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                    id="alamat" name="alamat" rows="3" required>{{ old('alamat', $user->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="kelompok_id" class="form-label">Kelompok</label>
                                <select class="form-select @error('kelompok_id') is-invalid @enderror" 
                                    id="kelompok_id" name="kelompok_id">
                                    <option value="">Pilih Kelompok</option>
                                    @foreach($kelompok as $k)
                                        <option value="{{ $k->id }}" 
                                            {{ old('kelompok_id', $user->kelompok_id) == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama_kelompok }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kelompok_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="foto_profil" class="form-label">Foto Profil</label>
                                @if($user->foto_profil)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($user->foto_profil) }}" 
                                            alt="{{ $user->name }}" 
                                            class="rounded"
                                            width="100">
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('foto_profil') is-invalid @enderror" 
                                    id="foto_profil" name="foto_profil" accept="image/*">
                                @error('foto_profil')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Show/hide NIM/NIP/Jurusan based on role
        $('#role').change(function() {
            if ($(this).val() === 'mahasiswa') {
                $('#nim').parent().show();
                $('#jurusan').parent().show();
                $('#nip').parent().hide();
                $('#nim').prop('required', true);
                $('#jurusan').prop('required', true);
                $('#nip').prop('required', false);
            } else if ($(this).val() === 'dpl') {
                $('#nim').parent().hide();
                $('#jurusan').parent().hide();
                $('#nip').parent().show();
                $('#nim').prop('required', false);
                $('#jurusan').prop('required', false);
                $('#nip').prop('required', true);
            } else {
                $('#nim').parent().hide();
                $('#jurusan').parent().hide();
                $('#nip').parent().hide();
                $('#nim').prop('required', false);
                $('#jurusan').prop('required', false);
                $('#nip').prop('required', false);
            }
        }).trigger('change');

        // Show/hide kelompok based on role
        $('#role').change(function() {
            if ($(this).val() === 'mahasiswa') {
                $('#kelompok_id').parent().show();
                $('#kelompok_id').prop('required', true);
            } else {
                $('#kelompok_id').parent().hide();
                $('#kelompok_id').prop('required', false);
            }
        }).trigger('change');
    </script>
    @endpush
</x-app-layout> 
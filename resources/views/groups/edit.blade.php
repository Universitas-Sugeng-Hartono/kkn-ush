<x-app-layout>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">Edit Kelompok</h2>
                        <p class="text-muted">Edit data kelompok KKN</p>
                    </div>
                    <div>
                        <a href="{{ route('groups.show', $group) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('groups.update', $group) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Informasi Dasar -->
                                <div class="col-md-6">
                                    <h5 class="mb-4">Informasi Dasar</h5>

                                    <div class="mb-3">
                                        <label for="nama_kelompok" class="form-label">Nama Kelompok</label>
                                        <input type="text" 
                                               class="form-control @error('nama_kelompok') is-invalid @enderror" 
                                               id="nama_kelompok" 
                                               name="nama_kelompok" 
                                               value="{{ old('nama_kelompok', $group->nama_kelompok) }}"
                                               required>
                                        @error('nama_kelompok')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="tahun_akademik_id" class="form-label">Tahun Akademik</label>
                                        <select class="form-select @error('tahun_akademik_id') is-invalid @enderror" 
                                                id="tahun_akademik_id" 
                                                name="tahun_akademik_id" 
                                                required>
                                            <option value="">Pilih Tahun Akademik</option>
                                            @foreach($tahunAkademik as $ta)
                                                <option value="{{ $ta->id }}" 
                                                        {{ old('tahun_akademik_id', $group->tahun_akademik_id) == $ta->id ? 'selected' : '' }}>
                                                    {{ $ta->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('tahun_akademik_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="semester_id" class="form-label">Semester</label>
                                        <select class="form-select @error('semester_id') is-invalid @enderror" 
                                                id="semester_id" 
                                                name="semester_id" 
                                                required>
                                            <option value="">Pilih Semester</option>
                                            @foreach($semester as $s)
                                                <option value="{{ $s->id }}" 
                                                        {{ old('semester_id', $group->semester_id) == $s->id ? 'selected' : '' }}>
                                                    {{ $s->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('semester_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="lokasi_id" class="form-label">Lokasi</label>
                                        <select class="form-select @error('lokasi_id') is-invalid @enderror" 
                                                id="lokasi_id" 
                                                name="lokasi_id" 
                                                required>
                                            <option value="">Pilih Lokasi</option>
                                            @foreach($lokasi as $l)
                                                <option value="{{ $l->id }}" 
                                                        {{ old('lokasi_id', $group->lokasi_id) == $l->id ? 'selected' : '' }}>
                                                    {{ $l->nama_desa }}, {{ $l->nama_kecamatan }}, {{ $l->nama_kabupaten }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('lokasi_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="dpl_id" class="form-label">Dosen Pembimbing Lapangan (DPL)</label>
                                        <select class="form-select @error('dpl_id') is-invalid @enderror" 
                                                id="dpl_id" 
                                                name="dpl_id">
                                            <option value="">Pilih DPL</option>
                                            @foreach($dpl as $d)
                                                <option value="{{ $d->id }}" 
                                                        {{ old('dpl_id', $group->dpl_id) == $d->id ? 'selected' : '' }}>
                                                    {{ $d->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('dpl_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                                  id="deskripsi" 
                                                  name="deskripsi" 
                                                  rows="3">{{ old('deskripsi', $group->deskripsi) }}</textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Daftar Mahasiswa -->
                                <div class="col-md-6">
                                    <h5 class="mb-4">Anggota Kelompok</h5>

                                    <div class="mb-3">
                                        <label class="form-label">Pilih Mahasiswa</label>
                                        <div class="card">
                                            <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                                                <div class="list-group list-group-flush">
                                                    @foreach($mahasiswa as $m)
                                                        <label class="list-group-item">
                                                            <input class="form-check-input me-2" 
                                                                   type="checkbox" 
                                                                   name="mahasiswa_ids[]" 
                                                                   value="{{ $m->id }}"
                                                                   {{ in_array($m->id, old('mahasiswa_ids', $group->mahasiswa->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                            <div>
                                                                <strong>{{ $m->name }}</strong>
                                                                <br>
                                                                <small class="text-muted">
                                                                    {{ $m->nim }} - {{ $m->jurusan ? ucfirst($m->jurusan) : 'Belum diisi' }}
                                                                </small>
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @error('mahasiswa_ids')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Initialize Select2 for better select boxes
        $(document).ready(function() {
            $('#tahun_akademik_id').select2({
                theme: 'bootstrap-5'
            });

            $('#semester_id').select2({
                theme: 'bootstrap-5'
            });

            $('#lokasi_id').select2({
                theme: 'bootstrap-5'
            });

            $('#dpl_id').select2({
                theme: 'bootstrap-5'
            });
        });
    </script>
    @endpush
</x-app-layout> 
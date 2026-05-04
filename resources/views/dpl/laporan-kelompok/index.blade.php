<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Laporan Kelompok (DPL)</h1>
                @if($tahunAktif && $semesterAktif)
                    <div class="text-muted small">Periode aktif: {{ $tahunAktif->nama }} - {{ $semesterAktif->nama }}</div>
                @endif
            </div>
        </div>

        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">
                <form method="GET" action="{{ route('dpl.laporan-kelompok.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Tahun Akademik</label>
                        <select name="tahun_akademik_id" class="form-select form-select-sm">
                            <option value="">Semua Tahun</option>
                            @foreach($tahunAkademikList as $ta)
                                <option value="{{ $ta->id }}" {{ $tahun_akademik_id == $ta->id ? 'selected' : '' }}>
                                    {{ $ta->nama }} {{ $ta->is_aktif ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Semester</label>
                        <select name="semester_id" class="form-select form-select-sm">
                            <option value="">Semua Semester</option>
                            @foreach($semesterList as $sem)
                                <option value="{{ $sem->id }}" {{ $semester_id == $sem->id ? 'selected' : '' }}>
                                    {{ $sem->nama }} {{ $sem->is_aktif ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Kelompok</label>
                        <select class="form-select form-select-sm" name="kelompok_id">
                            <option value="">Semua Kelompok</option>
                            @foreach($kelompokList as $k)
                                <option value="{{ $k->id }}" {{ $selected_kelompok_id == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelompok }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                                <i class="fas fa-filter me-2"></i>Tampilkan
                            </button>
                            <a href="{{ route('dpl.laporan-kelompok.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-undo me-2"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Laporan</h5>
                <span class="badge bg-secondary">{{ $laporan->count() }} file</span>
            </div>
            <div class="card-body">
                @if($laporan->isEmpty())
                    <div class="text-muted">Belum ada laporan.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Kelompok</th>
                                    <th>Judul</th>
                                    <th>Uploader</th>
                                    <th>Tanggal</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($laporan as $item)
                                    <tr>
                                        <td>{{ $item->kelompok?->nama_kelompok ?? '-' }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ $item->judul ?: $item->file_original_name }}</div>
                                            <div class="text-muted small">{{ $item->file_original_name }}</div>
                                        </td>
                                        <td>{{ $item->user?->name ?? '-' }}</td>
                                        <td>{{ $item->created_at?->format('d M Y H:i') }}</td>
                                        <td class="text-end">
                                            <a class="btn btn-sm btn-success" href="{{ route('laporan-kelompok.download', $item) }}">
                                                <i class="fas fa-download me-1"></i>Download
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>


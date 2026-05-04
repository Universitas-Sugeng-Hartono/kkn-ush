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

            /* Table responsive */
            .table-responsive {
                font-size: 14px;
            }

            /* DataTables mobile adjustments */
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                margin-bottom: 10px;
            }

            .dataTables_wrapper .dataTables_length select,
            .dataTables_wrapper .dataTables_filter input {
                font-size: 14px;
                padding: 5px;
            }

            /* Button adjustments */
            .btn-sm {
                font-size: 12px;
                padding: 5px 8px;
            }

            /* Text adjustments */
            h2.fw-bold {
                font-size: 1.5rem;
            }

            /* Table cell padding */
            .table td,
            .table th {
                padding: 8px 5px;
                font-size: 14px;
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

            .btn-sm {
                font-size: 11px;
                padding: 4px 6px;
            }

            .table td,
            .table th {
                padding: 6px 3px;
                font-size: 13px;
            }
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Mahasiswa') }}
        </h2>
    </x-slot>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">Daftar Mahasiswa Bimbingan</h2>
                        <p class="text-muted">Kelola dan pantau mahasiswa yang Anda bimbing</p>
                    </div>
                    <div class="text-end">
                        <div class="text-muted small">
                            Periode aktif: {{ $tahunAktif?->nama ?? '-' }} - {{ $semesterAktif?->nama ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Periode -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form action="{{ route('students.index') }}" method="GET" class="row g-3 align-items-end">
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
                        <label class="form-label fw-bold small">Jurusan</label>
                        <select name="jurusan" class="form-select form-select-sm">
                            <option value="">Semua Jurusan</option>
                            @foreach($jurusanList as $jurusan => $count)
                            <option value="{{ $jurusan }}" {{ $selected_jurusan == $jurusan ? 'selected' : '' }}>
                                {{ ucfirst($jurusan) }} ({{ $count }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                                <i class="fas fa-filter me-2"></i>Filter
                            </button>
                            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-undo me-2"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="studentsTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Jurusan</th>
                                <th>Email</th>
                                <th>Kelompok</th>
                                <th>Lokasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                            <tr>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->nim ?? '-' }}</td>
                                <td>{{ $student->jurusan ? ucfirst($student->jurusan) : '-' }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->kelompok?->nama ?? '-' }}</td>
                                <td>{{ $student->kelompok?->lokasi?->nama ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#studentsTable').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                },
                columnDefs: [{
                    targets: -1,
                    searchable: false,
                    orderable: false,
                    className: 'text-center'
                }]
            });
        });
    </script>
    @endpush
</x-app-layout>
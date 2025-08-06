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
            
            /* Filter section - Stack vertically */
            .row .col-md-4,
            .row .col-md-3,
            .row .col-md-2 {
                margin-bottom: 15px;
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
            .table td, .table th {
                padding: 8px 5px;
                font-size: 14px;
            }
            
            /* Profile image smaller */
            .rounded-circle {
                width: 28px !important;
                height: 28px !important;
            }
            
            /* Button group adjustments */
            .btn-group .btn {
                padding: 4px 6px;
                font-size: 11px;
            }
            
            /* Form controls */
            .form-control, .form-select {
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
            
            .btn-sm {
                font-size: 11px;
                padding: 4px 6px;
            }
            
            .table td, .table th {
                padding: 6px 3px;
                font-size: 13px;
            }
            
            .btn-group .btn {
                padding: 3px 5px;
                font-size: 10px;
            }
            
            .form-control, .form-select {
                font-size: 13px;
                padding: 6px 10px;
            }
            
            /* Hide some columns on very small screens */
            .table th:nth-child(3),
            .table td:nth-child(3),
            .table th:nth-child(4),
            .table td:nth-child(4),
            .table th:nth-child(8),
            .table td:nth-child(8) {
                display: none;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">Penilaian Mahasiswa</h2>
                        <p class="text-muted">Kelola penilaian mahasiswa bimbingan Anda</p>
                    </div>
                    <a href="{{ route('grades.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Penilaian
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter dan Search -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="search" class="form-label">Cari Mahasiswa</label>
                                <input type="text" class="form-control" id="search" placeholder="Nama, NIM, atau Jurusan...">
                            </div>
                            <div class="col-md-3">
                                <label for="group_filter" class="form-label">Filter Kelompok</label>
                                <select class="form-select" id="group_filter">
                                    <option value="">Semua Kelompok</option>
                                    @foreach($groups ?? [] as $group)
                                        <option value="{{ $group->id }}">{{ $group->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="status_filter" class="form-label">Filter Grade</label>
                                <select class="form-select" id="status_filter">
                                    <option value="">Semua Grade</option>
                                    <option value="A">Grade A</option>
                                    <option value="A-">Grade A-</option>
                                    <option value="B+">Grade B+</option>
                                    <option value="B">Grade B</option>
                                    <option value="C">Grade C</option>
                                    <option value="D">Grade D</option>
                                    <option value="E">Grade E</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-secondary w-100" onclick="resetFilters()">
                                    <i class="fas fa-refresh me-1"></i>Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Penilaian -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Daftar Penilaian</h5>
                    </div>
                    <div class="card-body">
                        @if($grades->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover" id="gradesTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>NIM</th>
                                            <th>Jurusan</th>
                                            <th>Kelompok</th>
                                            <th>Nilai Akhir</th>
                                            <th>Grade</th>
                                            <th>Status</th>
                                            <th>DPL</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($grades as $index => $grade)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($grade->user->photo)
                                                        <img src="{{ asset('storage/' . $grade->user->photo) }}" 
                                                             alt="Photo" class="rounded-circle me-2" 
                                                             style="width: 32px; height: 32px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                             style="width: 32px; height: 32px;">
                                                            <i class="fas fa-user text-white" style="font-size: 14px;"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">{{ $grade->user->name }}</div>
                                                        <small class="text-muted">{{ $grade->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $grade->user->nim ?? '-' }}</td>
                                            <td>{{ $grade->user->jurusan ? ucfirst($grade->user->jurusan) : '-' }}</td>
                                            <td>
                                                @if($grade->user->kelompok)
                                                    <span class="badge bg-primary">{{ $grade->user->kelompok->nama }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $grade->nilai_akhir >= 80 ? 'success' : ($grade->nilai_akhir >= 60 ? 'warning' : 'danger') }}">
                                                    {{ number_format($grade->nilai_akhir, 1) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $grade->grade === 'A' ? 'success' : ($grade->grade === 'B' ? 'info' : ($grade->grade === 'C' ? 'warning' : 'danger')) }}">
                                                    {{ $grade->grade }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($grade->nilai_akhir >= 60)
                                                    <span class="badge bg-success">Lulus</span>
                                                @else
                                                    <span class="badge bg-danger">Tidak Lulus</span>
                                                @endif
                                            </td>
                                            <td>{{ $grade->dpl->name ?? '-' }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('grades.show', $grade) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('grades.edit', $grade) }}" 
                                                       class="btn btn-sm btn-outline-warning" 
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('grades.destroy', $grade) }}" 
                                                          method="POST" 
                                                          class="d-inline" 
                                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus penilaian ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-danger" 
                                                                title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                                    <p>Belum ada data penilaian</p>
                                                    <a href="{{ route('grades.create') }}" class="btn btn-primary">
                                                        <i class="fas fa-plus me-2"></i>Tambah Penilaian Pertama
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                                <h5>Belum Ada Penilaian</h5>
                                <p class="text-muted">Belum ada data penilaian mahasiswa. Silakan tambahkan penilaian baru.</p>
                                <a href="{{ route('grades.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Tambah Penilaian Pertama
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#gradesTable').DataTable({
                responsive: true,
                pageLength: 25,
                order: [[0, 'asc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                }
            });

            // Search functionality - real-time search
            $('#search').on('keyup', function() {
                var searchTerm = this.value.toLowerCase();
                
                // Clear previous custom search
                $.fn.dataTable.ext.search.pop();
                
                if (searchTerm) {
                    // Custom search function
                    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                        var name = data[1].toLowerCase(); // Nama Mahasiswa column
                        var nim = data[2].toLowerCase(); // NIM column
                        var jurusan = data[3].toLowerCase(); // Jurusan column
                        
                        if (name.indexOf(searchTerm) !== -1 || 
                            nim.indexOf(searchTerm) !== -1 || 
                            jurusan.indexOf(searchTerm) !== -1) {
                            return true;
                        }
                        return false;
                    });
                }
                
                table.draw();
            });

            // Group filter
            $('#group_filter').on('change', function() {
                var groupName = $(this).find('option:selected').text();
                if (groupName && groupName !== 'Semua Kelompok') {
                    table.column(4).search(groupName).draw(); // Kelompok column
                } else {
                    table.column(4).search('').draw();
                }
            });

            // Grade filter
            $('#status_filter').on('change', function() {
                var grade = $(this).val();
                if (grade) {
                    table.column(6).search(grade).draw(); // Grade column (index 6)
                } else {
                    table.column(6).search('').draw();
                }
            });
        });

        function resetFilters() {
            $('#search').val('');
            $('#group_filter').val('');
            $('#status_filter').val('');
            var table = $('#gradesTable').DataTable();
            
            // Clear custom search
            $.fn.dataTable.ext.search.pop();
            
            table.search('').columns().search('').draw();
        }

        function confirmDelete(gradeId) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data penilaian yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    form.action = `/grades/${gradeId}`;
                    form.submit();
                }
            });
        }
    </script>
    @endpush
</x-app-layout> 
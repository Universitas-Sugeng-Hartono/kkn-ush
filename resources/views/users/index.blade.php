<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Manajemen User</h1>
                <div class="text-muted small">
                    Periode aktif: {{ $tahunAktif?->nama ?? '-' }} - {{ $semesterAktif?->nama ?? '-' }}
                </div>
            </div>
            <div class="d-flex gap-2">
                <form action="{{ route('users.bulk-reset-password') }}" method="POST" id="bulk-reset-form" style="display: none;">
                    @csrf
                    <input type="hidden" name="user_ids" id="bulk-user-ids">
                </form>
                <button type="button" class="btn btn-warning text-dark" onclick="confirmBulkReset()" id="btn-bulk-reset" style="display: none;">
                    <i class="fas fa-key me-2"></i>Reset Terpilih
                </button>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-file-excel me-2"></i>Import Excel
                </button>
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah User
                </a>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form action="{{ route('users.index') }}" method="GET" class="row g-3 align-items-end" id="filterForm">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">Tahun Akademik</label>
                        <select name="tahun_akademik_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">— Semua Tahun —</option>
                            @foreach($tahunAkademikList as $ta)
                            <option value="{{ $ta->id }}" {{ $tahun_akademik_id == $ta->id ? 'selected' : '' }}>
                                {{ $ta->nama }} {{ $ta->is_aktif ? '(Aktif)' : '' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">Semester</label>
                        <select name="semester_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">— Semua Semester —</option>
                            @foreach($semesterList as $sem)
                            <option value="{{ $sem->id }}" {{ $semester_id == $sem->id ? 'selected' : '' }}>
                                {{ $sem->nama }} {{ $sem->is_aktif ? '(Aktif)' : '' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped datatable">
                        <thead>
                            <tr>
                                <th style="width: 1%;" class="text-center">
                                    <input class="form-check-input" type="checkbox" id="check-all">
                                </th>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>NIM/NIP</th>
                                <th>Jurusan</th>
                                <th>No HP</th>
                                <th>Tahun Akademik</th>
                                <th>Semester</th>
                                <th>Kelompok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td class="text-center">
                                    <input class="form-check-input user-checkbox" type="checkbox" value="{{ $user->id }}">
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($user->foto_profil)
                                        <img src="{{ Storage::url($user->foto_profil) }}"
                                            alt="{{ $user->name }}"
                                            class="rounded-circle me-2"
                                            width="40" height="40"
                                            style="object-fit: cover;">
                                        @else
                                        <div class="rounded-circle bg-primary text-white me-2 d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        @endif
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @foreach($user->roles as $role)
                                    <span class="badge bg-primary">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $user->nim ?? $user->nip ?? '-' }}</td>
                                <td>{{ $user->jurusan ? ucfirst($user->jurusan) : '-' }}</td>
                                <td>{{ $user->no_hp }}</td>
                                <td>{{ $user->tahunAkademik->nama ?? '-' }}</td>
                                <td>{{ $user->semester->nama ?? '-' }}</td>
                                <td>{{ $user->kelompok->nama_kelompok ?? '-' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('users.show', $user) }}"
                                            class="btn btn-sm btn-info text-white"
                                            data-bs-toggle="tooltip"
                                            title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}"
                                            class="btn btn-sm btn-warning text-white"
                                            data-bs-toggle="tooltip"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('users.reset-password', $user) }}"
                                            method="POST"
                                            class="d-inline"
                                            id="reset-form-{{ $user->id }}">
                                            @csrf
                                            <button type="button"
                                                class="btn btn-sm btn-secondary"
                                                data-bs-toggle="tooltip"
                                                title="Reset Password"
                                                onclick="confirmReset('{{ $user->id }}', '{{ addslashes($user->name) }}')">
                                                <i class="fas fa-key"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('users.destroy', $user) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirmDelete('delete-form-{{ $user->id }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                data-bs-toggle="tooltip"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert error import --}}
    @if(session('import_errors'))
    <div class="modal fade show d-block" id="importErrorModal" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Laporan Import</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="document.getElementById('importErrorModal').remove()"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">Beberapa baris tidak dapat diimport. Detail:</p>
                    <ul class="list-group list-group-flush">
                        @foreach(session('import_errors') as $err)
                        <li class="list-group-item list-group-item-warning small">
                            <i class="fas fa-times-circle me-2 text-danger"></i>{{ $err }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('importErrorModal').remove()">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Import Excel -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-success text-white rounded-top-4 py-3 px-4">
                    <h5 class="modal-title fw-bold" id="importModalLabel">
                        <i class="fas fa-file-excel me-2"></i>Import Mahasiswa dari Excel
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">

                        {{-- Info periode --}}
                        <div class="alert alert-info border-0 rounded-3 mb-4" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            Mahasiswa yang diimport akan otomatis masuk ke periode aktif:
                            <strong>{{ $tahunAktif?->nama ?? '-' }} — {{ $semesterAktif?->nama ?? '-' }}</strong>.
                        </div>

                        <div class="alert alert-info border-0 rounded-3 py-2 px-3 mb-4" style="font-size: 0.82rem;">
                            <i class="fas fa-lightbulb me-1"></i>
                            <strong>Tips:</strong> Password default setiap user = NIM masing-masing. Penugasan kelompok untuk mahasiswa dapat dilakukan melalui menu <strong>Kelompok</strong> setelah data diimport.
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <a href="{{ route('users.template') }}" class="btn btn-outline-success btn-sm rounded-pill px-4">
                                <i class="fas fa-download me-2"></i>Download Template Excel
                            </a>
                        </div>

                        <div>
                            <label class="form-label fw-semibold">Pilih File <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".xlsx,.xls,.csv" required>
                            @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text"><i class="fas fa-info-circle me-1 text-success"></i>Format: <strong>.xlsx, .xls, .csv</strong> — Maks. 5MB. Password default = NIM masing-masing mahasiswa.</div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom-4 border-top-0 px-4 py-3">
                        <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success px-4 rounded-pill" id="importSubmitBtn">
                            <i class="fas fa-upload me-2"></i>Mulai Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        var table;
        $(document).ready(function() {
            table = $('.datatable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                },
                order: [
                    [1, 'asc']
                ],
                columnDefs: [
                    { orderable: false, targets: 0 }
                ]
            });

            // Handle check-all on current page
            $('#check-all').on('change', function() {
                var isChecked = $(this).prop('checked');
                $('.user-checkbox').prop('checked', isChecked);
                toggleBulkButton();
            });

            // Handle individual checkbox changes
            $(document).on('change', '.user-checkbox', function() {
                toggleBulkButton();
                // Check if all checkboxes on current page are checked
                var allChecked = $('.user-checkbox').length === $('.user-checkbox:checked').length;
                $('#check-all').prop('checked', allChecked);
            });

            // Uncheck "check-all" when changing page
            table.on('draw', function() {
                $('#check-all').prop('checked', false);
                toggleBulkButton(); // Also update button state
            });
        });

        function toggleBulkButton() {
            // Kita bisa mengambil semua checkbox yang di centang dari instance datatable
            // namun agar lebih aman (menghindari user lupa kalau di page lain ada yang dicentang),
            // kita gunakan API table.$ untuk mendapatkan semua yang dicentang di seluruh halaman:
            var checkedCount = table.$('.user-checkbox:checked').length;
            if (checkedCount > 0) {
                $('#btn-bulk-reset').show();
            } else {
                $('#btn-bulk-reset').hide();
            }
        }

        function confirmBulkReset() {
            var checked = table.$('.user-checkbox:checked');
            if (checked.length === 0) return;

            var userIds = [];
            checked.each(function() {
                userIds.push($(this).val());
            });

            Swal.fire({
                title: 'Reset Password Massal?',
                html: `Apakah Anda yakin ingin mereset password <strong>${checked.length}</strong> user yang dipilih menjadi NIM/NIP mereka?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-key me-2"></i>Ya, Reset Semua',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#bulk-user-ids').val(JSON.stringify(userIds));
                    $('#bulk-reset-form').submit();
                }
            });
        }

        function confirmReset(userId, userName) {
            Swal.fire({
                title: 'Reset Password?',
                html: `Apakah Anda yakin ingin mereset password user <strong>${userName}</strong> menjadi NIM/NIP-nya?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-key me-2"></i>Ya, Reset',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('reset-form-' + userId).submit();
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
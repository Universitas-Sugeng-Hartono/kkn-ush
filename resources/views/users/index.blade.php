<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Manajemen User</h1>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah User
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>NIM/NIP</th>
                                <th>Jurusan</th>
                                <th>No HP</th>
                                <th>Kelompok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
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

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                },
                order: [[0, 'asc']],
            });
        });
    </script>
    @endpush
</x-app-layout> 
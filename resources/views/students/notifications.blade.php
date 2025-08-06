<x-app-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Notifikasi</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Notifikasi</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Daftar Notifikasi</h5>
                        @if($notifications->where('is_read', false)->count() > 0)
                            <button type="button" class="btn btn-primary btn-sm" onclick="markAllAsRead()">
                                <i class="fas fa-check-double me-1"></i>
                                Tandai Semua Dibaca
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($notifications->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Judul</th>
                                            <th>Pesan</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($notifications as $notification)
                                            <tr class="{{ $notification->is_read ? '' : 'table-warning' }}">
                                                <td>
                                                    <strong>{{ $notification->title }}</strong>
                                                </td>
                                                <td>
                                                    {{ Str::limit($notification->message, 100) }}
                                                </td>
                                                <td>
                                                    {{ $notification->created_at->format('d/m/Y H:i') }}
                                                </td>
                                                <td>
                                                    @if($notification->is_read)
                                                        <span class="badge bg-success">Dibaca</span>
                                                    @else
                                                        <span class="badge bg-warning">Belum Dibaca</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!$notification->is_read)
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-primary"
                                                                onclick="markAsRead({{ $notification->id }})">
                                                            <i class="fas fa-check"></i>
                                                            Tandai Dibaca
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center mt-3">
                                {{ $notifications->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Tidak ada notifikasi</h5>
                                <p class="text-muted">Anda belum memiliki notifikasi saat ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/mark-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload halaman untuk memperbarui tampilan
                    location.reload();
                } else {
                    alert('Gagal menandai notifikasi sebagai dibaca');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menandai notifikasi');
            });
        }

        function markAllAsRead() {
            if (confirm('Apakah Anda yakin ingin menandai semua notifikasi sebagai dibaca?')) {
                fetch('/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload halaman untuk memperbarui tampilan
                        location.reload();
                    } else {
                        alert('Gagal menandai semua notifikasi sebagai dibaca');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menandai notifikasi');
                });
            }
        }
    </script>
    @endpush
</x-app-layout> 
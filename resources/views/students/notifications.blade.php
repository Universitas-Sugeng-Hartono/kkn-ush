<x-app-layout>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 bg-white p-3 rounded shadow-sm">
                    <div>
                        <h2 class="fw-bold mb-1">Notifikasi</h2>
                        <p class="text-muted mb-0 small">Pusat pemberitahuan dan pembaruan terkait aktivitas KKN Anda</p>
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
                                <table class="table table-striped table-hover w-100">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="20%">Judul</th>
                                            <th width="35%">Pesan</th>
                                            <th width="15%">Tanggal</th>
                                            <th width="10%">Status</th>
                                            <th width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($notifications as $index => $notification)
                                            <tr class="{{ $notification->is_read ? '' : 'table-warning' }}">
                                                <td>{{ $index + 1 }}</td>
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
                                                    <div class="d-flex gap-1">
                                                        @php
                                                            $url = '#';
                                                            if (str_contains($notification->type, 'logbook')) {
                                                                $url = route('logbooks.index');
                                                            } elseif (str_contains($notification->type, 'attendance') || str_contains($notification->type, 'absensi')) {
                                                                $url = route('attendance.index');
                                                            }
                                                        @endphp
                                                        
                                                        @if($url !== '#')
                                                            <a href="{{ $url }}" class="btn btn-sm btn-info text-white" title="Lihat Detail">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @endif

                                                        @if(!$notification->is_read)
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-outline-primary"
                                                                    title="Tandai Dibaca"
                                                                    onclick="markAsRead({{ $notification->id }})">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        @endif
                                                    </div>
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
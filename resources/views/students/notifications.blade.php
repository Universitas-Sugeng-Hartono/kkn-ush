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
                                                    @php $dataObj = !empty($notification->data) ? (is_string($notification->data) ? json_decode($notification->data, true) : $notification->data) : []; @endphp
                                                    <div class="mb-1">
                                                        @if(!empty($dataObj['urgency']) && $dataObj['urgency'] === 'urgent')
                                                            <span class="badge bg-danger me-1"><i class="fas fa-exclamation-circle me-1"></i> Darurat</span>
                                                        @elseif(!empty($dataObj['urgency']) && $dataObj['urgency'] === 'important')
                                                            <span class="badge bg-warning text-dark me-1"><i class="fas fa-exclamation-triangle me-1"></i> Penting</span>
                                                        @endif
                                                        @if(!empty($dataObj['category']))
                                                            <span class="badge bg-secondary me-1">[{{ $dataObj['category'] }}]</span>
                                                        @endif
                                                        @if(str_contains($notification->type, 'broadcast'))
                                                            <span class="badge bg-primary me-1"><i class="fas fa-bullhorn"></i> Pengumuman</span>
                                                        @endif
                                                    </div>
                                                    <strong>{{ $notification->title }}</strong>
                                                    @if(!empty($dataObj['banner_image']))
                                                        <div class="mt-2">
                                                            <a href="{{ $dataObj['banner_image'] }}" target="_blank">
                                                                <img src="{{ $dataObj['banner_image'] }}" alt="Banner" class="img-thumbnail" style="max-height: 100px; border-radius: 8px;">
                                                            </a>
                                                        </div>
                                                    @endif
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
                                                    <div class="d-flex align-items-center gap-1">
                                                        @php
                                                            $url = '#';
                                                            $dataObj = !empty($notification->data) ? (is_string($notification->data) ? json_decode($notification->data, true) : $notification->data) : [];
                                                            if (str_contains($notification->type, 'logbook')) {
                                                                $url = route('logbooks.index');
                                                            } elseif (str_contains($notification->type, 'attendance') || str_contains($notification->type, 'absensi')) {
                                                                $url = route('attendance.index');
                                                            } elseif (str_contains($notification->type, 'broadcast') && !empty($dataObj['action_url'])) {
                                                                $url = $dataObj['action_url'];
                                                            }
                                                        @endphp
                                                        
                                                        <button type="button" class="btn btn-sm btn-primary rounded-pill d-inline-flex align-items-center gap-1 px-3" title="Baca Detail Pengumuman"
                                                            data-id="{{ $notification->id }}"
                                                            data-title="{{ $notification->title }}"
                                                            data-message="{{ $notification->message }}"
                                                            data-category="{{ $dataObj['category'] ?? '' }}"
                                                            data-urgency="{{ $dataObj['urgency'] ?? 'normal' }}"
                                                            data-banner="{{ $dataObj['banner_image'] ?? '' }}"
                                                            data-url="{{ $url !== '#' ? $url : '' }}"
                                                            data-sender="{{ $dataObj['sender_name'] ?? ($dataObj['sender_role'] ?? 'Sistem') }}"
                                                            data-time="{{ $notification->created_at->format('d M Y, H:i') }}"
                                                            data-is-read="{{ $notification->is_read ? '1' : '0' }}"
                                                            onclick="showStudentNotifModal(this)">
                                                            <i class="fas fa-eye"></i> Baca Detail
                                                        </button>

                                                        @if(!$notification->is_read)
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-outline-secondary rounded-circle"
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

    <!-- Modal Detail Notifikasi Mahasiswa -->
    <div class="modal fade" id="studentNotifModal" tabindex="-1" aria-labelledby="studentNotifModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header bg-light border-bottom p-4">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="studentNotifModalLabel">
                        <i class="fas fa-bullhorn text-primary"></i> Detail Pengumuman & Notifikasi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <span id="modal_notif_urgency" class="badge bg-secondary"></span>
                        <span id="modal_notif_category" class="badge bg-light text-dark border"></span>
                        <span id="modal_notif_sender" class="badge bg-primary"></span>
                        <span id="modal_notif_time" class="badge bg-light text-muted border"></span>
                    </div>
                    <h4 id="modal_notif_title" class="fw-bold text-dark mb-3"></h4>
                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <small class="text-muted d-block mb-1 fw-bold">Isi Pesan:</small>
                        <div id="modal_notif_message" class="text-dark" style="white-space: pre-wrap; font-size: 0.95rem;"></div>
                    </div>
                    <div id="modal_notif_banner_box" class="mb-3 text-center" style="display: none;">
                        <small class="text-muted d-block mb-2 text-start fw-bold">Gambar Lampiran / Banner:</small>
                        <a id="modal_notif_banner_link" href="#" target="_blank">
                            <img id="modal_notif_banner_img" src="" alt="Banner Notifikasi" class="img-fluid rounded-3 border shadow-sm" style="max-height: 300px;">
                        </a>
                    </div>
                    <div id="modal_notif_url_box" class="mb-0" style="display: none;">
                        <small class="text-muted d-block mb-1 fw-bold">Tautan Terkait:</small>
                        <a id="modal_notif_url_link" href="#" target="_blank" class="btn btn-primary rounded-pill px-4 d-inline-flex align-items-center gap-2">
                            <i class="fas fa-external-link-alt"></i> Buka Tautan Tujuan
                        </a>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top p-3">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
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

        function showStudentNotifModal(btn) {
            document.getElementById('modal_notif_title').textContent = btn.dataset.title;
            document.getElementById('modal_notif_message').textContent = btn.dataset.message;
            
            const urgencyEl = document.getElementById('modal_notif_urgency');
            if (btn.dataset.urgency === 'urgent') {
                urgencyEl.className = 'badge bg-danger';
                urgencyEl.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>Darurat';
            } else if (btn.dataset.urgency === 'important') {
                urgencyEl.className = 'badge bg-warning text-dark';
                urgencyEl.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Penting';
            } else {
                urgencyEl.className = 'badge bg-info';
                urgencyEl.innerHTML = '<i class="fas fa-info-circle me-1"></i>Normal';
            }

            const catEl = document.getElementById('modal_notif_category');
            if (btn.dataset.category && btn.dataset.category !== '') {
                catEl.textContent = btn.dataset.category;
                catEl.style.display = 'inline-block';
            } else {
                catEl.style.display = 'none';
            }

            document.getElementById('modal_notif_sender').innerHTML = '<i class="fas fa-user-circle me-1"></i> Dari: ' + (btn.dataset.sender || 'Sistem');
            document.getElementById('modal_notif_time').innerHTML = '<i class="fas fa-clock me-1"></i> ' + btn.dataset.time;

            const bannerBox = document.getElementById('modal_notif_banner_box');
            const bannerImg = document.getElementById('modal_notif_banner_img');
            const bannerLink = document.getElementById('modal_notif_banner_link');
            if (btn.dataset.banner && btn.dataset.banner !== '') {
                bannerImg.src = btn.dataset.banner;
                bannerLink.href = btn.dataset.banner;
                bannerBox.style.display = 'block';
            } else {
                bannerBox.style.display = 'none';
            }

            const urlBox = document.getElementById('modal_notif_url_box');
            const urlLink = document.getElementById('modal_notif_url_link');
            if (btn.dataset.url && btn.dataset.url !== '') {
                urlLink.href = btn.dataset.url;
                urlBox.style.display = 'block';
            } else {
                urlBox.style.display = 'none';
            }

            new bootstrap.Modal(document.getElementById('studentNotifModal')).show();

            if (btn.dataset.isRead === '0') {
                fetch('/notifications/' + btn.dataset.id + '/mark-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        btn.dataset.isRead = '1';
                        const row = btn.closest('tr');
                        if (row) row.classList.remove('table-warning');
                        const statusBadge = row ? row.querySelector('td:nth-child(5) .badge') : null;
                        if (statusBadge) {
                            statusBadge.className = 'badge bg-success';
                            statusBadge.textContent = 'Dibaca';
                        }
                        const checkBtn = btn.parentElement ? btn.parentElement.querySelector('button[title="Tandai Dibaca"]') : null;
                        if (checkBtn) checkBtn.remove();
                    }
                });
            }
        }
    </script>
    @endpush
</x-app-layout> 
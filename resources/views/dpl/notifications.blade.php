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
            
            /* Text adjustments */
            h2.fw-bold {
                font-size: 1.5rem;
            }
            
            h5.card-title {
                font-size: 1.1rem;
            }
            
            /* Button adjustments */
            .btn {
                font-size: 14px;
                padding: 8px 12px;
            }
            
            /* List group adjustments */
            .list-group-item {
                padding: 12px !important;
            }
            
            .list-group-item h6 {
                font-size: 1rem;
            }
            
            .list-group-item p {
                font-size: 13px;
            }
            
            .list-group-item small {
                font-size: 11px;
            }
            
            /* Icon adjustments */
            .rounded-circle {
                width: 35px !important;
                height: 35px !important;
            }
            
            .rounded-circle i {
                font-size: 14px;
            }
            
            /* Quick actions adjustments */
            .row .col-md-6 {
                margin-bottom: 15px;
            }
            
            .card-header h5 {
                font-size: 1rem;
            }
            
            /* Stack buttons vertically on mobile */
            .mt-3 .btn {
                margin-bottom: 8px;
                width: 100%;
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
            
            h5.card-title {
                font-size: 1rem;
            }
            
            .btn {
                font-size: 13px;
                padding: 6px 10px;
            }
            
            .list-group-item {
                padding: 10px !important;
            }
            
            .list-group-item h6 {
                font-size: 0.9rem;
            }
            
            .list-group-item p {
                font-size: 12px;
            }
            
            .list-group-item small {
                font-size: 10px;
            }
            
            .rounded-circle {
                width: 30px !important;
                height: 30px !important;
            }
            
            .rounded-circle i {
                font-size: 12px;
            }
            
            .card-header h5 {
                font-size: 0.9rem;
            }
            
            /* Stack header buttons vertically */
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 10px;
            }
            
            .d-flex.justify-content-between .btn {
                width: 100%;
            }
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notifikasi DPL') }}
        </h2>
    </x-slot>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold">Notifikasi DPL</h2>
                        <p class="text-muted">Daftar notifikasi terkait mahasiswa bimbingan Anda</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-primary me-2" onclick="markAllDplRead()">
                            <i class="fas fa-check-double me-1"></i>Tandai Semua Dibaca
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @if(count($notifications) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover datatable w-100">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="20%">Kategori</th>
                                    <th width="45%">Pesan Notifikasi</th>
                                    <th width="15%">Waktu</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notifications as $index => $notification)
                                    @php
                                        switch($notification['type']) {
                                            case 'logbook_pending':
                                                $icon = 'book'; $color = 'warning'; break;
                                            case 'absensi_pending':
                                                $icon = 'calendar-check'; $color = 'info'; break;
                                            case 'no_logbook_today':
                                                $icon = 'book-dead'; $color = 'danger'; break;
                                            case 'no_absensi_today':
                                                $icon = 'user-clock'; $color = 'warning'; break;
                                            case 'broadcast':
                                            case 'dpl_broadcast':
                                                $icon = 'bullhorn'; $color = 'primary'; break;
                                            default:
                                                $icon = 'info-circle'; $color = 'primary'; break;
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} px-2 py-1 rounded">
                                                <i class="fas fa-{{ $icon }} me-1"></i> {{ $notification['title'] }}
                                            </span>
                                        </td>
                                        <td>
                                            @php $dataObj = !empty($notification['data']) ? (is_string($notification['data']) ? json_decode($notification['data'], true) : $notification['data']) : []; @endphp
                                            <div class="mb-1">
                                                @if(!empty($dataObj['urgency']) && $dataObj['urgency'] === 'urgent')
                                                    <span class="badge bg-danger me-1"><i class="fas fa-exclamation-circle me-1"></i> Darurat</span>
                                                @elseif(!empty($dataObj['urgency']) && $dataObj['urgency'] === 'important')
                                                    <span class="badge bg-warning text-dark me-1"><i class="fas fa-exclamation-triangle me-1"></i> Penting</span>
                                                @endif
                                                @if(!empty($dataObj['category']))
                                                    <span class="badge bg-secondary me-1">[{{ $dataObj['category'] }}]</span>
                                                @endif
                                            </div>
                                            <div>{{ $notification['message'] }}</div>
                                            @if(!empty($dataObj['banner_image']))
                                                <div class="mt-2">
                                                    <a href="{{ $dataObj['banner_image'] }}" target="_blank">
                                                        <img src="{{ $dataObj['banner_image'] }}" alt="Banner" class="img-thumbnail" style="max-height: 100px; border-radius: 8px;">
                                                    </a>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted" data-order="{{ $notification['created_at']->timestamp }}">
                                                {{ $notification['created_at']->diffForHumans() }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($notification['type'] == 'logbook_pending')
                                                <a href="{{ route('logbooks.pending') }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-eye"></i> Review
                                                </a>
                                            @elseif($notification['type'] == 'absensi_pending')
                                                <a href="{{ route('attendance.pending') }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-check"></i> Validasi
                                                </a>
                                            @elseif($notification['type'] == 'no_logbook_today')
                                                <a href="{{ route('students.show', $notification['data']['user_id']) }}" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-user"></i> Detail
                                                </a>
                                            @elseif($notification['type'] == 'no_absensi_today')
                                                <a href="{{ route('students.show', $notification['data']['user_id']) }}" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-user"></i> Detail
                                                </a>
                                            @elseif($notification['type'] == 'broadcast' || $notification['type'] == 'dpl_broadcast')
                                                @php $dataObj = !empty($notification['data']) ? (is_string($notification['data']) ? json_decode($notification['data'], true) : $notification['data']) : []; @endphp
                                                <button type="button" class="btn btn-sm btn-primary rounded-pill d-inline-flex align-items-center gap-1 px-3" title="Baca Detail Pengumuman"
                                                    data-id="{{ $notification['id'] ?? '' }}"
                                                    data-title="{{ $notification['title'] }}"
                                                    data-message="{{ $notification['message'] }}"
                                                    data-category="{{ $dataObj['category'] ?? '' }}"
                                                    data-urgency="{{ $dataObj['urgency'] ?? 'normal' }}"
                                                    data-banner="{{ $dataObj['banner_image'] ?? '' }}"
                                                    data-url="{{ !empty($dataObj['action_url']) ? $dataObj['action_url'] : '' }}"
                                                    data-sender="{{ $dataObj['sender_name'] ?? ($dataObj['sender_role'] ?? 'Admin') }}"
                                                    data-time="{{ $notification['created_at']->format('d M Y, H:i') }}"
                                                    data-is-read="{{ !empty($notification['is_read']) && $notification['is_read'] ? '1' : '0' }}"
                                                    onclick="showDplNotifModal(this)">
                                                    <i class="fas fa-eye"></i> Baca Detail
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak Ada Notifikasi</h5>
                        <p class="text-muted">Semua mahasiswa bimbingan Anda sudah melengkapi tugas hari ini</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        @if(count($notifications) > 0)
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-book me-2"></i>Aksi Cepat Logbook
                        </h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('logbooks.pending') }}" class="btn btn-warning me-2">
                            <i class="fas fa-eye"></i> Review Logbook Pending
                        </a>
                        <a href="{{ route('students.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-users"></i> Lihat Mahasiswa
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar-check me-2"></i>Aksi Cepat Absensi
                        </h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('attendance.pending') }}" class="btn btn-info me-2">
                            <i class="fas fa-check"></i> Validasi Absensi
                        </a>
                        <a href="{{ route('groups.monitoring') }}" class="btn btn-outline-success">
                            <i class="fas fa-map"></i> Monitoring Kelompok
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Modal Detail Notifikasi DPL -->
    <div class="modal fade" id="dplNotifModal" tabindex="-1" aria-labelledby="dplNotifModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header bg-light border-bottom p-4">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="dplNotifModalLabel">
                        <i class="fas fa-bullhorn text-primary"></i> Detail Pengumuman & Notifikasi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <span id="dpl_modal_urgency" class="badge bg-secondary"></span>
                        <span id="dpl_modal_category" class="badge bg-light text-dark border"></span>
                        <span id="dpl_modal_sender" class="badge bg-primary"></span>
                        <span id="dpl_modal_time" class="badge bg-light text-muted border"></span>
                    </div>
                    <h4 id="dpl_modal_title" class="fw-bold text-dark mb-3"></h4>
                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <small class="text-muted d-block mb-1 fw-bold">Isi Pesan:</small>
                        <div id="dpl_modal_message" class="text-dark" style="white-space: pre-wrap; font-size: 0.95rem;"></div>
                    </div>
                    <div id="dpl_modal_banner_box" class="mb-3 text-center" style="display: none;">
                        <small class="text-muted d-block mb-2 text-start fw-bold">Gambar Lampiran / Banner:</small>
                        <a id="dpl_modal_banner_link" href="#" target="_blank">
                            <img id="dpl_modal_banner_img" src="" alt="Banner Notifikasi" class="img-fluid rounded-3 border shadow-sm" style="max-height: 300px;">
                        </a>
                    </div>
                    <div id="dpl_modal_url_box" class="mb-0" style="display: none;">
                        <small class="text-muted d-block mb-1 fw-bold">Tautan Terkait:</small>
                        <a id="dpl_modal_url_link" href="#" target="_blank" class="btn btn-primary rounded-pill px-4 d-inline-flex align-items-center gap-2">
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
        $(document).ready(function() {
            $('.datatable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                },
                order: [
                    [3, 'desc'] // Urutkan berdasarkan waktu secara default
                ],
                columnDefs: [
                    { orderable: false, targets: 4 } // Aksi tidak perlu bisa diurutkan
                ]
            });
        });

        function markAllDplRead() {
            if (confirm('Tandai semua notifikasi database sebagai sudah dibaca?')) {
                fetch('{{ route("dpl.notifications.mark-all-read") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(err => console.error('Error marking read:', err));
            }
        }

        function showDplNotifModal(btn) {
            document.getElementById('dpl_modal_title').textContent = btn.dataset.title;
            document.getElementById('dpl_modal_message').textContent = btn.dataset.message;
            
            const urgencyEl = document.getElementById('dpl_modal_urgency');
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

            const catEl = document.getElementById('dpl_modal_category');
            if (btn.dataset.category && btn.dataset.category !== '') {
                catEl.textContent = btn.dataset.category;
                catEl.style.display = 'inline-block';
            } else {
                catEl.style.display = 'none';
            }

            document.getElementById('dpl_modal_sender').innerHTML = '<i class="fas fa-user-circle me-1"></i> Dari: ' + (btn.dataset.sender || 'Admin');
            document.getElementById('dpl_modal_time').innerHTML = '<i class="fas fa-clock me-1"></i> ' + btn.dataset.time;

            const bannerBox = document.getElementById('dpl_modal_banner_box');
            const bannerImg = document.getElementById('dpl_modal_banner_img');
            const bannerLink = document.getElementById('dpl_modal_banner_link');
            if (btn.dataset.banner && btn.dataset.banner !== '') {
                bannerImg.src = btn.dataset.banner;
                bannerLink.href = btn.dataset.banner;
                bannerBox.style.display = 'block';
            } else {
                bannerBox.style.display = 'none';
            }

            const urlBox = document.getElementById('dpl_modal_url_box');
            const urlLink = document.getElementById('dpl_modal_url_link');
            if (btn.dataset.url && btn.dataset.url !== '') {
                urlLink.href = btn.dataset.url;
                urlBox.style.display = 'block';
            } else {
                urlBox.style.display = 'none';
            }

            new bootstrap.Modal(document.getElementById('dplNotifModal')).show();

            if (btn.dataset.isRead === '0' && btn.dataset.id) {
                fetch('{{ route("dpl.notifications.mark-read") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ id: btn.dataset.id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        btn.dataset.isRead = '1';
                    }
                })
                .catch(err => console.error('Error marking as read:', err));
            }
        }
    </script>
    @endpush
</x-app-layout> 
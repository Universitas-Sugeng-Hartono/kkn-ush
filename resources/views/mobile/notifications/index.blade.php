@extends('layouts.mobile-app')

@section('content')
    <!-- Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h2>Notifications</h2>
                <p class="date-info">{{ now()->format('l, d F Y') }}</p>
            </div>
            <div class="header-actions">
                <button class="btn-action" onclick="markAllAsRead()" id="markAllBtn">
                    <i class="fas fa-check-double"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-icon total">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $totalNotifications }}</div>
                    <div class="stat-label">Total</div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon unread">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $unreadNotifications }}</div>
                    <div class="stat-label">Unread</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="notifications-section">
        <div class="section-header">
            <h3>Recent Notifications</h3>
            @if($unreadNotifications > 0)
                <button class="btn-mark-all" onclick="markAllAsRead()">
                    Mark All Read
                </button>
            @endif
        </div>
        
        <div class="notifications-list">
            @forelse($notifications as $notification)
                @php
                    $dataObj = !empty($notification->data) ? (is_string($notification->data) ? json_decode($notification->data, true) : $notification->data) : [];
                    $urgency = $dataObj['urgency'] ?? 'normal';
                    $category = $dataObj['category'] ?? '';
                    $bannerImg = $dataObj['banner_image'] ?? null;
                    $actionUrl = $dataObj['action_url'] ?? null;
                @endphp
                <div class="notification-item {{ ($notification->is_read || $notification->read_at) ? 'read' : 'unread' }}" 
                     data-id="{{ $notification->id }}"
                     data-title="{{ $notification->data['title'] ?? ($notification->title ?? 'Notification') }}"
                     data-message="{{ $notification->data['message'] ?? ($notification->message ?? '-') }}"
                     data-category="{{ $category }}"
                     data-urgency="{{ $urgency }}"
                     data-banner="{{ $bannerImg ?? '' }}"
                     data-url="{{ $actionUrl ?? '' }}"
                     data-time="{{ $notification->created_at->diffForHumans() }}"
                     data-is-read="{{ ($notification->is_read || $notification->read_at) ? '1' : '0' }}"
                     style="cursor: pointer;"
                     onclick="showMobileNotifDetail(this)">
                    <div class="notification-icon">
                        @php $type = $notification->type ?? ($notification->data['type'] ?? ''); @endphp
                        @if(str_contains($type, 'logbook'))
                            <i class="fas fa-book"></i>
                        @elseif(str_contains($type, 'attendance') || str_contains($type, 'absensi'))
                            <i class="fas fa-calendar-check"></i>
                        @elseif(str_contains($type, 'broadcast') || str_contains($type, 'pengumuman'))
                            <i class="fas fa-bullhorn" style="color: #2563eb;"></i>
                        @elseif($type === 'system')
                            <i class="fas fa-info-circle"></i>
                        @else
                            <i class="fas fa-bell"></i>
                        @endif
                    </div>
                    <div class="notification-content">
                        <div class="notification-title d-flex align-items-center gap-1 flex-wrap">
                            @if($urgency === 'urgent')
                                <span class="badge bg-danger text-white me-1" style="font-size: 0.65rem;"><i class="fas fa-exclamation-circle me-1"></i>Darurat</span>
                            @elseif($urgency === 'important')
                                <span class="badge bg-warning text-dark me-1" style="font-size: 0.65rem;"><i class="fas fa-exclamation-triangle me-1"></i>Penting</span>
                            @endif
                            @if($category)
                                <span class="badge bg-secondary text-white me-1" style="font-size: 0.65rem;">[{{ $category }}]</span>
                            @endif
                            {{ $notification->data['title'] ?? ($notification->title ?? 'Notification') }}
                        </div>
                        <div class="notification-message" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $notification->data['message'] ?? ($notification->message ?? '-') }}</div>
                        @if($bannerImg)
                            <div class="my-2">
                                <img src="{{ $bannerImg }}" alt="Banner" style="max-height: 80px; width: 100%; object-fit: cover; border-radius: 8px;">
                            </div>
                        @endif
                        @if($actionUrl)
                            <div class="mb-1">
                                <a href="{{ $actionUrl }}" target="_blank" class="badge bg-primary text-white text-decoration-none" onclick="event.stopPropagation();"><i class="fas fa-external-link-alt me-1"></i> Buka Link Tujuan</a>
                            </div>
                        @endif
                        <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="notification-status">
                        @if(!($notification->is_read || $notification->read_at))
                            <div class="unread-indicator"></div>
                        @endif
                        <div class="notification-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-bell-slash"></i>
                    <p>No notifications yet</p>
                    <span>You'll see notifications here when there are updates</span>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
        <div class="pagination-section">
            <div class="pagination-info">
                Showing {{ $notifications->firstItem() ?? 0 }} to {{ $notifications->lastItem() ?? 0 }} of {{ $notifications->total() }} notifications
            </div>
            <div class="pagination-links">
                @if($notifications->previousPageUrl())
                    <a href="{{ $notifications->previousPageUrl() }}" class="btn-pagination">
                        <i class="fas fa-chevron-left"></i>
                        Previous
                    </a>
                @endif
                
                @if($notifications->nextPageUrl())
                    <a href="{{ $notifications->nextPageUrl() }}" class="btn-pagination">
                        Next
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Modal Detail Notifikasi Mobile -->
    <div class="modal fade" id="mobileNotifModal" tabindex="-1" aria-labelledby="mobileNotifModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="margin: 1rem;">
            <div class="modal-content rounded-4 border-0 shadow" style="overflow: hidden;">
                <div class="modal-header bg-light border-bottom px-4 py-3">
                    <h6 class="modal-title fw-bold text-dark d-flex align-items-center gap-2 mb-0" id="mobileNotifModalLabel">
                        <i class="fas fa-bullhorn text-primary"></i> Detail Pengumuman
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <span id="mob_modal_urgency" class="badge bg-secondary"></span>
                        <span id="mob_modal_category" class="badge bg-light text-dark border"></span>
                        <span id="mob_modal_time" class="badge bg-light text-muted border"></span>
                    </div>
                    <h5 id="mob_modal_title" class="fw-bold text-dark mb-3"></h5>
                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <div id="mob_modal_message" class="text-dark" style="white-space: pre-wrap; font-size: 0.9rem; line-height: 1.5;"></div>
                    </div>
                    <div id="mob_modal_banner_box" class="mb-3 text-center" style="display: none;">
                        <small class="text-muted d-block mb-2 text-start fw-bold" style="font-size: 0.75rem;">Gambar Lampiran:</small>
                        <a id="mob_modal_banner_link" href="#" target="_blank">
                            <img id="mob_modal_banner_img" src="" alt="Banner Notifikasi" class="img-fluid rounded-3 border shadow-sm" style="max-height: 250px; width: 100%; object-fit: contain;">
                        </a>
                    </div>
                    <div id="mob_modal_url_box" class="mb-0 text-center" style="display: none;">
                        <a id="mob_modal_url_link" href="#" target="_blank" class="btn btn-primary btn-sm rounded-pill px-4 py-2 w-100 d-flex align-items-center justify-content-center gap-2 fw-bold">
                            <i class="fas fa-external-link-alt"></i> Buka Tautan Tujuan
                        </a>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top p-3">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4 w-100 fw-bold" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .stats-section {
        margin-bottom: 2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .stat-item {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f3f4;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
    }

    .stat-icon.total {
        background: linear-gradient(135deg, #0B1F3A 0%, #163057 100%);
    }

    .stat-icon.unread {
        background: linear-gradient(135deg, #F2B705 0%, #d9a404 100%);
    }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a202c;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.75rem;
        color: #6c757d;
        font-weight: 500;
        margin-top: 0.25rem;
    }

    .notifications-section {
        margin-bottom: 2rem;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .section-header h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1a202c;
        margin: 0;
    }

    .btn-mark-all {
        background: linear-gradient(135deg, #F2B705 0%, #d9a404 100%);
        color: #0B1F3A;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-mark-all:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(242, 183, 5, 0.3);
    }

    .notifications-list {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f3f4;
    }

    .notification-item {
        display: flex;
        align-items: flex-start;
        padding: 1rem;
        border-bottom: 1px solid #f1f3f4;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-item:hover {
        background: #f8f9fa;
    }

    .notification-item.unread {
        background: rgba(242, 183, 5, 0.05);
        border-left: 4px solid #F2B705;
    }

    .notification-item.read {
        opacity: 0.8;
    }

    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #f8f9fa;
        color: #0B1F3A;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .notification-item.unread .notification-icon {
        background: rgba(242, 183, 5, 0.1);
        color: #F2B705;
    }

    .notification-content {
        flex: 1;
        margin-right: 1rem;
    }

    .notification-title {
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
        line-height: 1.4;
    }

    .notification-message {
        color: #6c757d;
        font-size: 0.75rem;
        line-height: 1.4;
        margin-bottom: 0.5rem;
    }

    .notification-time {
        font-size: 0.75rem;
        color: #9ca3af;
    }

    .notification-status {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }

    .unread-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #F2B705;
        flex-shrink: 0;
    }

    .notification-arrow {
        color: #cbd5e0;
        font-size: 0.75rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state p {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state span {
        font-size: 0.875rem;
        opacity: 0.8;
    }

    .pagination-section {
        background: white;
        border-radius: 16px;
        padding: 1rem;
        margin-top: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f3f4;
    }

    .pagination-info {
        text-align: center;
        color: #6c757d;
        font-size: 0.75rem;
        margin-bottom: 1rem;
    }

    .pagination-links {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
    }

    .btn-pagination {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #0B1F3A 0%, #163057 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-pagination:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(11, 31, 58, 0.3);
        color: white;
    }

    .btn-action {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(242, 183, 5, 0.2);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-action:hover {
        background: rgba(242, 183, 5, 0.3);
        transform: scale(1.1);
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .notification-item {
            padding: 0.75rem;
        }
        
        .pagination-links {
            flex-direction: column;
        }
    }
</style>
@endpush

@push('scripts')
<script>
function showMobileNotifDetail(card) {
    document.getElementById('mob_modal_title').textContent = card.dataset.title;
    document.getElementById('mob_modal_message').textContent = card.dataset.message;
    
    const urgencyEl = document.getElementById('mob_modal_urgency');
    if (card.dataset.urgency === 'urgent') {
        urgencyEl.className = 'badge bg-danger';
        urgencyEl.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>Darurat';
    } else if (card.dataset.urgency === 'important') {
        urgencyEl.className = 'badge bg-warning text-dark';
        urgencyEl.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Penting';
    } else {
        urgencyEl.className = 'badge bg-info';
        urgencyEl.innerHTML = '<i class="fas fa-info-circle me-1"></i>Normal';
    }

    const catEl = document.getElementById('mob_modal_category');
    if (card.dataset.category && card.dataset.category !== '') {
        catEl.textContent = card.dataset.category;
        catEl.style.display = 'inline-block';
    } else {
        catEl.style.display = 'none';
    }

    document.getElementById('mob_modal_time').innerHTML = '<i class="fas fa-clock me-1"></i> ' + card.dataset.time;

    const bannerBox = document.getElementById('mob_modal_banner_box');
    const bannerImg = document.getElementById('mob_modal_banner_img');
    const bannerLink = document.getElementById('mob_modal_banner_link');
    if (card.dataset.banner && card.dataset.banner !== '') {
        bannerImg.src = card.dataset.banner;
        bannerLink.href = card.dataset.banner;
        bannerBox.style.display = 'block';
    } else {
        bannerBox.style.display = 'none';
    }

    const urlBox = document.getElementById('mob_modal_url_box');
    const urlLink = document.getElementById('mob_modal_url_link');
    if (card.dataset.url && card.dataset.url !== '') {
        urlLink.href = card.dataset.url;
        urlBox.style.display = 'block';
    } else {
        urlBox.style.display = 'none';
    }

    new bootstrap.Modal(document.getElementById('mobileNotifModal')).show();

    if (card.dataset.isRead === '0') {
        markAsRead(card.dataset.id, card);
        card.dataset.isRead = '1';
    }
}

function markAsRead(notificationId, element) {
    fetch(`/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI
            element.classList.remove('unread');
            element.classList.add('read');
            
            // Remove unread indicator
            const unreadIndicator = element.querySelector('.unread-indicator');
            if (unreadIndicator) {
                unreadIndicator.remove();
            }
            
            // Update stats
            updateNotificationStats();
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
    });
}

function markAllAsRead() {
    const markAllBtn = document.getElementById('markAllBtn');
    const originalContent = markAllBtn.innerHTML;
    
    markAllBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    markAllBtn.disabled = true;

    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update all notification items
            const unreadItems = document.querySelectorAll('.notification-item.unread');
            unreadItems.forEach(item => {
                item.classList.remove('unread');
                item.classList.add('read');
                
                const unreadIndicator = item.querySelector('.unread-indicator');
                if (unreadIndicator) {
                    unreadIndicator.remove();
                }
            });
            
            // Update stats
            updateNotificationStats();
            
            // Hide mark all button
            const markAllButton = document.querySelector('.btn-mark-all');
            if (markAllButton) {
                markAllButton.style.display = 'none';
            }
        }
    })
    .catch(error => {
        console.error('Error marking all notifications as read:', error);
    })
    .finally(() => {
        markAllBtn.innerHTML = originalContent;
        markAllBtn.disabled = false;
    });
}

function updateNotificationStats() {
    // Update unread count
    const unreadItems = document.querySelectorAll('.notification-item.unread');
    const unreadCount = unreadItems.length;
    
    // Update the unread stat number
    const unreadStat = document.querySelector('.stat-icon.unread + .stat-content .stat-number');
    if (unreadStat) {
        unreadStat.textContent = unreadCount;
    }
    
    // Update total count if needed
    const totalItems = document.querySelectorAll('.notification-item');
    const totalCount = totalItems.length;
    
    const totalStat = document.querySelector('.stat-icon.total + .stat-content .stat-number');
    if (totalStat) {
        totalStat.textContent = totalCount;
    }
}

// Auto-refresh notifications every 30 seconds
setInterval(function() {
    // You can implement auto-refresh here if needed
    // For now, we'll just update the time stamps
    const timeElements = document.querySelectorAll('.notification-time');
    timeElements.forEach(element => {
        const timestamp = element.getAttribute('data-time');
        if (timestamp) {
            const date = new Date(timestamp);
            element.textContent = getTimeAgo(date);
        }
    });
}, 30000);

function getTimeAgo(date) {
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) {
        return 'Just now';
    } else if (diffInSeconds < 3600) {
        const minutes = Math.floor(diffInSeconds / 60);
        return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
    } else if (diffInSeconds < 86400) {
        const hours = Math.floor(diffInSeconds / 3600);
        return `${hours} hour${hours > 1 ? 's' : ''} ago`;
    } else {
        const days = Math.floor(diffInSeconds / 86400);
        return `${days} day${days > 1 ? 's' : ''} ago`;
    }
}
</script>
@endpush 
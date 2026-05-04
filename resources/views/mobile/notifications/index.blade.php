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
                <div class="notification-item {{ $notification->read_at ? 'read' : 'unread' }}" 
                     onclick="markAsRead({{ $notification->id }}, this)">
                    <div class="notification-icon">
                        @php $type = $notification->data['type'] ?? ''; @endphp
                        @if($type === 'logbook')
                            <i class="fas fa-book"></i>
                        @elseif($type === 'attendance')
                            <i class="fas fa-calendar-check"></i>
                        @elseif($type === 'system')
                            <i class="fas fa-info-circle"></i>
                        @else
                            <i class="fas fa-bell"></i>
                        @endif
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">{{ $notification->data['title'] ?? 'Notification' }}</div>
                        <div class="notification-message">{{ $notification->data['message'] ?? '-' }}</div>
                        <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="notification-status">
                        @if(!$notification->read_at)
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
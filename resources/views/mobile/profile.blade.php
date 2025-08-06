@extends('layouts.mobile-app')

@section('content')
    <!-- Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h2>Profile</h2>
                <p class="date-info">{{ now()->format('l, d F Y') }}</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('mobile.dashboard') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Profile Info -->
    <div class="profile-section">
        <div class="profile-header">
            <div class="profile-avatar">
                            @if(Auth::user()->photo)
                <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Profile Photo">
                @else
                    <i class="fas fa-user"></i>
                @endif
            </div>
            <div class="profile-info">
                <h3>{{ Auth::user()->name }}</h3>
                <p class="profile-email">{{ Auth::user()->email }}</p>
                <p class="profile-role">{{ Auth::user()->roles->first()->name ?? 'User' }}</p>
            </div>
        </div>
    </div>

    <!-- Profile Details -->
    <div class="details-section">
        <h3>Personal Information</h3>
        
        <div class="detail-item">
            <div class="detail-label">
                <i class="fas fa-user"></i>
                <span>Full Name</span>
            </div>
            <div class="detail-value">{{ Auth::user()->name }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">
                <i class="fas fa-envelope"></i>
                <span>Email</span>
            </div>
            <div class="detail-value">{{ Auth::user()->email }}</div>
        </div>

        @if(Auth::user()->nim)
        <div class="detail-item">
            <div class="detail-label">
                <i class="fas fa-id-card"></i>
                <span>NIM</span>
            </div>
            <div class="detail-value">{{ Auth::user()->nim }}</div>
        </div>
        @endif

        @if(Auth::user()->kelompok)
        <div class="detail-item">
            <div class="detail-label">
                <i class="fas fa-users"></i>
                <span>Kelompok</span>
            </div>
            <div class="detail-value">{{ Auth::user()->kelompok->nama }}</div>
        </div>
        @endif

        <div class="detail-item">
            <div class="detail-label">
                <i class="fas fa-calendar-alt"></i>
                <span>Joined</span>
            </div>
            <div class="detail-value">{{ Auth::user()->created_at->format('d F Y') }}</div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="stats-section">
        <h3>Your Activity</h3>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-icon logbook">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $totalLogbooks }}</div>
                    <div class="stat-label">Logbooks</div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon attendance">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $totalAttendance }}</div>
                    <div class="stat-label">Attendance</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="actions-section">
        <h3>Account Actions</h3>
        
        <div class="action-list">
            <a href="{{ route('mobile.profile.edit') }}" class="action-item">
                <div class="action-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <div class="action-content">
                    <div class="action-title">Edit Profile</div>
                    <div class="action-subtitle">Update your personal information</div>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>

            <a href="{{ route('mobile.profile.password') }}" class="action-item">
                <div class="action-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="action-content">
                    <div class="action-title">Change Password</div>
                    <div class="action-subtitle">Update your password</div>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>

            <button type="button" class="action-item" onclick="showLogoutConfirm()">
                <div class="action-icon logout">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <div class="action-content">
                    <div class="action-title">Logout</div>
                    <div class="action-subtitle">Sign out from your account</div>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </button>
        </div>
    </div>

    <!-- Logout Form (Hidden) -->
    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
@endsection

@push('styles')
<style>
    .profile-section {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f3f4;
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0B1F3A 0%, #163057 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-avatar i {
        font-size: 2rem;
        color: white;
    }

    .profile-info h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.25rem;
    }

    .profile-email {
        margin: 0;
        color: #6c757d;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .profile-role {
        margin: 0;
        color: #0B1F3A;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        background: rgba(242, 183, 5, 0.1);
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        display: inline-block;
    }

    .details-section, .stats-section, .actions-section {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f3f4;
    }

    .details-section h3, .stats-section h3, .actions-section h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-item {
        display: flex;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #f1f3f4;
    }

    .detail-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .detail-label {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        color: #6c757d;
        font-size: 0.875rem;
        min-width: 120px;
    }

    .detail-label i {
        color: #0B1F3A;
        width: 16px;
    }

    .detail-value {
        color: #1a202c;
        font-size: 0.875rem;
        flex: 1;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .stat-item {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: white;
    }

    .stat-icon.logbook {
        background: linear-gradient(135deg, #0B1F3A 0%, #163057 100%);
    }

    .stat-icon.attendance {
        background: linear-gradient(135deg, #F2B705 0%, #d9a404 100%);
    }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 1.25rem;
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

    .action-list {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .action-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-radius: 12px;
        text-decoration: none;
        color: #1a202c;
        transition: all 0.2s ease;
        border: none;
        background: none;
        cursor: pointer;
        width: 100%;
        text-align: left;
    }

    .action-item:hover {
        background: #f8f9fa;
        transform: translateX(4px);
    }

    .action-icon {
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

    .action-icon.logout {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .action-content {
        flex: 1;
    }

    .action-title {
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
    }

    .action-subtitle {
        color: #6c757d;
        font-size: 0.75rem;
    }

    .action-arrow {
        color: #cbd5e0;
        font-size: 0.875rem;
    }

    .btn-back {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .profile-header {
            flex-direction: column;
            text-align: center;
        }
        
        .profile-avatar {
            width: 100px;
            height: 100px;
        }
        
        .detail-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .detail-label {
            min-width: auto;
        }
    }
</style>
@endpush

@push('scripts')
<script>
function showLogoutConfirm() {
    if (confirm('Apakah Anda yakin ingin keluar dari aplikasi?')) {
        document.getElementById('logoutForm').submit();
    }
}
</script>
@endpush 
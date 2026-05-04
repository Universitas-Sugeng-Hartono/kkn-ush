@extends('layouts.mobile-app')

@section('content')
<!-- Welcome Header -->
<div class="welcome-header">
    <div class="user-info">
        <div class="user-avatar">
            <i class="fas fa-user"></i>
        </div>
        <div class="user-details">
            <h4>Hello, {{ Auth::user()->name }}</h4>
            <p class="date-info">{{ now()->format('l, d F Y') }}</p>
            <p class="date-info" style="margin-top:4px;">
                <span class="badge bg-success">
                    <i class="fas fa-calendar-alt me-1"></i>
                    {{ ($tahunAktif?->nama ?? '-') }} - {{ ($semesterAktif?->nama ?? '-') }}
                </span>
            </p>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="stats-section">
    <h3>Your KKN Progress</h3>
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
        <div class="stat-item">
            <div class="stat-icon pending">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $pendingLogbooks + $pendingAttendance }}</div>
                <div class="stat-label">Pending</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon total">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $totalLogbooks + $totalAttendance }}</div>
                <div class="stat-label">Total</div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="actions-section">
    <h3>Quick Actions</h3>
    <div class="action-buttons">
        <a href="{{ route('mobile.logbooks.create') }}" class="action-btn primary">
            <i class="fas fa-plus"></i>
            <span>Add Logbook</span>
        </a>
        <a href="{{ route('mobile.attendance.create') }}" class="action-btn secondary">
            <i class="fas fa-clock"></i>
            <span>Mark Attendance</span>
        </a>
        <a href="{{ route('mobile.laporan-kelompok.index') }}" class="action-btn secondary">
            <i class="fas fa-file-alt"></i>
            <span>Laporan Kelompok</span>
        </a>
    </div>
</div>

<!-- Group Information -->
@if($group)
<div class="group-section">
    <h3>Informasi Kelompok</h3>
    <div class="group-info">
        <div class="group-header">
            <div class="group-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="group-details">
                <div class="group-name">{{ $group->nama_kelompok }}</div>
                <div class="group-location">
                    @if($group->lokasi)
                    {{ $group->lokasi->nama }}
                    @else
                    Lokasi KKN
                    @endif
                </div>
            </div>
        </div>

        @if($dpl)
        <div class="dpl-info">
            <div class="info-label">
                <i class="fas fa-user-tie"></i>
                <span>Dosen Pembimbing Lapangan</span>
            </div>
            <div class="dpl-grid">
                <div class="dpl-item">
                    <div class="dpl-avatar">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="dpl-details">
                        <div class="dpl-name">{{ $dpl->name }}</div>
                        @if($dpl->no_hp)
                        <div class="dpl-contact">
                            <i class="fas fa-phone"></i>
                            <span>{{ $dpl->no_hp }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($groupMembers->count() > 0)
        <div class="members-info">
            <div class="info-label">
                <i class="fas fa-user-friends"></i>
                <span>Anggota Kelompok ({{ $groupMembers->count() }})</span>
            </div>
            <div class="members-grid">
                @foreach($groupMembers as $member)
                <div class="member-item">
                    <div class="member-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="member-details">
                        <div class="member-name">{{ $member->name }}</div>
                        @if($member->jurusan)
                        <div class="member-jurusan">
                            <i class="fas fa-graduation-cap"></i>
                            <span>{{ $member->jurusan }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@else
<div class="group-section">
    <div class="no-group">
        <div class="no-group-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="no-group-content">
            <h4>Belum Terdaftar dalam Kelompok</h4>
            <p>Anda belum terdaftar dalam kelompok KKN. Silakan hubungi admin untuk pendaftaran.</p>
        </div>
    </div>
</div>
@endif

<!-- Recent Activities -->
<div class="activities-section">
    <div class="section-header">
        <h3>Recent Activities</h3>
    </div>
    <div class="activities-list">
        @forelse($recentActivities as $activity)
        <div class="activity-item">
            <div class="activity-icon">
                @if($activity->type === 'logbook')
                <i class="fas fa-book"></i>
                @elseif($activity->type === 'attendance')
                <i class="fas fa-calendar-check"></i>
                @else
                <i class="fas fa-info-circle"></i>
                @endif
            </div>
            <div class="activity-content">
                <div class="activity-title">{{ $activity->title }}</div>
                <div class="activity-time">{{ $activity->created_at->diffForHumans() }}</div>
            </div>
            <div class="activity-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <p>No recent activities</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Group Section Styles */
    .group-section {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f3f4;
    }

    .group-section h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .group-info {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .group-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f3f4;
    }

    .group-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, #0B1F3A 0%, #163057 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }

    .group-details {
        flex: 1;
    }

    .group-name {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.25rem;
    }

    .group-location {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .dpl-info,
    .members-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .info-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: #6c757d;
        font-size: 0.875rem;
    }

    .info-label i {
        color: #0B1F3A;
        width: 16px;
    }

    .info-value {
        color: #1a202c;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .members-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 0.75rem;
    }

    .member-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 1rem 0.5rem;
        background: #f8f9fa;
        border-radius: 12px;
        transition: all 0.2s ease;
    }

    .member-item:hover {
        background: #e9ecef;
        transform: translateY(-1px);
    }

    .member-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #0B1F3A;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.125rem;
        margin-bottom: 0.75rem;
    }

    .member-details {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        width: 100%;
    }

    .member-name {
        font-size: 0.875rem;
        color: #1a202c;
        font-weight: 600;
        line-height: 1.2;
    }

    .member-jurusan {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
        font-size: 0.75rem;
        color: #6c757d;
    }

    .member-jurusan i {
        font-size: 0.7rem;
    }

    /* DPL Styles - Updated for grid layout */
    .dpl-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 0.75rem;
    }

    .dpl-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 1rem 0.5rem;
        background: #f8f9fa;
        border-radius: 12px;
        transition: all 0.2s ease;
    }

    .dpl-item:hover {
        background: #e9ecef;
        transform: translateY(-1px);
    }

    .dpl-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #0B1F3A;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.125rem;
        margin-bottom: 0.75rem;
    }

    .dpl-details {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        width: 100%;
    }

    .dpl-name {
        font-size: 0.875rem;
        color: #1a202c;
        font-weight: 600;
        line-height: 1.2;
    }

    .dpl-contact {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
        font-size: 0.75rem;
        color: #6c757d;
    }

    .dpl-contact i {
        font-size: 0.7rem;
    }

    /* No Group State */
    .no-group {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 2rem 1rem;
    }

    .no-group-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .no-group-content h4 {
        font-size: 1rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 0.5rem;
    }

    .no-group-content p {
        font-size: 0.875rem;
        color: #6c757d;
        line-height: 1.5;
    }

    @media (max-width: 480px) {
        .group-header {
            flex-direction: column;
            text-align: center;
            gap: 0.75rem;
        }

        .group-icon {
            width: 56px;
            height: 56px;
            font-size: 1.5rem;
        }

        .members-grid {
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 0.5rem;
        }

        .dpl-grid {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }

        .member-item,
        .dpl-item {
            padding: 0.75rem 0.5rem;
        }

        .member-avatar,
        .dpl-avatar {
            width: 40px;
            height: 40px;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .member-name,
        .dpl-name {
            font-size: 0.8rem;
        }

        .member-jurusan,
        .dpl-contact {
            font-size: 0.7rem;
        }
    }
</style>
@endpush
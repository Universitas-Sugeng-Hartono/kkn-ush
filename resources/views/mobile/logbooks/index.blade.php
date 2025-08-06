@extends('layouts.mobile-app')

@section('content')
    <!-- Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h2>My Logbooks</h2>
                <p class="date-info">{{ now()->format('l, d F Y') }}</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('mobile.logbooks.create') }}" class="btn-add">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Calendar View -->
    <div class="calendar-section">
        <div class="calendar-header">
            <h3>Calendar View</h3>
            <div class="calendar-nav">
                <button class="nav-btn" onclick="previousMonth()">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <span class="current-month">{{ now()->format('F Y') }}</span>
                <button class="nav-btn" onclick="nextMonth()">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        
        <div class="calendar-grid">
            <!-- Days of week -->
            <div class="calendar-days">
                <div class="day-header">M</div>
                <div class="day-header">T</div>
                <div class="day-header">W</div>
                <div class="day-header">T</div>
                <div class="day-header">F</div>
                <div class="day-header">S</div>
                <div class="day-header">S</div>
            </div>
            
            <!-- Calendar dates -->
            <div class="calendar-dates" id="calendarDates">
                <!-- Calendar will be populated by JavaScript -->
            </div>
        </div>
    </div>

    <!-- Recent Logbooks -->
    <div class="recent-section">
        <div class="section-header">
            <h3>Recent Logbooks</h3>
        </div>
        
        <div class="logbooks-list">
            @forelse($logbooks as $logbook)
                <div class="logbook-item" onclick="window.location.href='{{ route('mobile.logbooks.show', $logbook->id) }}'">
                    <div class="logbook-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="logbook-content">
                        <div class="logbook-title">{{ $logbook->judul }}</div>
                        <div class="logbook-date">{{ $logbook->tanggal->format('d M Y') }}</div>
                    </div>
                    <div class="logbook-status {{ $logbook->status }}">
                        <span class="status-badge">{{ ucfirst($logbook->status) }}</span>
                    </div>
                    <div class="logbook-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-book"></i>
                    <p>No logbooks yet</p>
                    <a href="{{ route('mobile.logbooks.create') }}" class="btn-add-logbook">
                        <i class="fas fa-plus"></i>
                        Create First Logbook
                    </a>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('scripts')
<script>
let currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();
// Data tanggal logbook yang sudah di-submit/approved dari backend
const logbookDates = @json($logbookDates ?? []);

function renderCalendar() {
    const calendarDates = document.getElementById('calendarDates');
    const firstDay = new Date(currentYear, currentMonth, 1);
    const lastDay = new Date(currentYear, currentMonth + 1, 0);
    const startDate = new Date(firstDay);
    startDate.setDate(startDate.getDate() - firstDay.getDay());
    let html = '';
    for (let i = 0; i < 42; i++) {
        const date = new Date(startDate);
        date.setDate(startDate.getDate() + i);
        const isCurrentMonth = date.getMonth() === currentMonth;
        const isToday = date.toDateString() === new Date().toDateString();
        // Format tanggal dengan timezone yang benar
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const dateStr = `${year}-${month}-${day}`;
        const hasEvent = logbookDates.includes(dateStr);
        
        if (i < 10) { // Debug untuk 10 tanggal pertama
            console.log(`Date ${i}:`, date.toDateString(), 'formatted:', dateStr, 'hasEvent:', hasEvent);
        }
        
        html += `
            <div class="calendar-date ${isCurrentMonth ? 'current-month' : 'other-month'} ${isToday ? 'today' : ''} ${hasEvent ? 'has-event' : ''}" 
                 data-date="${dateStr}" onclick="onCalendarDateClick('${dateStr}', ${isCurrentMonth})">
                <span class="date-number">${date.getDate()}</span>
                ${hasEvent ? '<div class="event-dot"></div>' : ''}
            </div>
        `;
    }
    calendarDates.innerHTML = html;
    document.querySelector('.current-month').textContent = new Date(currentYear, currentMonth).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
}

function previousMonth() {
    currentMonth--;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    renderCalendar();
}

function nextMonth() {
    currentMonth++;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    renderCalendar();
}

function onCalendarDateClick(dateStr, isCurrentMonth) {
    if (!isCurrentMonth) return;
    console.log('Clicked date:', dateStr, 'isCurrentMonth:', isCurrentMonth);
    // Redirect ke create logbook dengan tanggal terisi otomatis
    window.location.href = `{{ route('mobile.logbooks.create') }}?tanggal=${dateStr}`;
}

document.addEventListener('DOMContentLoaded', function() {
    renderCalendar();
});
</script>
@endpush 
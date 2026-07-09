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

    <!-- Tab Switcher -->
    <div class="tabs-section mb-3" style="padding: 0 16px;">
        <div class="d-flex bg-light rounded-pill p-1">
            <button class="btn btn-sm rounded-pill flex-fill py-2 btn-primary" id="tab-individu" onclick="switchTab('individu')">
                <i class="fas fa-user me-1"></i> Individu
            </button>
            <button class="btn btn-sm rounded-pill flex-fill py-2 text-dark bg-transparent border-0" id="tab-kelompok" onclick="switchTab('kelompok')">
                <i class="fas fa-users me-1"></i> Kelompok
            </button>
        </div>
    </div>

    <!-- Recent Logbooks -->
    <div class="recent-section">
        <!-- Logbook Individu List -->
        <div id="list-individu">
            <div class="section-header">
                <h3>Logbook Individu</h3>
            </div>
            
            <div class="logbooks-list">
                @forelse($logbooksIndividu as $logbook)
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
                        <p>Belum ada logbook individu</p>
                        <a href="{{ route('mobile.logbooks.create') }}?tipe=individu" class="btn-add-logbook">
                            <i class="fas fa-plus"></i>
                            Buat Logbook Individu
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Logbook Kelompok List -->
        <div id="list-kelompok" style="display: none;">
            <div class="section-header">
                <h3>Logbook Kelompok</h3>
            </div>
            
            <div class="logbooks-list">
                @forelse($logbooksKelompok as $logbook)
                    <div class="logbook-item" onclick="window.location.href='{{ route('mobile.logbooks.show', $logbook->id) }}'">
                        <div class="logbook-icon bg-success bg-opacity-10 text-success">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="logbook-content">
                            <div class="logbook-title">{{ $logbook->judul }}</div>
                            <div class="logbook-date">
                                {{ $logbook->tanggal->format('d M Y') }} 
                                <span class="text-muted text-xs ms-1">| Oleh: {{ $logbook->user?->name ?? 'Unknown' }}</span>
                            </div>
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
                        <i class="fas fa-users"></i>
                        <p>Belum ada logbook kelompok</p>
                        <a href="{{ route('mobile.logbooks.create') }}?tipe=kelompok" class="btn-add-logbook bg-success border-success text-white">
                            <i class="fas fa-plus"></i>
                            Buat Logbook Kelompok
                        </a>
                    </div>
                @endforelse
            </div>
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
    const activeTab = document.getElementById('tab-individu').classList.contains('btn-primary') ? 'individu' : 'kelompok';
    // Redirect ke create logbook dengan tanggal terisi otomatis
    window.location.href = `{{ route('mobile.logbooks.create') }}?tanggal=${dateStr}&tipe=${activeTab}`;
}

function switchTab(type) {
    const btnIndividu = document.getElementById('tab-individu');
    const btnKelompok = document.getElementById('tab-kelompok');
    const listIndividu = document.getElementById('list-individu');
    const listKelompok = document.getElementById('list-kelompok');
    
    if (type === 'individu') {
        btnIndividu.classList.add('btn-primary');
        btnIndividu.classList.remove('text-dark', 'bg-transparent', 'border-0');
        
        btnKelompok.classList.remove('btn-primary');
        btnKelompok.classList.add('text-dark', 'bg-transparent', 'border-0');
        
        listIndividu.style.display = 'block';
        listKelompok.style.display = 'none';
    } else {
        btnKelompok.classList.add('btn-primary');
        btnKelompok.classList.remove('text-dark', 'bg-transparent', 'border-0');
        
        btnIndividu.classList.remove('btn-primary');
        btnIndividu.classList.add('text-dark', 'bg-transparent', 'border-0');
        
        listIndividu.style.display = 'none';
        listKelompok.style.display = 'block';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    renderCalendar();
    
    // Auto switch to tab from URL if present
    const urlParams = new URLSearchParams(window.location.search);
    const tipe = urlParams.get('tipe');
    if (tipe === 'kelompok') {
        switchTab('kelompok');
    }
});
</script>
@endpush 
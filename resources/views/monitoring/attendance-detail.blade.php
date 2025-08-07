<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Absensi Mahasiswa') }}
        </h2>
    </x-slot>

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-12">
                <h2 class="fw-bold">Monitoring Absensi Mahasiswa</h2>
                <p class="text-muted">Detail kehadiran mahasiswa bimbingan untuk periode KKN</p>
            </div>
        </div>

        <!-- Statistik Cards - Menggunakan style dashboard -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ count($attendanceData) }}</h4>
                                <p class="mb-0">Total Mahasiswa</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ count($days) }}</h4>
                                <p class="mb-0">Hari KKN</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-calendar fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">
                                    @php
                                        $pendingTotal = 0;
                                        foreach($attendanceData as $data) {
                                            foreach($data['days'] as $dayData) {
                                                if($dayData['status'] === 'pending') $pendingTotal++;
                                            }
                                        }
                                    @endphp
                                    {{ $pendingTotal }}
                                </h4>
                                <p class="mb-0">Pending</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">
                                    @php
                                        $totalValidated = 0;
                                        foreach($attendanceData as $data) {
                                            foreach($data['days'] as $dayData) {
                                                if($dayData['status'] === 'validated') $totalValidated++;
                                            }
                                        }
                                    @endphp
                                    {{ $totalValidated }}
                                </h4>
                                <p class="mb-0">Hadir</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-chart-line fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards - Style dashboard -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="card-subtitle mb-2 text-muted">Rajin Hadir</h6>
                                <h2 class="card-title mb-0 text-success">
                                    @php
                                        $rajinHadir = 0;
                                        foreach($attendanceData as $data) {
                                            $validatedCount = 0;
                                            foreach($data['days'] as $dayData) {
                                                if($dayData['status'] === 'validated') $validatedCount++;
                                            }
                                            if($validatedCount >= 15) $rajinHadir++;
                                        }
                                    @endphp
                                    {{ $rajinHadir }}
                                </h2>
                                <small class="text-muted">≥15 hari hadir</small>
                            </div>
                            <div class="bg-success rounded-circle p-3">
                                <i class="fas fa-user-check fa-fw text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="card-subtitle mb-2 text-muted">Pending Approval</h6>
                                <h2 class="card-title mb-0 text-warning">{{ $pendingTotal }}</h2>
                                <small class="text-muted">Butuh validasi</small>
                            </div>
                            <div class="bg-warning rounded-circle p-3">
                                <i class="fas fa-clock fa-fw text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="card-subtitle mb-2 text-muted">Jarang Hadir</h6>
                                <h2 class="card-title mb-0 text-danger">
                                    @php
                                        $jarangHadir = 0;
                                        foreach($attendanceData as $data) {
                                            $validatedCount = 0;
                                            foreach($data['days'] as $dayData) {
                                                if($dayData['status'] === 'validated') $validatedCount++;
                                            }
                                            if($validatedCount <= 8) $jarangHadir++;
                                        }
                                    @endphp
                                    {{ $jarangHadir }}
                                </h2>
                                <small class="text-muted">≤8 hari hadir</small>
                            </div>
                            <div class="bg-danger rounded-circle p-3">
                                <i class="fas fa-user-times fa-fw text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="card-subtitle mb-2 text-muted">Total Mahasiswa</h6>
                                <h2 class="card-title mb-0 text-primary">{{ count($attendanceData) }}</h2>
                                <small class="text-muted">Yang dibimbing</small>
                            </div>
                            <div class="bg-primary rounded-circle p-3">
                                <i class="fas fa-users fa-fw text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Control Panel -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Kontrol Panel</h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" class="form-control" placeholder="Cari mahasiswa..." id="searchInput">
                                </div>
                            </div>
                            <div class="col-md-6 text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    <span class="text-muted">04 Aug 2025 - 26 Aug 2025</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-table me-2"></i>
                            Data Kehadiran Mahasiswa
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-muted mb-0">Periode Kehadiran KKN ({{ count($days) }} Hari)</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="sticky-col bg-light" style="left: 0; z-index: 10; min-width: 120px;">NIM</th>
                                        <th class="sticky-col bg-light" style="left: 120px; z-index: 10; min-width: 250px;">Nama Mahasiswa</th>
                                        <th class="sticky-col bg-light text-center" style="left: 370px; z-index: 10; min-width: 120px;">Summary</th>
                                        @foreach($days as $day)
                                            @php
                                                $date = \Carbon\Carbon::parse($day);
                                                $dayName = $date->locale('id')->dayName;
                                                $isToday = $date->isToday();
                                                $isWeekend = $date->isWeekend();
                                            @endphp
                                            <th class="text-center border" style="min-width: 60px; background-color: {{ $isToday ? '#ff6b6b' : ($isWeekend ? '#fff3e0' : '#f8f9fa') }};">
                                                <div class="text-xs text-muted mb-1">{{ substr($dayName, 0, 3) }}</div>
                                                <div class="font-bold {{ $isToday ? 'text-white' : '' }}">{{ $date->format('d') }}</div>
                                                <div class="text-xs {{ $isToday ? 'text-white' : 'text-muted' }}">{{ $date->format('M') }}</div>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($attendanceData as $data)
                                        <tr>
                                            <td class="sticky-col bg-white" style="left: 0; z-index: 5; min-width: 120px;">
                                                <div class="font-mono fw-bold">{{ $data['mahasiswa']->nim ?? '-' }}</div>
                                            </td>
                                            <td class="sticky-col bg-white" style="left: 120px; z-index: 5; min-width: 250px;">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 12px; font-weight: bold;">
                                                        {{ strtoupper(substr($data['mahasiswa']->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $data['mahasiswa']->name }}</div>
                                                        @if($data['mahasiswa']->jurusan)
                                                            <small class="text-muted">{{ ucfirst($data['mahasiswa']->jurusan) }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="sticky-col bg-white text-center" style="left: 370px; z-index: 5; min-width: 120px;">
                                                @php
                                                    $validatedCount = 0;
                                                    $pendingCount = 0;
                                                    foreach($data['days'] as $dayData) {
                                                        if($dayData['status'] === 'validated') $validatedCount++;
                                                        elseif($dayData['status'] === 'pending') $pendingCount++;
                                                    }
                                                    $attendanceRate = round(($validatedCount / count($days)) * 100);
                                                @endphp
                                                <div class="text-sm">
                                                    <div class="text-success fw-bold">✓ {{ $validatedCount }}/{{ count($days) }}</div>
                                                    @if($pendingCount > 0)
                                                        <div class="text-warning">⏳ {{ $pendingCount }}</div>
                                                    @endif
                                                    <div class="text-muted">{{ $attendanceRate }}%</div>
                                                </div>
                                            </td>
                                            
                                            @foreach($days as $day)
                                                @php
                                                    $dayData = $data['days'][$day];
                                                    $attendance = $dayData['attendance'];
                                                    $date = \Carbon\Carbon::parse($day);
                                                    
                                                    if($attendance) {
                                                        if($attendance->status === 'validated') {
                                                            $bgColor = 'bg-success';
                                                            $symbol = '✓';
                                                            $title = 'Hadir (Validated)';
                                                        } elseif($attendance->status === 'pending') {
                                                            $bgColor = 'bg-warning';
                                                            $symbol = '⏳';
                                                            $title = 'Pending Approval';
                                                        } else {
                                                            $bgColor = 'bg-secondary';
                                                            $symbol = '?';
                                                            $title = 'Status: ' . $attendance->status;
                                                        }
                                                    } else {
                                                        $bgColor = 'bg-danger';
                                                        $symbol = '✗';
                                                        $title = 'Tidak Hadir';
                                                    }
                                                @endphp
                                                <td class="text-center border" style="min-width: 60px;">
                                                    <div class="d-flex align-items-center justify-content-center" style="height: 40px;">
                                                        @if($attendance)
                                                            <a href="{{ route('history.attendances.show', $attendance->id) }}" 
                                                               class="text-decoration-none">
                                                                <div class="rounded-circle d-flex align-items-center justify-content-center {{ $bgColor }} text-white" 
                                                                     style="width: 32px; height: 32px; font-size: 16px; font-weight: bold;"
                                                                     data-bs-toggle="tooltip" 
                                                                     data-bs-placement="top" 
                                                                     title="{{ $title }}">
                                                                    {{ $symbol }}
                                                                </div>
                                                            </a>
                                                        @else
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center {{ $bgColor }} text-white" 
                                                                 style="width: 32px; height: 32px; font-size: 16px; font-weight: bold;"
                                                                 data-bs-toggle="tooltip" 
                                                                 data-bs-placement="top" 
                                                                 title="{{ $title }}">
                                                                {{ $symbol }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ 3 + count($days) }}" class="text-center py-5">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">Tidak ada mahasiswa yang dibimbing</h5>
                                                    <p class="text-muted">Anda belum memiliki mahasiswa bimbingan.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('tbody tr');
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                tableRows.forEach(row => {
                    const studentName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const studentNim = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                    
                    if (studentName.includes(searchTerm) || studentNim.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout> 
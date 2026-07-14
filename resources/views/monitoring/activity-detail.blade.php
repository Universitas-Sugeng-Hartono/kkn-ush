<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Aktivitas Mahasiswa (30 Hari Terakhir)') }}
        </h2>
    </x-slot>

    <!-- Custom CSS untuk tampilan yang lebih menarik -->
    <style>
        .activity-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }
        
        .activity-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .activity-header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .date-header {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .date-cell {
            text-align: center;
            padding: 0.5rem 0.25rem;
            font-weight: 600;
            color: #4a5568;
            min-width: 40px;
            font-size: 0.75rem;
        }
        
        .student-row {
            transition: all 0.3s ease;
        }
        
        .student-row:hover {
            background-color: #f7fafc;
            transform: translateX(5px);
        }
        
        .status-indicator {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }
        
        .status-indicator:hover {
            transform: scale(1.2);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .summary-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .summary-card:hover {
            transform: translateY(-5px);
        }
        
        .nav-buttons {
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .nav-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .table-container {
            overflow-x: auto;
            max-width: 100%;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .table-container::-webkit-scrollbar {
            height: 8px;
        }
        
        .table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .table-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        .table-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        .sticky-column {
            position: sticky;
            left: 0;
            background: white;
            z-index: 5;
            border-right: 2px solid #e2e8f0;
        }
        
        .sticky-column-2 {
            position: sticky;
            left: 80px;
            background: white;
            z-index: 5;
            border-right: 2px solid #e2e8f0;
        }
        
        .sticky-column-3 {
            position: sticky;
            left: 160px;
            background: white;
            z-index: 5;
            border-right: 2px solid #e2e8f0;
        }
        
        .sticky-column-4 {
            position: sticky;
            left: 240px;
            background: white;
            z-index: 5;
            border-right: 2px solid #e2e8f0;
        }
    </style>

    <div class="activity-container">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Card -->
            <div class="activity-card mb-6">
                <div class="activity-header">
                    <h1 class="text-4xl font-bold mb-4">Monitoring Aktivitas Mahasiswa</h1>
                    <p class="text-xl opacity-90 mb-6">Detail gabungan absensi dan logbook mahasiswa bimbingan Anda untuk periode KKN</p>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-number">{{ count($activityData) }}</div>
                            <div class="stat-label">Total Mahasiswa</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ count($days) }}</div>
                            <div class="stat-label">Hari KKN</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ $startDate->format('d M') }} - {{ $endDate->format('d M Y') }}</div>
                            <div class="stat-label">Periode KKN</div>
                        </div>
                    </div>
                </div>

                <!-- Filter Form -->
                <div class="p-6 border-t border-gray-100 bg-light bg-opacity-25">
                    <form method="GET" action="{{ route('monitoring.activity-detail') }}" id="filterForm">
                        <div class="row g-3 align-items-center justify-content-center">
                            @if($dplList)
                                <div class="col-md-4">
                                    <select class="form-select border border-gray-300 rounded shadow-sm py-2 px-3" name="dpl_id" onchange="document.getElementById('filterForm').submit()">
                                        <option value="">Semua DPL Pendamping</option>
                                        @foreach($dplList as $dplOption)
                                            <option value="{{ $dplOption->id }}" {{ $dpl_id == $dplOption->id ? 'selected' : '' }}>
                                                DPL: {{ $dplOption->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select border border-gray-300 rounded shadow-sm py-2 px-3" name="tahun_akademik_id" onchange="document.getElementById('filterForm').submit()">
                                        <option value="">Semua Tahun Akademik</option>
                                        @foreach($tahunAkademikList as $ta)
                                            <option value="{{ $ta->id }}" {{ $tahun_akademik_id == $ta->id ? 'selected' : '' }}>
                                                Tahun Akademik: {{ $ta->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select border border-gray-300 rounded shadow-sm py-2 px-3" name="semester_id" onchange="document.getElementById('filterForm').submit()">
                                        <option value="">Semua Semester</option>
                                        @foreach($semesterList as $sem)
                                            <option value="{{ $sem->id }}" {{ $semester_id == $sem->id ? 'selected' : '' }}>
                                                Semester: {{ $sem->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="col-md-5">
                                    <select class="form-select border border-gray-300 rounded shadow-sm py-2 px-3" name="tahun_akademik_id" onchange="document.getElementById('filterForm').submit()">
                                        <option value="">Semua Tahun Akademik</option>
                                        @foreach($tahunAkademikList as $ta)
                                            <option value="{{ $ta->id }}" {{ $tahun_akademik_id == $ta->id ? 'selected' : '' }}>
                                                Tahun Akademik: {{ $ta->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <select class="form-select border border-gray-300 rounded shadow-sm py-2 px-3" name="semester_id" onchange="document.getElementById('filterForm').submit()">
                                        <option value="">Semua Semester</option>
                                        @foreach($semesterList as $sem)
                                            <option value="{{ $sem->id }}" {{ $semester_id == $sem->id ? 'selected' : '' }}>
                                                Semester: {{ $sem->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
                
                <!-- Navigation Buttons -->
                <div class="p-6 bg-gray-50 border-t border-gray-100">
                    <div class="nav-buttons flex flex-wrap justify-center">
                        <a href="{{ route('monitoring.attendance-detail') }}" class="nav-btn bg-blue-500 hover:bg-blue-600 text-white">
                            <i class="fas fa-calendar-check"></i>
                            Detail Absensi
                        </a>
                        <a href="{{ route('monitoring.logbook-detail') }}" class="nav-btn bg-green-500 hover:bg-green-600 text-white">
                            <i class="fas fa-book"></i>
                            Detail Logbook
                        </a>
                        <a href="{{ route('dashboard') }}" class="nav-btn bg-gray-500 hover:bg-gray-600 text-white">
                            <i class="fas fa-home"></i>
                            Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Legend -->
            <div class="mb-6">
                <div class="activity-card p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Keterangan Status:
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="legend-item">
                            <div class="w-6 h-6 bg-green-500 rounded-full"></div>
                            <span class="text-sm font-medium">Hadir & Logbook</span>
                        </div>
                        <div class="legend-item">
                            <div class="w-6 h-6 bg-blue-500 rounded-full"></div>
                            <span class="text-sm font-medium">Hadir Saja</span>
                        </div>
                        <div class="legend-item">
                            <div class="w-6 h-6 bg-yellow-500 rounded-full"></div>
                            <span class="text-sm font-medium">Logbook Saja</span>
                        </div>
                        <div class="legend-item">
                            <div class="w-6 h-6 bg-red-500 rounded-full"></div>
                            <span class="text-sm font-medium">Tidak Aktif</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Table -->
            <div class="activity-card overflow-hidden mb-6">
                <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom bg-white">
                    <h6 class="fw-bold mb-0 text-gray-800">
                        <i class="fas fa-table me-2 text-primary"></i>
                        Data Aktivitas Mahasiswa —
                        <span class="{{ $tipe === 'kelompok' ? 'text-success' : 'text-primary' }} fw-bold">
                            Logbook {{ $tipe === 'kelompok' ? 'Kelompok' : 'Individu' }}
                        </span>
                    </h6>
                    <div class="btn-group shadow-sm" role="group" aria-label="Tipe Logbook">
                        <a href="{{ request()->fullUrlWithQuery(['tipe' => 'individu']) }}"
                           class="btn btn-sm {{ $tipe === 'individu' ? 'btn-primary' : 'btn-outline-primary' }} fw-semibold px-3">
                            <i class="fas fa-user me-1"></i>Individu
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['tipe' => 'kelompok']) }}"
                           class="btn btn-sm {{ $tipe === 'kelompok' ? 'btn-success' : 'btn-outline-success' }} fw-semibold px-3">
                            <i class="fas fa-users me-1"></i>Kelompok
                        </a>
                    </div>
                </div>
                <div class="table-container">
                    <table class="min-w-full">
                        <!-- Header dengan tanggal lengkap -->
                        <thead>
                            <tr class="date-header">
                                <th class="sticky-column px-4 py-3 text-left text-sm font-semibold text-gray-900 border-r border-gray-200" rowspan="2">No</th>
                                <th class="sticky-column-2 px-4 py-3 text-left text-sm font-semibold text-gray-900 border-r border-gray-200" rowspan="2">NIM</th>
                                <th class="sticky-column-3 px-4 py-3 text-left text-sm font-semibold text-gray-900 border-r border-gray-200" rowspan="2">Nama Mahasiswa</th>
                                <th class="sticky-column-4 px-4 py-3 text-center text-sm font-semibold text-gray-900 border-r border-gray-200" rowspan="2">Summary</th>
                                <th class="px-2 py-2 text-center text-sm font-semibold text-gray-900" colspan="{{ count($days) }}">
                                    Periode Aktivitas ({{ count($days) }} Hari)
                                </th>
                            </tr>
                            <tr class="date-header border-t border-gray-200">
                                @foreach($days as $day)
                                    @php
                                        $date = \Carbon\Carbon::parse($day);
                                        $dayName = $date->locale('id')->dayName;
                                        $isToday = $date->isToday();
                                        $isWeekend = $date->isWeekend();
                                    @endphp
                                    <th class="date-cell border-r border-gray-200 {{ $isToday ? 'bg-blue-100' : '' }} {{ $isWeekend ? 'bg-red-50' : '' }}">
                                        <div class="text-xs text-gray-500 mb-1">{{ substr($dayName, 0, 3) }}</div>
                                        <div class="font-bold text-xs">{{ $date->format('d') }}</div>
                                        <div class="text-xs text-gray-500">{{ $date->format('M') }}</div>
                                        @if($isToday)
                                            <div class="text-xs text-blue-600 font-semibold">H</div>
                                        @endif
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($activityData as $data)
                                <tr class="student-row border-b border-gray-100">
                                    <td class="sticky-column px-4 py-3 text-sm font-medium text-gray-900 border-r border-gray-100">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="sticky-column-2 px-4 py-3 text-sm text-gray-900 border-r border-gray-100">
                                        <div class="font-mono font-semibold text-xs">{{ $data['mahasiswa']->nim ?? '-' }}</div>
                                    </td>
                                    <td class="sticky-column-3 px-4 py-3 border-r border-gray-100">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center text-white font-bold text-xs">
                                                    {{ strtoupper(substr($data['mahasiswa']->name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $data['mahasiswa']->name }}</div>
                                                @if($data['mahasiswa']->jurusan)
                                                    <div class="text-xs text-gray-500">{{ ucfirst($data['mahasiswa']->jurusan) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="sticky-column-4 px-4 py-3 text-center border-r border-gray-100">
                                        @php
                                            $bothCount = 0;
                                            $attendanceOnlyCount = 0;
                                            $logbookOnlyCount = 0;
                                            $inactiveCount = 0;
                                            foreach($data['days'] as $dayData) {
                                                $hasAttendance = $dayData['attendance_status'] === 'validated';
                                                $hasLogbook = in_array($dayData['logbook_status'], ['approved', 'submitted']);
                                                
                                                if($hasAttendance && $hasLogbook) {
                                                    $bothCount++;
                                                } elseif($hasAttendance) {
                                                    $attendanceOnlyCount++;
                                                } elseif($hasLogbook) {
                                                    $logbookOnlyCount++;
                                                } else {
                                                    $inactiveCount++;
                                                }
                                            }
                                            $activityRate = round((($bothCount + $attendanceOnlyCount + $logbookOnlyCount) / count($days)) * 100);
                                        @endphp
                                        <div class="space-y-1">
                                            <div class="text-xs text-green-600 font-semibold">✓ {{ $bothCount }}/{{ count($days) }}</div>
                                            @if($attendanceOnlyCount > 0)
                                                <div class="text-xs text-blue-600"><i class="fas fa-user"></i> {{ $attendanceOnlyCount }}</div>
                                            @endif
                                            @if($logbookOnlyCount > 0)
                                                <div class="text-xs text-yellow-600"><i class="fas fa-book"></i> {{ $logbookOnlyCount }}</div>
                                            @endif
                                            <div class="text-xs text-gray-500">{{ $activityRate }}%</div>
                                        </div>
                                    </td>
                                    @foreach($days as $day)
                                        @php
                                            $dayData = $data['days'][$day];
                                            $attendance = $dayData['attendance'];
                                            $logbook = $dayData['logbook'];
                                            $date = \Carbon\Carbon::parse($day);
                                            
                                            // Tentukan status berdasarkan attendance dan logbook
                                            if($attendance && $logbook) {
                                                if($attendance->status === 'validated' && $logbook->status === 'approved') {
                                                    $bgColor = 'bg-success';
                                                    $symbol = '✓';
                                                    $title = 'Hadir & Logbook Disetujui';
                                                } elseif($attendance->status === 'validated' && ($logbook->status === 'submitted' || $logbook->status === 'pending')) {
                                                    $bgColor = 'bg-info';
                                                    $symbol = '✓';
                                                    $title = 'Hadir & Logbook Pending';
                                                } elseif(($attendance->status === 'pending') && $logbook->status === 'approved') {
                                                    $bgColor = 'bg-warning';
                                                    $symbol = '!';
                                                    $title = 'Absen Pending & Logbook Disetujui';
                                                } elseif(($attendance->status === 'pending') && ($logbook->status === 'submitted' || $logbook->status === 'pending')) {
                                                    $bgColor = 'bg-warning';
                                                    $symbol = '!';
                                                    $title = 'Absen & Logbook Pending';
                                                } else {
                                                    $bgColor = 'bg-secondary';
                                                    $symbol = '?';
                                                    $title = 'Status: ' . $attendance->status . ' / ' . $logbook->status;
                                                }
                                            } elseif($attendance && !$logbook) {
                                                if($attendance->status === 'validated') {
                                                    $bgColor = 'bg-primary';
                                                    $symbol = '✓';
                                                    $title = 'Hadir (Tidak Ada Logbook)';
                                                } elseif($attendance->status === 'pending') {
                                                    $bgColor = 'bg-warning';
                                                    $symbol = '!';
                                                    $title = 'Absen Pending (Tidak Ada Logbook)';
                                                } else {
                                                    $bgColor = 'bg-secondary';
                                                    $symbol = '?';
                                                    $title = 'Status: ' . $attendance->status;
                                                }
                                            } elseif(!$attendance && $logbook) {
                                                if($logbook->status === 'approved') {
                                                    $bgColor = 'bg-success';
                                                    $symbol = '✓';
                                                    $title = 'Tidak Hadir (Logbook Disetujui)';
                                                } elseif($logbook->status === 'submitted' || $logbook->status === 'pending') {
                                                    $bgColor = 'bg-warning';
                                                    $symbol = '!';
                                                    $title = 'Tidak Hadir (Logbook Pending)';
                                                } else {
                                                    $bgColor = 'bg-secondary';
                                                    $symbol = '?';
                                                    $title = 'Status: ' . $logbook->status;
                                                }
                                            } else {
                                                $bgColor = 'bg-danger';
                                                $symbol = '✗';
                                                $title = 'Tidak Hadir & Tidak Submit Logbook';
                                            }
                                        @endphp
                                        <td class="text-center border" style="min-width: 60px;">
                                            <div class="d-flex align-items-center justify-content-center" style="height: 40px;">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center {{ $bgColor }} text-white" 
                                                     style="width: 32px; height: 32px; font-size: 16px; font-weight: bold;"
                                                     data-bs-toggle="tooltip" 
                                                     data-bs-placement="top" 
                                                     title="{{ $title }}">
                                                    {{ $symbol }}
                                                </div>
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 4 + count($days) }}" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-users fa-3x text-gray-300 mb-4"></i>
                                            <h3 class="text-lg font-medium">Tidak ada mahasiswa yang dibimbing</h3>
                                            <p class="text-sm">Anda belum memiliki mahasiswa bimbingan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="summary-card bg-gradient-to-br from-green-400 to-green-600 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-lg font-semibold mb-2">Sangat Aktif</h4>
                            @php
                                $sangatAktif = 0;
                                foreach($activityData as $data) {
                                    $bothCount = 0;
                                    foreach($data['days'] as $dayData) {
                                        $hasAttendance = $dayData['attendance_status'] === 'validated';
                                        $hasLogbook = in_array($dayData['logbook_status'], ['approved', 'submitted']);
                                        if($hasAttendance && $hasLogbook) $bothCount++;
                                    }
                                    if($bothCount >= 20) $sangatAktif++;
                                }
                            @endphp
                            <p class="text-3xl font-bold">{{ $sangatAktif }}</p>
                            <p class="text-sm opacity-90">≥ 20 hari lengkap</p>
                        </div>
                        <i class="fas fa-star fa-2x opacity-80"></i>
                    </div>
                </div>
                
                <div class="summary-card bg-gradient-to-br from-blue-400 to-blue-600 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-lg font-semibold mb-2">Cukup Aktif</h4>
                            @php
                                $cukupAktif = 0;
                                foreach($activityData as $data) {
                                    $totalActive = 0;
                                    foreach($data['days'] as $dayData) {
                                        $hasAttendance = $dayData['attendance_status'] === 'validated';
                                        $hasLogbook = in_array($dayData['logbook_status'], ['approved', 'submitted']);
                                        if($hasAttendance || $hasLogbook) $totalActive++;
                                    }
                                    if($totalActive >= 15 && $totalActive < 20) $cukupAktif++;
                                }
                            @endphp
                            <p class="text-3xl font-bold">{{ $cukupAktif }}</p>
                            <p class="text-sm opacity-90">15-19 hari aktif</p>
                        </div>
                        <i class="fas fa-thumbs-up fa-2x opacity-80"></i>
                    </div>
                </div>
                
                <div class="summary-card bg-gradient-to-br from-red-400 to-red-600 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-lg font-semibold mb-2">Kurang Aktif</h4>
                            @php
                                $kurangAktif = 0;
                                foreach($activityData as $data) {
                                    $totalActive = 0;
                                    foreach($data['days'] as $dayData) {
                                        $hasAttendance = $dayData['attendance_status'] === 'validated';
                                        $hasLogbook = in_array($dayData['logbook_status'], ['approved', 'submitted']);
                                        if($hasAttendance || $hasLogbook) $totalActive++;
                                    }
                                    if($totalActive < 15) $kurangAktif++;
                                }
                            @endphp
                            <p class="text-3xl font-bold">{{ $kurangAktif }}</p>
                            <p class="text-sm opacity-90">< 15 hari aktif</p>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x opacity-80"></i>
                    </div>
                </div>
                
                <div class="summary-card bg-gradient-to-br from-purple-400 to-purple-600 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-lg font-semibold mb-2">Total Mahasiswa</h4>
                            <p class="text-3xl font-bold">{{ count($activityData) }}</p>
                            <p class="text-sm opacity-90">yang dibimbing</p>
                        </div>
                        <i class="fas fa-users fa-2x opacity-80"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Initialize Bootstrap tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    @endpush
</x-app-layout> 
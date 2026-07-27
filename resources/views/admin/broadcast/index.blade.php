<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Broadcast Pengumuman') }}
        </h2>
    </x-slot>

    @push('styles')
    <style>
        /* Custom Premium Aesthetics for Broadcast */
        .broadcast-banner {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #3b82f6 100%);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(30, 58, 138, 0.25);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .broadcast-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .banner-icon-wrapper {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            color: #fbbf24;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            border: 1px solid rgba(255,255,255,0.2);
            animation: floatIcon 3s ease-in-out infinite;
        }

        @keyframes floatIcon {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-8px) rotate(5deg); }
        }

        .card-premium {
            background: #ffffff;
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
            height: 100%;
        }

        .card-premium:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .card-header-premium {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 1.25rem 1.75rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-header-premium h5 {
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            font-size: 1.15rem;
        }

        /* Target Selection Cards */
        .target-option, .urgency-option {
            position: relative;
        }

        .target-option input[type="radio"], .urgency-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .target-card, .urgency-card {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.25s ease;
            background: #f8fafc;
            height: 100%;
        }

        .target-option input[type="radio"]:checked + .target-card {
            border-color: #3b82f6;
            background: rgba(59, 130, 246, 0.08);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }

        .urgency-option input[value="normal"]:checked + .urgency-card {
            border-color: #3b82f6;
            background: rgba(59, 130, 246, 0.08);
        }

        .urgency-option input[value="important"]:checked + .urgency-card {
            border-color: #f59e0b;
            background: rgba(245, 158, 11, 0.08);
        }

        .urgency-option input[value="urgent"]:checked + .urgency-card {
            border-color: #ef4444;
            background: rgba(239, 68, 68, 0.08);
            animation: pulseBorder 1.5s infinite;
        }

        @keyframes pulseBorder {
            0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            50% { box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
        }

        .target-icon, .urgency-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #e2e8f0;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            transition: all 0.25s ease;
        }

        .target-option input[type="radio"]:checked + .target-card .target-icon {
            background: #3b82f6;
            color: white;
            transform: scale(1.05);
        }

        .urgency-option input[value="normal"]:checked + .urgency-card .urgency-icon {
            background: #3b82f6; color: white;
        }
        .urgency-option input[value="important"]:checked + .urgency-card .urgency-icon {
            background: #f59e0b; color: white;
        }
        .urgency-option input[value="urgent"]:checked + .urgency-card .urgency-icon {
            background: #ef4444; color: white;
        }

        .target-info h6, .urgency-info h6 {
            margin: 0;
            font-weight: 700;
            color: #1e293b;
            font-size: 0.9rem;
        }

        .target-info p, .urgency-info p {
            margin: 0;
            font-size: 0.75rem;
            color: #64748b;
        }

        .form-control-premium, .form-select-premium {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control-premium:focus, .form-select-premium:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
            outline: none;
        }

        .btn-broadcast {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            border: none;
            border-radius: 14px;
            padding: 0.85rem 1.5rem;
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
        }

        .btn-broadcast:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            color: white;
        }

        .btn-broadcast:active {
            transform: translateY(0);
        }

        /* History Table Styling */
        .table-premium {
            margin: 0;
        }

        .table-premium thead th {
            background: #f8fafc;
            color: #64748b;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem 1.25rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .table-premium tbody tr {
            transition: background-color 0.2s ease;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-premium tbody tr:hover {
            background-color: #f8fafc;
        }

        .table-premium tbody td {
            padding: 1.25rem;
            vertical-align: middle;
            color: #334155;
            font-size: 0.95rem;
        }

        .badge-recipients {
            background: rgba(59, 130, 246, 0.1);
            color: #2563eb;
            font-weight: 700;
            padding: 0.5rem 0.85rem;
            border-radius: 30px;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .empty-history {
            text-align: center;
            padding: 4rem 2rem;
            color: #94a3b8;
        }

        .empty-history i {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            opacity: 0.4;
            color: #64748b;
        }

        .empty-history h6 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 0.25rem;
        }

        .image-preview-box {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            background: #f8fafc;
            max-height: 200px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .image-preview-box img {
            max-height: 170px;
            border-radius: 8px;
            object-fit: cover;
        }
    </style>
    @endpush

    <div class="container-fluid py-4">
        <!-- Banner -->
        <div class="broadcast-banner">
            <div>
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3 d-inline-flex align-items-center gap-2">
                    <i class="fas fa-bolt"></i> Real-time Push Notification & Rich Media
                </span>
                <h1 class="fw-bold mb-2 display-6 text-white">Broadcast Pengumuman</h1>
                <p class="mb-0 text-white-50 fs-6">Kirim pesan informasi penting, urgensi darurat, tautan aksi, dan gambar banner secara serentak ke HP/Desktop seluruh pengguna.</p>
            </div>
            <div class="banner-icon-wrapper d-none d-md-flex">
                <i class="fas fa-bullhorn"></i>
            </div>
        </div>

        @if($tahunAktif && $semesterAktif)
        <div class="alert alert-info rounded-3 p-3 mb-4 shadow-sm border-0 d-flex align-items-center" style="background: #eff6ff; color: #1e40af;">
            <i class="fas fa-sliders-h fs-4 me-3 text-primary"></i>
            <div>
                <strong>Filter Otomatis Latar Belakang Aktif:</strong> Broadcast secara otomatis dikirimkan kepada pengguna dan kelompok yang terdaftar pada <b>{{ $tahunAktif->nama }} ({{ $semesterAktif->nama }})</b> tanpa perlu memilih ulang tahun akademik.
            </div>
        </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 p-3 mb-4 shadow-sm border-0 d-flex align-items-center" role="alert" style="background: #ecfdf5; color: #065f46;">
                <i class="fas fa-check-circle fs-4 me-3 text-success"></i>
                <div>
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 p-3 mb-4 shadow-sm border-0 d-flex align-items-center" role="alert" style="background: #fef2f2; color: #991b1b;">
                <i class="fas fa-exclamation-triangle fs-4 me-3 text-danger"></i>
                <div>
                    <strong>Gagal!</strong> {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger rounded-4 p-3 mb-4 shadow-sm border-0" style="background: #fef2f2; color: #991b1b;">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4">
            <!-- Form Broadcast -->
            <div class="col-lg-5">
                <div class="card-premium">
                    <div class="card-header-premium">
                        <i class="fas fa-paper-plane text-primary fs-5"></i>
                        <h5>Buat & Kirim Broadcast</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="broadcastForm" action="{{ route('admin.broadcast.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- 1. Target Penerima -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark mb-2">1. Target Peran (Role)</label>
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="target-option d-block">
                                            <input type="radio" name="target" value="all" checked>
                                            <div class="target-card">
                                                <div class="target-icon"><i class="fas fa-users"></i></div>
                                                <div class="target-info">
                                                    <h6>Semua Pengguna</h6>
                                                    <p>Mahasiswa & DPL KKN</p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-6">
                                        <label class="target-option d-block">
                                            <input type="radio" name="target" value="mahasiswa">
                                            <div class="target-card">
                                                <div class="target-icon"><i class="fas fa-user-graduate"></i></div>
                                                <div class="target-info">
                                                    <h6>Mahasiswa</h6>
                                                    <p>Khusus Mhs</p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-6">
                                        <label class="target-option d-block">
                                            <input type="radio" name="target" value="dpl">
                                            <div class="target-card">
                                                <div class="target-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                                                <div class="target-info">
                                                    <h6>DPL</h6>
                                                    <p>Khusus Dosen</p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Filter Lanjutan -->
                            <div class="mb-3 p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                <label class="form-label fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                                    <i class="fas fa-filter text-primary"></i> 2. Filter Lanjutan & Pengingat Otomatis (Opsional)
                                </label>
                                <div class="mb-2">
                                    <small class="text-muted d-block mb-1">Filter Wilayah / Lokasi KKN:</small>
                                    <select class="form-select form-select-premium form-select-sm" name="lokasi_id">
                                        <option value="">Semua Wilayah / Lokasi</option>
                                        @foreach($lokasis as $lok)
                                            <option value="{{ $lok->id }}">{{ $lok->nama_desa }}, Kec. {{ $lok->nama_kecamatan }}, Kab. {{ $lok->nama_kabupaten }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <small class="text-muted d-block mb-1">Filter Status Aktivitas Mahasiswa:</small>
                                    <select class="form-select form-select-premium form-select-sm" name="status_aktivitas">
                                        <option value="">Semua Mahasiswa (Standar)</option>
                                        <option value="no_logbook_today">Belum Mengirim Logbook Hari Ini</option>
                                        <option value="no_absensi_today">Belum Melakukan Absensi Hari Ini</option>
                                    </select>
                                </div>
                            </div>

                            <!-- 3. Kategori & Urgensi -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark mb-2">3. Kategori & Level Urgensi</label>
                                <div class="mb-2">
                                    <select class="form-select form-select-premium" name="category" required>
                                        <option value="Akademik">Akademik & Regulasi</option>
                                        <option value="Monev">Monitoring & Evaluasi</option>
                                        <option value="Laporan">Laporan & Logbook</option>
                                        <option value="Jadwal">Jadwal & Kegiatan</option>
                                        <option value="Darurat">Darurat & Keamanan</option>
                                    </select>
                                </div>
                                <div class="row g-2">
                                    <div class="col-4">
                                        <label class="urgency-option d-block">
                                            <input type="radio" name="urgency" value="normal" checked>
                                            <div class="urgency-card p-2 text-center flex-column">
                                                <div class="urgency-icon mb-1 mx-auto"><i class="fas fa-info-circle"></i></div>
                                                <div class="urgency-info">
                                                    <h6 style="font-size: 0.8rem;">Normal</h6>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <label class="urgency-option d-block">
                                            <input type="radio" name="urgency" value="important">
                                            <div class="urgency-card p-2 text-center flex-column">
                                                <div class="urgency-icon mb-1 mx-auto"><i class="fas fa-exclamation-triangle"></i></div>
                                                <div class="urgency-info">
                                                    <h6 style="font-size: 0.8rem;">Penting</h6>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <label class="urgency-option d-block">
                                            <input type="radio" name="urgency" value="urgent">
                                            <div class="urgency-card p-2 text-center flex-column">
                                                <div class="urgency-icon mb-1 mx-auto"><i class="fas fa-bell"></i></div>
                                                <div class="urgency-info">
                                                    <h6 style="font-size: 0.8rem; color: #ef4444;">Darurat</h6>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. Judul Pengumuman -->
                            <div class="mb-3">
                                <label for="title" class="form-label fw-bold text-dark mb-1">4. Judul Pengumuman</label>
                                <input type="text" class="form-control form-control-premium" id="title" name="title" placeholder="Contoh: Pengumuman Jadwal Monev KKN 2026" required maxlength="255">
                            </div>

                            <!-- 5. Pesan Pengumuman -->
                            <div class="mb-3">
                                <label for="message" class="form-label fw-bold text-dark mb-1">5. Isi Pesan / Instruksi</label>
                                <textarea class="form-control form-control-premium" id="message" name="message" rows="4" placeholder="Tuliskan isi pengumuman secara jelas dan ringkas..." required></textarea>
                            </div>

                            <!-- 6. Interaktivitas & Rich Media -->
                            <div class="mb-4 p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                <label class="form-label fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                                    <i class="fas fa-link text-primary"></i> 6. Deep Link & Gambar Banner (Opsional)
                                </label>
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">Action URL (Tautan Tujuan Saat Notif Diklik):</small>
                                    <input type="url" class="form-control form-control-premium form-control-sm" name="action_url" placeholder="https://... atau biarkan kosong untuk standar">
                                </div>
                                <div>
                                    <small class="text-muted d-block mb-1">Unggah Gambar Banner (Format: JPG, PNG, WEBP, GIF | Max: 2MB | Rekomendasi: 1024x512 px):</small>
                                    <input type="file" class="form-control form-control-premium form-control-sm mb-2" id="banner_image" name="banner_image" accept=".jpg,.jpeg,.png,.webp,.gif,image/*" onchange="previewBannerImage(this)">
                                    <div id="imagePreviewContainer" class="image-preview-box" style="display: none;">
                                        <img id="bannerPreview" src="" alt="Preview Banner">
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Kirim -->
                            <button type="button" class="btn-broadcast" onclick="confirmBroadcast()">
                                <i class="fas fa-broadcast-tower"></i> Kirim Broadcast Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Riwayat Broadcast -->
            <div class="col-lg-7">
                <div class="card-premium d-flex flex-column">
                    <div class="card-header-premium justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-history text-primary fs-5"></i>
                            <h5>Riwayat Broadcast Terkirim</h5>
                        </div>
                        <span class="badge bg-secondary rounded-pill px-3 py-1">{{ $broadcasts->total() }} Pengumuman</span>
                    </div>
                    <div class="card-body p-0 flex-grow-1">
                        @if($broadcasts->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-premium w-100 mb-0">
                                    <thead>
                                        <tr>
                                            <th width="30%">Judul & Kategori</th>
                                            <th width="30%">Isi Pesan</th>
                                            <th width="12%" class="text-center">Penerima</th>
                                            <th width="13%" class="text-end">Waktu</th>
                                            <th width="15%" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($broadcasts as $broadcast)
                                            @php
                                                $dataObj = !empty($broadcast->data) ? (is_string($broadcast->data) ? json_decode($broadcast->data, true) : $broadcast->data) : [];
                                                $category = $dataObj['category'] ?? 'Akademik';
                                                $urgency = $dataObj['urgency'] ?? 'normal';
                                                $bannerImage = $dataObj['banner_image'] ?? null;
                                                $actionUrl = $dataObj['action_url'] ?? null;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="mb-1">
                                                        @if($urgency === 'urgent')
                                                            <span class="badge bg-danger mb-1"><i class="fas fa-exclamation-circle me-1"></i> Darurat</span>
                                                        @elseif($urgency === 'important')
                                                            <span class="badge bg-warning text-dark mb-1"><i class="fas fa-exclamation-triangle me-1"></i> Penting</span>
                                                        @endif
                                                        <span class="badge bg-secondary mb-1">{{ $category }}</span>
                                                    </div>
                                                    <div class="fw-bold text-dark mb-1">{{ $broadcast->title }}</div>
                                                    @if($bannerImage || $actionUrl)
                                                        <div class="d-flex gap-1 mt-1">
                                                            @if($bannerImage)
                                                                <span class="badge bg-light text-primary border" title="Ada Banner Image"><i class="fas fa-image"></i> Gambar</span>
                                                            @endif
                                                            @if($actionUrl)
                                                                <a href="{{ $actionUrl }}" target="_blank" class="badge bg-light text-success border text-decoration-none" title="Buka Action URL"><i class="fas fa-external-link-alt"></i> URL</a>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="text-muted small" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                                        {{ $broadcast->message }}
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge-recipients">
                                                        <i class="fas fa-user-check"></i>
                                                        {{ number_format($broadcast->total_recipients) }} User
                                                    </span>
                                                </td>
                                                <td class="text-end text-nowrap">
                                                    <span class="text-muted small" title="{{ $broadcast->created_at->format('d M Y, H:i') }}">
                                                        {{ $broadcast->created_at->diffForHumans() }}
                                                    </span>
                                                </td>
                                                <td class="text-center text-nowrap">
                                                    <button type="button" class="btn btn-sm btn-outline-info rounded-pill me-1" title="Lihat Detail"
                                                        data-id="{{ $broadcast->id }}"
                                                        data-title="{{ $broadcast->title }}"
                                                        data-message="{{ $broadcast->message }}"
                                                        data-category="{{ $category }}"
                                                        data-urgency="{{ $urgency }}"
                                                        data-banner="{{ $bannerImage ?? '' }}"
                                                        data-url="{{ $actionUrl ?? '' }}"
                                                        data-recipients="{{ number_format($broadcast->total_recipients) }}"
                                                        data-time="{{ $broadcast->created_at->format('d M Y, H:i') }} ({{ $broadcast->created_at->diffForHumans() }})"
                                                        onclick="showViewModal(this)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-warning rounded-pill me-1" title="Edit Broadcast"
                                                        data-id="{{ $broadcast->id }}"
                                                        data-title="{{ $broadcast->title }}"
                                                        data-message="{{ $broadcast->message }}"
                                                        data-category="{{ $category }}"
                                                        data-urgency="{{ $urgency }}"
                                                        data-banner="{{ $bannerImage ?? '' }}"
                                                        data-url="{{ $actionUrl ?? '' }}"
                                                        onclick="showEditModal(this)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <form action="{{ route('admin.broadcast.destroy', $broadcast->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat broadcast ini dari seluruh penerima?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" title="Hapus Broadcast">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-3 border-top d-flex justify-content-center">
                                {{ $broadcasts->links() }}
                            </div>
                        @else
                            <div class="empty-history">
                                <i class="fas fa-bell-slash"></i>
                                <h6>Belum Ada Broadcast Terkirim</h6>
                                <p class="text-muted mb-0 small">Pengumuman yang Anda kirim akan muncul dalam daftar riwayat ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal View Broadcast -->
    <div class="modal fade" id="viewBroadcastModal" tabindex="-1" aria-labelledby="viewBroadcastModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header bg-light border-bottom p-4">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="viewBroadcastModalLabel">
                        <i class="fas fa-bullhorn text-primary"></i> Detail Riwayat Broadcast
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span id="view_urgency" class="badge bg-secondary"></span>
                        <span id="view_category" class="badge bg-light text-dark border"></span>
                        <span id="view_recipients" class="badge bg-primary"></span>
                        <span id="view_time" class="badge bg-light text-muted border"></span>
                    </div>
                    <h4 id="view_title" class="fw-bold text-dark mb-3"></h4>
                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <small class="text-muted d-block mb-1 fw-bold">Isi Pesan:</small>
                        <div id="view_message" class="text-dark" style="white-space: pre-wrap; font-size: 0.95rem;"></div>
                    </div>
                    <div id="view_banner_container" class="mb-3 text-center" style="display: none;">
                        <small class="text-muted d-block mb-2 text-start fw-bold">Gambar Banner:</small>
                        <a id="view_banner_link" href="#" target="_blank">
                            <img id="view_banner_img" src="" alt="Banner Broadcast" class="img-fluid rounded-3 border shadow-sm" style="max-height: 250px;">
                        </a>
                    </div>
                    <div id="view_url_container" class="mb-0" style="display: none;">
                        <small class="text-muted d-block mb-1 fw-bold">Action URL:</small>
                        <a id="view_url_link" href="#" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-2">
                            <i class="fas fa-external-link-alt"></i> <span id="view_url_text"></span>
                        </a>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top p-3">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Broadcast -->
    <div class="modal fade" id="editBroadcastModal" tabindex="-1" aria-labelledby="editBroadcastModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form id="editBroadcastForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-light border-bottom p-4">
                        <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="editBroadcastModalLabel">
                            <i class="fas fa-edit text-warning"></i> Edit Riwayat Broadcast
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-warning py-2 px-3 rounded-3 small mb-3">
                            <i class="fas fa-info-circle me-1"></i> Perubahan ini akan memperbarui judul dan pesan pada seluruh pengguna yang menerima broadcast ini.
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">Judul Pengumuman <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-premium form-control-sm" id="edit_title" name="title" required>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-bold small text-dark">Kategori</label>
                                <select class="form-select form-select-premium form-select-sm" id="edit_category" name="category">
                                    <option value="Akademik">Akademik & Regulasi</option>
                                    <option value="Monev">Monitoring & Evaluasi</option>
                                    <option value="Laporan">Laporan & Logbook</option>
                                    <option value="Jadwal">Jadwal & Kegiatan</option>
                                    <option value="Darurat">Darurat & Keamanan</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold small text-dark">Urgensi</label>
                                <select class="form-select form-select-premium form-select-sm" id="edit_urgency" name="urgency">
                                    <option value="normal">Normal</option>
                                    <option value="important">Penting</option>
                                    <option value="urgent">Darurat</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">Isi Pesan <span class="text-danger">*</span></label>
                            <textarea class="form-control form-control-premium form-control-sm" id="edit_message" name="message" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">Action URL (Opsional)</label>
                            <input type="url" class="form-control form-control-premium form-control-sm" id="edit_action_url" name="action_url" placeholder="https://...">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold small text-dark">Ganti Gambar Banner (Opsional | Max 2MB)</label>
                            <input type="file" class="form-control form-control-premium form-control-sm mb-2" name="banner_image" accept=".jpg,.jpeg,.png,.webp,.gif,image/*">
                            <div id="edit_banner_preview_box" class="mt-2 text-center" style="display: none;">
                                <small class="text-muted d-block mb-1 small text-start">Gambar Saat Ini:</small>
                                <img id="edit_banner_preview_img" src="" alt="Banner Saat Ini" class="img-thumbnail rounded-3" style="max-height: 80px;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top p-3">
                        <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning btn-sm rounded-pill px-4 fw-bold"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function previewBannerImage(input) {
            const previewContainer = document.getElementById('imagePreviewContainer');
            const previewImg = document.getElementById('bannerPreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.style.display = 'flex';
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                previewContainer.style.display = 'none';
                previewImg.src = '';
            }
        }

        function confirmBroadcast() {
            const form = document.getElementById('broadcastForm');
            const title = document.getElementById('title').value.trim();
            const message = document.getElementById('message').value.trim();
            const targetRadio = document.querySelector('input[name="target"]:checked');
            const urgencyRadio = document.querySelector('input[name="urgency"]:checked');
            const categorySelect = document.querySelector('select[name="category"]');
            const categoryText = categorySelect.options[categorySelect.selectedIndex].text;
            
            if (!title || !message) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Form Belum Lengkap!',
                    text: 'Harap isi judul dan isi pesan pengumuman sebelum mengirim broadcast.',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }

            let targetText = 'Semua Pengguna';
            if (targetRadio.value === 'mahasiswa') targetText = 'Khusus Mahasiswa';
            if (targetRadio.value === 'dpl') targetText = 'Khusus DPL';

            let urgencyBadge = '<span class="badge bg-info px-3 py-1">Normal</span>';
            if (urgencyRadio.value === 'important') urgencyBadge = '<span class="badge bg-warning text-dark px-3 py-1">Penting</span>';
            if (urgencyRadio.value === 'urgent') urgencyBadge = '<span class="badge bg-danger px-3 py-1">Darurat (High Priority)</span>';

            Swal.fire({
                title: 'Konfirmasi Kirim Broadcast',
                html: `Anda akan mengirim pengumuman <b>"${title}"</b> ke target:<br><br>
                       <div class="d-flex justify-content-center gap-2 mb-2">
                           <span class="badge bg-primary px-3 py-1">${targetText}</span>
                           <span class="badge bg-secondary px-3 py-1">${categoryText}</span>
                           ${urgencyBadge}
                       </div>
                       <small class="text-muted">Pengumuman akan otomatis terfilter untuk <b>Tahun Akademik Aktif</b> dan langsung dikirim ke HP & Desktop penerima via Firebase Web Push!</small>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fas fa-paper-plane me-2"></i> Ya, Kirim Sekarang!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mengirim Broadcast...',
                        text: 'Mohon tunggu, sistem sedang mendistribusikan notifikasi ke pengguna.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    form.submit();
                }
            });
        }

        function showViewModal(btn) {
            document.getElementById('view_title').textContent = btn.dataset.title;
            document.getElementById('view_message').textContent = btn.dataset.message;
            
            const urgencyEl = document.getElementById('view_urgency');
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

            document.getElementById('view_category').textContent = btn.dataset.category || 'Akademik';
            document.getElementById('view_recipients').innerHTML = '<i class="fas fa-users me-1"></i>' + btn.dataset.recipients + ' Penerima';
            document.getElementById('view_time').innerHTML = '<i class="fas fa-clock me-1"></i>' + btn.dataset.time;

            const bannerContainer = document.getElementById('view_banner_container');
            const bannerImg = document.getElementById('view_banner_img');
            const bannerLink = document.getElementById('view_banner_link');
            if (btn.dataset.banner && btn.dataset.banner !== '') {
                bannerImg.src = btn.dataset.banner;
                bannerLink.href = btn.dataset.banner;
                bannerContainer.style.display = 'block';
            } else {
                bannerContainer.style.display = 'none';
            }

            const urlContainer = document.getElementById('view_url_container');
            const urlLink = document.getElementById('view_url_link');
            const urlText = document.getElementById('view_url_text');
            if (btn.dataset.url && btn.dataset.url !== '') {
                urlLink.href = btn.dataset.url;
                urlText.textContent = btn.dataset.url;
                urlContainer.style.display = 'block';
            } else {
                urlContainer.style.display = 'none';
            }

            new bootstrap.Modal(document.getElementById('viewBroadcastModal')).show();
        }

        function showEditModal(btn) {
            const form = document.getElementById('editBroadcastForm');
            form.action = '/admin/broadcast/' + btn.dataset.id;
            
            document.getElementById('edit_title').value = btn.dataset.title || '';
            document.getElementById('edit_message').value = btn.dataset.message || '';
            document.getElementById('edit_category').value = btn.dataset.category || 'Akademik';
            document.getElementById('edit_urgency').value = btn.dataset.urgency || 'normal';
            document.getElementById('edit_action_url').value = (btn.dataset.url && btn.dataset.url !== '') ? btn.dataset.url : '';

            const bannerBox = document.getElementById('edit_banner_preview_box');
            const bannerImg = document.getElementById('edit_banner_preview_img');
            if (btn.dataset.banner && btn.dataset.banner !== '') {
                bannerImg.src = btn.dataset.banner;
                bannerBox.style.display = 'block';
            } else {
                bannerBox.style.display = 'none';
            }

            new bootstrap.Modal(document.getElementById('editBroadcastModal')).show();
        }
    </script>
    @endpush
</x-app-layout>

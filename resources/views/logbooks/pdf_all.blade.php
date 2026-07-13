<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Semua Logbook KKN - {{ ucfirst($tipe) }}</title>
    <style>
        @page {
            margin: 140px 40px 100px 40px;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #000;
        }
        header {
            position: fixed;
            top: -120px;
            left: 0px;
            right: 0px;
            height: 100px;
        }
        footer {
            position: fixed;
            bottom: -80px;
            left: 0px;
            right: 0px;
            height: 70px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: middle;
            padding: 0;
        }
        .logo-cell {
            width: 15%;
            text-align: center;
        }
        .logo {
            max-width: 90px;
            height: auto;
        }
        .text-cell {
            width: 85%;
            text-align: center;
        }
        .univ-name {
            font-size: 20pt;
            font-weight: bold;
            margin: 0;
            padding: 0;
            letter-spacing: 1px;
        }
        .univ-motto {
            font-size: 11pt;
            margin: 0;
            padding: 0;
        }
        .univ-address {
            font-size: 10pt;
            margin: 0;
            padding: 0;
        }
        .header-line {
            margin-top: 10px;
            border-top: 3px solid #000;
            border-bottom: 1px solid #000;
            height: 2px;
        }
        
        .footer-blue-strip {
            background-color: #002b5e;
            color: #fff;
            text-align: center;
            font-weight: bold;
            font-style: italic;
            font-size: 10pt;
            padding: 5px 0;
            margin-bottom: 5px;
        }
        .footer-prodi {
            text-align: center;
            font-size: 8.5pt;
            line-height: 1.3;
        }
        
        /* Content Styles */
        .page-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin-top: 0;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 4px;
            vertical-align: top;
        }
        .label-col {
            width: 25%;
            font-weight: bold;
        }
        .colon-col {
            width: 3%;
        }
        .val-col {
            width: 72%;
        }
        .description-box {
            border: 1px solid #ccc;
            padding: 10px;
            min-height: 100px;
            margin-bottom: 20px;
            background-color: #fcfcfc;
        }
        .photos-container {
            margin-top: 20px;
            text-align: center;
        }
        .photo-item {
            display: inline-block;
            margin: 5px;
            width: 45%;
            text-align: center;
            vertical-align: top;
        }
        .photo-item img {
            max-width: 100%;
            max-height: 250px;
            width: auto;
            height: auto;
            border: 1px solid #ddd;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <header>
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" class="logo" alt="Logo USH">
                    @endif
                </td>
                <td class="text-cell">
                    <h1 class="univ-name">UNIVERSITAS SUGENG HARTONO</h1>
                    <p class="univ-motto">ADAPTIF - KREATIF - MANDIRI</p>
                    <p class="univ-address">Jl. Ir. Soekarno, Madegondo, Grogol, Sukoharjo, 57552 | Telp. +62 811-2674-670</p>
                    <p class="univ-address">Website: www.sugenghartono.ac.id | Email: ush@sugenghartono.ac.id</p>
                </td>
            </tr>
        </table>
        <div class="header-line"></div>
    </header>

    <footer>
        <div class="footer-blue-strip">
            BUILDING THE NATION THROUGH EDUCATION
        </div>
        <div class="footer-prodi">
            S1 Informatika | S1 Bisnis Digital | S1 Hukum Bisnis | S1 Manajemen Bisnis Internasional<br>
            S1 Teknologi Pangan | S1 Gizi | S1 Bahasa dan Kebudayaan Inggris | S1 Pariwisata | D4 Akuntansi Bisnis Digital
        </div>
    </footer>

    <main>
        @foreach($logbooks as $index => $logbook)
            <div class="page-title">
                Logbook Kegiatan KKN<br>
                @if($logbook->is_kelompok)
                    (Kegiatan Kelompok)
                @else
                    (Kegiatan Individu)
                @endif
            </div>

            <table class="info-table">
                <tr>
                    <td class="label-col">Nama / Kelompok</td>
                    <td class="colon-col">:</td>
                    <td class="val-col">
                        {{ $logbook->user->name }} 
                        @if($logbook->kelompok)
                            ({{ $logbook->kelompok->nama_kelompok }})
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label-col">NIM / NIP</td>
                    <td class="colon-col">:</td>
                    <td class="val-col">{{ $logbook->user->nim ?? $logbook->user->nip ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">Judul Kegiatan</td>
                    <td class="colon-col">:</td>
                    <td class="val-col">{{ $logbook->judul }}</td>
                </tr>
                <tr>
                    <td class="label-col">Tanggal Kegiatan</td>
                    <td class="colon-col">:</td>
                    <td class="val-col">{{ \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('l, d F Y') }}</td>
                </tr>
                <tr>
                    <td class="label-col">Waktu Pelaksanaan</td>
                    <td class="colon-col">:</td>
                    <td class="val-col">{{ date('H:i', strtotime($logbook->waktu_mulai)) }} - {{ date('H:i', strtotime($logbook->waktu_selesai)) }}</td>
                </tr>
                <tr>
                    <td class="label-col">Jenis Kegiatan</td>
                    <td class="colon-col">:</td>
                    <td class="val-col">{{ ucfirst($logbook->jenis) }}</td>
                </tr>
                <tr>
                    <td class="label-col">Lokasi</td>
                    <td class="colon-col">:</td>
                    <td class="val-col">{{ $logbook->lokasi }}</td>
                </tr>
                <tr>
                    <td class="label-col">Status Laporan</td>
                    <td class="colon-col">:</td>
                    <td class="val-col">
                        @if($logbook->status == 'approved') Disetujui DPL
                        @elseif($logbook->status == 'rejected') Ditolak DPL
                        @elseif($logbook->status == 'submitted') Menunggu Persetujuan
                        @else Draft
                        @endif
                    </td>
                </tr>
            </table>

            <div style="font-weight: bold; margin-bottom: 5px;">Keterangan Kegiatan:</div>
            <div class="description-box">
                {!! nl2br(e($logbook->keterangan)) !!}
            </div>

            @if($logbook->photos && $logbook->photos->count() > 0)
            <div style="font-weight: bold; margin-bottom: 5px; margin-top: 20px;">Foto Dokumentasi:</div>
            <div class="photos-container">
                @foreach($logbook->photos as $photo)
                    @php
                    $photoPath = \Illuminate\Support\Facades\Storage::disk('public')->path($photo->path);
                    $photoBase64 = '';
                    if (file_exists($photoPath)) {
                        $ext = pathinfo($photoPath, PATHINFO_EXTENSION);
                        $photoData = file_get_contents($photoPath);
                        $photoBase64 = 'data:image/' . $ext . ';base64,' . base64_encode($photoData);
                    }
                @endphp
                    @if($photoBase64)
                        <div class="photo-item">
                            <img src="{{ $photoBase64 }}" alt="Dokumentasi">
                        </div>
                    @endif
                @endforeach
            </div>
            @endif

            @if(!$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Raport — {{ $santri->nisn ?? '-' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            line-height: 1.5;
        }
        /* ---- KOP SURAT ---- */
        .kop {
            display: table;
            width: 100%;
            border-bottom: 3px double #1B6B3A;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .kop-logo  { display: table-cell; width: 70px; vertical-align: middle; text-align: center; }
        .kop-logo img { width: 60px; height: 60px; border-radius: 50%; }
        .kop-text  { display: table-cell; vertical-align: middle; text-align: center; }
        .kop-text h1 { font-size: 17px; font-weight: 900; text-transform: uppercase; color: #1B6B3A; letter-spacing: .5px; }
        .kop-text h2 { font-size: 13px; font-weight: 700; margin-top: 2px; }
        .kop-text p  { font-size: 10px; color: #555; margin-top: 2px; }

        /* ---- JUDUL ---- */
        .judul {
            text-align: center;
            font-size: 14px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #1B6B3A;
            margin: 10px 0 4px;
            border-top: 1px solid #1B6B3A;
            border-bottom: 1px solid #1B6B3A;
            padding: 5px 0;
        }
        .subjudul {
            text-align: center;
            font-size: 11px;
            color: #555;
            margin-bottom: 12px;
        }

        /* ---- INFO SANTRI ---- */
        .info-table { width: 100%; margin-bottom: 14px; border-collapse: collapse; }
        .info-table td { padding: 3px 6px; font-size: 11px; }
        .info-table .lbl { font-weight: bold; width: 130px; color: #333; }
        .info-table .sep { width: 10px; }

        /* ---- TABEL NILAI ---- */
        .nilai-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; font-size: 10.5px; }
        .nilai-table th {
            background-color: #1B6B3A;
            color: white;
            padding: 6px 5px;
            text-align: center;
            border: 1px solid #155c2f;
            font-size: 10px;
        }
        .nilai-table td {
            border: 1px solid #ccc;
            padding: 5px 6px;
            text-align: center;
            vertical-align: middle;
        }
        .nilai-table td.text-left { text-align: left; }
        .nilai-table tr:nth-child(even) td { background: #f9f9f9; }
        .nilai-table tfoot td {
            background: #f0fdf4;
            font-weight: bold;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        /* ---- ABSENSI + CATATAN ---- */
        .bottom-section { width: 100%; display: table; margin-bottom: 14px; }
        .bottom-left  { display: table-cell; width: 45%; vertical-align: top; padding-right: 10px; }
        .bottom-right { display: table-cell; width: 55%; vertical-align: top; }
        .absensi-table { width: 100%; border-collapse: collapse; font-size: 10.5px; }
        .absensi-table th {
            background: #1B6B3A; color: white;
            padding: 5px; text-align: center; border: 1px solid #155c2f;
        }
        .absensi-table td { border: 1px solid #ccc; padding: 4px 6px; }
        .catatan-box {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 8px;
            min-height: 70px;
            font-size: 10.5px;
            color: #333;
        }

        /* ---- TTD ---- */
        .ttd-table { width: 100%; border-collapse: collapse; margin-top: 24px; page-break-inside: avoid; }
        .ttd-table td { width: 33%; text-align: center; vertical-align: bottom; padding: 6px; font-size: 10.5px; }
        .ttd-name { font-weight: bold; border-top: 1px solid #333; padding-top: 4px; margin-top: 50px; }

        /* ---- PREDIKAT ---- */
        .predikat {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 10px;
        }
        .grade-a  { background:#d1fae5; color:#065f46; }
        .grade-b  { background:#dbeafe; color:#1e40af; }
        .grade-c  { background:#fef3c7; color:#92400e; }
        .grade-d  { background:#fee2e2; color:#991b1b; }
    </style>
</head>
<body>

{{-- KOP SURAT --}}
<div class="kop">
    <div class="kop-logo">
        @if(!empty($pengaturan->logo) && file_exists(public_path('storage/' . $pengaturan->logo)))
            <img src="{{ public_path('storage/' . $pengaturan->logo) }}" alt="Logo">
        @else
            <img src="{{ public_path('images/logo.png') }}" alt="Logo">
        @endif
    </div>
    <div class="kop-text">
        <h1>{{ $pengaturan->nama_pondok ?? 'PONDOK PESANTREN SUBULUSSALAM' }}</h1>
        <h2>LAPORAN HASIL BELAJAR SANTRI</h2>
        <p>{{ $pengaturan->alamat ?? 'Kabupaten Tangerang, Banten' }}</p>
        <p>
            @if($pengaturan->email ?? null)Email: {{ $pengaturan->email }} &nbsp;|&nbsp;@endif
            Telp: {{ $pengaturan->telepon ?? '-' }}
        </p>
    </div>
</div>

<div class="judul">RAPORT SANTRI</div>
<div class="subjudul">
    Tahun Ajaran {{ $tahunAjaran->nama ?? '-' }} &mdash; Semester {{ ucfirst($tahunAjaran->semester ?? '-') }}
</div>

{{-- INFO SANTRI --}}
<table class="info-table">
    <tr>
        <td class="lbl">Nama Santri</td>
        <td class="sep">:</td>
        <td><strong>{{ $santri->nama ?? $santri->user?->name ?? '-' }}</strong></td>
        <td class="lbl">Tahun Ajaran</td>
        <td class="sep">:</td>
        <td>{{ $tahunAjaran->nama ?? '-' }}</td>
    </tr>
    <tr>
        <td class="lbl">NISN</td>
        <td class="sep">:</td>
        <td>{{ $santri->nisn ?? '-' }}</td>
        <td class="lbl">Semester</td>
        <td class="sep">:</td>
        <td>{{ ucfirst($tahunAjaran->semester ?? '-') }}</td>
    </tr>
    <tr>
        <td class="lbl">Kelas / Kamar</td>
        <td class="sep">:</td>
        <td>{{ $santri->kelas?->nama ?? '-' }}</td>
        <td class="lbl">Peringkat Kelas</td>
        <td class="sep">:</td>
        <td>{{ $raport->peringkat ?? '-' }} dari {{ $raport->jumlah_siswa ?? '-' }} Santri</td>
    </tr>
    <tr>
        <td class="lbl">Wali Santri</td>
        <td class="sep">:</td>
        <td>{{ $santri->waliSantri?->nama ?? '-' }}</td>
        <td class="lbl">Predikat Akhir</td>
        <td class="sep">:</td>
        <td><strong>{{ $raport->predikat_akhir ?? '-' }}</strong></td>
    </tr>
</table>

{{-- TABEL NILAI --}}
<table class="nilai-table">
    <thead>
        <tr>
            <th style="width:4%">No</th>
            <th style="width:28%;text-align:left;">Mata Pelajaran</th>
            <th style="width:8%">Harian</th>
            <th style="width:8%">Tugas</th>
            <th style="width:8%">UTS</th>
            <th style="width:8%">UAS</th>
            <th style="width:8%">Hafalan</th>
            <th style="width:7%">Adab</th>
            <th style="width:9%">Nilai Akhir</th>
            <th style="width:10%">Predikat</th>
        </tr>
    </thead>
    <tbody>
        @forelse($raportDetails as $i => $detail)
            @php
                $na = $detail->nilai_akhir ?? 0;
                $predClass = $na >= 80 ? 'grade-a' : ($na >= 70 ? 'grade-b' : ($na >= 60 ? 'grade-c' : 'grade-d'));
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td class="text-left">{{ $detail->mapel?->nama ?? '-' }}</td>
                <td>{{ $detail->nilai_harian ?? '-' }}</td>
                <td>{{ $detail->nilai_tugas ?? '-' }}</td>
                <td>{{ $detail->nilai_uts ?? '-' }}</td>
                <td>{{ $detail->nilai_uas ?? '-' }}</td>
                <td>{{ $detail->nilai_hafalan ?? '-' }}</td>
                <td>{{ $detail->nilai_adab ?? '-' }}</td>
                <td><strong>{{ number_format($na, 1) }}</strong></td>
                <td>
                    <span class="predikat {{ $predClass }}">
                        {{ $detail->predikat ?? \App\Models\Nilai::hitungPredikat($na) }}
                    </span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" style="text-align:center;color:#999;font-style:italic;">
                    Data nilai belum tersedia
                </td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="8" style="text-align:right;">Rata-rata Nilai Keseluruhan</td>
            <td><strong>{{ number_format($raport->rata_rata ?? 0, 1) }}</strong></td>
            <td><strong>{{ $raport->predikat_akhir ?? '-' }}</strong></td>
        </tr>
    </tfoot>
</table>

{{-- ABSENSI + CATATAN --}}
<div class="bottom-section">
    <div class="bottom-left">
        <table class="absensi-table">
            <thead>
                <tr><th colspan="2">Rekap Kehadiran</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>Hadir</td>
                    <td style="text-align:center;font-weight:bold;">{{ $raport->hadir ?? 0 }} hari</td>
                </tr>
                <tr>
                    <td>Sakit</td>
                    <td style="text-align:center;font-weight:bold;">{{ $raport->sakit ?? 0 }} hari</td>
                </tr>
                <tr>
                    <td>Izin</td>
                    <td style="text-align:center;font-weight:bold;">{{ $raport->izin ?? 0 }} hari</td>
                </tr>
                <tr>
                    <td>Tanpa Keterangan (Alfa)</td>
                    <td style="text-align:center;font-weight:bold;color:#991b1b;">{{ $raport->alfa ?? 0 }} hari</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="bottom-right">
        <div style="font-weight:bold;margin-bottom:5px;font-size:10.5px;">Catatan Wali Kelas:</div>
        <div class="catatan-box">
            {{ $raport->catatan_wali_kelas ?? 'Tetap semangat belajar dan tingkatkan terus prestasi. Semoga Allah memberkahi ilmu yang dipelajari.' }}
        </div>
    </div>
</div>

{{-- TANDA TANGAN --}}
<table class="ttd-table">
    <tr>
        <td>
            Mengetahui,<br>
            <strong>Orang Tua / Wali Santri</strong>
            <div class="ttd-name">
                {{ $santri->waliSantri?->nama ?? '(...................................)' }}
            </div>
        </td>
        <td>
            {{ $pengaturan->kota ?? 'Tangerang' }}, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}<br>
            <strong>Mudir / Pimpinan Pondok</strong>
            <div class="ttd-name">
                {{ $pengaturan->kepala_pondok ?? '(...................................)' }}
            </div>
        </td>
        <td>
            &nbsp;<br>
            <strong>Wali Kelas / Ustadz</strong>
            <div class="ttd-name">
                {{ $santri->kelas?->wali_kelas ?? '(...................................)' }}
            </div>
        </td>
    </tr>
</table>

</body>
</html>

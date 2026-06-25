<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Raport - {{ $santri->nama }}</title>
    <style>
        @page {
            margin: 1.5cm 1.5cm 1.5cm 1.5cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.3;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .w-100 { width: 100%; }
        
        .header-table {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo {
            width: 80px;
            height: auto;
        }
        .school-name {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .school-address {
            font-size: 10pt;
        }
        
        .title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
        }
        
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            vertical-align: top;
            padding: 2px 5px;
        }
        .info-table .label {
            width: 120px;
        }
        .info-table .colon {
            width: 10px;
        }
        
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 5px 8px;
        }
        table.data-table th {
            background-color: #e0e0e0;
            text-align: center;
            font-weight: bold;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .signature-table {
            width: 100%;
            margin-top: 30px;
        }
        .signature-table td {
            text-align: center;
            vertical-align: top;
            width: 33%;
        }
        .signature-space {
            height: 70px;
        }
        .underline {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td width="15%" class="text-center">
                <!-- Using absolute public path or base64 is better for dompdf, but we use storage link or base64 if possible. For now we use the asset or absolute path -->
                @if($pengaturan && $pengaturan->logo)
                    <img src="{{ public_path('storage/' . $pengaturan->logo) }}" class="logo" alt="Logo">
                @else
                    <!-- Fallback logo if not uploaded -->
                    <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo" onerror="this.style.display='none'">
                @endif
            </td>
            <td width="85%" class="text-center">
                <div class="school-name">{{ $pengaturan->nama_pondok ?? 'PONDOK PESANTREN' }}</div>
                <div class="school-address">
                    {{ $pengaturan->alamat ?? 'Alamat' }}, {{ $pengaturan->kecamatan ?? '' }}, {{ $pengaturan->kabupaten ?? '' }}<br>
                    Telp: {{ $pengaturan->telepon ?? '-' }} | Email: {{ $pengaturan->email ?? '-' }}
                </div>
            </td>
        </tr>
    </table>

    <div class="title">
        CAPAIAN HASIL BELAJAR SANTRI<br>
        (RAPORT)
    </div>

    <!-- Data Santri & Madrasah -->
    <table class="info-table">
        <tr>
            <td class="label">Nama Santri</td>
            <td class="colon">:</td>
            <td class="font-bold">{{ $santri->nama }}</td>
            
            <td class="label">Kelas / Fase</td>
            <td class="colon">:</td>
            <td class="font-bold">{{ $santri->kelas->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">NIS / NISN</td>
            <td class="colon">:</td>
            <td>{{ $santri->nisn ?? '-' }}</td>
            
            <td class="label">Semester</td>
            <td class="colon">:</td>
            <td>{{ ucfirst($tahunAjaran->semester ?? '-') }}</td>
        </tr>
        <tr>
            <td class="label">Nama Madrasah</td>
            <td class="colon">:</td>
            <td>{{ $pengaturan->nama_pondok ?? '-' }}</td>
            
            <td class="label">Tahun Ajaran</td>
            <td class="colon">:</td>
            <td>{{ $tahunAjaran->nama ?? '-' }}</td>
        </tr>
    </table>

    <!-- Tabel Nilai -->
    <div class="font-bold mb-2">A. Sikap dan Capaian Kompetensi</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Mata Pelajaran</th>
                <th width="10%">Nilai Akhir</th>
                <th width="10%">Predikat</th>
                <th width="40%">Deskripsi Capaian Kompetensi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($raportDetails as $index => $detail)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $detail->mapel->nama ?? '-' }}</td>
                <td class="text-center font-bold">{{ $detail->nilai_akhir }}</td>
                <td class="text-center font-bold">{{ $detail->predikat }}</td>
                <td style="font-size: 10pt;">
                    {{ $detail->catatan ?? 'Menunjukkan capaian yang ' . ($detail->predikat == 'A' ? 'sangat baik' : ($detail->predikat == 'B' ? 'baik' : 'cukup')) . ' dalam memahami materi pelajaran.' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada data nilai</td>
            </tr>
            @endforelse
        </tbody>
        @if($raportDetails->isNotEmpty())
        <tfoot>
            <tr>
                <td colspan="2" class="text-center font-bold">Total Nilai</td>
                <td class="text-center font-bold">{{ $raportDetails->sum('nilai_akhir') }}</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2" class="text-center font-bold">Rata-rata</td>
                <td class="text-center font-bold">{{ $raport->rata_rata }}</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2" class="text-center font-bold">Peringkat Kelas</td>
                <td class="text-center font-bold">{{ $raport->peringkat }} dari {{ $raport->jumlah_siswa }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <!-- Ekstrakurikuler -->
    <div class="font-bold mb-2">B. Ekstrakurikuler</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="45%">Kegiatan Ekstrakurikuler</th>
                <th width="15%">Nilai</th>
                <th width="35%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <!-- Dummy data karena tabel ekstrakurikuler belum ada di db -->
            <tr>
                <td class="text-center">1</td>
                <td>Pramuka</td>
                <td class="text-center">Baik</td>
                <td>Aktif mengikuti kegiatan mingguan</td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td>Muhadharah</td>
                <td class="text-center">Sangat Baik</td>
                <td>Memiliki kemampuan berpidato yang baik</td>
            </tr>
        </tbody>
    </table>

    <!-- Prestasi -->
    <div class="font-bold mb-2">C. Prestasi</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="45%">Jenis Prestasi</th>
                <th width="50%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <!-- Dummy data -->
            <tr>
                <td colspan="3" class="text-center">-</td>
            </tr>
        </tbody>
    </table>

    <!-- Ketidakhadiran & Catatan -->
    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td width="48%" style="vertical-align: top;">
                <div class="font-bold mb-2">D. Ketidakhadiran</div>
                <table class="data-table" style="margin-bottom: 0;">
                    <tr>
                        <td width="60%">Sakit</td>
                        <td width="40%" class="text-center">{{ $raport->sakit }} hari</td>
                    </tr>
                    <tr>
                        <td>Izin</td>
                        <td class="text-center">{{ $raport->izin }} hari</td>
                    </tr>
                    <tr>
                        <td>Tanpa Keterangan (Alpa)</td>
                        <td class="text-center">{{ $raport->alfa }} hari</td>
                    </tr>
                </table>
            </td>
            <td width="4%"></td>
            <td width="48%" style="vertical-align: top;">
                <div class="font-bold mb-2">E. Catatan Wali Kelas</div>
                <div style="border: 1px solid #000; padding: 10px; min-height: 80px; font-style: italic;">
                    {{ $raport->catatan_wali_kelas ?? 'Tingkatkan terus belajarmu dan pertahankan prestasimu.' }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Tanggapan Orang Tua -->
    <div class="font-bold mb-2">F. Tanggapan Orang Tua / Wali</div>
    <div style="border: 1px solid #000; padding: 10px; min-height: 60px;">
        <br><br><br>
    </div>

    <!-- Tanda Tangan -->
    <table class="signature-table">
        <tr>
            <td>
                Mengetahui,<br>
                Orang Tua / Wali
                <div class="signature-space"></div>
                (.............................................)
            </td>
            <td>
                <br>
                Kepala {{ $pengaturan->nama_pondok ?? 'Madrasah' }}
                <div class="signature-space"></div>
                <span class="font-bold underline">{{ $pengaturan->kepala_pondok ?? '___________________' }}</span><br>
                NIP. {{ $pengaturan->nip_kepala ?? '-' }}
            </td>
            <td>
                Diberikan di: {{ $pengaturan->kabupaten ?? '........' }}<br>
                Tanggal: {{ date('d F Y') }}<br>
                Wali Kelas
                <div class="signature-space"></div>
                <span class="font-bold underline">{{ $santri->kelas->waliKelas->nama_lengkap ?? '___________________' }}</span><br>
                NIP. {{ $santri->kelas->waliKelas->nip ?? '-' }}
            </td>
        </tr>
    </table>

</body>
</html>

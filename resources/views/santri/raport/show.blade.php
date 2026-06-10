@extends('layouts.app')
@section('title', 'Detail Raport')
@section('page-title', 'Detail Hasil Belajar')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('santri.raport.index') }}">Raport</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12 text-end mb-3">
        <a href="{{ route('santri.raport.index') }}" class="btn btn-secondary me-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('santri.raport.download', $raport->id) }}" class="btn btn-success">
            <i class="bi bi-download"></i> Unduh PDF
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <!-- Kop Surat / Header -->
        <div class="text-center pb-4 mb-4 border-bottom">
            <h4 class="fw-bold mb-1 text-primary text-uppercase">{{ $pondok->nama_pondok ?? 'PONDOK PESANTREN SUBULUSSALAM' }}</h4>
            <h5 class="fw-bold mb-2">LAPORAN HASIL BELAJAR SANTRI (RAPORT)</h5>
            <p class="text-muted small mb-0">{{ $pondok->alamat ?? 'Kabupaten Tangerang, Banten' }}</p>
        </div>

        <!-- Info Santri -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="fw-semibold text-muted" style="width: 150px;">Nama Santri</td>
                        <td style="width: 10px;">:</td>
                        <td class="fw-bold">{{ $raport->santri->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">NISN</td>
                        <td>:</td>
                        <td>{{ $raport->santri->nisn ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Kelas</td>
                        <td>:</td>
                        <td>{{ $raport->kelas->nama ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="fw-semibold text-muted" style="width: 150px;">Tahun Ajaran</td>
                        <td style="width: 10px;">:</td>
                        <td>{{ $raport->tahunAjaran->tahun ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Semester</td>
                        <td>:</td>
                        <td class="text-capitalize">{{ $raport->tahunAjaran->semester ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Peringkat Kelas</td>
                        <td>:</td>
                        <td><strong>{{ $raport->peringkat ?? '-' }}</strong> dari {{ $raport->jumlah_siswa ?? '-' }} Santri</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Tabel Nilai -->
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle">
                <thead class="table-light text-center" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th class="text-start">Mata Pelajaran</th>
                        <th>Harian</th>
                        <th>Tugas</th>
                        <th>UTS</th>
                        <th>UAS</th>
                        <th>Hafalan</th>
                        <th>Adab</th>
                        <th>Nilai Akhir</th>
                        <th>Predikat</th>
                    </tr>
                </thead>
                <tbody style="font-size: 13px;">
                    @forelse($raport->detail as $i => $d)
                        @php
                            $na = $d->nilai_akhir ?? 0;
                            $predClass = $na >= 80 ? 'bg-success-subtle text-success' : ($na >= 70 ? 'bg-primary-subtle text-primary' : ($na >= 60 ? 'bg-warning-subtle text-warning-dark' : 'bg-danger-subtle text-danger'));
                        @endphp
                        <tr>
                            <td class="text-center text-muted">{{ $i + 1 }}</td>
                            <td><strong>{{ $d->mapel->nama }}</strong></td>
                            <td class="text-center">{{ $d->nilai_harian ?? '-' }}</td>
                            <td class="text-center">{{ $d->nilai_tugas ?? '-' }}</td>
                            <td class="text-center">{{ $d->nilai_uts ?? '-' }}</td>
                            <td class="text-center">{{ $d->nilai_uas ?? '-' }}</td>
                            <td class="text-center">{{ $d->nilai_hafalan ?? '-' }}</td>
                            <td class="text-center">{{ $d->nilai_adab ?? '-' }}</td>
                            <td class="text-center fw-bold">{{ number_format($na, 1) }}</td>
                            <td class="text-center">
                                <span class="badge {{ $predClass }} rounded-pill px-2 py-1">
                                    {{ $d->predikat ?? \App\Models\Nilai::hitungPredikat($na) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">Data nilai tidak tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light fw-bold" style="font-size: 13px;">
                    <tr>
                        <td colspan="8" class="text-end ps-4 py-3">Rata-rata Nilai Keseluruhan</td>
                        <td class="text-center text-primary py-3" style="font-size: 15px;">{{ number_format($raport->rata_rata, 1) }}</td>
                        <td class="text-center py-3"><span class="badge bg-primary px-3 py-2">{{ $raport->predikat_akhir ?? '-' }}</span></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Rekap Absensi + Catatan -->
        <div class="row g-4">
            <div class="col-md-5">
                <div class="card border border-light-subtle shadow-none h-100">
                    <div class="card-header bg-light-subtle border-0 fw-bold text-muted small py-3">REKAP KEHADIRAN</div>
                    <div class="card-body p-0">
                        <table class="table align-middle mb-0" style="font-size: 13px;">
                            <tr>
                                <td class="ps-4"><i class="bi bi-circle-fill text-success me-2 small"></i> Hadir</td>
                                <td class="text-end pe-4 fw-bold">{{ $raport->hadir ?? 0 }} Hari</td>
                            </tr>
                            <tr>
                                <td class="ps-4"><i class="bi bi-circle-fill text-warning me-2 small"></i> Sakit</td>
                                <td class="text-end pe-4 fw-bold">{{ $raport->sakit ?? 0 }} Hari</td>
                            </tr>
                            <tr>
                                <td class="ps-4"><i class="bi bi-circle-fill text-info me-2 small"></i> Izin</td>
                                <td class="text-end pe-4 fw-bold">{{ $raport->izin ?? 0 }} Hari</td>
                            </tr>
                            <tr>
                                <td class="ps-4"><i class="bi bi-circle-fill text-danger me-2 small"></i> Tanpa Keterangan (Alfa)</td>
                                <td class="text-end pe-4 fw-bold text-danger">{{ $raport->alfa ?? 0 }} Hari</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card border border-light-subtle shadow-none h-100">
                    <div class="card-header bg-light-subtle border-0 fw-bold text-muted small py-3">CATATAN WALI KELAS</div>
                    <div class="card-body py-3">
                        <p class="mb-0 text-dark italic font-monospace" style="font-size: 13px; line-height: 1.6; font-style: italic;">
                            "{{ $raport->catatan_wali_kelas ?? 'Tetap semangat belajar dan tingkatkan terus prestasi. Semoga Allah memberkahi ilmu yang dipelajari.' }}"
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

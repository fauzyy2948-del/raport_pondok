@extends('layouts.app')
@section('title', 'Detail Raport — ' . ($raport->santri->nama ?? $raport->santri->user?->name ?? 'Santri'))
@section('page-title', 'Detail Raport Santri')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.raport.index') }}">Raport</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@push('styles')
<style>
.show-header {
    background: linear-gradient(135deg, #0d3d20, #1B6B3A, #2E8B57);
    border-radius: 16px;
    padding: 26px 30px;
    color: white;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
}
.show-header::before {
    content: '';
    position: absolute;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
    top: -80px; right: -40px;
}
.show-header .santri-photo {
    width: 72px; height: 72px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(255,255,255,.5);
    flex-shrink: 0;
}
.show-header h4 { font-size: 20px; font-weight: 800; margin-bottom: 4px; }
.show-header .meta { font-size: 12px; opacity: .8; }

.info-row { display: flex; flex-wrap: wrap; gap: 24px; margin-top: 16px; }
.info-item { }
.info-item .lbl { font-size: 10px; opacity: .65; text-transform: uppercase; letter-spacing: .5px; }
.info-item .val { font-size: 14px; font-weight: 700; }

.nilai-table th { font-size: 11px; text-transform: uppercase; letter-spacing: .5px; }
.nilai-cell { font-size: 13px; font-weight: 700; }
.predikat-pill {
    padding: 2px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}

.absensi-box {
    border-radius: 12px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
}
.absensi-box .ab-icon { font-size: 28px; }
.absensi-box .ab-val { font-size: 22px; font-weight: 800; line-height: 1; }
.absensi-box .ab-lbl { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; opacity: .75; }

.section-title {
    font-size: 13px;
    font-weight: 700;
    color: var(--gray-800);
    padding-bottom: 10px;
    border-bottom: 2px solid var(--gray-200);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

[data-theme="dark"] .show-header { background: linear-gradient(135deg, #091f10, #134d2a, #1a5c35); }
</style>
@endpush

@section('content')
@php
    $santri     = $raport->santri;
    $tahunAjaran= $raport->tahunAjaran;
    $kelas      = $raport->kelas;
    $details    = $raport->detail->sortByDesc('nilai_akhir');
    $rn         = $raport->rata_rata ?? 0;
    $rnColor    = $rn >= 80 ? '#065f46' : ($rn >= 70 ? '#92400e' : '#991b1b');
    $rnBg       = $rn >= 80 ? '#d1fae5' : ($rn >= 70 ? '#fef3c7' : '#fee2e2');
@endphp

{{-- Header --}}
<div class="show-header">
    <div class="d-flex align-items-start gap-3 flex-wrap">
        <img src="{{ $santri?->foto_url ?? asset('images/default-santri.png') }}" class="santri-photo" alt="foto">
        <div class="flex-1">
            <h4>{{ $santri?->nama ?? $santri?->user?->name ?? '-' }}</h4>
            <div class="meta">
                NISN: {{ $santri?->nisn ?? '-' }}
                &nbsp;·&nbsp; Kelas: {{ $kelas?->nama ?? '-' }}
                &nbsp;·&nbsp; {{ $tahunAjaran?->label ?? '-' }}
            </div>
            <div class="info-row">
                <div class="info-item">
                    <div class="lbl">Rata-rata Nilai</div>
                    <div class="val" style="font-size:22px;">{{ number_format($rn, 1) }}</div>
                </div>
                <div class="info-item">
                    <div class="lbl">Predikat</div>
                    <div class="val">{{ $raport->predikat_akhir ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="lbl">Peringkat</div>
                    <div class="val">{{ $raport->peringkat ?? '-' }} / {{ $raport->jumlah_siswa ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="lbl">Status</div>
                    <div class="val">
                        @if($raport->diterbitkan)
                            <span style="background:rgba(209,250,229,.25);color:#bbf7d0;padding:2px 10px;border-radius:20px;font-size:12px;">
                                ✓ Diterbitkan
                            </span>
                        @else
                            <span style="background:rgba(255,255,255,.15);color:rgba(255,255,255,.9);padding:2px 10px;border-radius:20px;font-size:12px;">
                                ⏳ Belum Diterbitkan
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column gap-2 mt-1">
            <a href="{{ route('admin.raport.cetak', $raport->id) }}" target="_blank"
               class="btn btn-sm" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.35);">
                <i class="bi bi-file-earmark-pdf me-1"></i>Cetak PDF
            </a>
            @if(!$raport->diterbitkan)
                <form action="{{ route('admin.raport.terbitkan', $raport->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-gold btn-sm w-100"
                        onclick="return confirm('Terbitkan raport ini? Santri dan wali dapat melihatnya.')">
                        <i class="bi bi-send-fill me-1"></i>Terbitkan
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Tabel Nilai --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="section-title">
                    <i class="bi bi-table text-primary"></i>Rincian Nilai Mata Pelajaran
                </div>
                @if($details->isEmpty())
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-journal-x fs-1 d-block mb-3 opacity-25"></i>
                        Belum ada detail nilai
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 nilai-table">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Mata Pelajaran</th>
                                    <th class="text-center">Harian</th>
                                    <th class="text-center">Tugas</th>
                                    <th class="text-center">UTS</th>
                                    <th class="text-center">UAS</th>
                                    <th class="text-center">Hafalan</th>
                                    <th class="text-center">Adab</th>
                                    <th class="text-center">Akhir</th>
                                    <th class="text-center">Predikat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($details as $i => $d)
                                    @php
                                        $na = $d->nilai_akhir ?? 0;
                                        $naColor = $na >= 80 ? '#065f46' : ($na >= 70 ? '#92400e' : '#991b1b');
                                        $naBg    = $na >= 80 ? '#d1fae5' : ($na >= 70 ? '#fef3c7' : '#fee2e2');
                                    @endphp
                                    <tr>
                                        <td class="text-muted" style="font-size:12px;">{{ $i+1 }}</td>
                                        <td>
                                            <div class="fw-600" style="font-size:13px;">{{ $d->mapel?->nama ?? '-' }}</div>
                                            @if($d->mapel?->kategori)
                                                <small class="text-muted">{{ ucfirst($d->mapel->kategori) }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center nilai-cell">{{ $d->nilai_harian ?? '-' }}</td>
                                        <td class="text-center nilai-cell">{{ $d->nilai_tugas ?? '-' }}</td>
                                        <td class="text-center nilai-cell">{{ $d->nilai_uts ?? '-' }}</td>
                                        <td class="text-center nilai-cell">{{ $d->nilai_uas ?? '-' }}</td>
                                        <td class="text-center nilai-cell">{{ $d->nilai_hafalan ?? '-' }}</td>
                                        <td class="text-center nilai-cell">{{ $d->nilai_adab ?? '-' }}</td>
                                        <td class="text-center">
                                            <span class="predikat-pill" style="background:{{ $naBg }};color:{{ $naColor }};">
                                                {{ number_format($na, 1) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-700" style="color:{{ $naColor }};">
                                                {{ $d->predikat ?? \App\Models\Nilai::hitungPredikat($na) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background:var(--light);">
                                    <td colspan="8" class="text-end fw-700">Rata-rata Keseluruhan</td>
                                    <td class="text-center">
                                        <span class="predikat-pill fw-800" style="background:{{ $rnBg }};color:{{ $rnColor }};font-size:13px;">
                                            {{ number_format($rn, 1) }}
                                        </span>
                                    </td>
                                    <td class="text-center fw-800" style="color:{{ $rnColor }};">
                                        {{ $raport->predikat_akhir ?? '-' }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Sidebar Info --}}
    <div class="col-lg-4 d-flex flex-column gap-3">
        {{-- Absensi --}}
        <div class="card">
            <div class="card-body">
                <div class="section-title">
                    <i class="bi bi-calendar3 text-primary"></i>Rekap Kehadiran
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="absensi-box" style="background:#d1fae5;">
                            <span class="ab-icon">✅</span>
                            <div>
                                <div class="ab-val" style="color:#065f46;">{{ $raport->hadir ?? 0 }}</div>
                                <div class="ab-lbl" style="color:#065f46;">Hadir</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="absensi-box" style="background:#fef3c7;">
                            <span class="ab-icon">🤒</span>
                            <div>
                                <div class="ab-val" style="color:#92400e;">{{ $raport->sakit ?? 0 }}</div>
                                <div class="ab-lbl" style="color:#92400e;">Sakit</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="absensi-box" style="background:#e0f2fe;">
                            <span class="ab-icon">📝</span>
                            <div>
                                <div class="ab-val" style="color:#075985;">{{ $raport->izin ?? 0 }}</div>
                                <div class="ab-lbl" style="color:#075985;">Izin</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="absensi-box" style="background:#fee2e2;">
                            <span class="ab-icon">❌</span>
                            <div>
                                <div class="ab-val" style="color:#991b1b;">{{ $raport->alfa ?? 0 }}</div>
                                <div class="ab-lbl" style="color:#991b1b;">Alfa</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Santri --}}
        <div class="card">
            <div class="card-body">
                <div class="section-title">
                    <i class="bi bi-person-fill text-primary"></i>Info Santri
                </div>
                @php $rows = [
                    ['Nama Lengkap', $santri?->nama ?? $santri?->user?->name ?? '-'],
                    ['NISN', $santri?->nisn ?? '-'],
                    ['Kelas', $kelas?->nama ?? '-'],
                    ['Jenis Kelamin', $santri?->jenis_kelamin ? ucfirst($santri->jenis_kelamin) : '-'],
                    ['Wali Santri', $santri?->waliSantri?->nama ?? '-'],
                    ['Tahun Ajaran', $tahunAjaran?->label ?? '-'],
                    ['Diterbitkan', $raport->diterbitkan_pada?->locale('id')->isoFormat('D MMMM Y') ?? '-'],
                ]; @endphp
                @foreach($rows as [$lbl, $val])
                    <div class="d-flex justify-content-between py-2 border-bottom" style="border-color:var(--gray-200)!important;font-size:12px;">
                        <span class="text-muted">{{ $lbl }}</span>
                        <span class="fw-600 text-end" style="max-width:55%;">{{ $val }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Catatan Wali Kelas --}}
        @if($raport->catatan_wali_kelas)
        <div class="card">
            <div class="card-body">
                <div class="section-title">
                    <i class="bi bi-chat-quote-fill text-primary"></i>Catatan Wali Kelas
                </div>
                <p style="font-size:13px;line-height:1.7;color:var(--gray-800);">
                    {{ $raport->catatan_wali_kelas }}
                </p>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Navigation --}}
<div class="d-flex gap-2 mt-4">
    <a href="{{ route('admin.raport.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
    </a>
    <a href="{{ route('admin.raport.dashboard') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-speedometer2 me-1"></i>Dashboard Raport
    </a>
</div>
@endsection

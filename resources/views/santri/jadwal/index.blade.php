@extends('layouts.app')
@section('title', 'Jadwal Pelajaran Saya')
@section('page-title', 'Jadwal Pelajaran')
@section('breadcrumb')
    <li class="breadcrumb-item active">Jadwal Pelajaran</li>
@endsection

@section('content')
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary-subtle text-primary p-3 rounded-3 fs-3">
                    <i class="bi bi-calendar3"></i>
                </div>
                <div>
                    <h5 class="mb-1 fw-bold">Jadwal Pelajaran Kelas {{ $santri->kelas->nama ?? '-' }}</h5>
                    <p class="text-muted mb-0">Berikut adalah jadwal pelajaran Anda untuk tahun ajaran aktif.</p>
                </div>
            </div>
            <div>
                <button type="button" onclick="window.print()" class="btn btn-outline-secondary">
                    <i class="bi bi-printer"></i> Cetak Jadwal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Print-Only Header -->
<div class="d-none d-print-block text-center mb-4">
    <h4><strong>PONDOK PESANTREN SUBULUSSALAM</strong></h4>
    <h5>JADWAL PELAJARAN SANTRI</h5>
    <p class="mb-1"><strong>Nama:</strong> {{ $santri->nama }} | <strong>NISN:</strong> {{ $santri->nisn }} | <strong>Kelas:</strong> {{ $santri->kelas->nama ?? '-' }}</p>
    <hr style="border-top: 3px double #000; margin-top: 10px;">
</div>

@php
    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Ahad'];
@endphp

<div class="row">
    @foreach($days as $day)
        @php
            $jadwalHari = $jadwal->get($day, collect());
        @endphp
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-bold text-primary">{{ $day }}</h5>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2" style="font-size: 11px;">
                        {{ $jadwalHari->count() }} Pelajaran
                    </span>
                </div>
                <div class="card-body p-0">
                    @if($jadwalHari->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x display-6 mb-2 d-block opacity-50"></i>
                            <span style="font-size: 13px;">Tidak ada jadwal</span>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-muted" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <tr>
                                        <th class="ps-4">Waktu</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Ustadz / Guru</th>
                                        <th class="pe-4">Ruangan</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 13px;">
                                    @foreach(\App\Models\Jadwal::listJamLengkap() as $time => $slot)
                                        @php
                                            [$start, $end] = explode('-', $time);
                                            $j = $jadwalHari->first(function($item) use ($start, $end) {
                                                return substr($item->jam_mulai, 0, 5) == $start && substr($item->jam_selesai, 0, 5) == $end;
                                            });
                                        @endphp
                                        @if($slot['type'] === 'istirahat')
                                            <tr class="table-light text-muted italic">
                                                <td class="ps-4 text-secondary">{{ $slot['time'] }}</td>
                                                <td colspan="2" class="text-center fw-semibold text-secondary" style="letter-spacing: 1px;">{{ $slot['label'] }}</td>
                                                <td class="pe-4"></td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td class="ps-4 text-primary fw-semibold">{{ $slot['time'] }}</td>
                                                @if($j)
                                                    <td><strong>{{ $j->mapel->nama }}</strong></td>
                                                    <td>{{ $j->ustadz->nama_lengkap }}</td>
                                                    <td class="pe-4 text-muted">{{ $j->ruangan ?? '-' }}</td>
                                                @else
                                                    <td colspan="3" class="text-muted text-center italic">- Kosong -</td>
                                                @endif
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@push('styles')
<style>
@media print {
    /* Hide layout navigation, sidebar, topbar, buttons and filters */
    .sidebar, .topbar, .sidebar-overlay, .scroll-top, .breadcrumb, .card.mb-4, .text-end, .btn, form {
        display: none !important;
    }
    /* Expand content width */
    .main-content {
        margin-left: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }
    .content-wrapper {
        padding: 0 !important;
        margin: 0 !important;
    }
    /* Grid layout for cards on printed page */
    .row {
        display: flex !important;
        flex-wrap: wrap !important;
        margin-right: -15px !important;
        margin-left: -15px !important;
    }
    .col-md-6 {
        flex: 0 0 50% !important;
        max-width: 50% !important;
        padding-right: 15px !important;
        padding-left: 15px !important;
        box-sizing: border-box !important;
    }
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
        page-break-inside: avoid !important;
        margin-bottom: 20px !important;
    }
    .card-header {
        background-color: #f8f9fa !important;
        border-bottom: 1px solid #000 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    /* Set background colors and text for printing */
    body {
        background: white !important;
        color: black !important;
    }
    .table-light {
        background-color: #f8f9fa !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
}
</style>
@endpush

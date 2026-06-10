@extends('layouts.app')
@section('title', 'Jadwal Pelajaran')
@section('page-title', 'Jadwal Pelajaran')
@section('breadcrumb')
    <li class="breadcrumb-item active">Jadwal</li>
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.jadwal.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <select name="kelas_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Pilih Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8 text-end">
                @if(request('kelas_id'))
                    <button type="button" onclick="window.print()" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-printer"></i> Cetak Jadwal
                    </button>
                @endif
                <a href="{{ route('admin.jadwal.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> Tambah Jadwal
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Print-Only Header -->
@if(request('kelas_id'))
    @php
        $selectedKelasNama = $kelasList->firstWhere('id', request('kelas_id'))->nama ?? '';
    @endphp
    <div class="d-none d-print-block text-center mb-4">
        <h4><strong>PONDOK PESANTREN SUBULUSSALAM</strong></h4>
        <h5>JADWAL PELAJARAN KELAS {{ strtoupper($selectedKelasNama) }}</h5>
        <hr style="border-top: 3px double #000; margin-top: 10px;">
    </div>
@endif

@if(request('kelas_id'))
    @php
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Ahad'];
    @endphp
    <div class="row">
        @foreach($days as $day)
            @php
                $jadwalHari = $jadwals->where('hari', $day);
            @endphp
            @if($jadwalHari->count() > 0)
                <div class="col-md-6 mb-4">
                     <div class="card h-100">
                         <div class="card-header bg-light">
                             <h5 class="mb-0">{{ $day }}</h5>
                         </div>
                         <div class="card-body p-0">
                             <table class="table table-hover mb-0">
                                 <thead>
                                     <tr>
                                         <th>Waktu</th>
                                         <th>Mata Pelajaran</th>
                                         <th>Ustadz</th>
                                         <th class="text-end">Aksi</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     @foreach(\App\Models\Jadwal::listJamLengkap() as $time => $slot)
                                         @php
                                             [$start, $end] = explode('-', $time);
                                             $j = $jadwalHari->first(function($item) use ($start, $end) {
                                                 return substr($item->jam_mulai, 0, 5) == $start && substr($item->jam_selesai, 0, 5) == $end;
                                             });
                                         @endphp
                                         @if($slot['type'] === 'istirahat')
                                             <tr class="table-light text-muted italic">
                                                 <td>{{ $slot['time'] }}</td>
                                                 <td colspan="2" class="text-center fw-semibold text-secondary" style="letter-spacing: 1px;">{{ $slot['label'] }}</td>
                                                 <td></td>
                                             </tr>
                                         @else
                                             <tr>
                                                 <td>{{ $slot['time'] }}</td>
                                                 @if($j)
                                                     <td><strong>{{ $j->mapel->nama }}</strong></td>
                                                     <td>{{ $j->ustadz->nama_lengkap }}</td>
                                                     <td class="text-end">
                                                         <a href="{{ route('admin.jadwal.edit', $j->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                             <i class="bi bi-pencil"></i>
                                                         </a>
                                                         <form action="{{ route('admin.jadwal.destroy', $j->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus jadwal ini?')">
                                                             @csrf
                                                             @method('DELETE')
                                                             <button class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                                         </form>
                                                     </td>
                                                 @else
                                                     <td colspan="2" class="text-muted text-center italic">- Kosong -</td>
                                                     <td class="text-end">
                                                         <a href="{{ route('admin.jadwal.create', ['kelas_id' => request('kelas_id'), 'hari' => $day, 'jam_mulai' => $start, 'jam_selesai' => $end]) }}" class="btn btn-xs btn-outline-primary" title="Tambah Jadwal">
                                                             <i class="bi bi-plus-lg"></i>
                                                         </a>
                                                     </td>
                                                 @endif
                                             </tr>
                                         @endif
                                     @endforeach
                                 </tbody>
                             </table>
                         </div>
                     </div>
                 </div>
             @endif
         @endforeach
    </div>
@else
    <div class="alert alert-info text-center py-5">
        <i class="bi bi-info-circle display-4 d-block mb-3"></i>
        <h4>Silakan pilih kelas terlebih dahulu</h4>
        <p>Jadwal pelajaran akan ditampilkan berdasarkan kelas yang dipilih.</p>
    </div>
@endif
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

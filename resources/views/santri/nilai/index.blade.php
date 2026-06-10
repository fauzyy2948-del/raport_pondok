@extends('layouts.app')
@section('title', 'Nilai Saya')
@section('page-title', 'Nilai Akademik')
@section('breadcrumb')
    <li class="breadcrumb-item active">Nilai</li>
@endsection

@section('content')
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body py-3">
        <form action="{{ route('santri.nilai.index') }}" method="GET" class="row align-items-center g-3">
            <div class="col-md-4">
                <label class="form-label mb-1 small text-muted">Pilih Tahun Ajaran</label>
                <select name="tahun_ajaran_id" class="form-select" onchange="this.form.submit()">
                    @foreach($tahunAjaran as $ta)
                        <option value="{{ $ta->id }}" {{ $selectedTahun == $ta->id ? 'selected' : '' }}>
                            {{ $ta->tahun }} - Semester {{ ucfirst($ta->semester) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-star-fill text-warning me-2"></i>Daftar Nilai Hasil Belajar</h5>
    </div>
    <div class="card-body p-0">
        @if($nilai->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-clipboard-x display-5 d-block mb-3 text-muted opacity-50"></i>
                <p class="mb-0">Data nilai belum diinput oleh ustadz pengajar.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Mata Pelajaran</th>
                            <th class="text-center">Harian</th>
                            <th class="text-center">Tugas</th>
                            <th class="text-center">UTS</th>
                            <th class="text-center">UAS</th>
                            <th class="text-center">Hafalan</th>
                            <th class="text-center">Adab</th>
                            <th class="text-center">Nilai Akhir</th>
                            <th class="text-center">Predikat</th>
                            <th class="pe-4">Catatan</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 13px;">
                        @foreach($nilai as $i => $n)
                            @php
                                $na = $n->nilai_akhir ?? 0;
                                $predClass = $na >= 80 ? 'bg-success-subtle text-success' : ($na >= 70 ? 'bg-primary-subtle text-primary' : ($na >= 60 ? 'bg-warning-subtle text-warning-dark' : 'bg-danger-subtle text-danger'));
                            @endphp
                            <tr>
                                <td class="ps-4 text-muted">{{ $i + 1 }}</td>
                                <td><strong>{{ $n->mapel->nama }}</strong></td>
                                <td class="text-center">{{ $n->nilai_harian ?? '-' }}</td>
                                <td class="text-center">{{ $n->nilai_tugas ?? '-' }}</td>
                                <td class="text-center">{{ $n->nilai_uts ?? '-' }}</td>
                                <td class="text-center">{{ $n->nilai_uas ?? '-' }}</td>
                                <td class="text-center">{{ $n->nilai_hafalan ?? '-' }}</td>
                                <td class="text-center">{{ $n->nilai_adab ?? '-' }}</td>
                                <td class="text-center fw-bold text-dark">{{ number_format($na, 1) }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $predClass }} rounded-pill px-2 py-1" style="font-size: 11px;">
                                        {{ $n->predikat ?? \App\Models\Nilai::hitungPredikat($na) }}
                                    </span>
                                </td>
                                <td class="pe-4 text-muted small" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $n->catatan }}">
                                    {{ $n->catatan ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

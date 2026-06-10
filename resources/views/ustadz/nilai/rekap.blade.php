@extends('layouts.app')
@section('title', 'Rekap Nilai')
@section('page-title', 'Rekap Nilai Santri')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('ustadz.nilai.index') }}">Nilai</a></li>
    <li class="breadcrumb-item active">Rekap</li>
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-funnel text-primary me-2"></i>Filter Rekap Nilai</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('ustadz.nilai.rekap') }}" class="row g-3">
            <div class="col-md-5">
                <label class="form-label">Tahun Ajaran</label>
                <select name="tahun_ajaran_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Pilih Tahun Ajaran</option>
                    @foreach($tahunAjaran as $ta)
                        <option value="{{ $ta->id }}" {{ $selectedTahun == $ta->id ? 'selected' : '' }}>
                            {{ $ta->nama }} - {{ $ta->semester }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">Kelas</label>
                <select name="kelas_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Pilih Kelas</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ $selectedKelas == $k->id ? 'selected' : '' }}>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
            </div>
        </form>
    </div>
</div>

@if($selectedKelas && $selectedTahun)
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-table text-primary me-2"></i>Data Rekap Nilai</h5>
        <button class="btn btn-sm btn-outline-secondary" onclick="window.print()"><i class="bi bi-printer me-1"></i>Cetak</button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="50" class="text-center">No</th>
                        <th>NISN</th>
                        <th>Nama Santri</th>
                        <th>Mata Pelajaran</th>
                        <th class="text-center">Nilai Akhir</th>
                        <th class="text-center">Predikat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapData as $index => $santri)
                        @if($santri->nilai->count() > 0)
                            @foreach($santri->nilai as $i => $n)
                                <tr>
                                    @if($i == 0)
                                        <td rowspan="{{ $santri->nilai->count() }}" class="text-center align-middle">{{ $index + 1 }}</td>
                                        <td rowspan="{{ $santri->nilai->count() }}" class="align-middle">{{ $santri->nisn }}</td>
                                        <td rowspan="{{ $santri->nilai->count() }}" class="align-middle fw-bold">{{ $santri->nama }}</td>
                                    @endif
                                    <td>{{ $n->mapel->nama ?? '-' }}</td>
                                    <td class="text-center"><strong>{{ $n->nilai_akhir }}</strong></td>
                                    <td class="text-center">
                                        <span class="badge {{ $n->predikat == 'A' ? 'bg-success' : ($n->predikat == 'B' ? 'bg-primary' : ($n->predikat == 'C' ? 'bg-warning' : 'bg-danger')) }}">
                                            {{ $n->predikat }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $santri->nisn }}</td>
                                <td class="fw-bold">{{ $santri->nama }}</td>
                                <td colspan="3" class="text-center text-muted">Belum ada nilai yang diinput</td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Tidak ada data santri pada kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="alert alert-info">
    <i class="bi bi-info-circle me-2"></i>Silakan pilih Tahun Ajaran dan Kelas untuk menampilkan rekap nilai.
</div>
@endif
@endsection

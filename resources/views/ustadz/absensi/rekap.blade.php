@extends('layouts.app')
@section('title', 'Rekap Absensi')
@section('page-title', 'Rekap Absensi Santri')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('ustadz.absensi.index') }}">Absensi</a></li>
    <li class="breadcrumb-item active">Rekap</li>
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-funnel text-primary me-2"></i>Filter Rekap Absensi</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('ustadz.absensi.rekap') }}" class="row g-3">
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
        <h5 class="mb-0"><i class="bi bi-table text-primary me-2"></i>Data Rekap Absensi</h5>
        <button class="btn btn-sm btn-outline-secondary" onclick="window.print()"><i class="bi bi-printer me-1"></i>Cetak</button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="50" class="text-center" rowspan="2">No</th>
                        <th rowspan="2" class="align-middle">NISN</th>
                        <th rowspan="2" class="align-middle">Nama Santri</th>
                        <th colspan="4" class="text-center">Kehadiran</th>
                        <th rowspan="2" class="text-center align-middle">Total<br>Pertemuan</th>
                        <th rowspan="2" class="text-center align-middle">% Kehadiran</th>
                    </tr>
                    <tr>
                        <th class="text-center text-success">Hadir</th>
                        <th class="text-center text-primary">Sakit</th>
                        <th class="text-center text-warning">Izin</th>
                        <th class="text-center text-danger">Alfa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapData as $index => $data)
                        @php
                            $persentase = $data['total'] > 0 ? round(($data['hadir'] / $data['total']) * 100, 1) : 0;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $data['santri']->nisn }}</td>
                            <td class="fw-bold">{{ $data['santri']->nama }}</td>
                            <td class="text-center">{{ $data['hadir'] }}</td>
                            <td class="text-center">{{ $data['sakit'] }}</td>
                            <td class="text-center">{{ $data['izin'] }}</td>
                            <td class="text-center">{{ $data['alfa'] }}</td>
                            <td class="text-center">{{ $data['total'] }}</td>
                            <td class="text-center">
                                <span class="badge {{ $persentase >= 80 ? 'bg-success' : ($persentase >= 60 ? 'bg-warning' : 'bg-danger') }}">
                                    {{ $persentase }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Tidak ada data santri pada kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="alert alert-info">
    <i class="bi bi-info-circle me-2"></i>Silakan pilih Tahun Ajaran dan Kelas untuk menampilkan rekap absensi.
</div>
@endif
@endsection

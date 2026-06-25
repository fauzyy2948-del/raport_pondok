@extends('layouts.app')
@section('title', 'Absensi Saya')
@section('page-title', 'Absensi Akademik')
@section('breadcrumb')
    <li class="breadcrumb-item active">Absensi</li>
@endsection

@section('content')
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body py-3">
        <form action="{{ route('santri.absensi.index') }}" method="GET" class="row align-items-center g-3">
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

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-success text-white h-100">
            <div class="card-body text-center py-4">
                <h6 class="text-white-50 mb-2">Hadir</h6>
                <h2 class="mb-0 fw-bold">{{ $stats->get('Hadir', 0) }}</h2>
                <small class="text-white-50">Hari</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-warning text-white h-100">
            <div class="card-body text-center py-4">
                <h6 class="text-white-50 mb-2 text-dark">Sakit</h6>
                <h2 class="mb-0 fw-bold text-dark">{{ $stats->get('Sakit', 0) }}</h2>
                <small class="text-white-50 text-dark">Hari</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-info text-white h-100">
            <div class="card-body text-center py-4">
                <h6 class="text-white-50 mb-2">Izin</h6>
                <h2 class="mb-0 fw-bold">{{ $stats->get('Izin', 0) }}</h2>
                <small class="text-white-50">Hari</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-danger text-white h-100">
            <div class="card-body text-center py-4">
                <h6 class="text-white-50 mb-2">Alfa (Tanpa Keterangan)</h6>
                <h2 class="mb-0 fw-bold">{{ $stats->get('Alfa', 0) }}</h2>
                <small class="text-white-50">Hari</small>
            </div>
        </div>
    </div>
</div>


@endsection

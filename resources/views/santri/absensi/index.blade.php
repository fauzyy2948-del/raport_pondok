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

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-calendar-check-fill text-success me-2"></i>Riwayat Kehadiran</h5>
    </div>
    <div class="card-body p-0">
        @if($absensi->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-calendar-x display-5 d-block mb-3 text-muted opacity-50"></i>
                <p class="mb-0">Belum ada riwayat absensi untuk tahun ajaran ini.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th class="text-center">Status Kehadiran</th>
                            <th class="pe-4">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 13px;">
                        @foreach($absensi as $i => $a)
                            @php
                                $status = $a->status;
                                $badgeClass = match($status) {
                                    'Hadir' => 'bg-success-subtle text-success',
                                    'Sakit' => 'bg-warning-subtle text-warning-dark',
                                    'Izin' => 'bg-info-subtle text-info',
                                    'Alfa' => 'bg-danger-subtle text-danger',
                                    default => 'bg-secondary-subtle text-secondary'
                                };
                                $tanggal = \Carbon\Carbon::parse($a->tanggal);
                            @endphp
                            <tr>
                                <td class="ps-4 text-muted">{{ $i + 1 + ($absensi->currentPage() - 1) * $absensi->perPage() }}</td>
                                <td><strong>{{ $tanggal->isoFormat('D MMMM Y') }}</strong></td>
                                <td>{{ $tanggal->isoFormat('dddd') }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2" style="font-size: 11px; min-width: 80px;">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="pe-4 text-muted">{{ $a->keterangan ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($absensi->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $absensi->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

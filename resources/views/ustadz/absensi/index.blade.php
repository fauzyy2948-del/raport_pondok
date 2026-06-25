@extends('layouts.app')
@section('title', 'Rekap Absensi Semester')
@section('page-title', 'Rekap Absensi Semester')
@section('breadcrumb')
    <li class="breadcrumb-item active">Absensi</li>
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('ustadz.absensi.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label">Pilih Kelas</label>
                <select name="kelas_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama }} {{ $k->tingkat ? ' - Tingkat ' . $k->tingkat : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-7">
                <div class="alert alert-info mb-0 py-2">
                    <i class="bi bi-info-circle me-1"></i>
                    Input ini akan langsung tersimpan ke data cetak Raport semester: <strong>{{ $tahunAktif->nama ?? '-' }}</strong>.
                </div>
            </div>
        </form>
    </div>
</div>

@if(request('kelas_id') && $kelasTerpilih)
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold" style="color: var(--primary);">
                <i class="bi bi-table me-2"></i> Input Rekap Absensi: Kelas {{ $kelasTerpilih->nama }}
            </h5>
        </div>
        <div class="card-body p-0">
            <form action="{{ route('ustadz.absensi.store') }}" method="POST">
                @csrf
                <input type="hidden" name="kelas_id" value="{{ $kelasTerpilih->id }}">
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th>Nama Santri</th>
                                <th width="15%" class="text-center">Sakit</th>
                                <th width="15%" class="text-center">Izin</th>
                                <th width="15%" class="text-center">Alfa / Tdk Hadir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($santris as $s)
                                @php
                                    // Ambil data raport untuk santri di tahun ajaran aktif, jika ada
                                    $raport = $s->raport->first();
                                    $sakit = $raport ? $raport->sakit : 0;
                                    $izin = $raport ? $raport->izin : 0;
                                    $alfa = $raport ? $raport->alfa : 0;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $s->nama }}</div>
                                        <div class="text-muted small">NISN: {{ $s->nisn }}</div>
                                    </td>
                                    <td>
                                        <input type="number" name="absensi[{{ $s->id }}][sakit]" class="form-control text-center" value="{{ $sakit }}" min="0" required>
                                    </td>
                                    <td>
                                        <input type="number" name="absensi[{{ $s->id }}][izin]" class="form-control text-center" value="{{ $izin }}" min="0" required>
                                    </td>
                                    <td>
                                        <input type="number" name="absensi[{{ $s->id }}][alfa]" class="form-control text-center" value="{{ $alfa }}" min="0" required>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data santri di kelas ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($santris->count() > 0)
                    <div class="card-footer bg-light p-3 text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Simpan Rekap Absensi
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
@elseif(!request('kelas_id'))
    <div class="alert bg-white border shadow-sm text-center py-5">
        <i class="bi bi-ui-checks-grid display-4 d-block mb-3 text-muted opacity-50"></i>
        <h4>Pilih Kelas</h4>
        <p class="text-muted">Silakan pilih kelas terlebih dahulu untuk mulai menginput rekap absensi.</p>
    </div>
@endif

@endsection

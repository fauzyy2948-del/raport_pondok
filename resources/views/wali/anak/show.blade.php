@extends('layouts.app')
@section('title', 'Perkembangan Santri')
@section('page-title', 'Perkembangan Ananda: ' . $santri->nama)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('wali.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Perkembangan Anak</li>
@endsection

@section('content')
<!-- Filter Tahun Ajaran -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body py-3">
        <form action="{{ route('wali.anak.show', $santri->id) }}" method="GET" class="row align-items-center g-3">
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

<div class="row g-4">
    <!-- Profil Singkat -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                <img src="{{ $santri->foto_url }}" class="rounded-circle mb-3" width="120" height="120" alt="Foto" style="object-fit: cover; border: 3px solid var(--bs-primary-bg-subtle);">
                <h5 class="mb-1 fw-bold text-dark">{{ $santri->nama }}</h5>
                <p class="text-muted mb-3 small">NISN: {{ $santri->nisn }} | Kelas: {{ $santri->kelas->nama ?? '-' }}</p>
                
                @if($raport->isNotEmpty())
                    <a href="{{ route('wali.raport.download', $raport->first()->id) }}" class="btn btn-success w-100 py-2 fw-semibold">
                        <i class="bi bi-download me-2"></i> Unduh Raport
                    </a>
                @else
                    <button class="btn btn-secondary w-100 py-2 fw-semibold" disabled>Raport Belum Tersedia</button>
                @endif
            </div>
        </div>
    </div>

    <!-- Ringkasan Kehadiran & Nilai -->
    <div class="col-md-8">
        <div class="row g-3 h-100">
            @php
                $rataRataNilai = $nilai->avg('nilai_akhir') ?? 0;
            @endphp
            <div class="col-sm-6">
                <div class="card border-0 shadow-sm bg-info text-white h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h6 class="text-white-50 small mb-1">Rata-rata Nilai Semester Ini</h6>
                        <h2 class="mb-0 fw-bold">{{ number_format($rataRataNilai, 1) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card border-0 shadow-sm bg-success text-white h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h6 class="text-white-50 small mb-1">Total Kehadiran (Hadir)</h6>
                        <h2 class="mb-0 fw-bold">{{ $absensi->get('Hadir', 0) }} Hari</h2>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card border-0 shadow-sm bg-warning text-dark h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h6 class="text-dark-50 small mb-1">Izin / Sakit</h6>
                        <h2 class="mb-0 fw-bold text-dark">{{ $absensi->get('Izin', 0) + $absensi->get('Sakit', 0) }} Hari</h2>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card border-0 shadow-sm bg-danger text-white h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h6 class="text-white-50 small mb-1">Tanpa Keterangan (Alfa)</h6>
                        <h2 class="mb-0 fw-bold">{{ $absensi->get('Alfa', 0) }} Hari</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Rincian Nilai -->
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-journal-text me-2"></i>Rincian Nilai Mata Pelajaran</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                        <thead class="table-light text-muted" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                            <tr>
                                <th class="ps-4">Mata Pelajaran</th>
                                <th class="text-center">Harian</th>
                                <th class="text-center">Tugas</th>
                                <th class="text-center">UTS</th>
                                <th class="text-center">UAS</th>
                                <th class="text-center">Hafalan</th>
                                <th class="text-center">Adab</th>
                                <th class="text-center fw-bold bg-primary-subtle text-primary">Nilai Akhir</th>
                                <th class="pe-4 text-center">Predikat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($nilai as $n)
                                @php
                                    $na = $n->nilai_akhir ?? 0;
                                    $predClass = $na >= 80 ? 'bg-success-subtle text-success' : ($na >= 70 ? 'bg-primary-subtle text-primary' : ($na >= 60 ? 'bg-warning-subtle text-warning-dark' : 'bg-danger-subtle text-danger'));
                                @endphp
                                <tr>
                                    <td class="ps-4"><strong>{{ $n->mapel->nama }}</strong></td>
                                    <td class="text-center">{{ $n->nilai_harian ?? '-' }}</td>
                                    <td class="text-center">{{ $n->nilai_tugas ?? '-' }}</td>
                                    <td class="text-center">{{ $n->nilai_uts ?? '-' }}</td>
                                    <td class="text-center">{{ $n->nilai_uas ?? '-' }}</td>
                                    <td class="text-center">{{ $n->nilai_hafalan ?? '-' }}</td>
                                    <td class="text-center">{{ $n->nilai_adab ?? '-' }}</td>
                                    <td class="text-center fw-bold text-primary bg-primary-subtle">{{ number_format($na, 1) }}</td>
                                    <td class="pe-4 text-center">
                                        <span class="badge {{ $predClass }} rounded-pill px-2 py-1" style="font-size: 11px;">
                                            {{ $n->predikat ?? \App\Models\Nilai::hitungPredikat($na) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">Belum ada data nilai.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Catatan Pembinaan -->
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-chat-left-text me-2 text-warning"></i>Catatan Pembinaan & Prestasi</h5>
            </div>
            <div class="card-body">
                @forelse($catatan as $c)
                    <div class="d-flex mb-3 pb-3 border-bottom">
                        <div class="me-3">
                            @if($c->jenis == 'Prestasi')
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="bi bi-trophy"></i></div>
                            @elseif($c->jenis == 'Pelanggaran')
                                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="bi bi-exclamation-triangle"></i></div>
                            @else
                                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="bi bi-info-circle"></i></div>
                            @endif
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold text-dark">{{ $c->jenis }}</h6>
                            <div class="small text-muted mb-1"><i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($c->tanggal)->isoFormat('D MMMM Y') }} | Oleh: {{ $c->ustadz->nama_lengkap ?? '-' }}</div>
                            <p class="mb-0 small text-muted">{{ $c->keterangan }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-3">Belum ada catatan pembinaan.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Perkembangan Santri')
@section('page-title', 'Perkembangan Ananda: ' . $anak->user->name)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('wali.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Perkembangan Anak</li>
@endsection

@section('content')
<div class="row g-4">
    <!-- Profil Singkat -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                @if($anak->user->foto)
                    <img src="{{ asset('storage/'.$anak->user->foto) }}" class="rounded-circle mb-3" width="120" height="120" alt="Foto">
                @else
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px;">
                        <i class="bi bi-person text-secondary" style="font-size: 4rem;"></i>
                    </div>
                @endif
                <h5 class="mb-1 fw-bold">{{ $anak->user->name }}</h5>
                <p class="text-muted mb-3">NISN: {{ $anak->nisn }} | Kelas: {{ $anak->kelas->nama_kelas ?? '-' }}</p>
                
                @if($raportTersedia)
                    <a href="{{ route('wali.anak.raport', $anak->id) }}" class="btn btn-success w-100">
                        <i class="bi bi-download"></i> Download Raport
                    </a>
                @else
                    <button class="btn btn-secondary w-100" disabled>Raport Belum Tersedia</button>
                @endif
            </div>
        </div>
    </div>

    <!-- Ringkasan Kehadiran & Nilai -->
    <div class="col-md-8">
        <div class="row g-3 h-100">
            <div class="col-sm-6">
                <div class="card bg-info text-white h-100">
                    <div class="card-body">
                        <h6 class="text-white-50">Rata-rata Nilai Semester Ini</h6>
                        <h2 class="mb-0">{{ number_format($rataRataNilai, 1) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
                        <h6 class="text-white-50">Total Kehadiran (Hadir)</h6>
                        <h2 class="mb-0">{{ $rekapAbsen['Hadir'] ?? 0 }} Hari</h2>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card bg-warning text-dark h-100">
                    <div class="card-body">
                        <h6 class="text-dark-50">Izin / Sakit</h6>
                        <h2 class="mb-0">{{ ($rekapAbsen['Izin'] ?? 0) + ($rekapAbsen['Sakit'] ?? 0) }} Hari</h2>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card bg-danger text-white h-100">
                    <div class="card-body">
                        <h6 class="text-white-50">Tanpa Keterangan (Alfa)</h6>
                        <h2 class="mb-0">{{ $rekapAbsen['Alfa'] ?? 0 }} Hari</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Rincian Nilai -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-journal-text text-primary me-2"></i>Rincian Nilai Mata Pelajaran</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mata Pelajaran</th>
                                <th class="text-center">Harian</th>
                                <th class="text-center">Tugas</th>
                                <th class="text-center">UTS</th>
                                <th class="text-center">UAS</th>
                                <th class="text-center bg-primary text-white">Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($nilais as $n)
                                <tr>
                                    <td><strong>{{ $n->mapel->nama_mapel }}</strong></td>
                                    <td class="text-center">{{ $n->nilai_harian ?? '-' }}</td>
                                    <td class="text-center">{{ $n->nilai_tugas ?? '-' }}</td>
                                    <td class="text-center">{{ $n->nilai_uts ?? '-' }}</td>
                                    <td class="text-center">{{ $n->nilai_uas ?? '-' }}</td>
                                    <td class="text-center fw-bold text-primary">{{ $n->nilai_akhir ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">Belum ada data nilai.</td>
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
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-chat-left-text text-primary me-2"></i>Catatan Pembinaan</h5>
            </div>
            <div class="card-body">
                @forelse($catatans as $c)
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
                            <h6 class="mb-1">{{ $c->jenis }}</h6>
                            <div class="small text-muted mb-1">{{ \Carbon\Carbon::parse($c->tanggal)->format('d M Y') }}</div>
                            <p class="mb-0">{{ $c->keterangan }}</p>
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

@extends('layouts.app')
@section('title', 'Dashboard Ustadz')
@section('page-title', 'Dashboard Ustadz / Guru')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Jadwal Mengajar</h6>
                        <h3 class="mb-0">{{ $jadwalCount }}</h3>
                    </div>
                    <div class="fs-1 opacity-50"><i class="bi bi-calendar-event"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Total Santri Diajar</h6>
                        <h3 class="mb-0">{{ $santriCount }}</h3>
                    </div>
                    <div class="fs-1 opacity-50"><i class="bi bi-people"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-calendar-check text-primary me-2"></i>Jadwal Mengajar Hari Ini</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Waktu</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwalHariIni as $j)
                                <tr>
                                    <td>{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</td>
                                    <td><strong>{{ $j->mapel->nama ?? '-' }}</strong></td>
                                    <td>{{ $j->kelas->nama ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('ustadz.absensi.index', ['kelas_id' => $j->kelas_id, 'jadwal_id' => $j->id]) }}" class="btn btn-sm btn-outline-primary">Input Absen</a>
                                        <a href="{{ route('ustadz.nilai.index', ['kelas_id' => $j->kelas_id, 'mapel_id' => $j->mapel_id]) }}" class="btn btn-sm btn-outline-success">Input Nilai</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Tidak ada jadwal mengajar hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-megaphone text-warning me-2"></i>Pengumuman Terbaru</h5>
            </div>
            <div class="card-body">
                @forelse($pengumuman as $p)
                    <div class="border-bottom pb-3 mb-3">
                        <h6 class="mb-1 text-primary">{{ $p->judul }}</h6>
                        <div class="small text-muted mb-2"><i class="bi bi-clock"></i> {{ $p->created_at->diffForHumans() }}</div>
                        <p class="mb-0 small">{{ Str::limit($p->isi, 100) }}</p>
                    </div>
                @empty
                    <div class="text-center text-muted py-3">Belum ada pengumuman.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

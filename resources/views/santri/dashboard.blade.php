@extends('layouts.app')
@section('title', 'Dashboard Santri')
@section('page-title', 'Ahlan wa Sahlan, ' . Auth::user()->name)
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Total Kehadiran</h6>
                        <h3 class="mb-0">{{ $hadirCount ?? 0 }} Hari</h3>
                    </div>
                    <div class="fs-1 opacity-50"><i class="bi bi-calendar-check"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Rata-rata Nilai (Semester Ini)</h6>
                        <h3 class="mb-0">{{ number_format($rataRataNilai ?? 0, 1) }}</h3>
                    </div>
                    <div class="fs-1 opacity-50"><i class="bi bi-bar-chart"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Status Raport</h6>
                        <h3 class="mb-0">{{ $raportTersedia ? 'Tersedia' : 'Belum' }}</h3>
                    </div>
                    <div class="fs-1 opacity-50"><i class="bi bi-file-earmark-text"></i></div>
                </div>
                @if($raportTersedia)
                    <div class="mt-2">
                        <a href="{{ route('santri.raport.download', $raport->id) }}" class="btn btn-sm btn-light text-success w-100">
                            <i class="bi bi-download"></i> Unduh Raport
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-calendar3 text-primary me-2"></i>Jadwal Pelajaran Hari Ini</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Waktu</th>
                                <th>Mata Pelajaran</th>
                                <th>Ustadz / Guru</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwalHariIni ?? [] as $j)
                                <tr>
                                    <td>{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</td>
                                    <td><strong>{{ $j->mapel->nama }}</strong></td>
                                    <td>{{ $j->ustadz->nama_lengkap }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Tidak ada jadwal pelajaran hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">


        {{-- Pengumuman --}}
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-megaphone text-warning me-2"></i>Pengumuman Pondok</h5>
            </div>
            <div class="card-body">
                @forelse($pengumumans ?? [] as $p)
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

@push('scripts')

@endpush

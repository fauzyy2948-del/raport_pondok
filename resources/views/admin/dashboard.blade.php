@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
{{-- Greeting --}}
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
    <div>
        <h4 class="fw-700 mb-1" style="color:var(--primary);">
            Assalamu'alaikum, {{ Str::limit(auth()->user()->name, 20) }} 👋
        </h4>
        <p class="text-muted mb-0" style="font-size:13px;">
            <i class="bi bi-calendar3 me-1"></i>
            {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
            @if($tahunAktif)
                &nbsp;|&nbsp; Tahun Ajaran: <strong>{{ $tahunAktif->label }}</strong>
            @endif
        </p>
    </div>
    @if(!$tahunAktif)
        <a href="{{ route('admin.tahun-ajaran.create') }}" class="btn btn-gold btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Atur Tahun Ajaran
        </a>
    @endif
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card green h-100">
            <span class="stat-icon">🎓</span>
            <div class="stat-value">{{ number_format($stats['total_santri']) }}</div>
            <div class="stat-label">Total Santri Aktif</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card gold h-100">
            <span class="stat-icon">👨‍🏫</span>
            <div class="stat-value">{{ number_format($stats['total_ustadz']) }}</div>
            <div class="stat-label">Ustadz / Guru</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card blue h-100">
            <span class="stat-icon">🏫</span>
            <div class="stat-value">{{ number_format($stats['total_kelas']) }}</div>
            <div class="stat-label">Kelas / Kamar</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card purple h-100">
            <span class="stat-icon">📚</span>
            <div class="stat-value">{{ number_format($stats['total_mapel']) }}</div>
            <div class="stat-label">Mata Pelajaran</div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="row g-3 mb-4">
    <div class="col-md-12">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-bar-chart-fill me-2 text-primary"></i>Santri per Kelas</span>
            </div>
            <div class="card-body">
                <canvas id="chartSantriKelas" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Bottom Row --}}
<div class="row g-3">
    {{-- Pengumuman Terbaru --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-megaphone-fill me-2 text-primary"></i>Pengumuman Terbaru</span>
                <a href="{{ route('admin.pengumuman.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus"></i> Tambah
                </a>
            </div>
            <div class="card-body p-0">
                @forelse($pengumuman as $p)
                    <div class="d-flex align-items-start gap-3 p-3 border-bottom" style="border-color:var(--gray-200)!important;">
                        <div class="mt-1">
                            @if($p->prioritas === 'urgent')
                                <span class="badge bg-danger">Urgent</span>
                            @elseif($p->prioritas === 'tinggi')
                                <span class="badge bg-warning">Penting</span>
                            @else
                                <span class="badge bg-primary">Info</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="fw-600" style="font-size:13px;">{{ $p->judul }}</div>
                            <small class="text-muted">{{ $p->tanggal_mulai->format('d M Y') }} &middot; {{ ucfirst($p->target) }}</small>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-megaphone fs-3 d-block mb-2 opacity-25"></i>
                        Belum ada pengumuman
                    </div>
                @endforelse
            </div>
            @if($pengumuman->isNotEmpty())
                <div class="card-body pt-2 pb-3">
                    <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-outline-secondary btn-sm w-100" style="font-size:12px;">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Kalender Akademik --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-event-fill me-2 text-primary"></i>Agenda Mendatang</span>
            </div>
            <div class="card-body p-0">
                @forelse($kalender as $k)
                    <div class="d-flex align-items-center gap-3 p-3 border-bottom" style="border-color:var(--gray-200)!important;">
                        <div class="text-center" style="min-width:42px;background:var(--light);border-radius:8px;padding:6px;">
                            <div style="font-size:18px;font-weight:800;color:var(--primary);line-height:1;">{{ $k->tanggal_mulai->format('d') }}</div>
                            <div style="font-size:10px;color:var(--gray-500);text-transform:uppercase;">{{ $k->tanggal_mulai->format('M') }}</div>
                        </div>
                        <div class="flex-1">
                            <div class="fw-600" style="font-size:13px;">{{ $k->nama }}</div>
                            <small class="text-muted">{{ ucfirst($k->jenis) }}</small>
                        </div>
                        <span class="badge" style="background:{{ $k->warna }}22;color:{{ $k->warna }};">{{ ucfirst($k->jenis) }}</span>
                    </div>
                @empty
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-calendar3 fs-3 d-block mb-2 opacity-25"></i>
                        Tidak ada agenda mendatang
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Chart Santri per Kelas
const ctxKelas = document.getElementById('chartSantriKelas');
if (ctxKelas) {
    new Chart(ctxKelas, {
        type: 'bar',
        data: {
            labels: {!! json_encode($santriPerKelas->pluck('nama')) !!},
            datasets: [{
                label: 'Jumlah Santri',
                data: {!! json_encode($santriPerKelas->pluck('jumlah')) !!},
                backgroundColor: 'rgba(27,107,58,0.8)',
                borderColor: '#1B6B3A',
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });
}


</script>
@endpush

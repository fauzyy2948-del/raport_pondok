@extends('layouts.app')

@section('title', 'Dashboard Raport')
@section('page-title', 'Dashboard Raport')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.raport.index') }}">Raport</a></li>
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@push('styles')
<style>
/* ====== RAPORT DASHBOARD STYLES ====== */
.raport-hero {
    background: linear-gradient(135deg, #0d3d20 0%, #1B6B3A 50%, #2E8B57 100%);
    border-radius: 16px;
    padding: 28px 32px;
    color: white;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
}
.raport-hero::before {
    content: '📋';
    position: absolute;
    right: 24px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 90px;
    opacity: 0.12;
    pointer-events: none;
}
.raport-hero::after {
    content: '';
    position: absolute;
    top: -60px; left: -60px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
    pointer-events: none;
}
.raport-hero h2 { font-size: 24px; font-weight: 800; margin-bottom: 4px; }
.raport-hero p { font-size: 13px; opacity: 0.8; margin-bottom: 0; }

.filter-bar {
    background: white;
    border-radius: 12px;
    padding: 16px 20px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}
.filter-bar label { font-size: 12px; font-weight: 600; color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap; }

/* Stat Cards special */
.stat-raport {
    border-radius: 14px;
    padding: 22px 24px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}
.stat-raport:hover { transform: translateY(-4px); box-shadow: 0 10px 30px rgba(0,0,0,0.14); }
.stat-raport .sr-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    margin-bottom: 14px;
}
.stat-raport .sr-value { font-size: 34px; font-weight: 800; line-height: 1; margin-bottom: 4px; }
.stat-raport .sr-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.7px; opacity: 0.7; }
.stat-raport .sr-sub { font-size: 12px; margin-top: 10px; padding-top: 10px; border-top: 1px solid rgba(0,0,0,0.06); }

.sr-green { background: linear-gradient(135deg, #f0fdf4, #dcfce7); color: #14532d; }
.sr-green .sr-icon { background: #16a34a22; color: #16a34a; }
.sr-blue { background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #1e3a8a; }
.sr-blue .sr-icon { background: #2563eb22; color: #2563eb; }
.sr-amber { background: linear-gradient(135deg, #fffbeb, #fef3c7); color: #78350f; }
.sr-amber .sr-icon { background: #d9770622; color: #d97706; }
.sr-rose { background: linear-gradient(135deg, #fff1f2, #ffe4e6); color: #881337; }
.sr-rose .sr-icon { background: #e1193322; color: #e11933; }

/* Progress Kelas Cards */
.kelas-card {
    background: white;
    border-radius: 12px;
    padding: 18px 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    transition: all 0.25s ease;
    border-left: 4px solid transparent;
    height: 100%;
}
.kelas-card:hover { transform: translateX(4px); box-shadow: 0 4px 20px rgba(0,0,0,0.10); }
.kelas-card.done { border-left-color: #16a34a; }
.kelas-card.partial { border-left-color: #d97706; }
.kelas-card.empty { border-left-color: #dc2626; }
.kelas-card .kc-title { font-size: 14px; font-weight: 700; color: var(--gray-800); margin-bottom: 2px; }
.kelas-card .kc-sub { font-size: 11px; color: var(--gray-500); }
.kelas-card .kc-stats { display: flex; gap: 12px; margin-top: 12px; font-size: 12px; }
.kelas-card .kc-stat { text-align: center; flex: 1; }
.kelas-card .kc-stat .val { font-size: 18px; font-weight: 800; line-height: 1; }
.kelas-card .kc-stat .lbl { font-size: 10px; color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.3px; }
.kc-progress { height: 6px; border-radius: 10px; background: var(--gray-200); margin-top: 14px; overflow: hidden; }
.kc-progress-bar { height: 100%; border-radius: 10px; transition: width 0.8s ease; }

/* Chart card */
.chart-card {
    background: white;
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    overflow: hidden;
}
.chart-card .cc-header {
    padding: 18px 22px;
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.chart-card .cc-title { font-size: 14px; font-weight: 700; color: var(--gray-800); }
.chart-card .cc-body { padding: 20px 22px; }

/* Top Santri */
.top-santri-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 0;
    border-bottom: 1px solid var(--gray-200);
    transition: all 0.2s;
}
.top-santri-item:last-child { border-bottom: none; }
.top-santri-item:hover { background: var(--light); margin: 0 -22px; padding: 12px 22px; border-radius: 8px; }
.rank-badge {
    width: 30px; height: 30px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 800;
    flex-shrink: 0;
}
.rank-1 { background: linear-gradient(135deg, #fbbf24, #d97706); color: white; }
.rank-2 { background: linear-gradient(135deg, #94a3b8, #64748b); color: white; }
.rank-3 { background: linear-gradient(135deg, #cd7c2a, #b5621e); color: white; }
.rank-other { background: var(--gray-200); color: var(--gray-500); }
.santri-avatar-sm {
    width: 36px; height: 36px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--gray-200);
    flex-shrink: 0;
}
.top-santri-nama { font-size: 13px; font-weight: 600; color: var(--gray-800); line-height: 1.3; }
.top-santri-kelas { font-size: 11px; color: var(--gray-500); }
.nilai-badge-lg {
    font-size: 15px; font-weight: 800;
    padding: 4px 12px;
    border-radius: 8px;
    margin-left: auto;
    flex-shrink: 0;
}

/* Raport Terbaru */
.raport-terbaru-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 20px;
    border-bottom: 1px solid var(--gray-200);
    font-size: 13px;
    transition: all 0.2s;
}
.raport-terbaru-item:last-child { border-bottom: none; }
.raport-terbaru-item:hover { background: var(--light); }
.rt-avatar {
    width: 34px; height: 34px;
    border-radius: 50%; object-fit: cover;
    border: 2px solid var(--light);
    flex-shrink: 0;
}
.rt-nama { font-weight: 600; color: var(--gray-800); line-height: 1.2; }
.rt-meta { font-size: 11px; color: var(--gray-500); }

/* Predikat Legend */
.predikat-legend { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; justify-content: center; }
.predikat-legend-item { display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 500; }
.predikat-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }

/* Quick Actions */
.quick-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 18px 12px;
    border-radius: 12px;
    border: 2px solid var(--gray-200);
    background: white;
    text-decoration: none;
    color: var(--gray-800);
    font-size: 12px;
    font-weight: 600;
    transition: all 0.25s;
    text-align: center;
}
.quick-action-btn:hover {
    border-color: var(--primary);
    background: var(--light);
    color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(27,107,58,0.15);
}
.quick-action-btn .qa-icon { font-size: 24px; }

/* Animate in */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}
.anim-card { animation: fadeUp 0.5s ease both; }
.anim-card:nth-child(1) { animation-delay: 0.05s; }
.anim-card:nth-child(2) { animation-delay: 0.10s; }
.anim-card:nth-child(3) { animation-delay: 0.15s; }
.anim-card:nth-child(4) { animation-delay: 0.20s; }

[data-theme="dark"] .stat-raport.sr-green { background: linear-gradient(135deg, #0d2e1a, #14391f); color: #86efac; }
[data-theme="dark"] .stat-raport.sr-blue { background: linear-gradient(135deg, #0c1a35, #102044); color: #93c5fd; }
[data-theme="dark"] .stat-raport.sr-amber { background: linear-gradient(135deg, #2d1a00, #3b2000); color: #fcd34d; }
[data-theme="dark"] .stat-raport.sr-rose { background: linear-gradient(135deg, #2d0b14, #3b0f1a); color: #fda4af; }
[data-theme="dark"] .kelas-card { background: #1e1e2e; }
[data-theme="dark"] .chart-card { background: #1e1e2e; }
[data-theme="dark"] .filter-bar { background: #1e1e2e; }
[data-theme="dark"] .quick-action-btn { background: #1e1e2e; border-color: #333; color: #e0e0e0; }
[data-theme="dark"] .top-santri-item:hover { background: #252535; }
[data-theme="dark"] .raport-terbaru-item:hover { background: #252535; }
</style>
@endpush

@section('content')

{{-- Hero Banner --}}
<div class="raport-hero anim-card">
    <div class="row align-items-center">
        <div class="col-md-7">
            <h2>Dashboard Raport 📊</h2>
            <p>
                Pantau progres penerbitan, distribusi nilai, dan performa santri secara real-time.
                @if($tahunSelected)
                    &nbsp;|&nbsp; <strong>{{ $tahunSelected->label }}</strong>
                @endif
            </p>
        </div>
        <div class="col-md-5 d-flex gap-2 justify-content-md-end mt-3 mt-md-0 flex-wrap">
            <a href="{{ route('admin.raport.index') }}" class="btn btn-gold btn-sm">
                <i class="bi bi-list-ul me-1"></i>Kelola Raport
            </a>
            <a href="{{ route('admin.raport.index') }}" class="btn btn-sm" style="background:rgba(255,255,255,0.2);color:white;border:1px solid rgba(255,255,255,0.3);">
                <i class="bi bi-download me-1"></i>Export
            </a>
        </div>
    </div>
</div>

{{-- Filter Tahun Ajaran --}}
<form method="GET" action="{{ route('admin.raport.dashboard') }}" class="filter-bar">
    <label><i class="bi bi-funnel me-1"></i>Filter:</label>
    <select name="tahun_ajaran_id" class="form-select form-select-sm" style="width:auto;min-width:220px;" onchange="this.form.submit()">
        <option value="">Semua Tahun Ajaran</option>
        @foreach($tahunAjaran as $ta)
            <option value="{{ $ta->id }}" {{ $selectedTahun == $ta->id ? 'selected' : '' }}>
                {{ $ta->label }}{{ $ta->aktif ? ' ✦ Aktif' : '' }}
            </option>
        @endforeach
    </select>
    @if($selectedTahun)
        <a href="{{ route('admin.raport.dashboard') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-x me-1"></i>Reset
        </a>
    @endif
    <span class="ms-auto text-muted" style="font-size:12px;">
        <i class="bi bi-clock me-1"></i>Update: {{ now()->locale('id')->isoFormat('D MMM Y, HH:mm') }}
    </span>
</form>

{{-- Stat Cards Row --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 anim-card">
        <div class="stat-raport sr-green h-100">
            <div class="sr-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
            <div class="sr-value">{{ number_format($totalRaport) }}</div>
            <div class="sr-label">Total Raport</div>
            <div class="sr-sub">
                <i class="bi bi-people me-1"></i>dari {{ $totalSantriAktif }} santri aktif
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 anim-card">
        <div class="stat-raport sr-blue h-100">
            <div class="sr-icon"><i class="bi bi-patch-check-fill"></i></div>
            <div class="sr-value">{{ number_format($raportTerbit) }}</div>
            <div class="sr-label">Sudah Diterbitkan</div>
            <div class="sr-sub">
                @php $pctTerbit = $totalRaport > 0 ? round(($raportTerbit/$totalRaport)*100) : 0; @endphp
                <div class="d-flex justify-content-between mb-1">
                    <span>Progress</span><span class="fw-700">{{ $pctTerbit }}%</span>
                </div>
                <div class="kc-progress">
                    <div class="kc-progress-bar" style="width:{{ $pctTerbit }}%;background:#2563eb;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 anim-card">
        <div class="stat-raport sr-amber h-100">
            <div class="sr-icon"><i class="bi bi-hourglass-split"></i></div>
            <div class="sr-value">{{ number_format($raportBelumTerbit) }}</div>
            <div class="sr-label">Belum Diterbitkan</div>
            <div class="sr-sub">
                <i class="bi bi-exclamation-circle me-1"></i>Menunggu penerbitan
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 anim-card">
        <div class="stat-raport sr-rose h-100">
            <div class="sr-icon"><i class="bi bi-graph-up-arrow"></i></div>
            <div class="sr-value">{{ number_format($rataRataUmum, 1) }}</div>
            <div class="sr-label">Rata-rata Nilai</div>
            <div class="sr-sub">
                <span class="badge" style="font-size:11px;background:{{ $rataRataUmum >= 80 ? '#d1fae5;color:#065f46' : ($rataRataUmum >= 70 ? '#fef3c7;color:#92400e' : '#fee2e2;color:#991b1b') }};">
                    {{ \App\Models\Nilai::hitungPredikat($rataRataUmum) }}
                </span>
                &nbsp;Predikat Rata-rata
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="row g-2 mb-4">
    <div class="col-12">
        <div class="chart-card">
            <div class="cc-header">
                <span class="cc-title"><i class="bi bi-lightning-fill me-2 text-warning"></i>Aksi Cepat</span>
            </div>
            <div class="cc-body">
                <div class="row g-2">
                    <div class="col-6 col-md-3">
                        <a href="{{ route('admin.raport.index') }}" class="quick-action-btn w-100">
                            <span class="qa-icon">📋</span>
                            <span>Kelola Raport</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('admin.raport.index') }}" class="quick-action-btn w-100">
                            <span class="qa-icon">⚡</span>
                            <span>Generate Raport</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('admin.santri.index') }}" class="quick-action-btn w-100">
                            <span class="qa-icon">🎓</span>
                            <span>Data Santri</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('admin.tahun-ajaran.index') }}" class="quick-action-btn w-100">
                            <span class="qa-icon">📅</span>
                            <span>Tahun Ajaran</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="row g-3 mb-4">
    {{-- Distribusi Predikat --}}
    <div class="col-md-5">
        <div class="chart-card h-100">
            <div class="cc-header">
                <span class="cc-title"><i class="bi bi-pie-chart-fill me-2" style="color:#8b5cf6;"></i>Distribusi Predikat</span>
                <span class="badge" style="background:var(--light);color:var(--gray-800);font-size:11px;">
                    {{ $distribusiPredikat->sum('jumlah') }} raport
                </span>
            </div>
            <div class="cc-body">
                @if($distribusiPredikat->isEmpty())
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-pie-chart fs-1 d-block mb-3 opacity-25"></i>
                        Belum ada data raport
                    </div>
                @else
                    <div class="d-flex justify-content-center" style="height:200px;">
                        <canvas id="chartPredikat"></canvas>
                    </div>
                    <div class="predikat-legend">
                        @php
                        $predikatColors = [
                            'A'  => '#10b981',
                            'B+' => '#3b82f6',
                            'B'  => '#6366f1',
                            'C+' => '#f59e0b',
                            'C'  => '#f97316',
                            'D'  => '#ef4444',
                            'E'  => '#6b7280',
                        ];
                        @endphp
                        @foreach($distribusiPredikat as $dp)
                        <div class="predikat-legend-item">
                            <div class="predikat-dot" style="background:{{ $predikatColors[$dp->predikat_akhir] ?? '#999' }};"></div>
                            <span>{{ $dp->predikat_akhir }}: <strong>{{ $dp->jumlah }}</strong></span>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Nilai Rata-rata per Mapel --}}
    <div class="col-md-7">
        <div class="chart-card h-100">
            <div class="cc-header">
                <span class="cc-title"><i class="bi bi-bar-chart-fill me-2" style="color:#1B6B3A;"></i>Nilai Rata-rata per Mata Pelajaran</span>
            </div>
            <div class="cc-body">
                @if($nilaiPerMapel->isEmpty())
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-bar-chart fs-1 d-block mb-3 opacity-25"></i>
                        Belum ada data nilai
                    </div>
                @else
                    <canvas id="chartMapel" style="height:220px;"></canvas>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Progress per Kelas --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="chart-card">
            <div class="cc-header">
                <span class="cc-title"><i class="bi bi-building me-2" style="color:#0891b2;"></i>Progress Raport per Kelas</span>
                <span class="text-muted" style="font-size:12px;">{{ $kelasProgress->count() }} kelas</span>
            </div>
            <div class="cc-body">
                @if($kelasProgress->isEmpty())
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-building fs-1 d-block mb-3 opacity-25"></i>
                        Belum ada data kelas
                    </div>
                @else
                    <div class="row g-3">
                        @foreach($kelasProgress as $kp)
                            @php
                                $statusClass = $kp['persen'] == 100 ? 'done' : ($kp['persen'] > 0 ? 'partial' : 'empty');
                                $barColor = $kp['persen'] == 100 ? '#16a34a' : ($kp['persen'] > 0 ? '#d97706' : '#dc2626');
                                $predikatRata = \App\Models\Nilai::hitungPredikat($kp['rata_rata']);
                            @endphp
                            <div class="col-md-6 col-xl-4">
                                <div class="kelas-card {{ $statusClass }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="kc-title">{{ $kp['nama'] }}</div>
                                            <div class="kc-sub">{{ $kp['total_santri'] }} santri aktif</div>
                                        </div>
                                        <span class="badge" style="font-size:11px;background:{{ $kp['persen']==100 ? '#d1fae5;color:#065f46' : ($kp['persen']>0 ? '#fef3c7;color:#92400e' : '#fee2e2;color:#991b1b') }}">
                                            {{ $kp['persen'] }}%
                                        </span>
                                    </div>
                                    <div class="kc-stats">
                                        <div class="kc-stat">
                                            <div class="val" style="color:#1B6B3A;">{{ $kp['sudah_generate'] }}</div>
                                            <div class="lbl">Generate</div>
                                        </div>
                                        <div class="kc-stat">
                                            <div class="val" style="color:#2563eb;">{{ $kp['terbit'] }}</div>
                                            <div class="lbl">Terbit</div>
                                        </div>
                                        <div class="kc-stat">
                                            <div class="val" style="color:#d97706;">{{ $kp['total_santri'] - $kp['sudah_generate'] }}</div>
                                            <div class="lbl">Belum</div>
                                        </div>
                                        <div class="kc-stat">
                                            <div class="val" style="color:{{ $kp['rata_rata'] >= 80 ? '#16a34a' : ($kp['rata_rata'] >= 70 ? '#d97706' : '#dc2626') }};">
                                                {{ $kp['rata_rata'] > 0 ? $kp['rata_rata'] : '-' }}
                                            </div>
                                            <div class="lbl">Rata-rata</div>
                                        </div>
                                    </div>
                                    <div class="kc-progress mt-3">
                                        <div class="kc-progress-bar" style="width:{{ $kp['persen'] }}%;background:{{ $barColor }};"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Bottom Row: Top Santri + Raport Terbaru --}}
<div class="row g-3">
    {{-- Top 10 Santri --}}
    <div class="col-md-6">
        <div class="chart-card h-100">
            <div class="cc-header">
                <span class="cc-title"><i class="bi bi-trophy-fill me-2" style="color:#d97706;"></i>Top 10 Santri Berprestasi</span>
                <a href="{{ route('admin.raport.index') }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px;">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="cc-body" style="padding:0 22px;">
                @forelse($topSantri as $i => $r)
                    @php
                        $rankClass = match($i) { 0 => 'rank-1', 1 => 'rank-2', 2 => 'rank-3', default => 'rank-other' };
                        $nilaiColor = $r->rata_rata >= 80 ? '#065f46' : ($r->rata_rata >= 70 ? '#92400e' : '#991b1b');
                        $niBg = $r->rata_rata >= 80 ? '#d1fae5' : ($r->rata_rata >= 70 ? '#fef3c7' : '#fee2e2');
                    @endphp
                    <div class="top-santri-item">
                        <div class="rank-badge {{ $rankClass }}">{{ $i + 1 }}</div>
                        @if($r->santri)
                            <img src="{{ $r->santri->foto_url ?? asset('images/default-santri.png') }}" class="santri-avatar-sm" alt="foto">
                            <div class="flex-1">
                                <div class="top-santri-nama">{{ $r->santri->nama ?? ($r->santri->user->name ?? '-') }}</div>
                                <div class="top-santri-kelas">{{ $r->kelas->nama ?? '-' }} &middot; {{ $r->predikat_akhir ?? '-' }}</div>
                            </div>
                        @else
                            <div class="flex-1"><div class="top-santri-nama text-muted">Santri tidak ditemukan</div></div>
                        @endif
                        <div class="nilai-badge-lg" style="background:{{ $niBg }};color:{{ $nilaiColor }};">
                            {{ number_format($r->rata_rata, 1) }}
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-trophy fs-1 d-block mb-3 opacity-25"></i>
                        Belum ada data raport
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Raport Baru Diterbitkan --}}
    <div class="col-md-6">
        <div class="chart-card h-100">
            <div class="cc-header">
                <span class="cc-title"><i class="bi bi-patch-check-fill me-2" style="color:#16a34a;"></i>Raport Baru Diterbitkan</span>
                <span class="badge" style="background:#d1fae5;color:#065f46;font-size:11px;">{{ $raportTerbit }} total</span>
            </div>
            <div style="max-height:420px;overflow-y:auto;">
                @forelse($raportTerbaru as $rt)
                    <div class="raport-terbaru-item">
                        @if($rt->santri)
                            <img src="{{ $rt->santri->foto_url ?? asset('images/default-santri.png') }}" class="rt-avatar" alt="foto">
                            <div class="flex-1">
                                <div class="rt-nama">{{ $rt->santri->nama ?? '-' }}</div>
                                <div class="rt-meta">
                                    {{ $rt->kelas->nama ?? '-' }}
                                    &middot; {{ $rt->diterbitkan_pada?->locale('id')->isoFormat('D MMM Y') ?? '-' }}
                                </div>
                            </div>
                        @else
                            <div class="rt-avatar d-flex align-items-center justify-content-center" style="background:var(--light);">
                                <i class="bi bi-person text-muted"></i>
                            </div>
                            <div class="flex-1">
                                <div class="rt-nama text-muted">Santri tidak ditemukan</div>
                            </div>
                        @endif
                        @php
                            $rn = $rt->rata_rata ?? 0;
                            $rnColor = $rn >= 80 ? '#065f46' : ($rn >= 70 ? '#92400e' : '#991b1b');
                            $rnBg = $rn >= 80 ? '#d1fae5' : ($rn >= 70 ? '#fef3c7' : '#fee2e2');
                        @endphp
                        <div style="text-align:right;flex-shrink:0;">
                            <div class="fw-700" style="font-size:13px;background:{{ $rnBg }};color:{{ $rnColor }};padding:2px 10px;border-radius:6px;">
                                {{ number_format($rn, 1) }}
                            </div>
                            <div style="font-size:10px;color:var(--gray-500);margin-top:2px;">{{ $rt->predikat_akhir ?? '-' }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-file-earmark-check fs-1 d-block mb-3 opacity-25"></i>
                        Belum ada raport yang diterbitkan
                    </div>
                @endforelse
            </div>
            @if($raportTerbit > 8)
                <div style="padding:12px 20px;border-top:1px solid var(--gray-200);">
                    <a href="{{ route('admin.raport.index') }}" class="btn btn-outline-secondary btn-sm w-100" style="font-size:12px;">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ---- Chart Distribusi Predikat ----
    const ctxPredikat = document.getElementById('chartPredikat');
    if (ctxPredikat) {
        const predikatData = @json($distribusiPredikat);
        const predikatColors = {
            'A': '#10b981', 'B+': '#3b82f6', 'B': '#6366f1',
            'C+': '#f59e0b', 'C': '#f97316', 'D': '#ef4444', 'E': '#6b7280'
        };
        new Chart(ctxPredikat, {
            type: 'doughnut',
            data: {
                labels: predikatData.map(d => 'Predikat ' + d.predikat_akhir),
                datasets: [{
                    data: predikatData.map(d => d.jumlah),
                    backgroundColor: predikatData.map(d => predikatColors[d.predikat_akhir] || '#999'),
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverBorderWidth: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = Math.round((ctx.parsed / total) * 100);
                                return ` ${ctx.parsed} santri (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // ---- Chart Nilai per Mapel ----
    const ctxMapel = document.getElementById('chartMapel');
    if (ctxMapel) {
        const mapelData = @json($nilaiPerMapel);
        const mapelLabels = mapelData.map(d => d.nama);
        const mapelNilai  = mapelData.map(d => d.rata_rata);

        // Color gradient based on value
        const barColors = mapelNilai.map(v =>
            v >= 85 ? 'rgba(16,185,129,0.85)'
            : v >= 75 ? 'rgba(59,130,246,0.85)'
            : v >= 65 ? 'rgba(245,158,11,0.85)'
            : 'rgba(239,68,68,0.85)'
        );

        new Chart(ctxMapel, {
            type: 'bar',
            data: {
                labels: mapelLabels,
                datasets: [{
                    label: 'Rata-rata Nilai',
                    data: mapelNilai,
                    backgroundColor: barColors,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` Rata-rata: ${ctx.parsed.x}`
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 100,
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        ticks: { font: { size: 11 } }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { font: { size: 12 }, color: '#374151' }
                    }
                }
            }
        });
    }

    // ---- Animate progress bars on scroll ----
    const progressBars = document.querySelectorAll('.kc-progress-bar[style*="width"]');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.transition = 'width 1s cubic-bezier(0.4,0,0.2,1)';
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });
    progressBars.forEach(bar => observer.observe(bar));
});
</script>
@endpush

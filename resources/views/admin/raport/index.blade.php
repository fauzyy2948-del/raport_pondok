@extends('layouts.app')
@section('title', 'Manajemen Raport')
@section('page-title', 'Manajemen Raport Santri')
@section('breadcrumb')
    <li class="breadcrumb-item active">Raport</li>
@endsection

@push('styles')
<style>
.raport-header-card {
    background: linear-gradient(135deg, #0d3d20 0%, #1B6B3A 50%, #2E8B57 100%);
    border-radius: 14px;
    padding: 22px 26px;
    color: white;
    margin-bottom: 22px;
    position: relative;
    overflow: hidden;
}
.raport-header-card::after {
    content:'📋';
    position:absolute;right:20px;top:50%;transform:translateY(-50%);
    font-size:80px;opacity:.1;pointer-events:none;
}
.raport-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}
.raport-status-badge.terbit   { background:#d1fae5; color:#065f46; }
.raport-status-badge.generated { background:#dbeafe; color:#1e40af; }
.raport-status-badge.empty     { background:#f3f4f6; color:#6b7280; }
.action-group { display: flex; gap: 6px; flex-wrap: wrap; justify-content: flex-end; }

/* Generate bulk modal */
.generate-modal-card {
    border-left: 4px solid #1B6B3A;
    border-radius: 8px;
    padding: 14px 18px;
    background: #f0fdf4;
    margin-bottom: 0;
}
[data-theme="dark"] .generate-modal-card { background: #0d2e1a; }

.nilai-chip {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 700;
}
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="raport-header-card">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h5 class="fw-800 mb-1">📋 Manajemen Raport</h5>
            <p class="mb-0" style="font-size:13px;opacity:.85;">
                Generate, kelola, dan terbitkan raport santri.
                @if($aktifTA)
                    &nbsp;|&nbsp; Tahun Aktif: <strong>{{ $aktifTA->label }}</strong>
                @endif
            </p>
        </div>
        <div class="col-md-6 d-flex gap-2 justify-content-md-end mt-3 mt-md-0 flex-wrap">
            <a href="{{ route('admin.raport.dashboard') }}" class="btn btn-sm" style="background:rgba(255,255,255,.2);color:white;border:1px solid rgba(255,255,255,.35);">
                <i class="bi bi-speedometer2 me-1"></i>Dashboard Raport
            </a>
            <button type="button" class="btn btn-gold btn-sm" data-bs-toggle="modal" data-bs-target="#modalGenerate">
                <i class="bi bi-lightning-fill me-1"></i>Generate Massal
            </button>
        </div>
    </div>
</div>

{{-- Filter Card --}}
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.raport.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Tahun Ajaran</label>
                <select name="tahun_ajaran_id" class="form-select">
                    <option value="">Semua Tahun Ajaran</option>
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" {{ ($selectedTahunId ?? '') == $ta->id ? 'selected' : '' }}>
                            {{ $ta->label }}{{ $ta->aktif ? ' ✦' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Kelas</label>
                <select name="kelas_id" class="form-select">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Cari Santri</label>
                <input type="text" name="search" class="form-control" placeholder="Nama atau NISN..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-1">
                    <i class="bi bi-search"></i> Cari
                </button>
                @if(request('search') || request('kelas_id') || request('tahun_ajaran_id'))
                    <a href="{{ route('admin.raport.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Summary Bar --}}
@php
    $totalSantriHalaman = $santris->total();
    $sudahGenerate = $santris->getCollection()->filter(fn($s) => $s->raport->isNotEmpty())->count();
    $terbit = $santris->getCollection()->filter(fn($s) => $s->raport->where('diterbitkan', true)->isNotEmpty())->count();
@endphp
<div class="d-flex gap-3 mb-3 flex-wrap" style="font-size:12px;">
    <span class="raport-status-badge" style="background:#f3f4f6;color:#374151;font-size:12px;">
        <i class="bi bi-people"></i> {{ number_format($santris->total()) }} santri aktif
    </span>
    <span class="raport-status-badge generated">
        <i class="bi bi-file-earmark-check"></i> {{ $sudahGenerate }} sudah generate (halaman ini)
    </span>
    <span class="raport-status-badge terbit">
        <i class="bi bi-patch-check"></i> {{ $terbit }} sudah terbit (halaman ini)
    </span>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Santri</th>
                        <th>Kelas</th>
                        <th>Status Raport</th>
                        <th>Rata-rata</th>
                        <th>Peringkat</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($santris as $no => $s)
                        @php
                            $raport = $s->raport->first();
                            $hasRaport = $raport !== null;
                            $isTerbit = $hasRaport && $raport->diterbitkan;
                        @endphp
                        <tr>
                            <td class="text-muted" style="font-size:12px;">
                                {{ $santris->firstItem() + $no }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div>
                                        <div class="fw-600" style="font-size:13px;">{{ $s->nama ?? $s->user?->name }}</div>
                                        <small class="text-muted">{{ $s->nisn }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background:#e0f2fe;color:#075985;">
                                    {{ $s->kelas?->nama ?? '-' }}
                                </span>
                            </td>
                            <td>
                                @if($isTerbit)
                                    <span class="raport-status-badge terbit">
                                        <i class="bi bi-patch-check-fill"></i> Diterbitkan
                                    </span>
                                @elseif($hasRaport)
                                    <span class="raport-status-badge generated">
                                        <i class="bi bi-file-earmark-check"></i> Sudah Generate
                                    </span>
                                @else
                                    <span class="raport-status-badge empty">
                                        <i class="bi bi-file-earmark"></i> Belum Generate
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($hasRaport)
                                    @php
                                        $rn = $raport->rata_rata;
                                        $rnColor = $rn >= 80 ? '#065f46' : ($rn >= 70 ? '#92400e' : '#991b1b');
                                        $rnBg    = $rn >= 80 ? '#d1fae5' : ($rn >= 70 ? '#fef3c7' : '#fee2e2');
                                    @endphp
                                    <span class="nilai-chip" style="background:{{ $rnBg }};color:{{ $rnColor }};">
                                        {{ number_format($rn, 1) }}
                                    </span>
                                    <small class="text-muted ms-1">{{ $raport->predikat_akhir }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($hasRaport)
                                    <small class="text-muted">
                                        Ke-{{ $raport->peringkat ?? '-' }} / {{ $raport->jumlah_siswa ?? '-' }}
                                    </small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="action-group">
                                    @if($hasRaport)
                                        {{-- Lihat Detail --}}
                                        <a href="{{ route('admin.raport.show', $raport->id) }}"
                                           class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        {{-- Cetak PDF --}}
                                        <a href="{{ route('admin.raport.cetak', $raport->id) }}"
                                           class="btn btn-sm btn-danger" target="_blank" title="Cetak PDF">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        </a>
                                        {{-- Terbitkan (jika belum) --}}
                                        @if(!$isTerbit)
                                            <form action="{{ route('admin.raport.terbitkan', $raport->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Terbitkan Raport"
                                                    onclick="return confirm('Terbitkan raport {{ $s->nama ?? $s->user?->name }}?')">
                                                    <i class="bi bi-send-fill"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span title="Sudah Diterbitkan" class="btn btn-sm" style="background:#d1fae5;color:#065f46;pointer-events:none;">
                                                <i class="bi bi-patch-check-fill"></i>
                                            </span>
                                        @endif
                                    @else
                                        {{-- Generate per santri --}}
                                        <form action="{{ route('admin.raport.generate') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="tahun_ajaran_id" value="{{ $selectedTahunId ?? $aktifTA?->id }}">
                                            <input type="hidden" name="santri_id_single" value="{{ $s->id }}">
                                            <button type="submit" class="btn btn-sm btn-primary" title="Generate Raport">
                                                <i class="bi bi-arrow-repeat me-1"></i>Generate
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2 opacity-25"></i>
                                Tidak ada santri aktif ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($santris->hasPages())
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Menampilkan {{ $santris->firstItem() }}–{{ $santris->lastItem() }} dari {{ $santris->total() }} santri
            </small>
            {{ $santris->links() }}
        </div>
    @endif
</div>

{{-- Modal Generate Massal --}}
<div class="modal fade" id="modalGenerate" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <form action="{{ route('admin.raport.generate') }}" method="POST">
                @csrf
                <div class="modal-header" style="border-bottom:1px solid var(--gray-200);">
                    <h5 class="modal-title fw-700">
                        <i class="bi bi-lightning-fill text-warning me-2"></i>Generate Raport Massal
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="generate-modal-card mb-3">
                        <small class="text-muted d-block mb-2">
                            <i class="bi bi-info-circle me-1"></i>
                            Generate akan membuat/memperbarui raport untuk semua santri yang sudah memiliki nilai.
                        </small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                        <select name="tahun_ajaran_id" class="form-select" required>
                            <option value="">Pilih Tahun Ajaran</option>
                            @foreach($tahunAjarans as $ta)
                                <option value="{{ $ta->id }}" {{ ($selectedTahunId ?? $aktifTA?->id) == $ta->id ? 'selected' : '' }}>
                                    {{ $ta->label }}{{ $ta->aktif ? ' ✦ Aktif' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Filter Kelas <span class="text-muted">(opsional)</span></label>
                        <select name="kelas_id" class="form-select">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Kosongkan untuk generate semua kelas sekaligus.</div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--gray-200);">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-lightning-fill me-1"></i>Generate Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto submit filter on select change
document.querySelectorAll('select[name="tahun_ajaran_id"]:not(.modal select), select[name="kelas_id"]:not(.modal select)')
    .forEach(el => {
        el.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
</script>
@endpush

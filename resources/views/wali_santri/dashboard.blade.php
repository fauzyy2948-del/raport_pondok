@extends('layouts.app')
@section('title', 'Dashboard Wali Santri')
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
                        <h6 class="text-white-50">Jumlah Anak</h6>
                        <h3 class="mb-0">{{ $anakCount ?? 0 }} Santri</h3>
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
                <h5 class="mb-0"><i class="bi bi-person-badge text-primary me-2"></i>Data Anak (Santri)</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Santri</th>
                                <th>NISN</th>
                                <th>Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($anaks ?? [] as $anak)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $anak->user->name }}</div>
                                    </td>
                                    <td>{{ $anak->nisn }}</td>
                                    <td>{{ $anak->kelas->nama_kelas ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('wali.anak.show', $anak->id) }}" class="btn btn-sm btn-outline-primary">
                                            Pantau Perkembangan
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Belum ada data anak terhubung ke akun Anda.</td>
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

@extends('layouts.app')
@section('title','Mata Pelajaran')
@section('page-title','Mata Pelajaran')
@section('breadcrumb')<li class="breadcrumb-item active">Mapel</li>@endsection
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="bi bi-book-fill me-2 text-primary"></i>Daftar Mata Pelajaran</span>
        <a href="{{ route('admin.mapel.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i>Tambah Mapel</a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-5"><div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span><input type="text" name="search" class="form-control" placeholder="Cari nama / kode..." value="{{ request('search') }}"></div></div>
            <div class="col-md-3"><select name="kategori" class="form-select"><option value="">Semua Kategori</option><option value="diniyah" {{ request('kategori') === 'diniyah' ? 'selected' : '' }}>Diniyah</option><option value="umum" {{ request('kategori') === 'umum' ? 'selected' : '' }}>Umum</option><option value="muatan_lokal" {{ request('kategori') === 'muatan_lokal' ? 'selected' : '' }}>Muatan Lokal</option></select></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Filter</button></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>#</th><th>Kode</th><th>Nama Mapel</th><th>Kategori</th><th>KKM</th><th>Bobot</th><th>Status</th><th width="90">Aksi</th></tr></thead>
                <tbody>
                @forelse($mapel as $i => $m)
                    <tr>
                        <td>{{ $mapel->firstItem() + $i }}</td>
                        <td><span class="badge bg-secondary">{{ $m->kode }}</span></td>
                        <td class="fw-600">{{ $m->nama }}</td>
                        <td>{!! $m->badge_kategori !!}</td>
                        <td>{{ $m->kkm }}</td>
                        <td>{{ $m->bobot }}</td>
                        <td>@if($m->aktif)<span class="badge bg-success">Aktif</span>@else<span class="badge bg-danger">Non-Aktif</span>@endif</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.mapel.edit', $m) }}" class="btn btn-sm" style="background:#fef3c7;color:#92400e;"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.mapel.destroy', $m) }}" class="d-inline">@csrf @method('DELETE')<button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#991b1b;" onclick="return confirm('Nonaktifkan mapel ini?')"><i class="bi bi-trash"></i></button></form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada mata pelajaran</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between mt-3 flex-wrap gap-2">
            <small class="text-muted">{{ $mapel->firstItem() ?? 0 }}–{{ $mapel->lastItem() ?? 0 }} dari {{ $mapel->total() }}</small>
            {{ $mapel->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

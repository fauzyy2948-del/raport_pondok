@extends('layouts.app')
@section('title', 'Data Ustadz')
@section('page-title', 'Data Ustadz / Guru')
@section('breadcrumb')
    <li class="breadcrumb-item active">Ustadz</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="bi bi-person-badge-fill me-2 text-primary"></i>Daftar Ustadz / Guru</span>
        <a href="{{ route('admin.ustadz.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Tambah Ustadz
        </a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama / NIP..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th><th>Nama / NIP</th><th>Jenis Kelamin</th>
                        <th>Status Kepegawaian</th><th>Telepon</th><th>Aktif</th><th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ustadz as $i => $u)
                    <tr>
                        <td class="text-muted">{{ $ustadz->firstItem() + $i }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $u->foto_url }}" class="avatar avatar-sm" alt="">
                                <div>
                                    <div class="fw-600">{{ $u->nama_lengkap }}</div>
                                    <small class="text-muted">{{ $u->nip ?? 'NIP belum diisi' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $u->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td><span class="badge bg-info">{{ $u->status }}</span></td>
                        <td>{{ $u->telepon ?? '-' }}</td>
                        <td>
                            @if($u->aktif)<span class="badge bg-success">Aktif</span>
                            @else<span class="badge bg-danger">Non-Aktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.ustadz.edit', $u) }}" class="btn btn-sm" style="background:#fef3c7;color:#92400e;"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.ustadz.destroy', $u) }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#991b1b;"
                                        onclick="return confirm('Hapus data {{ $u->nama }}?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted"><i class="bi bi-person-badge fs-2 d-block mb-2 opacity-25"></i>Tidak ada data ustadz</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <small class="text-muted">Menampilkan {{ $ustadz->firstItem() ?? 0 }}–{{ $ustadz->lastItem() ?? 0 }} dari {{ $ustadz->total() }}</small>
            {{ $ustadz->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

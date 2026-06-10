@extends('layouts.app')
@section('title', 'Data Santri')
@section('page-title', 'Data Santri')
@section('breadcrumb')
    <li class="breadcrumb-item active">Santri</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="bi bi-people-fill me-2 text-primary"></i>Daftar Santri</span>
        <a href="{{ route('admin.santri.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Tambah Santri
        </a>
    </div>
    <div class="card-body">
        {{-- Filter --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama / NISN..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="kelas_id" class="form-select">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="alumni" {{ request('status') === 'alumni' ? 'selected' : '' }}>Alumni</option>
                    <option value="keluar" {{ request('status') === 'keluar' ? 'selected' : '' }}>Keluar</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>Nama / NISN</th>
                        <th>Kelas</th>
                        <th>L/P</th>
                        <th>Wali</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($santri as $i => $s)
                    <tr>
                        <td class="text-muted">{{ $santri->firstItem() + $i }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $s->foto_url }}" class="avatar avatar-sm" alt="">
                                <div>
                                    <div class="fw-600">{{ $s->nama }}</div>
                                    <small class="text-muted">{{ $s->nisn }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $s->kelas?->nama ?? '<span class="text-muted">-</span>' }}</td>
                        <td><span class="badge {{ $s->jenis_kelamin === 'L' ? 'bg-primary' : 'bg-danger' }}">{{ $s->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span></td>
                        <td>{{ $s->waliSantri?->nama ?? '-' }}</td>
                        <td>
                            @if($s->status === 'aktif')<span class="badge bg-success">Aktif</span>
                            @elseif($s->status === 'alumni')<span class="badge bg-info">Alumni</span>
                            @else<span class="badge bg-secondary">{{ ucfirst($s->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.santri.show', $s) }}" class="btn btn-sm" style="background:var(--light);color:var(--primary);" data-bs-toggle="tooltip" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.santri.edit', $s) }}" class="btn btn-sm" style="background:#fef3c7;color:#92400e;" data-bs-toggle="tooltip" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.santri.destroy', $s) }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm" style="background:#fee2e2;color:#991b1b;"
                                        data-confirm="Hapus data santri {{ $s->nama }}?" data-form="" onclick="this.closest('form').submit();"
                                        data-bs-toggle="tooltip" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-2 d-block mb-2 opacity-25"></i>
                            Tidak ada data santri
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <small class="text-muted">Menampilkan {{ $santri->firstItem() ?? 0 }}–{{ $santri->lastItem() ?? 0 }} dari {{ $santri->total() }} data</small>
            {{ $santri->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Pengumuman')
@section('page-title', 'Pengumuman Pondok')
@section('breadcrumb')
    <li class="breadcrumb-item active">Pengumuman</li>
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <h5 class="mb-0 fw-bold text-dark">Daftar Pengumuman</h5>
        <div class="d-flex align-items-center gap-2">
            <form action="{{ route('admin.pengumuman.index') }}" method="GET" class="d-flex align-items-center">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Cari judul..." value="{{ request('search') }}">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-outline-secondary" title="Reset">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    @endif
                </div>
            </form>
            <a href="{{ route('admin.pengumuman.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah Pengumuman
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="ps-3">No</th>
                        <th>Judul & Isi</th>
                        <th>Target</th>
                        <th>Prioritas</th>
                        <th>Periode Aktif</th>
                        <th>Status</th>
                        <th>Lampiran</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengumumen as $p)
                        <tr>
                            <td class="ps-3">{{ ($pengumumen->currentPage() - 1) * $pengumumen->perPage() + $loop->iteration }}</td>
                            <td>
                                <strong class="text-dark d-block mb-1">{{ $p->judul }}</strong>
                                <div class="text-muted small text-truncate" style="max-width: 350px;" title="{{ $p->isi }}">
                                    {{ Str::limit($p->isi, 75) }}
                                </div>
                            </td>
                            <td>
                                @if($p->target == 'semua')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Semua Role</span>
                                @elseif($p->target == 'ustadz')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Ustadz / Guru</span>
                                @elseif($p->target == 'santri')
                                    <span class="badge bg-info-subtle text-info border border-info-subtle">Santri</span>
                                @elseif($p->target == 'wali_santri')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Wali Santri</span>
                                @endif
                            </td>
                            <td>
                                {!! $p->badge_prioritas !!}
                            </td>
                            <td>
                                <span class="small d-block text-dark">{{ $p->tanggal_mulai->format('d M Y') }}</span>
                                @if($p->tanggal_selesai)
                                    <span class="small text-muted d-block mt-0.5">s/d {{ $p->tanggal_selesai->format('d M Y') }}</span>
                                @else
                                    <span class="small text-muted d-block mt-0.5">Seterusnya</span>
                                @endif
                            </td>
                            <td>
                                @if($p->aktif)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Aktif</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                @if($p->lampiran)
                                    <a href="{{ asset('storage/' . $p->lampiran) }}" target="_blank" class="btn btn-link btn-sm text-decoration-none p-0">
                                        <i class="bi bi-file-earmark-pdf-fill text-danger fs-5"></i> Lihat File
                                    </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.pengumuman.edit', $p->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.pengumuman.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-megaphone fs-1 d-block mb-3 text-secondary opacity-50"></i>
                                    Belum ada pengumuman yang sesuai.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($pengumumen->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $pengumumen->links() }}
        </div>
    @endif
</div>
@endsection

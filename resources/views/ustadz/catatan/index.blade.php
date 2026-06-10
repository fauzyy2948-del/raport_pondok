@extends('layouts.app')
@section('title', 'Catatan Pembinaan Santri')
@section('page-title', 'Catatan Pembinaan Santri')
@section('breadcrumb')
    <li class="breadcrumb-item active">Catatan Pembinaan</li>
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-body d-flex justify-content-between align-items-center">
        <form action="{{ route('ustadz.catatan.index') }}" method="GET" class="d-flex gap-2 w-50">
            <select name="kelas_id" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                        {{ $kelas->nama_kelas }}
                    </option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('ustadz.catatan.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Catatan
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Santri</th>
                        <th>Tanggal</th>
                        <th>Jenis Catatan</th>
                        <th>Keterangan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($catatans as $c)
                        <tr>
                            <td>{{ $loop->iteration + $catatans->firstItem() - 1 }}</td>
                            <td>
                                <div class="fw-bold">{{ $c->santri->user->name }}</div>
                                <div class="small text-muted">{{ $c->santri->kelas->nama_kelas ?? '-' }}</div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($c->tanggal)->format('d M Y') }}</td>
                            <td>
                                @if(strtolower($c->jenis) == 'prestasi')
                                    <span class="badge bg-success"><i class="bi bi-trophy"></i> Prestasi</span>
                                @elseif(strtolower($c->jenis) == 'pelanggaran')
                                    <span class="badge bg-danger"><i class="bi bi-exclamation-triangle"></i> Pelanggaran</span>
                                @elseif(strtolower($c->jenis) == 'pembinaan')
                                    <span class="badge bg-info"><i class="bi bi-journal-text"></i> Pembinaan</span>
                                @elseif(strtolower($c->jenis) == 'kesehatan')
                                    <span class="badge bg-warning text-dark"><i class="bi bi-heart-pulse"></i> Kesehatan</span>
                                @else
                                    <span class="badge bg-secondary"><i class="bi bi-info-circle"></i> Lainnya</span>
                                @endif
                            </td>
                            <td><strong>{{ $c->judul }}</strong> - {{ Str::limit($c->isi, 50) }}</td>
                            <td class="text-end">
                                <form action="{{ route('ustadz.catatan.destroy', $c->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus catatan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Belum ada catatan pembinaan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($catatans->hasPages())
        <div class="card-footer">
            {{ $catatans->links() }}
        </div>
    @endif
</div>
@endsection

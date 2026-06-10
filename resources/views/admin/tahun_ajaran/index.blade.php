@extends('layouts.app')
@section('title','Tahun Ajaran')
@section('page-title','Tahun Ajaran')
@section('breadcrumb')<li class="breadcrumb-item active">Tahun Ajaran</li>@endsection
@section('content')
<div class="row g-3">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><i class="bi bi-calendar3 me-2 text-primary"></i>Tambah Tahun Ajaran</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.tahun-ajaran.store') }}">
                    @csrf
                    <div class="mb-3"><label class="form-label">Tahun Ajaran <span class="text-danger">*</span></label><input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="2024/2025" required>@error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="mb-3"><label class="form-label">Semester</label><select name="semester" class="form-select"><option value="ganjil" {{ old('semester') === 'ganjil' ? 'selected' : '' }}>Ganjil</option><option value="genap" {{ old('semester') === 'genap' ? 'selected' : '' }}>Genap</option></select></div>
                    <div class="mb-3"><label class="form-label">Tanggal Mulai</label><input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai') }}" required></div>
                    <div class="mb-3"><label class="form-label">Tanggal Selesai</label><input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai') }}" required></div>
                    <div class="mb-3 form-check form-switch"><input class="form-check-input" type="checkbox" name="aktif" value="1" {{ old('aktif') ? 'checked' : '' }}><label class="form-check-label fw-600">Jadikan Aktif</label></div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save me-1"></i>Simpan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-list-ul me-2 text-primary"></i>Daftar Tahun Ajaran</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>#</th><th>Tahun Ajaran</th><th>Semester</th><th>Periode</th><th>Status</th><th width="90">Aksi</th></tr></thead>
                        <tbody>
                            @forelse($tahunAjaran as $i => $ta)
                            <tr>
                                <td>{{ $tahunAjaran->firstItem() + $i }}</td>
                                <td class="fw-700">{{ $ta->nama }}</td>
                                <td><span class="badge bg-info">{{ ucfirst($ta->semester) }}</span></td>
                                <td style="font-size:12px;">{{ $ta->tanggal_mulai->format('d M Y') }} – {{ $ta->tanggal_selesai->format('d M Y') }}</td>
                                <td>@if($ta->aktif)<span class="badge bg-success">Aktif</span>@else<span class="badge bg-secondary">Tidak Aktif</span>@endif</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.tahun-ajaran.edit', $ta) }}" class="btn btn-sm" style="background:#fef3c7;color:#92400e;"><i class="bi bi-pencil"></i></a>
                                        @if(!$ta->aktif)
                                        <form method="POST" action="{{ route('admin.tahun-ajaran.destroy', $ta) }}" class="d-inline">@csrf @method('DELETE')<button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#991b1b;" onclick="return confirm('Hapus tahun ajaran ini?')"><i class="bi bi-trash"></i></button></form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada tahun ajaran</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">{{ $tahunAjaran->links('pagination::bootstrap-5') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title','Data Kelas')
@section('page-title','Data Kelas / Kamar')
@section('breadcrumb')<li class="breadcrumb-item active">Kelas</li>@endsection
@section('content')
<div class="row g-3">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><i class="bi bi-plus-circle-fill me-2 text-primary"></i>Tambah Kelas</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.kelas.store') }}">
                    @csrf
                    <div class="mb-3"><label class="form-label">Nama Kelas <span class="text-danger">*</span></label><input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Contoh: Kelas 1A, Kamar A" required>@error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="mb-3"><label class="form-label">Tingkat <span class="text-danger">*</span></label><input type="text" name="tingkat" class="form-control @error('tingkat') is-invalid @enderror" value="{{ old('tingkat') }}" placeholder="1, 2, 3, ..." required></div>
                    <div class="mb-3"><label class="form-label">Jenis</label><select name="jenis" class="form-select"><option value="campuran" {{ old('jenis') === 'campuran' ? 'selected' : '' }}>Campuran</option><option value="diniyah" {{ old('jenis') === 'diniyah' ? 'selected' : '' }}>Diniyah</option><option value="umum" {{ old('jenis') === 'umum' ? 'selected' : '' }}>Umum</option></select></div>
                    <div class="mb-3"><label class="form-label">Kapasitas</label><input type="number" name="kapasitas" class="form-control" value="{{ old('kapasitas', 30) }}" min="1" max="100"></div>
                    <div class="mb-3"><label class="form-label">Keterangan</label><textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea></div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save me-1"></i>Simpan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-door-open-fill me-2 text-primary"></i>Daftar Kelas ({{ $kelas->total() }})</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>#</th><th>Nama Kelas</th><th>Tingkat</th><th>Jenis</th><th>Santri</th><th>Kapasitas</th><th width="90">Aksi</th></tr></thead>
                        <tbody>
                            @forelse($kelas as $i => $k)
                            <tr>
                                <td>{{ $kelas->firstItem() + $i }}</td>
                                <td class="fw-600">{{ $k->nama }}</td>
                                <td>{{ $k->tingkat }}</td>
                                <td><span class="badge bg-info">{{ ucfirst($k->jenis) }}</span></td>
                                <td><span class="fw-700 text-primary">{{ $k->santri_count }}</span></td>
                                <td>{{ $k->kapasitas }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.kelas.edit', $k) }}" class="btn btn-sm" style="background:#fef3c7;color:#92400e;"><i class="bi bi-pencil"></i></a>
                                        <form method="POST" action="{{ route('admin.kelas.destroy', $k) }}" class="d-inline">@csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#991b1b;" onclick="return confirm('Hapus kelas {{ $k->nama }}?')"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada data kelas</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">{{ $kelas->links('pagination::bootstrap-5') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

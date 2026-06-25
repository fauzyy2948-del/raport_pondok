@extends('layouts.app')
@section('title','Tambah Mapel')
@section('page-title','Tambah Mata Pelajaran')
@section('breadcrumb')<li class="breadcrumb-item"><a href="{{ route('admin.mapel.index') }}">Mapel</a></li><li class="breadcrumb-item active">Tambah</li>@endsection
@section('content')
<div class="card" style="max-width:600px;">
    <div class="card-header"><i class="bi bi-book-fill me-2 text-primary"></i>Form Mata Pelajaran</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.mapel.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Kode <span class="text-danger">*</span></label><input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode') }}" placeholder="QH, BTQ, MTK..." required>@error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-8"><label class="form-label">Nama Mata Pelajaran <span class="text-danger">*</span></label><input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>@error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4"><label class="form-label">Kategori</label><select name="kategori" class="form-select"><option value="diniyah" {{ old('kategori') === 'diniyah' ? 'selected' : '' }}>Diniyah</option><option value="umum" {{ old('kategori') === 'umum' ? 'selected' : '' }}>Umum</option></select></div>
                <div class="col-md-4"><label class="form-label">KKM <span class="text-danger">*</span></label><input type="number" name="kkm" class="form-control @error('kkm') is-invalid @enderror" value="{{ old('kkm', 70) }}" min="0" max="100" required></div>
                <div class="col-md-4"><label class="form-label">Bobot</label><input type="number" name="bobot" class="form-control" value="{{ old('bobot', 1) }}" min="1"></div>
                <div class="col-12"><label class="form-label">Keterangan</label><textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea></div>
                <div class="col-12 d-flex gap-2 justify-content-end">
                    <a href="{{ route('admin.mapel.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

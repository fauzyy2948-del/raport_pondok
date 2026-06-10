@extends('layouts.app')
@section('title','Edit Mapel')
@section('page-title','Edit Mata Pelajaran')
@section('breadcrumb')<li class="breadcrumb-item"><a href="{{ route('admin.mapel.index') }}">Mapel</a></li><li class="breadcrumb-item active">Edit</li>@endsection
@section('content')
<div class="card" style="max-width:600px;">
    <div class="card-header"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit: {{ $mapel->nama }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.mapel.update', $mapel) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Kode</label><input type="text" name="kode" class="form-control" value="{{ old('kode', $mapel->kode) }}" required></div>
                <div class="col-md-8"><label class="form-label">Nama</label><input type="text" name="nama" class="form-control" value="{{ old('nama', $mapel->nama) }}" required></div>
                <div class="col-md-4"><label class="form-label">Kategori</label><select name="kategori" class="form-select"><option value="diniyah" {{ old('kategori',$mapel->kategori) === 'diniyah' ? 'selected' : '' }}>Diniyah</option><option value="umum" {{ old('kategori',$mapel->kategori) === 'umum' ? 'selected' : '' }}>Umum</option><option value="muatan_lokal" {{ old('kategori',$mapel->kategori) === 'muatan_lokal' ? 'selected' : '' }}>Muatan Lokal</option></select></div>
                <div class="col-md-4"><label class="form-label">KKM</label><input type="number" name="kkm" class="form-control" value="{{ old('kkm', $mapel->kkm) }}" min="0" max="100"></div>
                <div class="col-md-4"><label class="form-label">Bobot</label><input type="number" name="bobot" class="form-control" value="{{ old('bobot', $mapel->bobot) }}" min="1"></div>
                <div class="col-md-4"><label class="form-label">Status</label><div class="form-check form-switch mt-2"><input class="form-check-input" type="checkbox" name="aktif" value="1" {{ old('aktif', $mapel->aktif) ? 'checked' : '' }}><label class="form-check-label">Aktif</label></div></div>
                <div class="col-12"><label class="form-label">Keterangan</label><textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $mapel->keterangan) }}</textarea></div>
                <div class="col-12 d-flex gap-2 justify-content-end">
                    <a href="{{ route('admin.mapel.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Perbarui</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

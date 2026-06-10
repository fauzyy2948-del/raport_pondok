@extends('layouts.app')
@section('title', 'Edit Ustadz')
@section('page-title', 'Edit Ustadz')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.ustadz.index') }}">Ustadz</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit: <strong>{{ $ustadz->nama_lengkap }}</strong></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.ustadz.update', $ustadz) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-2"><label class="form-label">Gelar Depan</label><input type="text" name="gelar_depan" class="form-control" value="{{ old('gelar_depan', $ustadz->gelar_depan) }}"></div>
                <div class="col-md-6"><label class="form-label">Nama <span class="text-danger">*</span></label><input type="text" name="nama" class="form-control" value="{{ old('nama', $ustadz->nama) }}" required></div>
                <div class="col-md-4"><label class="form-label">Gelar Belakang</label><input type="text" name="gelar_belakang" class="form-control" value="{{ old('gelar_belakang', $ustadz->gelar_belakang) }}"></div>
                <div class="col-md-4"><label class="form-label">NIP</label><input type="text" name="nip" class="form-control" value="{{ old('nip', $ustadz->nip) }}"></div>
                <div class="col-md-4"><label class="form-label">Jenis Kelamin</label><select name="jenis_kelamin" class="form-select"><option value="L" {{ old('jenis_kelamin', $ustadz->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option><option value="P" {{ old('jenis_kelamin', $ustadz->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option></select></div>
                <div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select"><option value="GTY" {{ old('status', $ustadz->status) === 'GTY' ? 'selected' : '' }}>GTY</option><option value="GTT" {{ old('status', $ustadz->status) === 'GTT' ? 'selected' : '' }}>GTT</option><option value="PNS" {{ old('status', $ustadz->status) === 'PNS' ? 'selected' : '' }}>PNS</option><option value="Honorer" {{ old('status', $ustadz->status) === 'Honorer' ? 'selected' : '' }}>Honorer</option></select></div>
                <div class="col-md-4"><label class="form-label">Telepon</label><input type="text" name="telepon" class="form-control" value="{{ old('telepon', $ustadz->telepon) }}"></div>
                <div class="col-md-4"><label class="form-label">Tanggal Masuk</label><input type="date" name="tanggal_masuk" class="form-control" value="{{ old('tanggal_masuk', $ustadz->tanggal_masuk?->format('Y-m-d')) }}"></div>
                <div class="col-md-4"><label class="form-label">Pendidikan Terakhir</label><input type="text" name="pendidikan_terakhir" class="form-control" value="{{ old('pendidikan_terakhir', $ustadz->pendidikan_terakhir) }}"></div>
                <div class="col-md-4">
                    <label class="form-label">Status Aktif</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="aktif" value="1" {{ old('aktif', $ustadz->aktif) ? 'checked' : '' }}>
                        <label class="form-check-label">Aktif</label>
                    </div>
                </div>
                <div class="col-md-12"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $ustadz->alamat) }}</textarea></div>
                <div class="col-md-4">
                    <label class="form-label">Foto</label>
                    @if($ustadz->foto)<div class="mb-2"><img src="{{ $ustadz->foto_url }}" class="avatar avatar-lg border"></div>@endif
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>
                <div class="col-12 d-flex gap-2 justify-content-end mt-2">
                    <a href="{{ route('admin.ustadz.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Perbarui</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

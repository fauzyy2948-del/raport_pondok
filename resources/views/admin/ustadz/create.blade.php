@extends('layouts.app')
@section('title', 'Tambah Ustadz')
@section('page-title', 'Tambah Ustadz')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.ustadz.index') }}">Ustadz</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-person-plus-fill me-2 text-primary"></i>Form Tambah Ustadz / Guru</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.ustadz.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-12"><h6 class="fw-700 text-primary border-bottom pb-2">Data Pribadi</h6></div>
                <div class="col-md-2"><label class="form-label">Gelar Depan</label><input type="text" name="gelar_depan" class="form-control" value="{{ old('gelar_depan') }}" placeholder="Ust., Dr., dll"></div>
                <div class="col-md-6"><label class="form-label">Nama Lengkap <span class="text-danger">*</span></label><input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>@error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4"><label class="form-label">Gelar Belakang</label><input type="text" name="gelar_belakang" class="form-control" value="{{ old('gelar_belakang') }}" placeholder="M.Pd., Lc., dll"></div>
                <div class="col-md-4"><label class="form-label">NIP</label><input type="text" name="nip" class="form-control" value="{{ old('nip') }}"></div>
                <div class="col-md-4"><label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label><select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required><option value="">-- Pilih --</option><option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option><option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option></select></div>
                <div class="col-md-4"><label class="form-label">Status <span class="text-danger">*</span></label><select name="status" class="form-select" required><option value="GTY" {{ old('status') === 'GTY' ? 'selected' : '' }}>GTY</option><option value="GTT">GTT</option><option value="PNS">PNS</option><option value="Honorer">Honorer</option></select></div>
                <div class="col-md-4"><label class="form-label">Tempat Lahir</label><input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}"></div>
                <div class="col-md-4"><label class="form-label">Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}"></div>
                <div class="col-md-4"><label class="form-label">Tanggal Masuk</label><input type="date" name="tanggal_masuk" class="form-control" value="{{ old('tanggal_masuk') }}"></div>
                <div class="col-md-4"><label class="form-label">Telepon</label><input type="text" name="telepon" class="form-control" value="{{ old('telepon') }}"></div>
                <div class="col-md-4"><label class="form-label">Pendidikan Terakhir</label><input type="text" name="pendidikan_terakhir" class="form-control" value="{{ old('pendidikan_terakhir') }}"></div>
                <div class="col-md-12"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control" rows="2">{{ old('alamat') }}</textarea></div>
                <div class="col-md-4"><label class="form-label">Foto</label><input type="file" name="foto" class="form-control" accept="image/*"></div>
                <div class="col-12"><h6 class="fw-700 text-primary border-bottom pb-2 mt-2">Akun Login</h6></div>
                <div class="col-md-6"><label class="form-label">Email <span class="text-danger">*</span></label><input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Password <span class="text-danger">*</span></label><input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required></div>
                <div class="col-12 d-flex gap-2 justify-content-end mt-2">
                    <a href="{{ route('admin.ustadz.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

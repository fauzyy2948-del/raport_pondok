@extends('layouts.app')
@section('title', 'Tambah Santri')
@section('page-title', 'Tambah Santri')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.santri.index') }}">Santri</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-person-plus-fill me-2 text-primary"></i>Form Tambah Santri
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.santri.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-12"><h6 class="fw-700 text-primary border-bottom pb-2">Data Pribadi</h6></div>

                <div class="col-md-8">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                           value="{{ old('nama') }}" placeholder="Nama lengkap santri" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_masuk" class="form-control @error('tanggal_masuk') is-invalid @enderror"
                           value="{{ old('tanggal_masuk') }}" required>
                    @error('tanggal_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">NISN <span class="text-danger">*</span></label>
                    <input type="text" name="nisn" class="form-control @error('nisn') is-invalid @enderror"
                           value="{{ old('nisn') }}" placeholder="Nomor Induk Siswa Nasional" required>
                    @error('nisn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Alamat Asal</label>
                    <textarea name="alamat_asal" class="form-control" rows="2">{{ old('alamat_asal') }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Asal Sekolah</label>
                    <input type="text" name="asal_sekolah" class="form-control" value="{{ old('asal_sekolah') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kelas</label>
                    <select name="kelas_tingkat" class="form-select">
                        <option value="">-- Pilih Kelas --</option>
                        @for($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}" {{ old('kelas_tingkat') == $i ? 'selected' : '' }}>Kelas {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-12"><h6 class="fw-700 text-primary border-bottom pb-2 mt-3">Data Wali Santri</h6></div>

                <div class="col-md-6">
                    <label class="form-label">Nama Wali <span class="text-danger">*</span></label>
                    <input type="text" name="nama_wali" class="form-control @error('nama_wali') is-invalid @enderror" value="{{ old('nama_wali') }}" required>
                    @error('nama_wali')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telepon Wali</label>
                    <input type="text" name="telepon_wali" class="form-control" value="{{ old('telepon_wali') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pekerjaan Wali</label>
                    <input type="text" name="pekerjaan_wali" class="form-control" value="{{ old('pekerjaan_wali') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Hubungan</label>
                    <select name="hubungan_wali" class="form-select">
                        <option value="Ayah" {{ old('hubungan_wali') == 'Ayah' ? 'selected' : '' }}>Ayah</option>
                        <option value="Ibu" {{ old('hubungan_wali') == 'Ibu' ? 'selected' : '' }}>Ibu</option>
                        <option value="Wali" {{ old('hubungan_wali') == 'Wali' ? 'selected' : '' }}>Wali</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Alamat Wali</label>
                    <textarea name="alamat_wali" class="form-control" rows="2">{{ old('alamat_wali') }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Foto</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>

                <div class="col-12"><h6 class="fw-700 text-primary border-bottom pb-2 mt-2">Akun Login</h6></div>

                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="Min. 6 karakter" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 d-flex gap-2 justify-content-end mt-2">
                    <a href="{{ route('admin.santri.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Data
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Edit Santri')
@section('page-title', 'Edit Santri')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.santri.index') }}">Santri</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Data Santri: <strong>{{ $santri->nama }}</strong>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.santri.update', $santri) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-12"><h6 class="fw-700 text-primary border-bottom pb-2">Data Pribadi</h6></div>

                <div class="col-md-8">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                           value="{{ old('nama', $santri->nama) }}" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="aktif" {{ old('status', $santri->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="alumni" {{ old('status', $santri->status) === 'alumni' ? 'selected' : '' }}>Alumni</option>
                        <option value="keluar" {{ old('status', $santri->status) === 'keluar' ? 'selected' : '' }}>Keluar</option>
                        <option value="pindah" {{ old('status', $santri->status) === 'pindah' ? 'selected' : '' }}>Pindah</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">NISN <span class="text-danger">*</span></label>
                    <input type="text" name="nisn" class="form-control @error('nisn') is-invalid @enderror"
                           value="{{ old('nisn', $santri->nisn) }}" required>
                    @error('nisn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="L" {{ old('jenis_kelamin', $santri->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $santri->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $santri->tempat_lahir) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $santri->tanggal_lahir?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_masuk" class="form-control" value="{{ old('tanggal_masuk', $santri->tanggal_masuk->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kelas</label>
                    <select name="kelas_tingkat" class="form-select">
                        <option value="">-- Pilih Kelas --</option>
                        @for($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}" {{ old('kelas_tingkat', $santri->kelas?->tingkat) == $i ? 'selected' : '' }}>Kelas {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-12"><h6 class="fw-700 text-primary border-bottom pb-2 mt-3">Data Wali Santri</h6></div>

                <div class="col-md-6">
                    <label class="form-label">Nama Wali <span class="text-danger">*</span></label>
                    <input type="text" name="nama_wali" class="form-control @error('nama_wali') is-invalid @enderror" value="{{ old('nama_wali', $santri->waliSantri?->nama) }}" required>
                    @error('nama_wali')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telepon Wali</label>
                    <input type="text" name="telepon_wali" class="form-control" value="{{ old('telepon_wali', $santri->waliSantri?->telepon) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pekerjaan Wali</label>
                    <input type="text" name="pekerjaan_wali" class="form-control" value="{{ old('pekerjaan_wali', $santri->waliSantri?->pekerjaan) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Hubungan</label>
                    <select name="hubungan_wali" class="form-select">
                        <option value="Ayah" {{ old('hubungan_wali', $santri->waliSantri?->hubungan) == 'Ayah' ? 'selected' : '' }}>Ayah</option>
                        <option value="Ibu" {{ old('hubungan_wali', $santri->waliSantri?->hubungan) == 'Ibu' ? 'selected' : '' }}>Ibu</option>
                        <option value="Wali" {{ old('hubungan_wali', $santri->waliSantri?->hubungan) == 'Wali' ? 'selected' : '' }}>Wali</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Alamat Wali</label>
                    <textarea name="alamat_wali" class="form-control" rows="2">{{ old('alamat_wali', $santri->waliSantri?->alamat) }}</textarea>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Alamat Asal</label>
                    <textarea name="alamat_asal" class="form-control" rows="2">{{ old('alamat_asal', $santri->alamat_asal) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Foto</label>
                    @if($santri->foto)
                        <div class="mb-2"><img src="{{ $santri->foto_url }}" class="avatar avatar-lg border"></div>
                    @endif
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>

                <div class="col-12 d-flex gap-2 justify-content-end mt-2">
                    <a href="{{ route('admin.santri.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Perbarui Data
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

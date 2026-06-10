@extends('layouts.app')
@section('title', 'Pengaturan Pondok')
@section('page-title', 'Pengaturan Profil Pondok Pesantren')
@section('breadcrumb')
    <li class="breadcrumb-item active">Pengaturan</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="text-center mb-4">
                        <img src="{{ $pengaturan->logo_url }}" alt="Logo Pondok" id="logoPreview" class="img-fluid mb-3" style="max-height: 120px;">
                        <div>
                            <label class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-upload"></i> Ganti Logo
                                <input type="file" name="logo" class="d-none" accept="image/*" onchange="document.getElementById('logoPreview').src = window.URL.createObjectURL(this.files[0])">
                            </label>
                        </div>
                        @error('logo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Nama Pondok Pesantren <span class="text-danger">*</span></label>
                            <input type="text" name="nama_pondok" class="form-control @error('nama_pondok') is-invalid @enderror" value="{{ old('nama_pondok', $pengaturan->nama_pondok) }}" required>
                            @error('nama_pondok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Nama Pimpinan / Mudir <span class="text-danger">*</span></label>
                            <input type="text" name="kepala_pondok" class="form-control @error('kepala_pondok') is-invalid @enderror" value="{{ old('kepala_pondok', $pengaturan->kepala_pondok) }}" required>
                            @error('kepala_pondok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror" value="{{ old('telepon', $pengaturan->telepon) }}">
                            @error('telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $pengaturan->email) }}">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" required>{{ old('alamat', $pengaturan->alamat) }}</textarea>
                            @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Website</label>
                            <input type="url" name="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website', $pengaturan->website) }}">
                            @error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

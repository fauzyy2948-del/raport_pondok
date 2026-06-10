@extends('layouts.app')
@section('title', 'Tambah Pengumuman')
@section('page-title', 'Tambah Pengumuman')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.pengumuman.index') }}">Pengumuman</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-megaphone-fill me-2 text-primary"></i>Buat Pengumuman Baru</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.pengumuman.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-600">Judul Pengumuman <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" placeholder="Tuliskan judul pengumuman..." required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-600">Tujuan (Target) <span class="text-danger">*</span></label>
                            <select name="target" class="form-select @error('target') is-invalid @enderror" required>
                                <option value="semua" {{ old('target') == 'semua' ? 'selected' : '' }}>Semua Role</option>
                                <option value="ustadz" {{ old('target') == 'ustadz' ? 'selected' : '' }}>Ustadz / Guru</option>
                                <option value="santri" {{ old('target') == 'santri' ? 'selected' : '' }}>Santri</option>
                                <option value="wali_santri" {{ old('target') == 'wali_santri' ? 'selected' : '' }}>Wali Santri</option>
                            </select>
                            @error('target')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600">Prioritas <span class="text-danger">*</span></label>
                            <select name="prioritas" class="form-select @error('prioritas') is-invalid @enderror" required>
                                <option value="rendah" {{ old('prioritas') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                                <option value="normal" {{ old('prioritas') == 'normal' || !old('prioritas') ? 'selected' : '' }}>Normal</option>
                                <option value="tinggi" {{ old('prioritas') == 'tinggi' ? 'selected' : '' }}>Tinggi / Penting</option>
                                <option value="urgent" {{ old('prioritas') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                            @error('prioritas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-600">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai', now()->toDateString()) }}" required>
                            @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600">Tanggal Selesai <small class="text-muted">(Opsional)</small></label>
                            <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai') }}">
                            @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-600">Isi Pengumuman <span class="text-danger">*</span></label>
                        <textarea name="isi" class="form-control @error('isi') is-invalid @enderror" rows="6" placeholder="Tuliskan isi pengumuman secara rinci di sini..." required>{{ old('isi') }}</textarea>
                        @error('isi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-600">Lampiran File <small class="text-muted">(Opsional, PDF/JPG/PNG/DOC maks 2MB)</small></label>
                        <input type="file" name="lampiran" class="form-control @error('lampiran') is-invalid @enderror">
                        @error('lampiran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="text-end">
                        <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-secondary me-1">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Pengumuman</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

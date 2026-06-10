@extends('layouts.app')
@section('title', 'Edit Pengumuman')
@section('page-title', 'Edit Pengumuman')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.pengumuman.index') }}">Pengumuman</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Pengumuman</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.pengumuman.update', $pengumuman->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-600">Judul Pengumuman <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $pengumuman->judul) }}" placeholder="Tuliskan judul pengumuman..." required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-600">Tujuan (Target) <span class="text-danger">*</span></label>
                            <select name="target" class="form-select @error('target') is-invalid @enderror" required>
                                <option value="semua" {{ old('target', $pengumuman->target) == 'semua' ? 'selected' : '' }}>Semua Role</option>
                                <option value="ustadz" {{ old('target', $pengumuman->target) == 'ustadz' ? 'selected' : '' }}>Ustadz / Guru</option>
                                <option value="santri" {{ old('target', $pengumuman->target) == 'santri' ? 'selected' : '' }}>Santri</option>
                                <option value="wali_santri" {{ old('target', $pengumuman->target) == 'wali_santri' ? 'selected' : '' }}>Wali Santri</option>
                            </select>
                            @error('target')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600">Prioritas <span class="text-danger">*</span></label>
                            <select name="prioritas" class="form-select @error('prioritas') is-invalid @enderror" required>
                                <option value="rendah" {{ old('prioritas', $pengumuman->prioritas) == 'rendah' ? 'selected' : '' }}>Rendah</option>
                                <option value="normal" {{ old('prioritas', $pengumuman->prioritas) == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="tinggi" {{ old('prioritas', $pengumuman->prioritas) == 'tinggi' ? 'selected' : '' }}>Tinggi / Penting</option>
                                <option value="urgent" {{ old('prioritas', $pengumuman->prioritas) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                            @error('prioritas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-600">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai', $pengumuman->tanggal_mulai ? $pengumuman->tanggal_mulai->format('Y-m-d') : '') }}" required>
                            @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600">Tanggal Selesai <small class="text-muted">(Opsional)</small></label>
                            <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai', $pengumuman->tanggal_selesai ? $pengumuman->tanggal_selesai->format('Y-m-d') : '') }}">
                            @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-600">Isi Pengumuman <span class="text-danger">*</span></label>
                        <textarea name="isi" class="form-control @error('isi') is-invalid @enderror" rows="6" placeholder="Tuliskan isi pengumuman secara rinci di sini..." required>{{ old('isi', $pengumuman->isi) }}</textarea>
                        @error('isi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-600">Lampiran File <small class="text-muted">(Opsional, PDF/JPG/PNG/DOC maks 2MB)</small></label>
                        <input type="file" name="lampiran" class="form-control mb-2 @error('lampiran') is-invalid @enderror">
                        @if($pengumuman->lampiran)
                            <div class="form-text text-dark d-flex align-items-center gap-2">
                                <i class="bi bi-file-earmark-check text-success fs-5"></i>
                                <span>File saat ini: 
                                    <a href="{{ asset('storage/' . $pengumuman->lampiran) }}" target="_blank" class="fw-bold text-decoration-none text-primary">
                                        {{ basename($pengumuman->lampiran) }}
                                    </a>
                                </span>
                            </div>
                        @endif
                        @error('lampiran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4 form-check form-switch">
                        <input type="hidden" name="aktif" value="0">
                        <input type="checkbox" name="aktif" value="1" class="form-check-input" id="aktif" {{ old('aktif', $pengumuman->aktif) ? 'checked' : '' }}>
                        <label class="form-check-label fw-600" for="aktif">Tampilkan/Aktifkan Pengumuman</label>
                        <small class="text-muted d-block mt-1">Jika dinonaktifkan, pengumuman tidak akan muncul di dashboard target.</small>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-secondary me-1">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

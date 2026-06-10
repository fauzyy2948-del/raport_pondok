@extends('layouts.app')
@section('title', 'Edit Tahun Ajaran')
@section('page-title', 'Edit Tahun Ajaran')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.tahun-ajaran.index') }}">Tahun Ajaran</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Tahun Ajaran</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.tahun-ajaran.update', $tahunAjaran->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Tahun (Contoh: 2024/2025) <span class="text-danger">*</span></label>
                        <input type="text" name="tahun" class="form-control @error('tahun') is-invalid @enderror" value="{{ old('tahun', $tahunAjaran->tahun) }}" required>
                        @error('tahun')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Semester <span class="text-danger">*</span></label>
                        <select name="semester" class="form-select @error('semester') is-invalid @enderror" required>
                            <option value="Ganjil" {{ old('semester', $tahunAjaran->semester) == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="Genap" {{ old('semester', $tahunAjaran->semester) == 'Genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                        @error('semester')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4 form-check form-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" id="is_active" {{ old('is_active', $tahunAjaran->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Jadikan Tahun Ajaran Aktif</label>
                        <small class="d-block text-muted">Jika diaktifkan, tahun ajaran lain akan otomatis dinonaktifkan.</small>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.tahun-ajaran.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

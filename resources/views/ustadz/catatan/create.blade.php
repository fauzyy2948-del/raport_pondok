@extends('layouts.app')
@section('title', 'Tambah Catatan Pembinaan')
@section('page-title', 'Tambah Catatan Pembinaan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('ustadz.catatan.index') }}">Catatan</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('ustadz.catatan.store') }}" method="POST">
                    @csrf
                    @if($tahunAktif)
                        <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAktif->id }}">
                    @endif
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Pilih Santri <span class="text-danger">*</span></label>
                            <select name="santri_id" class="form-select @error('santri_id') is-invalid @enderror" required>
                                <option value="">Pilih Santri</option>
                                @foreach($santris as $s)
                                    <option value="{{ $s->id }}" {{ old('santri_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->user->name }} (Kelas: {{ $s->kelas->nama_kelas ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('santri_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jenis Catatan <span class="text-danger">*</span></label>
                            <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                                <option value="prestasi" {{ old('jenis') == 'prestasi' ? 'selected' : '' }}>Prestasi</option>
                                <option value="pelanggaran" {{ old('jenis') == 'pelanggaran' ? 'selected' : '' }}>Pelanggaran</option>
                                <option value="pembinaan" {{ old('jenis') == 'pembinaan' ? 'selected' : '' }}>Pembinaan / Umum</option>
                                <option value="kesehatan" {{ old('jenis') == 'kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                                <option value="lainnya" {{ old('jenis') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Judul / Perihal <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" placeholder="Contoh: Terlambat Shalat Berjamaah, Juara 1 Pidato" required>
                            @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Isi Catatan / Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="isi" class="form-control @error('isi') is-invalid @enderror" rows="4" placeholder="Jelaskan detail catatan pembinaan..." required>{{ old('isi') }}</textarea>
                            @error('isi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <a href="{{ route('ustadz.catatan.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Catatan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

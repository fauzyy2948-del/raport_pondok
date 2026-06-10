@extends('layouts.app')
@section('title','Edit Kelas')
@section('page-title','Edit Kelas')
@section('breadcrumb')<li class="breadcrumb-item"><a href="{{ route('admin.kelas.index') }}">Kelas</a></li><li class="breadcrumb-item active">Edit</li>@endsection
@section('content')
<div class="card" style="max-width:500px;">
    <div class="card-header"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Kelas: {{ $kelas->nama }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.kelas.update', $kelas) }}">
            @csrf @method('PUT')
            <div class="mb-3"><label class="form-label">Nama Kelas</label><input type="text" name="nama" class="form-control" value="{{ old('nama', $kelas->nama) }}" required></div>
            <div class="mb-3"><label class="form-label">Tingkat</label><input type="text" name="tingkat" class="form-control" value="{{ old('tingkat', $kelas->tingkat) }}" required></div>
            <div class="mb-3"><label class="form-label">Jenis</label><select name="jenis" class="form-select"><option value="campuran" {{ old('jenis',$kelas->jenis) === 'campuran' ? 'selected' : '' }}>Campuran</option><option value="diniyah" {{ old('jenis',$kelas->jenis) === 'diniyah' ? 'selected' : '' }}>Diniyah</option><option value="umum" {{ old('jenis',$kelas->jenis) === 'umum' ? 'selected' : '' }}>Umum</option></select></div>
            <div class="mb-3"><label class="form-label">Kapasitas</label><input type="number" name="kapasitas" class="form-control" value="{{ old('kapasitas', $kelas->kapasitas) }}" min="1"></div>
            <div class="mb-3"><label class="form-label">Keterangan</label><textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $kelas->keterangan) }}</textarea></div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection

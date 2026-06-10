@extends('layouts.app')
@section('title', 'Tambah Jadwal')
@section('page-title', 'Tambah Jadwal Pelajaran')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.jadwal.index') }}">Jadwal</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.jadwal.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                            <select name="tahun_ajaran_id" class="form-select @error('tahun_ajaran_id') is-invalid @enderror" required>
                                <option value="">Pilih Tahun Ajaran</option>
                                @foreach($tahunAjarans as $ta)
                                    <option value="{{ $ta->id }}" {{ old('tahun_ajaran_id', $aktifTA->id ?? '') == $ta->id ? 'selected' : '' }}>
                                        {{ $ta->tahun }} - Semester {{ $ta->semester }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tahun_ajaran_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kelas <span class="text-danger">*</span></label>
                            <select name="kelas_id" class="form-select @error('kelas_id') is-invalid @enderror" required>
                                <option value="">Pilih Kelas</option>
                                @foreach($kelasList as $k)
                                    <option value="{{ $k->id }}" {{ old('kelas_id', request('kelas_id')) == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                            <select name="mapel_id" class="form-select @error('mapel_id') is-invalid @enderror" required>
                                <option value="">Pilih Mapel</option>
                                @foreach($mapels as $m)
                                    <option value="{{ $m->id }}" {{ old('mapel_id') == $m->id ? 'selected' : '' }}>
                                        {{ $m->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mapel_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ustadz Pengajar <span class="text-danger">*</span></label>
                            <select name="ustadz_id" class="form-select @error('ustadz_id') is-invalid @enderror" required>
                                <option value="">Pilih Ustadz</option>
                                @foreach($ustadzs as $u)
                                    <option value="{{ $u->id }}" {{ old('ustadz_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ustadz_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Hari <span class="text-danger">*</span></label>
                            <select name="hari" class="form-select @error('hari') is-invalid @enderror" required>
                                <option value="">Pilih Hari</option>
                                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Ahad'] as $h)
                                    <option value="{{ $h }}" {{ old('hari', request('hari')) == $h ? 'selected' : '' }}>{{ $h }}</option>
                                @endforeach
                            </select>
                            @error('hari')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Waktu / Jam Pelajaran <span class="text-danger">*</span></label>
                            <select id="jam_select" class="form-select @error('jam_mulai') is-invalid @enderror @error('jam_selesai') is-invalid @enderror" required>
                                <option value="">Pilih Jam Pelajaran</option>
                                @foreach(\App\Models\Jadwal::listJam() as $time => $label)
                                    @php
                                        [$start, $end] = explode('-', $time);
                                        $selected = (old('jam_mulai', request('jam_mulai')) == $start && old('jam_selesai', request('jam_selesai')) == $end) ? 'selected' : '';
                                    @endphp
                                    <option value="{{ $time }}" {{ $selected }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai', request('jam_mulai')) }}">
                            <input type="hidden" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai', request('jam_selesai')) }}">
                            @error('jam_mulai')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            @error('jam_selesai')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    
                    <div class="mt-4 text-end">
                        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('jam_select').addEventListener('change', function() {
    const val = this.value;
    if (val) {
        const parts = val.split('-');
        document.getElementById('jam_mulai').value = parts[0];
        document.getElementById('jam_selesai').value = parts[1];
    } else {
        document.getElementById('jam_mulai').value = '';
        document.getElementById('jam_selesai').value = '';
    }
});
</script>
@endpush

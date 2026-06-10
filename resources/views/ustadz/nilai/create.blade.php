@extends('layouts.app')
@section('title', 'Input Nilai Kolektif')
@section('page-title', 'Input Nilai Kolektif')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('ustadz.nilai.index') }}">Nilai</a></li>
    <li class="breadcrumb-item active">Input Kolektif</li>
@endsection

@section('content')
<div class="card mb-4 border-primary">
    <div class="card-body">
        <h5 class="mb-0 text-primary">
            <i class="bi bi-info-circle me-2"></i>
            Kelas: {{ $kelas->nama_kelas }} | Mata Pelajaran: {{ $mapel->nama_mapel }}
        </h5>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <form action="{{ route('ustadz.nilai.store') }}" method="POST">
            @csrf
            <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
            <input type="hidden" name="mapel_id" value="{{ $mapel->id }}">
            <input type="hidden" name="tahun_ajaran_id" value="{{ $selectedTahun }}">
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th rowspan="2" class="align-middle" width="5%">No</th>
                            <th rowspan="2" class="align-middle text-start">Nama Santri</th>
                            <th colspan="6">Input Nilai (0-100)</th>
                        </tr>
                        <tr>
                            <th width="12%">Harian</th>
                            <th width="12%">Tugas</th>
                            <th width="12%">UTS</th>
                            <th width="12%">UAS</th>
                            <th width="12%">Hafalan</th>
                            <th width="12%">Adab</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($santris as $s)
                            @php
                                $nilai = $s->nilai->first();
                            @endphp
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start fw-bold">
                                    {{ $s->user->name }}
                                    <input type="hidden" name="nilai[{{ $s->id }}][santri_id]" value="{{ $s->id }}">
                                </td>
                                 <td><input type="number" name="nilai[{{ $s->id }}][nilai_harian]" list="nilai-list" class="form-control form-control-sm text-center" min="0" max="100" value="{{ $nilai->nilai_harian ?? '' }}"></td>
                                <td><input type="number" name="nilai[{{ $s->id }}][nilai_tugas]" list="nilai-list" class="form-control form-control-sm text-center" min="0" max="100" value="{{ $nilai->nilai_tugas ?? '' }}"></td>
                                <td><input type="number" name="nilai[{{ $s->id }}][nilai_uts]" list="nilai-list" class="form-control form-control-sm text-center" min="0" max="100" value="{{ $nilai->nilai_uts ?? '' }}"></td>
                                <td><input type="number" name="nilai[{{ $s->id }}][nilai_uas]" list="nilai-list" class="form-control form-control-sm text-center" min="0" max="100" value="{{ $nilai->nilai_uas ?? '' }}"></td>
                                <td><input type="number" name="nilai[{{ $s->id }}][nilai_hafalan]" list="nilai-list" class="form-control form-control-sm text-center" min="0" max="100" value="{{ $nilai->nilai_hafalan ?? '' }}"></td>
                                <td><input type="number" name="nilai[{{ $s->id }}][nilai_adab]" list="nilai-list" class="form-control form-control-sm text-center" min="0" max="100" value="{{ $nilai->nilai_adab ?? '' }}"></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Data santri tidak ditemukan di kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($santris->count() > 0)
                <div class="card-footer text-end bg-white">
                    <a href="{{ route('ustadz.nilai.index', ['kelas_id' => $kelas->id, 'mapel_id' => $mapel->id]) }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Semua Nilai</button>
                </div>
            @endif
        </form>
    </div>
</div>

<datalist id="nilai-list">
    @for($val = 10; $val <= 100; $val += 5)
        <option value="{{ $val }}"></option>
    @endfor
</datalist>
@endsection

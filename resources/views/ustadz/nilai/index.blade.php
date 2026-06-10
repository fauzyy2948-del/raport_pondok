@extends('layouts.app')
@section('title', 'Input Nilai Santri')
@section('page-title', 'Input Nilai Santri')
@section('breadcrumb')
    <li class="breadcrumb-item active">Nilai</li>
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('ustadz.nilai.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <select name="kelas_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Pilih Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="mapel_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Pilih Mapel</option>
                    @foreach($mapels as $mapel)
                        <option value="{{ $mapel->id }}" {{ request('mapel_id') == $mapel->id ? 'selected' : '' }}>
                            {{ $mapel->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 text-end">
                @if(request('kelas_id') && request('mapel_id'))
                    <a href="{{ route('ustadz.nilai.create', ['kelas_id' => request('kelas_id'), 'mapel_id' => request('mapel_id')]) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square"></i> Input / Edit Nilai Kolektif
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

@if(request('kelas_id') && request('mapel_id'))
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Rekap Nilai Kelas: {{ $kelasList->where('id', request('kelas_id'))->first()->nama_kelas ?? '' }} | Mapel: {{ $mapels->where('id', request('mapel_id'))->first()->nama_mapel ?? '' }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th rowspan="2" class="align-middle" width="5%">No</th>
                            <th rowspan="2" class="align-middle">NISN</th>
                            <th rowspan="2" class="align-middle text-start">Nama Santri</th>
                            <th colspan="6">Rincian Nilai</th>
                            <th rowspan="2" class="align-middle bg-primary text-white">Nilai Akhir</th>
                            <th rowspan="2" class="align-middle bg-success text-white">Huruf</th>
                        </tr>
                        <tr>
                            <th width="8%">Harian</th>
                            <th width="8%">Tugas</th>
                            <th width="8%">UTS</th>
                            <th width="8%">UAS</th>
                            <th width="8%">Hafalan</th>
                            <th width="8%">Adab</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($santris as $s)
                            @php
                                $nilai = $s->nilai->first();
                            @endphp
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $s->nisn }}</td>
                                <td class="text-start fw-bold">{{ $s->user->name }}</td>
                                <td>{{ $nilai->nilai_harian ?? '-' }}</td>
                                <td>{{ $nilai->nilai_tugas ?? '-' }}</td>
                                <td>{{ $nilai->nilai_uts ?? '-' }}</td>
                                <td>{{ $nilai->nilai_uas ?? '-' }}</td>
                                <td>{{ $nilai->nilai_hafalan ?? '-' }}</td>
                                <td>{{ $nilai->nilai_adab ?? '-' }}</td>
                                <td class="bg-light fw-bold fs-5">{{ $nilai->nilai_akhir ?? '-' }}</td>
                                <td class="bg-light fw-bold fs-5 text-primary">{{ $nilai->predikat ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">Data santri tidak ditemukan di kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-info text-center py-5">
        <i class="bi bi-info-circle display-4 d-block mb-3"></i>
        <h4>Pilih Kelas dan Mata Pelajaran</h4>
        <p>Silakan pilih kelas dan mata pelajaran pada filter di atas untuk melihat rekap nilai dan menginput nilai santri.</p>
    </div>
@endif
@endsection

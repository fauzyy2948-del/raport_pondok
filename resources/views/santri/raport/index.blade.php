@extends('layouts.app')
@section('title', 'Raport Saya')
@section('page-title', 'Laporan Hasil Belajar (Raport)')
@section('breadcrumb')
    <li class="breadcrumb-item active">Raport</li>
@endsection

@section('content')
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary-subtle text-primary p-3 rounded-3 fs-3">
                <i class="bi bi-file-earmark-text-fill"></i>
            </div>
            <div>
                <h5 class="mb-1 fw-bold">E-Raport Santri</h5>
                <p class="text-muted mb-0">Halaman ini menampilkan riwayat raport akademik Anda yang telah diterbitkan oleh wali kelas.</p>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-archive-fill text-muted me-2"></i>Daftar Raport Akademik</h5>
    </div>
    <div class="card-body p-0">
        @if($raport->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-folder-x display-5 d-block mb-3 text-muted opacity-50"></i>
                <p class="mb-0">Raport Anda belum diterbitkan untuk tahun ajaran aktif ini.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Tahun Ajaran</th>
                            <th>Semester</th>
                            <th>Kelas</th>
                            <th class="text-center">Nilai Rata-Rata</th>
                            <th class="text-center">Predikat Akhir</th>
                            <th class="text-center">Tanggal Terbit</th>
                            <th class="pe-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 13px;">
                        @foreach($raport as $i => $r)
                            @php
                                $diterbitkanPada = $r->diterbitkan_pada ? \Carbon\Carbon::parse($r->diterbitkan_pada) : null;
                            @endphp
                            <tr>
                                <td class="ps-4 text-muted">{{ $i + 1 }}</td>
                                <td><strong>{{ $r->tahunAjaran->tahun }}</strong></td>
                                <td><span class="badge bg-secondary-subtle text-secondary px-2 py-1 text-capitalize">{{ $r->tahunAjaran->semester }}</span></td>
                                <td>{{ $r->kelas->nama }}</td>
                                <td class="text-center fw-bold text-dark">{{ number_format($r->rata_rata, 1) }}</td>
                                <td class="text-center"><span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">{{ $r->predikat_akhir ?? '-' }}</span></td>
                                <td class="text-center text-muted">{{ $diterbitkanPada ? $diterbitkanPada->isoFormat('D MMMM Y') : '-' }}</td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('santri.raport.show', $r->id) }}" class="btn btn-sm btn-primary me-1">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('santri.raport.download', $r->id) }}" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-download"></i> Unduh PDF
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

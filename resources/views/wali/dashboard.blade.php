@extends('layouts.app')
@section('title', 'Dashboard Wali Santri')
@section('page-title', 'Dashboard Wali Santri')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-4 text-white bg-primary rounded-3" style="background: linear-gradient(135deg, var(--bs-primary), #155c2f) !important;">
        <h4 class="fw-bold mb-1">Ahlan wa Sahlan, {{ Auth::user()->name }}</h4>
        <p class="mb-0 text-white-50">Selamat datang di portal akademik wali santri. Berikut adalah ringkasan perkembangan akademik ananda.</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- List of Children -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-person-badge me-2"></i>Data Perkembangan Ananda</h5>
            </div>
            <div class="card-body p-0">
                @if($summaries->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-people display-4 d-block mb-3 opacity-50"></i>
                        <p class="mb-0">Belum ada data anak terhubung ke akun Anda.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                                <tr>
                                    <th class="ps-4">Nama Santri</th>
                                    <th>NISN</th>
                                    <th>Kelas</th>
                                    <th class="text-center">Rata-rata Nilai</th>
                                    <th class="text-center">Kehadiran (Hadir)</th>
                                    <th class="pe-4 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 13px;">
                                @foreach($summaries as $summary)
                                    @php
                                        $anak = $summary['santri'];
                                    @endphp
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $anak->foto_url }}" class="rounded-circle" width="32" height="32" alt="avatar" style="object-fit: cover;">
                                                <div class="fw-bold text-dark">{{ $anak->nama }}</div>
                                            </div>
                                        </td>
                                        <td>{{ $anak->nisn }}</td>
                                        <td>{{ $anak->kelas->nama ?? '-' }}</td>
                                        <td class="text-center fw-bold text-primary">{{ $summary['rata_nilai'] }}</td>
                                        <td class="text-center"><span class="badge bg-success-subtle text-success px-2 py-1">{{ $summary['hadir'] }} Hari</span></td>
                                        <td class="pe-4 text-end">
                                            <a href="{{ route('wali.anak.show', $anak->id) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-arrow-right-circle"></i> Pantau Detail
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

        @if(!$raportTerbaru->isEmpty())
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold text-success"><i class="bi bi-file-earmark-arrow-down-fill me-2"></i>Unduhan Raport Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                            <tbody style="font-size: 13px;">
                                @foreach($raportTerbaru as $rap)
                                    <tr>
                                        <td class="ps-4"><strong>{{ $rap->santri->nama }}</strong></td>
                                        <td>Tahun Ajaran: {{ $rap->tahunAjaran->tahun }} (Semester {{ ucfirst($rap->tahunAjaran->semester) }})</td>
                                        <td class="pe-4 text-end">
                                            <a href="{{ route('wali.raport.download', $rap->id) }}" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-download"></i> Unduh PDF
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Announcements -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold text-warning-dark"><i class="bi bi-megaphone-fill me-2 text-warning"></i>Pengumuman Pondok</h5>
            </div>
            <div class="card-body">
                @forelse($pengumuman as $p)
                    <div class="border-bottom pb-3 mb-3">
                        <h6 class="fw-bold mb-1 text-primary">{{ $p->judul }}</h6>
                        <div class="small text-muted mb-2"><i class="bi bi-clock"></i> {{ $p->created_at->diffForHumans() }}</div>
                        <p class="mb-0 small text-muted" style="line-height: 1.5;">{{ Str::limit($p->isi, 120) }}</p>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-chat-left-dots display-6 mb-2 d-block opacity-50"></i>
                        <span>Belum ada pengumuman baru.</span>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

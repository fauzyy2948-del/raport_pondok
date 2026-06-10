@extends('layouts.app')
@section('title', 'Dashboard Santri')
@section('page-title', 'Ahlan wa Sahlan, ' . Auth::user()->name)
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Total Kehadiran</h6>
                        <h3 class="mb-0">{{ $hadirCount ?? 0 }} Hari</h3>
                    </div>
                    <div class="fs-1 opacity-50"><i class="bi bi-calendar-check"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Rata-rata Nilai (Semester Ini)</h6>
                        <h3 class="mb-0">{{ number_format($rataRataNilai ?? 0, 1) }}</h3>
                    </div>
                    <div class="fs-1 opacity-50"><i class="bi bi-bar-chart"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Status Raport</h6>
                        <h3 class="mb-0">{{ $raportTersedia ? 'Tersedia' : 'Belum' }}</h3>
                    </div>
                    <div class="fs-1 opacity-50"><i class="bi bi-file-earmark-text"></i></div>
                </div>
                @if($raportTersedia)
                    <div class="mt-2">
                        <a href="{{ route('santri.raport.download', $raport->id) }}" class="btn btn-sm btn-light text-success w-100">
                            <i class="bi bi-download"></i> Unduh Raport
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-calendar3 text-primary me-2"></i>Jadwal Pelajaran Hari Ini</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Waktu</th>
                                <th>Mata Pelajaran</th>
                                <th>Ustadz / Guru</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwalHariIni ?? [] as $j)
                                <tr>
                                    <td>{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</td>
                                    <td><strong>{{ $j->mapel->nama }}</strong></td>
                                    <td>{{ $j->ustadz->nama_lengkap }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Tidak ada jadwal pelajaran hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        {{-- QR Code Absensi --}}
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-qr-code text-primary me-2"></i>QR Code Absensi Saya</h5>
            </div>
            <div class="card-body text-center">
                <div class="d-flex justify-content-center mb-3">
                    <div id="qrcode-santri" class="border p-2 bg-white rounded shadow-sm"></div>
                </div>
                <h6 class="fw-700 mb-1">{{ $santri->nama }}</h6>
                <small class="text-muted d-block mb-3">NISN: {{ $santri->nisn }}</small>
                
                <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="printSantriQR()">
                    <i class="bi bi-printer me-1"></i> Cetak QR Code
                </button>
            </div>
        </div>

        {{-- Pengumuman --}}
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-megaphone text-warning me-2"></i>Pengumuman Pondok</h5>
            </div>
            <div class="card-body">
                @forelse($pengumumans ?? [] as $p)
                    <div class="border-bottom pb-3 mb-3">
                        <h6 class="mb-1 text-primary">{{ $p->judul }}</h6>
                        <div class="small text-muted mb-2"><i class="bi bi-clock"></i> {{ $p->created_at->diffForHumans() }}</div>
                        <p class="mb-0 small">{{ Str::limit($p->isi, 100) }}</p>
                    </div>
                @empty
                    <div class="text-center text-muted py-3">Belum ada pengumuman.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    $(document).ready(function() {
        const nisnVal = "{{ $santri->nisn ?? '' }}";
        if (nisnVal) {
            new QRCode(document.getElementById("qrcode-santri"), {
                text: nisnVal,
                width: 140,
                height: 140,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        }
    });

    function printSantriQR() {
        const printWindow = window.open('', '_blank');
        const qrContent = document.getElementById("qrcode-santri").innerHTML;
        const studentName = "{{ $santri->nama ?? '' }}";
        const studentNisn = "{{ $santri->nisn ?? '' }}";
        const className = "{{ $santri->kelas->nama ?? '-' }}";
        
        printWindow.document.write(`
            <html>
            <head>
                <title>Cetak QR Code - ${studentName}</title>
                <style>
                    body {
                        font-family: 'Poppins', sans-serif;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        margin: 0;
                        background: #f8f9fa;
                    }
                    .card {
                        background: white;
                        border: 2px solid #1B6B3A;
                        border-radius: 12px;
                        padding: 24px;
                        text-align: center;
                        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                        max-width: 250px;
                    }
                    .qr-container {
                        margin: 15px 0;
                        display: flex;
                        justify-content: center;
                    }
                    .name {
                        font-weight: bold;
                        font-size: 16px;
                        color: #1B6B3A;
                        margin: 5px 0;
                    }
                    .nisn {
                        font-size: 12px;
                        color: #6c757d;
                        margin-bottom: 5px;
                    }
                    .class-tag {
                        background: #d1fae5;
                        color: #065f46;
                        padding: 3px 10px;
                        border-radius: 20px;
                        font-size: 11px;
                        display: inline-block;
                        margin-top: 5px;
                        font-weight: 600;
                    }
                    @media print {
                        body { background: white; }
                        .card { box-shadow: none; border: 2px solid #000; }
                    }
                </style>
            </head>
            <body>
                <div class="card">
                    <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #b8860b; font-weight: bold;">Kartu Absensi Santri</div>
                    <div class="qr-container">${qrContent}</div>
                    <div class="name">${studentName}</div>
                    <div class="nisn">NISN: ${studentNisn}</div>
                    <div class="class-tag">Kelas: ${className}</div>
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(function() { window.close(); }, 500);
                    };
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>
@endpush

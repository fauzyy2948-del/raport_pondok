@extends('layouts.app')
@section('title', 'Detail Santri')
@section('page-title', 'Detail Santri')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.santri.index') }}">Santri</a></li>
    <li class="breadcrumb-item active">{{ $santri->nama }}</li>
@endsection

@section('content')
<div class="row g-3">
    {{-- Profile Card --}}
    <div class="col-md-4">
        <div class="card text-center p-4">
            <img src="{{ $santri->foto_url }}" class="avatar-xl mx-auto mb-3 border" alt="">
            <h5 class="fw-700 mb-1">{{ $santri->nama }}</h5>
            <p class="text-muted mb-2" style="font-size:13px;">NISN: {{ $santri->nisn }}</p>
            <span class="badge {{ $santri->status === 'aktif' ? 'bg-success' : 'bg-secondary' }} mb-3">{{ ucfirst($santri->status) }}</span>
            <div class="text-start">
                <table class="table table-sm" style="font-size:12px;">
                    <tr><td class="text-muted">Kelas</td><td class="fw-600">{{ $santri->kelas?->nama ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Jenis Kelamin</td><td>{{ $santri->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                    <tr><td class="text-muted">Tempat, Tgl Lahir</td><td>{{ $santri->tempat_lahir }}, {{ $santri->tanggal_lahir?->format('d M Y') ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Usia</td><td>{{ $santri->usia ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Telepon</td><td>{{ $santri->telepon ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Wali</td><td>{{ $santri->waliSantri?->nama ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Masuk</td><td>{{ $santri->tanggal_masuk->format('d M Y') }}</td></tr>
                </table>
            </div>
            <div class="d-flex gap-2 mt-2">
                <a href="{{ route('admin.santri.edit', $santri) }}" class="btn btn-primary btn-sm flex-1 w-100">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
            </div>
        </div>

        {{-- QR Code Card --}}
        <div class="card p-4 text-center mt-3 no-print">
            <h6 class="fw-600 mb-3"><i class="bi bi-qr-code text-primary me-2"></i>QR Code Absensi</h6>
            
            <div class="d-flex justify-content-center mb-3">
                <div id="qrcode" class="border p-2 bg-white rounded shadow-sm"></div>
            </div>
            
            <small class="text-muted d-block mb-3" style="font-size: 11px;">
                NISN: {{ $santri->nisn }}
            </small>
            
            <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="printQRCode()">
                <i class="bi bi-printer me-1"></i> Cetak QR Code
            </button>
        </div>
    </div>

    <div class="col-md-8">
        {{-- Nilai Terbaru --}}
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-star-fill me-2 text-primary"></i>Nilai Terakhir</div>
            <div class="card-body p-0">
                @if($santri->nilai->isNotEmpty())
                <div class="table-responsive">
                    <table class="table mb-0" style="font-size:12px;">
                        <thead><tr>
                            <th>Mata Pelajaran</th><th>Harian</th><th>UTS</th><th>UAS</th><th>Akhir</th><th>Predikat</th>
                        </tr></thead>
                        <tbody>
                        @foreach($santri->nilai->take(6) as $n)
                        <tr>
                            <td class="fw-600">{{ $n->mapel?->nama }}</td>
                            <td>{{ $n->nilai_harian ?? '-' }}</td>
                            <td>{{ $n->nilai_uts ?? '-' }}</td>
                            <td>{{ $n->nilai_uas ?? '-' }}</td>
                            <td class="fw-700">{{ $n->nilai_akhir ?? '-' }}</td>
                            <td><span class="badge bg-primary">{{ $n->predikat ?? '-' }}</span></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-4 text-center text-muted" style="font-size:13px;"><i class="bi bi-star fs-3 d-block mb-2 opacity-25"></i>Belum ada data nilai</div>
                @endif
            </div>
        </div>

        {{-- Catatan Pembinaan --}}
        <div class="card">
            <div class="card-header"><i class="bi bi-journal-text me-2 text-primary"></i>Catatan Pembinaan</div>
            <div class="card-body p-0">
                @forelse($santri->catatanPembinaan->take(5) as $c)
                <div class="p-3 border-bottom d-flex gap-3 align-items-start" style="font-size:13px;">
                    <span class="badge {{ match($c->jenis) { 'prestasi'=>'bg-success','pelanggaran'=>'bg-danger', default=>'bg-info' } }}">{{ ucfirst($c->jenis) }}</span>
                    <div>
                        <div class="fw-600">{{ $c->judul }}</div>
                        <small class="text-muted">{{ $c->ustadz?->nama }} &middot; {{ $c->tanggal->format('d M Y') }}</small>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-muted" style="font-size:13px;"><i class="bi bi-journal fs-3 d-block mb-2 opacity-25"></i>Belum ada catatan</div>
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
        const nisnVal = "{{ $santri->nisn }}";
        if (nisnVal) {
            new QRCode(document.getElementById("qrcode"), {
                text: nisnVal,
                width: 150,
                height: 150,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        }
    });

    function printQRCode() {
        const printWindow = window.open('', '_blank');
        const qrContent = document.getElementById("qrcode").innerHTML;
        const studentName = "{{ $santri->nama }}";
        const studentNisn = "{{ $santri->nisn }}";
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

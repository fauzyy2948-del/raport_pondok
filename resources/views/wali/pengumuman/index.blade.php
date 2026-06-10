@extends('layouts.app')
@section('title', 'Pengumuman Pondok')
@section('page-title', 'Pengumuman')
@section('breadcrumb')
    <li class="breadcrumb-item active">Pengumuman</li>
@endsection

@section('content')
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-warning-subtle text-warning p-3 rounded-3 fs-3">
                <i class="bi bi-megaphone-fill"></i>
            </div>
            <div>
                <h5 class="mb-1 fw-bold">Informasi & Pengumuman</h5>
                <p class="text-muted mb-0">Halaman ini menampilkan pengumuman resmi dan berita terbaru dari pondok pesantren khusus untuk wali santri.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        @if($pengumuman->isEmpty())
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5 text-muted">
                    <i class="bi bi-chat-left-dots display-5 d-block mb-3 text-muted opacity-50"></i>
                    <p class="mb-0">Belum ada pengumuman untuk saat ini.</p>
                </div>
            </div>
        @else
            @foreach($pengumuman as $p)
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-2">
                            <h5 class="fw-bold text-primary mb-0">{{ $p->judul }}</h5>
                            <span class="badge bg-warning-subtle text-warning-dark px-3 py-2 rounded-pill" style="font-size: 11px;">
                                <i class="bi bi-clock me-1"></i> {{ $p->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-muted small mb-3">Diterbitkan pada: {{ $p->created_at->isoFormat('D MMMM Y HH:mm') }}</p>
                        <div class="text-dark" style="font-size: 14px; line-height: 1.6; white-space: pre-line;">
                            {{ $p->isi }}
                        </div>
                    </div>
                </div>
            @endforeach

            @if($pengumuman->hasPages())
                <div class="mt-4">
                    {{ $pengumuman->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

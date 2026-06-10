<div class="nav-section-label">Utama</div>

<a href="{{ route('wali.dashboard') }}" class="nav-link {{ request()->routeIs('wali.dashboard') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-speedometer2"></i></span>
    <span>Dashboard</span>
</a>

<div class="nav-section-label">Monitoring Anak</div>

@php $wali = auth()->user()->waliSantri; @endphp
@if($wali)
    @foreach($wali->santri as $anak)
        <a href="{{ route('wali.anak.show', $anak) }}" class="nav-link {{ request()->is("wali/anak/{$anak->id}*") ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-person-circle"></i></span>
            <span>{{ Str::limit($anak->nama, 20) }}</span>
        </a>
    @endforeach
@endif

<div class="nav-section-label">Informasi</div>

<a href="{{ route('wali.pengumuman.index') }}" class="nav-link {{ request()->routeIs('wali.pengumuman.*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-megaphone-fill"></i></span>
    <span>Pengumuman</span>
</a>

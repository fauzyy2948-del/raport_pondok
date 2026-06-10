<div class="nav-section-label">Utama</div>

<a href="{{ route('santri.dashboard') }}" class="nav-link {{ request()->routeIs('santri.dashboard') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-speedometer2"></i></span>
    <span>Dashboard</span>
</a>

<div class="nav-section-label">Akademik</div>

<a href="{{ route('santri.nilai.index') }}" class="nav-link {{ request()->routeIs('santri.nilai.*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-star-fill"></i></span>
    <span>Nilai Saya</span>
</a>

<a href="{{ route('santri.raport.index') }}" class="nav-link {{ request()->routeIs('santri.raport.*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-file-earmark-text-fill"></i></span>
    <span>Raport</span>
</a>

<a href="{{ route('santri.absensi.index') }}" class="nav-link {{ request()->routeIs('santri.absensi.*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-calendar-check-fill"></i></span>
    <span>Absensi</span>
</a>

<a href="{{ route('santri.jadwal.index') }}" class="nav-link {{ request()->routeIs('santri.jadwal.*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-clock-fill"></i></span>
    <span>Jadwal Pelajaran</span>
</a>

<div class="nav-section-label">Informasi</div>

<a href="{{ route('santri.pengumuman.index') }}" class="nav-link {{ request()->routeIs('santri.pengumuman.*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-megaphone-fill"></i></span>
    <span>Pengumuman</span>
</a>

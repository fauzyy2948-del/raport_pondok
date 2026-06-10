<div class="nav-section-label">Utama</div>

<a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-speedometer2"></i></span>
    <span>Dashboard</span>
</a>

<div class="nav-section-label">Data Master</div>

<a href="{{ route('admin.santri.index') }}" class="nav-link {{ request()->routeIs('admin.santri.*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-people-fill"></i></span>
    <span>Data Santri</span>
</a>

<a href="{{ route('admin.ustadz.index') }}" class="nav-link {{ request()->routeIs('admin.ustadz.*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-person-badge-fill"></i></span>
    <span>Data Ustadz/Guru</span>
</a>

<a href="{{ route('admin.wali-santri.index') }}" class="nav-link {{ request()->routeIs('admin.wali-santri.*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-house-heart-fill"></i></span>
    <span>Wali Santri</span>
</a>

<a href="{{ route('admin.kelas.index') }}" class="nav-link {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-door-open-fill"></i></span>
    <span>Data Kelas</span>
</a>

<a href="{{ route('admin.mapel.index') }}" class="nav-link {{ request()->routeIs('admin.mapel.*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-book-fill"></i></span>
    <span>Mata Pelajaran</span>
</a>

<a href="{{ route('admin.tahun-ajaran.index') }}" class="nav-link {{ request()->routeIs('admin.tahun-ajaran.*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-calendar3"></i></span>
    <span>Tahun Ajaran</span>
</a>

<div class="nav-section-label">Akademik</div>

<a href="{{ route('admin.jadwal.index') }}" class="nav-link {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-clock-fill"></i></span>
    <span>Jadwal Pelajaran</span>
</a>

<a href="{{ route('admin.raport.dashboard') }}" class="nav-link {{ request()->routeIs('admin.raport.dashboard') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-speedometer2"></i></span>
    <span>Dashboard Raport</span>
</a>

<a href="{{ route('admin.raport.index') }}" class="nav-link {{ request()->routeIs('admin.raport.index', 'admin.raport.show') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-file-earmark-text-fill"></i></span>
    <span>Kelola Raport</span>
</a>

<a href="{{ route('admin.pengumuman.index') }}" class="nav-link {{ request()->routeIs('admin.pengumuman.*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-megaphone-fill"></i></span>
    <span>Pengumuman</span>
</a>

<div class="nav-section-label">Sistem</div>

<a href="{{ route('admin.pengaturan.index') }}" class="nav-link {{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-gear-fill"></i></span>
    <span>Pengaturan Pondok</span>
</a>

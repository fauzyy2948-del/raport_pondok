<div class="nav-section-label">Utama</div>

<a href="{{ route('ustadz.dashboard') }}" class="nav-link {{ request()->routeIs('ustadz.dashboard') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-speedometer2"></i></span>
    <span>Dashboard</span>
</a>

<div class="nav-section-label">Akademik</div>

<a href="{{ route('ustadz.nilai.index') }}" class="nav-link {{ request()->routeIs('ustadz.nilai.*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-pencil-square"></i></span>
    <span>Input Nilai</span>
</a>

<a href="{{ route('ustadz.nilai.rekap') }}" class="nav-link {{ request()->is('ustadz/nilai/rekap*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-table"></i></span>
    <span>Rekap Nilai</span>
</a>

<a href="{{ route('ustadz.absensi.index') }}" class="nav-link {{ request()->routeIs('ustadz.absensi.index') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-calendar-check-fill"></i></span>
    <span>Absensi Santri</span>
</a>

<a href="{{ route('ustadz.absensi.rekap') }}" class="nav-link {{ request()->is('ustadz/absensi/rekap*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-bar-chart-fill"></i></span>
    <span>Rekap Absensi</span>
</a>

<div class="nav-section-label">Pembinaan</div>

<a href="{{ route('ustadz.catatan.index') }}" class="nav-link {{ request()->routeIs('ustadz.catatan.*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-journal-text"></i></span>
    <span>Catatan Pembinaan</span>
</a>

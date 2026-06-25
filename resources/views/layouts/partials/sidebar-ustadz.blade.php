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



<div class="nav-section-label">Pembinaan</div>

<a href="{{ route('ustadz.catatan.index') }}" class="nav-link {{ request()->routeIs('ustadz.catatan.*') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-journal-text"></i></span>
    <span>Catatan Pembinaan</span>
</a>

@if(auth()->user()->ustadz && auth()->user()->ustadz->isWaliKelas())
<div class="nav-section-label">Wali Kelas</div>

<a href="{{ route('ustadz.raport.dashboard') }}" class="nav-link {{ request()->routeIs('ustadz.raport.dashboard') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-speedometer2"></i></span>
    <span>Dashboard Raport</span>
</a>

<a href="{{ route('ustadz.raport.index') }}" class="nav-link {{ request()->routeIs('ustadz.raport.index', 'ustadz.raport.show') ? 'active' : '' }}">
    <span class="nav-icon"><i class="bi bi-file-earmark-text-fill"></i></span>
    <span>Kelola Raport</span>
</a>
@endif


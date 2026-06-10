<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-Raport') — Subulussalam</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    @stack('styles')
</head>
<body>

<!-- Sidebar Overlay (Mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ===== SIDEBAR ===== -->
<nav class="sidebar" id="sidebar">
    @php
        $appPengaturan = \App\Models\PengaturanPondok::first();
        $logoApp = $appPengaturan ? $appPengaturan->logo_url : asset('images/logo.png');
        $namaPondok = $appPengaturan ? $appPengaturan->nama_pondok : 'Subulussalam';
    @endphp
    <div class="sidebar-header">
        <img src="{{ $logoApp }}" alt="Logo" class="sidebar-logo">
        <div class="sidebar-brand">
            <h6>{{ Str::limit($namaPondok, 15) }}</h6>
            <small>E-Raport System</small>
        </div>
    </div>

    <div class="sidebar-nav" id="sidebarNav">
        @include('layouts.partials.sidebar-' . auth()->user()->role)
    </div>

    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ auth()->user()->foto_url }}" class="avatar avatar-sm" alt="avatar">
            <div class="flex-1" style="min-width:0;">
                <div style="font-size:11px;font-weight:700;color:white;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                    {{ auth()->user()->name }}
                </div>
                <div style="font-size:10px;color:rgba(255,255,255,0.6);text-transform:capitalize;">
                    {{ str_replace('_', ' ', auth()->user()->role) }}
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- ===== TOPBAR ===== -->
<header class="topbar" id="topbar">
    <div class="topbar-left">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="bi bi-list" style="font-size:22px;"></i>
        </button>
        <div>
            <div class="page-title">@yield('page-title', 'Dashboard')</div>
            <nav aria-label="breadcrumb" class="d-none d-md-block">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>
    </div>

    <div class="topbar-right">
        <!-- Dark Mode -->
        <button id="darkModeToggle" class="sidebar-toggle" title="Dark Mode">
            <i class="bi bi-moon-fill"></i>
        </button>

        <!-- User Dropdown -->
        <div class="dropdown user-dropdown">
            <button class="btn p-0 d-flex align-items-center gap-2" data-bs-toggle="dropdown" id="userDropdown">
                <img src="{{ auth()->user()->foto_url }}" alt="avatar" class="topbar-avatar">
                <div class="user-info text-start d-none d-md-block">
                    <span class="d-block" style="font-size:13px;font-weight:600;line-height:1.2;">{{ Str::limit(auth()->user()->name, 18) }}</span>
                    <small class="text-capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</small>
                </div>
                <i class="bi bi-chevron-down" style="font-size:11px;color:var(--gray-500);"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius:12px;min-width:180px;margin-top:8px;">
                <li><h6 class="dropdown-header" style="font-size:11px;">{{ auth()->user()->email }}</h6></li>
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('profile.index') }}" style="font-size:13px;">
                        <i class="bi bi-person me-2 text-primary"></i>Profil Saya
                    </a>
                </li>
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger" style="font-size:13px;">
                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

<!-- ===== MAIN CONTENT ===== -->
<main class="main-content" id="mainContent">
    <div class="content-wrapper">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible alert-auto-hide d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible alert-auto-hide d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="bi bi-exclamation-circle-fill fs-5"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</main>

<!-- Scroll to Top -->
<button class="scroll-top" title="Kembali ke atas"><i class="bi bi-arrow-up"></i></button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@vite(['resources/js/app.js'])
@stack('scripts')
</body>
</html>

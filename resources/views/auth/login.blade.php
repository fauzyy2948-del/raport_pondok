<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — E-Raport Pondok Pesantren Subulussalam</title>
    <meta name="description" content="Sistem Informasi E-Raport Pondok Pesantren Subulussalam Kabupaten Tangerang">
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
<div class="login-wrapper">
    <!-- Decorative Circles -->
    <div style="position:absolute;top:-100px;right:-100px;width:350px;height:350px;background:rgba(212,175,55,0.08);border-radius:50%;pointer-events:none;"></div>
    <div style="position:absolute;bottom:-80px;left:-80px;width:280px;height:280px;background:rgba(255,255,255,0.05);border-radius:50%;pointer-events:none;"></div>

    <div class="login-card">
        <!-- Logo & Branding -->
        @php
            $appPengaturan = \App\Models\PengaturanPondok::first();
            $logoApp = $appPengaturan ? $appPengaturan->logo_url : asset('images/logo.png');
        @endphp
        <div class="text-center mb-4">
            <img src="{{ $logoApp }}" alt="Logo" class="login-logo mb-3">
            <h1 class="login-title mb-1">E-Raport Pondok</h1>
            <p class="login-subtitle">Pondok Pesantren Subulussalam<br>Kabupaten Tangerang</p>
            <hr class="divider-gold">
            <p class="text-muted" style="font-size:11px;">بِسْمِ اللهِ الرَّحْمٰنِ الرَّحِيْمِ</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-3 alert-auto-hide">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        @if($errors->has('email'))
            <div class="alert alert-danger mb-3">
                <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first('email') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" id="loginForm">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text" style="background:var(--light);border:1.5px solid var(--gray-200);border-right:none;">
                        <i class="bi bi-envelope text-muted"></i>
                    </span>
                    <input type="email" name="email" id="email"
                           class="form-control @error('email') is-invalid @enderror"
                           style="border-left:none;"
                           value="{{ old('email') }}"
                           placeholder="nama@email.com" required autofocus>
                </div>
                @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text" style="background:var(--light);border:1.5px solid var(--gray-200);border-right:none;">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           style="border-left:none;border-right:none;"
                           placeholder="Masukkan password" required>
                    <button type="button" class="input-group-text" id="togglePass"
                            style="background:var(--light);border:1.5px solid var(--gray-200);border-left:none;cursor:pointer;">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
                @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember" style="font-size:13px;">Ingat saya</label>
                </div>
                <a href="{{ route('password.request') }}" style="font-size:12px;color:var(--primary);text-decoration:none;font-weight:600;">
                    Lupa password?
                </a>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2" style="font-size:14px;font-weight:700;">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
            </button>
        </form>

        <p class="text-center mt-4 mb-0" style="font-size:11px;color:var(--gray-500);">
            &copy; {{ date('Y') }} E-Raport Subulussalam. Hak cipta dilindungi.
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('togglePass')?.addEventListener('click', function() {
        const pwd = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            pwd.type = 'password';
            icon.className = 'bi bi-eye';
        }
    });
</script>
</body>
</html>

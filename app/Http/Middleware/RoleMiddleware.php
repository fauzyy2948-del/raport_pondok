<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->aktif) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan. Hubungi administrator.');
        }

        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Redirect ke dashboard role yang sesuai
        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'ustadz' => redirect()->route('ustadz.dashboard'),
            'santri' => redirect()->route('santri.dashboard'),
            'wali_santri' => redirect()->route('wali.dashboard'),
            default => redirect()->route('login'),
        };
    }
}

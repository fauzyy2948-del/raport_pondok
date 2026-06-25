<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Ustadz;
use App\Http\Controllers\Santri;
use App\Http\Controllers\WaliSantri;

// Halaman utama
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'ustadz' => redirect()->route('ustadz.dashboard'),
            'santri' => redirect()->route('santri.dashboard'),
            'wali_santri' => redirect()->route('wali.dashboard'),
            default => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.request');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profil', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profil', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// ==================== ADMIN ROUTES ====================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('santri', Admin\SantriController::class);
    Route::resource('ustadz', Admin\UstadzController::class);
    Route::resource('wali-santri', Admin\WaliSantriController::class);
    Route::resource('kelas', Admin\KelasController::class)->parameters(['kelas' => 'kelas']);
    Route::resource('mapel', Admin\MapelController::class);
    Route::resource('tahun-ajaran', Admin\TahunAjaranController::class);
    Route::resource('jadwal', Admin\JadwalController::class);
    Route::resource('pengumuman', Admin\PengumumanController::class);


    // Pengaturan
    Route::get('/pengaturan', [Admin\PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::put('/pengaturan', [Admin\PengaturanController::class, 'update'])->name('pengaturan.update');
});

// ==================== USTADZ ROUTES ====================
Route::middleware(['auth', 'role:ustadz'])->prefix('ustadz')->name('ustadz.')->group(function () {

    Route::get('/dashboard', [Ustadz\DashboardController::class, 'index'])->name('dashboard');

    // Nilai
    Route::get('/nilai', [Ustadz\NilaiController::class, 'index'])->name('nilai.index');
    Route::get('/nilai/create', [Ustadz\NilaiController::class, 'create'])->name('nilai.create');
    Route::post('/nilai', [Ustadz\NilaiController::class, 'store'])->name('nilai.store');
    Route::get('/nilai/rekap', [Ustadz\NilaiController::class, 'rekap'])->name('nilai.rekap');

    // Absensi
    Route::get('/absensi', [Ustadz\AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi', [Ustadz\AbsensiController::class, 'store'])->name('absensi.store');

    // Catatan Pembinaan
    Route::resource('catatan', Ustadz\CatatanController::class);

    // Raport (Wali Kelas)
    Route::get('/raport/dashboard', [Ustadz\RaportController::class, 'dashboard'])->name('raport.dashboard');
    Route::get('/raport', [Ustadz\RaportController::class, 'index'])->name('raport.index');
    Route::post('/raport/generate', [Ustadz\RaportController::class, 'generate'])->name('raport.generate');
    Route::get('/raport/{raport}', [Ustadz\RaportController::class, 'show'])->name('raport.show');
    Route::get('/raport/{raport}/cetak', [Ustadz\RaportController::class, 'cetak'])->name('raport.cetak');
    Route::post('/raport/{raport}/terbitkan', [Ustadz\RaportController::class, 'terbitkan'])->name('raport.terbitkan');
});

// ==================== SANTRI ROUTES ====================
Route::middleware(['auth', 'role:santri'])->prefix('santri')->name('santri.')->group(function () {

    Route::get('/dashboard', [Santri\DashboardController::class, 'index'])->name('dashboard');

    // Nilai
    Route::get('/nilai', function () {
        $santri = auth()->user()->santri;
        $tahunAktif = \App\Models\TahunAjaran::aktif();
        $selectedTahun = request('tahun_ajaran_id') ?? $tahunAktif?->id;
        $nilai = \App\Models\Nilai::with('mapel')
            ->where('santri_id', $santri?->id)
            ->when($selectedTahun, fn($q) => $q->where('tahun_ajaran_id', $selectedTahun))
            ->get();
        $tahunAjaran = \App\Models\TahunAjaran::orderByDesc('nama')->get();
        return view('santri.nilai.index', compact('nilai', 'santri', 'tahunAjaran', 'selectedTahun'));
    })->name('nilai.index');

    // Absensi
    Route::get('/absensi', function () {
        $santri = auth()->user()->santri;
        $tahunAktif = \App\Models\TahunAjaran::aktif();
        $selectedTahun = request('tahun_ajaran_id') ?? $tahunAktif?->id;
        
        $raport = \App\Models\Raport::where('santri_id', $santri?->id)
            ->where('tahun_ajaran_id', $selectedTahun)
            ->first();
            
        $stats = collect([
            'Hadir' => $raport ? $raport->hadir : 0,
            'Sakit' => $raport ? $raport->sakit : 0,
            'Izin' => $raport ? $raport->izin : 0,
            'Alfa' => $raport ? $raport->alfa : 0,
        ]);
        
        $tahunAjaran = \App\Models\TahunAjaran::orderByDesc('nama')->get();
        return view('santri.absensi.index', compact('stats', 'santri', 'tahunAjaran', 'selectedTahun'));
    })->name('absensi.index');

    // Jadwal
    Route::get('/jadwal', function () {
        $santri = auth()->user()->santri;
        $tahunAktif = \App\Models\TahunAjaran::aktif();
        $jadwal = \App\Models\Jadwal::with(['mapel', 'ustadz'])
            ->whereHas('kelas', fn($q) => $q->where('id', $santri?->kelas_id))
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif?->id))
            ->orderByRaw("CASE hari 
                WHEN 'Senin' THEN 1 
                WHEN 'Selasa' THEN 2 
                WHEN 'Rabu' THEN 3 
                WHEN 'Kamis' THEN 4 
                WHEN 'Jumat' THEN 5 
                WHEN 'Sabtu' THEN 6 
                WHEN 'Ahad' THEN 7 
                ELSE 8 END")
            ->orderBy('jam_mulai')->get()->groupBy('hari');
        return view('santri.jadwal.index', compact('jadwal', 'santri'));
    })->name('jadwal.index');

    // Pengumuman
    Route::get('/pengumuman', function () {
        $pengumuman = \App\Models\Pengumuman::aktif()->untukRole('santri')->latest()->paginate(10);
        return view('santri.pengumuman.index', compact('pengumuman'));
    })->name('pengumuman.index');

    // Raport
    Route::get('/raport', [Santri\RaportController::class, 'index'])->name('raport.index');
    Route::get('/raport/{raport}', [Santri\RaportController::class, 'show'])->name('raport.show');
    Route::get('/raport/{raport}/download', [Santri\RaportController::class, 'download'])->name('raport.download');
});

// ==================== WALI SANTRI ROUTES ====================
Route::middleware(['auth', 'role:wali_santri'])->prefix('wali')->name('wali.')->group(function () {

    Route::get('/dashboard', [WaliSantri\DashboardController::class, 'index'])->name('dashboard');

    // Anak
    Route::get('/anak/{santri}', [WaliSantri\AnakController::class, 'show'])->name('anak.show');
    Route::get('/anak/{raport}/raport/download', [WaliSantri\AnakController::class, 'downloadRaport'])->name('raport.download');

    // Pengumuman
    Route::get('/pengumuman', function () {
        $pengumuman = \App\Models\Pengumuman::aktif()->untukRole('wali_santri')->latest()->paginate(10);
        return view('wali.pengumuman.index', compact('pengumuman'));
    })->name('pengumuman.index');
});

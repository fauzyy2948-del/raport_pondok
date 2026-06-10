<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\Ustadz;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Absensi;
use App\Models\Pengumuman;
use App\Models\TahunAjaran;
use App\Models\PengaturanPondok;
use App\Models\KalenderAkademik;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $tahunAktif = TahunAjaran::aktif();
        $pondok = PengaturanPondok::pengaturan();

        $stats = [
            'total_santri' => Santri::where('status', 'aktif')->count(),
            'total_ustadz' => Ustadz::where('aktif', true)->count(),
            'total_kelas' => Kelas::count(),
            'total_mapel' => Mapel::where('aktif', true)->count(),
        ];

        // Data santri per kelas untuk chart
        $santriPerKelas = Kelas::withCount(['santri' => function ($q) {
            $q->where('status', 'aktif');
        }])->get()->map(fn($k) => [
            'nama' => $k->nama,
            'jumlah' => $k->santri_count,
        ]);

        // Data nilai rata-rata per mapel
        $nilaiPerMapel = [];
        if ($tahunAktif) {
            $nilaiPerMapel = Mapel::aktif()
                ->with(['nilai' => fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id)])
                ->get()
                ->map(fn($m) => [
                    'nama' => $m->nama,
                    'rata_rata' => round($m->nilai->avg('nilai_akhir') ?? 0, 1),
                ]);
        }

        // Absensi bulan ini
        $absensiStats = [];
        if ($tahunAktif) {
            $absensiStats = Absensi::where('tahun_ajaran_id', $tahunAktif->id)
                ->whereMonth('tanggal', now()->month)
                ->selectRaw('status, COUNT(*) as jumlah')
                ->groupBy('status')
                ->pluck('jumlah', 'status')
                ->toArray();
        }

        // Pengumuman terbaru
        $pengumuman = Pengumuman::aktif()->latest()->take(5)->get();

        // Kalender akademik mendatang
        $kalender = $tahunAktif
            ? KalenderAkademik::where('tahun_ajaran_id', $tahunAktif->id)
                ->where('tanggal_mulai', '>=', now()->toDateString())
                ->orderBy('tanggal_mulai')
                ->take(5)
                ->get()
            : collect();

        return view('admin.dashboard', compact(
            'stats', 'santriPerKelas', 'nilaiPerMapel',
            'absensiStats', 'pengumuman', 'kalender',
            'tahunAktif', 'pondok'
        ));
    }
}

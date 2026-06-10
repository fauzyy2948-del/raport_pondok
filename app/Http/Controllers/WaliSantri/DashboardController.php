<?php

namespace App\Http\Controllers\WaliSantri;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Absensi;
use App\Models\Raport;
use App\Models\Pengumuman;
use App\Models\TahunAjaran;
use App\Models\CatatanPembinaan;

class DashboardController extends Controller
{
    public function index()
    {
        $wali = auth()->user()->waliSantri;
        $tahunAktif = TahunAjaran::aktif();
        $anakList = $wali ? $wali->santri()->with('kelas')->aktif()->get() : collect();

        $summaries = $anakList->map(function ($anak) use ($tahunAktif) {
            $nilaiRata = Nilai::where('santri_id', $anak->id)
                ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif?->id))
                ->avg('nilai_akhir');

            $absensiStats = Absensi::where('santri_id', $anak->id)
                ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif?->id))
                ->selectRaw('status, COUNT(*) as jumlah')
                ->groupBy('status')
                ->pluck('jumlah', 'status');

            return [
                'santri' => $anak,
                'rata_nilai' => round($nilaiRata ?? 0, 1),
                'hadir' => $absensiStats['hadir'] ?? 0,
                'alfa' => $absensiStats['alfa'] ?? 0,
            ];
        });

        $pengumuman = Pengumuman::aktif()->untukRole('wali_santri')->latest()->take(5)->get();
        $raportTerbaru = Raport::with(['santri', 'tahunAjaran'])
            ->whereIn('santri_id', $anakList->pluck('id'))
            ->where('diterbitkan', true)
            ->latest()
            ->take(3)
            ->get();

        return view('wali.dashboard', compact('wali', 'summaries', 'pengumuman', 'raportTerbaru', 'tahunAktif'));
    }
}

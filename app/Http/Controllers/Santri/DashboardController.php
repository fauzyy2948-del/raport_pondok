<?php

namespace App\Http\Controllers\Santri;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Absensi;
use App\Models\Pengumuman;
use App\Models\Jadwal;
use App\Models\TahunAjaran;

class DashboardController extends Controller
{
    public function index()
    {
        $santri = auth()->user()->santri;
        $tahunAktif = TahunAjaran::aktif();

        if (!$santri) {
            return view('santri.dashboard', ['santri' => null, 'tahunAktif' => null]);
        }

        $nilaiTerbaru = Nilai::with('mapel')
            ->where('santri_id', $santri->id)
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->latest()
            ->take(5)
            ->get();

        $rataRataNilai = Nilai::where('santri_id', $santri->id)
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->avg('nilai_akhir');

        $absensiStats = Absensi::where('santri_id', $santri->id)
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->selectRaw('status, COUNT(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        $hadirCount = $absensiStats->get('Hadir', 0);

        $raport = \App\Models\Raport::where('santri_id', $santri->id)
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->where('diterbitkan', true)
            ->first();
        $raportTersedia = (bool)$raport;

        $hari = now()->locale('id')->isoFormat('dddd');
        if ($hari === 'Minggu') {
            $hari = 'Ahad';
        }

        $jadwalHariIni = Jadwal::with(['mapel', 'ustadz'])
            ->whereHas('kelas', fn($q) => $q->where('id', $santri->kelas_id))
            ->where('hari', $hari)
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->orderBy('jam_mulai')
            ->get();

        $pengumumans = Pengumuman::aktif()->untukRole('santri')->latest()->take(3)->get();

        return view('santri.dashboard', compact(
            'santri', 'tahunAktif', 'nilaiTerbaru',
            'rataRataNilai', 'absensiStats', 'hadirCount',
            'raport', 'raportTersedia', 'jadwalHariIni', 'pengumumans'
        ));
    }
}

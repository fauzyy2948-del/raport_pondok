<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Nilai;
use App\Models\Absensi;
use App\Models\Pengumuman;
use App\Models\TahunAjaran;

class DashboardController extends Controller
{
    public function index()
    {
        $ustadz = auth()->user()->ustadz;
        $tahunAktif = TahunAjaran::aktif();

        $hari = now()->locale('id')->isoFormat('dddd');
        if ($hari === 'Minggu') {
            $hari = 'Ahad';
        }

        $jadwalHariIni = Jadwal::with(['kelas', 'mapel'])
            ->where('ustadz_id', $ustadz?->id)
            ->where('hari', $hari)
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->orderBy('jam_mulai')
            ->get();

        $jadwalMingguIni = Jadwal::with(['kelas', 'mapel'])
            ->where('ustadz_id', $ustadz?->id)
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->orderByRaw("CASE hari 
                WHEN 'Senin' THEN 1 
                WHEN 'Selasa' THEN 2 
                WHEN 'Rabu' THEN 3 
                WHEN 'Kamis' THEN 4 
                WHEN 'Jumat' THEN 5 
                WHEN 'Sabtu' THEN 6 
                WHEN 'Ahad' THEN 7 
                ELSE 8 END")
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('hari');

        $totalNilaiDiinput = $ustadz
            ? Nilai::where('ustadz_id', $ustadz->id)
                ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
                ->count()
            : 0;

        $totalAbsensiDiinput = $ustadz
            ? Absensi::where('ustadz_id', $ustadz->id)
                ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
                ->whereMonth('tanggal', now()->month)
                ->count()
            : 0;

        $pengumuman = Pengumuman::aktif()
            ->untukRole('ustadz')
            ->latest()
            ->take(5)
            ->get();

        $jadwalCount = $ustadz
            ? Jadwal::where('ustadz_id', $ustadz->id)
                ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
                ->count()
            : 0;

        $santriCount = $ustadz
            ? \App\Models\Santri::whereIn('kelas_id', function ($query) use ($ustadz, $tahunAktif) {
                $query->select('kelas_id')
                    ->from('jadwal')
                    ->where('ustadz_id', $ustadz->id)
                    ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id));
            })->where('status', 'aktif')->count()
            : 0;

        return view('ustadz.dashboard', compact(
            'ustadz', 'tahunAktif', 'jadwalHariIni',
            'jadwalMingguIni', 'totalNilaiDiinput',
            'totalAbsensiDiinput', 'pengumuman', 'jadwalCount', 'santriCount'
        ));
    }
}

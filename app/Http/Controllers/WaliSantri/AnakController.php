<?php

namespace App\Http\Controllers\WaliSantri;

use App\Http\Controllers\Controller;
use App\Models\Raport;
use App\Models\Nilai;
use App\Models\Absensi;
use App\Models\CatatanPembinaan;
use App\Models\Santri;
use App\Models\PengaturanPondok;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AnakController extends Controller
{
    public function show(Santri $santri)
    {
        $wali = auth()->user()->waliSantri;
        if ($santri->wali_santri_id !== $wali?->id) {
            abort(403);
        }

        $tahunAktif = TahunAjaran::aktif();
        $tahunAjaran = TahunAjaran::orderByDesc('nama')->get();
        $selectedTahun = request('tahun_ajaran_id') ?? $tahunAktif?->id;

        $nilai = Nilai::with('mapel')
            ->where('santri_id', $santri->id)
            ->when($selectedTahun, fn($q) => $q->where('tahun_ajaran_id', $selectedTahun))
            ->get();

        $absensi = Absensi::where('santri_id', $santri->id)
            ->when($selectedTahun, fn($q) => $q->where('tahun_ajaran_id', $selectedTahun))
            ->selectRaw('status, COUNT(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        $catatan = CatatanPembinaan::with('ustadz')
            ->where('santri_id', $santri->id)
            ->when($selectedTahun, fn($q) => $q->where('tahun_ajaran_id', $selectedTahun))
            ->latest('tanggal')
            ->get();

        $raport = Raport::with('tahunAjaran')
            ->where('santri_id', $santri->id)
            ->where('diterbitkan', true)
            ->latest()
            ->get();

        return view('wali.anak.show', compact('santri', 'nilai', 'absensi', 'catatan', 'raport', 'tahunAjaran', 'selectedTahun'));
    }

    public function downloadRaport(Raport $raport)
    {
        $wali = auth()->user()->waliSantri;
        $santri = $raport->santri;
        if ($santri->wali_santri_id !== $wali?->id || !$raport->diterbitkan) {
            abort(403);
        }

        $raport->load(['santri.kelas', 'santri.waliSantri', 'tahunAjaran', 'detail.mapel']);
        $pengaturan = PengaturanPondok::pengaturan();
        $tahunAjaran = $raport->tahunAjaran;
        $raportDetails = $raport->detail;

        $pdf = Pdf::loadView('raport_pdf', compact('raport', 'pengaturan', 'santri', 'tahunAjaran', 'raportDetails'))
            ->setPaper('A4', 'portrait');

        return $pdf->download("raport_{$raport->santri->nisn}_{$raport->tahunAjaran->nama}.pdf");
    }
}

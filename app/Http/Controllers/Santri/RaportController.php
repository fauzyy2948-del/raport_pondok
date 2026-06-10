<?php

namespace App\Http\Controllers\Santri;

use App\Http\Controllers\Controller;
use App\Models\Raport;
use App\Models\PengaturanPondok;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RaportController extends Controller
{
    public function index()
    {
        $santri = auth()->user()->santri;
        $raport = Raport::with(['tahunAjaran', 'kelas'])
            ->where('santri_id', $santri?->id)
            ->where('diterbitkan', true)
            ->latest()
            ->get();

        return view('santri.raport.index', compact('raport', 'santri'));
    }

    public function show(Raport $raport)
    {
        $santri = auth()->user()->santri;
        if ($raport->santri_id !== $santri?->id || !$raport->diterbitkan) {
            abort(403);
        }

        $raport->load(['santri.kelas', 'santri.waliSantri', 'tahunAjaran', 'detail.mapel']);
        $pondok = PengaturanPondok::pengaturan();

        return view('santri.raport.show', compact('raport', 'pondok'));
    }

    public function download(Raport $raport)
    {
        $santri = auth()->user()->santri;
        if ($raport->santri_id !== $santri?->id || !$raport->diterbitkan) {
            abort(403);
        }

        $raport->load(['santri.kelas', 'santri.waliSantri', 'tahunAjaran', 'detail.mapel']);
        $pengaturan = PengaturanPondok::pengaturan();
        $santriModel = $raport->santri;
        $tahunAjaran = $raport->tahunAjaran;
        $raportDetails = $raport->detail;

        $pdf = Pdf::loadView('raport_pdf', compact('raport', 'pengaturan', 'santri' , 'tahunAjaran', 'raportDetails'))
            ->setPaper('A4', 'portrait');

        return $pdf->download("raport_{$raport->santri->nisn}_{$raport->tahunAjaran->nama}.pdf");
    }
}

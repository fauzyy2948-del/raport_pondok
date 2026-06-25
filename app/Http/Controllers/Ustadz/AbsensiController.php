<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Santri;
use App\Models\TahunAjaran;
use App\Models\Raport;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $tahunAktif = TahunAjaran::aktif();
        
        // Hanya tampilkan jika ada tahun ajaran aktif
        if (!$tahunAktif) {
            return redirect()->route('ustadz.dashboard')->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        $kelasList = Kelas::orderBy('nama')->get();
        $selectedKelasId = $request->kelas_id;
        
        $santris = collect();
        $kelasTerpilih = null;

        if ($selectedKelasId) {
            $kelasTerpilih = Kelas::find($selectedKelasId);
            if ($kelasTerpilih) {
                // Ambil santri di kelas terpilih beserta data raport di tahun ajaran aktif
                $santris = Santri::aktif()
                    ->where('kelas_id', $selectedKelasId)
                    ->with(['raport' => function ($q) use ($tahunAktif) {
                        $q->where('tahun_ajaran_id', $tahunAktif->id);
                    }])
                    ->orderBy('nama')
                    ->get();
            }
        }

        return view('ustadz.absensi.index', compact(
            'kelasList', 'santris', 'kelasTerpilih', 'tahunAktif'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'absensi' => 'required|array',
            'absensi.*.sakit' => 'required|integer|min:0',
            'absensi.*.izin' => 'required|integer|min:0',
            'absensi.*.alfa' => 'required|integer|min:0',
        ]);

        $tahunAktif = TahunAjaran::aktif();
        if (!$tahunAktif) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        foreach ($request->absensi as $santriId => $data) {
            Raport::updateOrCreate(
                [
                    'santri_id' => $santriId,
                    'tahun_ajaran_id' => $tahunAktif->id,
                ],
                [
                    'kelas_id' => $request->kelas_id,
                    'sakit' => $data['sakit'],
                    'izin' => $data['izin'],
                    'alfa' => $data['alfa'],
                ]
            );
        }

        return redirect()->back()->with('success', 'Rekap absensi semester berhasil disimpan.');
    }
}

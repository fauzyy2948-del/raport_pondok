<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Santri;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $ustadz = auth()->user()->ustadz;
        $tahunAktif = TahunAjaran::aktif();
        $tahunAjaran = TahunAjaran::orderByDesc('nama')->get();
        $kelasList = Kelas::orderBy('nama')->get();

        $selectedTahun = $request->tahun_ajaran_id ?? $tahunAktif?->id;
        $selectedKelas = $request->kelas_id;
        $selectedMapel = $request->mapel_id;

        // Mapel yang diampu ustadz ini
        $mapelDiampu = Mapel::aktif()
            ->whereHas('jadwal', fn($q) => $q->where('ustadz_id', $ustadz?->id)
                ->when($selectedTahun, fn($q) => $q->where('tahun_ajaran_id', $selectedTahun)))
            ->get();
        
        $mapels = $mapelDiampu;

        $santris = collect();
        if ($selectedKelas && $selectedMapel && $selectedTahun) {
            $santris = Santri::aktif()
                ->where('kelas_id', $selectedKelas)
                ->with(['nilai' => function ($query) use ($selectedMapel, $selectedTahun) {
                    $query->where('mapel_id', $selectedMapel)
                          ->where('tahun_ajaran_id', $selectedTahun);
                }])
                ->orderBy('nama')
                ->get();
        }

        return view('ustadz.nilai.index', compact(
            'santris', 'kelasList', 'tahunAjaran', 'mapels',
            'selectedTahun', 'selectedKelas', 'selectedMapel', 'tahunAktif'
        ));
    }

    public function create(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapel,id',
        ]);

        $ustadz = auth()->user()->ustadz;
        $tahunAktif = TahunAjaran::aktif();
        $selectedTahun = $request->tahun_ajaran_id ?? $tahunAktif?->id;

        $kelas = Kelas::findOrFail($request->kelas_id);
        $mapel = Mapel::findOrFail($request->mapel_id);

        $santris = Santri::aktif()
            ->where('kelas_id', $kelas->id)
            ->with(['nilai' => function ($query) use ($mapel, $selectedTahun) {
                $query->where('mapel_id', $mapel->id)
                      ->where('tahun_ajaran_id', $selectedTahun);
            }])
            ->orderBy('nama')
            ->get();

        return view('ustadz.nilai.create', compact('kelas', 'mapel', 'santris', 'selectedTahun'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'mapel_id' => 'required|exists:mapel,id',
            'nilai' => 'required|array',
            'nilai.*.santri_id' => 'required|exists:santri,id',
        ]);

        $ustadz = auth()->user()->ustadz;

        foreach ($request->nilai as $nilaiItem) {
            $nilaiHarian = $nilaiItem['nilai_harian'] ?? null;
            $nilaiTugas = $nilaiItem['nilai_tugas'] ?? null;
            $nilaiUts = $nilaiItem['nilai_uts'] ?? null;
            $nilaiUas = $nilaiItem['nilai_uas'] ?? null;
            $nilaiHafalan = $nilaiItem['nilai_hafalan'] ?? null;
            $nilaiAdab = $nilaiItem['nilai_adab'] ?? null;

            // Hitung nilai akhir
            $nilaiAkhir = round(
                (($nilaiHarian ?? 0) * 0.20) +
                (($nilaiTugas ?? 0) * 0.10) +
                (($nilaiUts ?? 0) * 0.25) +
                (($nilaiUas ?? 0) * 0.30) +
                (($nilaiHafalan ?? 0) * 0.10) +
                (($nilaiAdab ?? 0) * 0.05),
                2
            );

            Nilai::updateOrCreate(
                [
                    'santri_id' => $nilaiItem['santri_id'],
                    'mapel_id' => $request->mapel_id,
                    'tahun_ajaran_id' => $request->tahun_ajaran_id,
                ],
                [
                    'ustadz_id' => $ustadz?->id,
                    'nilai_harian' => $nilaiHarian,
                    'nilai_tugas' => $nilaiTugas,
                    'nilai_uts' => $nilaiUts,
                    'nilai_uas' => $nilaiUas,
                    'nilai_hafalan' => $nilaiHafalan,
                    'nilai_adab' => $nilaiAdab,
                    'nilai_akhir' => $nilaiAkhir,
                    'predikat' => Nilai::hitungPredikat($nilaiAkhir),
                    'catatan' => $nilaiItem['catatan'] ?? null,
                ]
            );
        }

        return redirect()->route('ustadz.nilai.index', [
            'kelas_id' => $request->kelas_id,
            'mapel_id' => $request->mapel_id,
        ])->with('success', 'Nilai berhasil disimpan.');
    }

    public function rekap(Request $request)
    {
        $ustadz = auth()->user()->ustadz;
        $tahunAktif = TahunAjaran::aktif();
        $selectedTahun = $request->tahun_ajaran_id ?? $tahunAktif?->id;
        $selectedKelas = $request->kelas_id;

        $rekapData = collect();
        if ($selectedKelas && $selectedTahun) {
            $rekapData = Santri::aktif()
                ->where('kelas_id', $selectedKelas)
                ->with(['nilai' => fn($q) => $q->where('tahun_ajaran_id', $selectedTahun)
                    ->where('ustadz_id', $ustadz?->id)->with('mapel')])
                ->orderBy('nama')
                ->get();
        }

        $kelas = Kelas::orderBy('nama')->get();
        $tahunAjaran = TahunAjaran::orderByDesc('nama')->get();

        return view('ustadz.nilai.rekap', compact('rekapData', 'kelas', 'tahunAjaran', 'selectedTahun', 'selectedKelas'));
    }
}

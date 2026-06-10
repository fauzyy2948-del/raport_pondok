<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Raport;
use App\Models\RaportDetail;
use App\Models\Santri;
use App\Models\Nilai;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\PengaturanPondok;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RaportController extends Controller
{
    public function dashboard(Request $request)
    {
        $tahunAktif = TahunAjaran::aktif();
        $tahunAjaran = TahunAjaran::orderByDesc('nama')->get();
        $selectedTahun = $request->tahun_ajaran_id ?? $tahunAktif?->id;
        $tahunSelected = $selectedTahun ? TahunAjaran::find($selectedTahun) : $tahunAktif;

        // Stat cards
        $totalSantriAktif = Santri::where('status', 'aktif')->count();
        $totalRaport = Raport::when($selectedTahun, fn($q) => $q->where('tahun_ajaran_id', $selectedTahun))->count();
        $raportTerbit = Raport::when($selectedTahun, fn($q) => $q->where('tahun_ajaran_id', $selectedTahun))
            ->where('diterbitkan', true)->count();
        $raportBelumTerbit = $totalRaport - $raportTerbit;
        $rataRataUmum = Raport::when($selectedTahun, fn($q) => $q->where('tahun_ajaran_id', $selectedTahun))
            ->avg('rata_rata') ?? 0;

        // Progress generate per kelas
        $kelasProgress = Kelas::withCount(['santri as total_santri' => function ($q) {
            $q->where('status', 'aktif');
        }])->get()->map(function ($kelas) use ($selectedTahun) {
            $sudahGenerate = Raport::where('kelas_id', $kelas->id)
                ->when($selectedTahun, fn($q) => $q->where('tahun_ajaran_id', $selectedTahun))
                ->count();
            $terbit = Raport::where('kelas_id', $kelas->id)
                ->when($selectedTahun, fn($q) => $q->where('tahun_ajaran_id', $selectedTahun))
                ->where('diterbitkan', true)
                ->count();
            $rataRata = Raport::where('kelas_id', $kelas->id)
                ->when($selectedTahun, fn($q) => $q->where('tahun_ajaran_id', $selectedTahun))
                ->avg('rata_rata') ?? 0;
            return [
                'nama'          => $kelas->nama,
                'total_santri'  => $kelas->total_santri,
                'sudah_generate'=> $sudahGenerate,
                'terbit'        => $terbit,
                'rata_rata'     => round($rataRata, 1),
                'persen'        => $kelas->total_santri > 0
                    ? round(($sudahGenerate / $kelas->total_santri) * 100)
                    : 0,
            ];
        });

        // Distribusi predikat
        $distribusiPredikat = Raport::when($selectedTahun, fn($q) => $q->where('tahun_ajaran_id', $selectedTahun))
            ->whereNotNull('predikat_akhir')
            ->selectRaw('predikat_akhir, COUNT(*) as jumlah')
            ->groupBy('predikat_akhir')
            ->orderByRaw("CASE predikat_akhir
                WHEN 'A' THEN 1 WHEN 'B+' THEN 2 WHEN 'B' THEN 3
                WHEN 'C+' THEN 4 WHEN 'C' THEN 5 WHEN 'D' THEN 6 ELSE 7 END")
            ->get();

        // Nilai rata-rata per mapel (dari raport_detail)
        $nilaiPerMapel = DB::table('raport_detail')
            ->join('raport', 'raport_detail.raport_id', '=', 'raport.id')
            ->join('mapel', 'raport_detail.mapel_id', '=', 'mapel.id')
            ->when($selectedTahun, fn($q) => $q->where('raport.tahun_ajaran_id', $selectedTahun))
            ->selectRaw('mapel.nama, ROUND(AVG(raport_detail.nilai_akhir), 1) as rata_rata')
            ->groupBy('mapel.id', 'mapel.nama')
            ->orderByDesc('rata_rata')
            ->take(10)
            ->get();

        // Top 10 santri terbaik
        $topSantri = Raport::with(['santri', 'kelas'])
            ->when($selectedTahun, fn($q) => $q->where('tahun_ajaran_id', $selectedTahun))
            ->whereNotNull('rata_rata')
            ->orderByDesc('rata_rata')
            ->take(10)
            ->get();

        // Raport terbaru diterbitkan
        $raportTerbaru = Raport::with(['santri', 'kelas', 'tahunAjaran'])
            ->where('diterbitkan', true)
            ->latest('diterbitkan_pada')
            ->take(8)
            ->get();

        // Trend raport per bulan (12 bulan terakhir)
        $trendBulanan = Raport::selectRaw('STRFTIME("%Y-%m", created_at) as bulan, COUNT(*) as jumlah')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupByRaw('STRFTIME("%Y-%m", created_at)')
            ->orderBy('bulan')
            ->get();

        return view('admin.raport.dashboard', compact(
            'tahunAktif', 'tahunAjaran', 'selectedTahun', 'tahunSelected',
            'totalSantriAktif', 'totalRaport', 'raportTerbit', 'raportBelumTerbit',
            'rataRataUmum', 'kelasProgress', 'distribusiPredikat',
            'nilaiPerMapel', 'topSantri', 'raportTerbaru', 'trendBulanan'
        ));
    }

    public function index(Request $request)
    {
        $aktifTA    = TahunAjaran::aktif();
        $tahunAjarans = TahunAjaran::orderByDesc('nama')->get();
        $kelasList  = Kelas::orderBy('nama')->get();
        $selectedTahunId = $request->tahun_ajaran_id ?? $aktifTA?->id;

        $santris = Santri::with([
                'kelas',
                'user',
                'raport' => fn($q) => $q->when($selectedTahunId, fn($q2) => $q2->where('tahun_ajaran_id', $selectedTahunId)),
            ])
            ->when($request->kelas_id, fn($q) => $q->where('kelas_id', $request->kelas_id))
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($q2) use ($request) {
                    $q2->where('nisn', 'like', '%'.$request->search.'%')
                       ->orWhere('nama', 'like', '%'.$request->search.'%')
                       ->orWhereHas('user', fn($q3) => $q3->where('name', 'like', '%'.$request->search.'%'));
                });
            })
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->paginate(20)
            ->withQueryString();

        return view('admin.raport.index', compact(
            'santris', 'kelasList', 'tahunAjarans', 'aktifTA', 'selectedTahunId'
        ));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'tahun_ajaran_id'  => 'required|exists:tahun_ajaran,id',
            'kelas_id'         => 'nullable|exists:kelas,id',
            'santri_id_single' => 'nullable|exists:santri,id',
        ]);

        $tahunAjaran = TahunAjaran::findOrFail($request->tahun_ajaran_id);

        $santriQuery = Santri::aktif()
            ->with(['nilai' => fn($q) => $q->where('tahun_ajaran_id', $tahunAjaran->id)->with('mapel')]);

        // Single santri generate (dari tombol per-baris di tabel)
        if ($request->santri_id_single) {
            $santriQuery->where('id', $request->santri_id_single);
        } elseif ($request->kelas_id) {
            $santriQuery->where('kelas_id', $request->kelas_id);
        }

        $santriList = $santriQuery->get();
        $generated = 0;

        DB::transaction(function () use ($santriList, $tahunAjaran, &$generated) {
            foreach ($santriList as $santri) {
                $nilaiList = $santri->nilai;

                if ($nilaiList->isEmpty()) continue;

                $rataRata = round($nilaiList->avg('nilai_akhir'), 2);

                // Hitung absensi
                $absensiStat = Absensi::where('santri_id', $santri->id)
                    ->where('tahun_ajaran_id', $tahunAjaran->id)
                    ->selectRaw('status, COUNT(*) as jumlah')
                    ->groupBy('status')
                    ->pluck('jumlah', 'status');

                $raport = Raport::updateOrCreate(
                    ['santri_id' => $santri->id, 'tahun_ajaran_id' => $tahunAjaran->id],
                    [
                        'kelas_id' => $santri->kelas_id,
                        'rata_rata' => $rataRata,
                        'hadir' => $absensiStat['hadir'] ?? 0,
                        'sakit' => $absensiStat['sakit'] ?? 0,
                        'izin' => $absensiStat['izin'] ?? 0,
                        'alfa' => $absensiStat['alfa'] ?? 0,
                        'predikat_akhir' => Nilai::hitungPredikat($rataRata),
                    ]
                );

                // Buat raport detail
                RaportDetail::where('raport_id', $raport->id)->delete();
                foreach ($nilaiList as $nilai) {
                    RaportDetail::create([
                        'raport_id' => $raport->id,
                        'mapel_id' => $nilai->mapel_id,
                        'nilai_harian' => $nilai->nilai_harian,
                        'nilai_tugas' => $nilai->nilai_tugas,
                        'nilai_uts' => $nilai->nilai_uts,
                        'nilai_uas' => $nilai->nilai_uas,
                        'nilai_hafalan' => $nilai->nilai_hafalan,
                        'nilai_adab' => $nilai->nilai_adab,
                        'nilai_akhir' => $nilai->nilai_akhir,
                        'predikat' => $nilai->predikat,
                        'catatan' => $nilai->catatan,
                    ]);
                }

                $generated++;
            }

            // Update peringkat per kelas
            $raportByKelas = Raport::where('tahun_ajaran_id', $tahunAjaran->id)
                ->whereIn('santri_id', $santriList->pluck('id'))
                ->orderByDesc('rata_rata')
                ->get();

            $peringkat = 1;
            foreach ($raportByKelas as $r) {
                $r->update(['peringkat' => $peringkat, 'jumlah_siswa' => $raportByKelas->count()]);
                $peringkat++;
            }
        });

        return redirect()->route('admin.raport.index')
            ->with('success', "Berhasil generate raport untuk {$generated} santri.");
    }

    public function show(Raport $raport)
    {
        $raport->load(['santri.kelas', 'santri.waliSantri', 'tahunAjaran', 'detail.mapel']);
        $pondok = PengaturanPondok::pengaturan();
        return view('admin.raport.show', compact('raport', 'pondok'));
    }

    public function cetak(Raport $raport)
    {
        $raport->load(['santri.kelas', 'santri.waliSantri', 'tahunAjaran', 'detail.mapel']);
        $pengaturan = PengaturanPondok::pengaturan();

        // Variabel eksplisit agar cocok dengan template PDF
        $santri       = $raport->santri;
        $tahunAjaran  = $raport->tahunAjaran;
        $raportDetails= $raport->detail;

        $pdf = Pdf::loadView('raport_pdf', compact(
            'raport', 'pengaturan', 'santri', 'tahunAjaran', 'raportDetails'
        ))->setPaper('A4', 'portrait');

        $filename = 'raport_' . ($santri->nisn ?? 'santri') . '_' . ($tahunAjaran->nama ?? 'ta') . '.pdf';
        return $pdf->stream($filename);
    }

    public function terbitkan(Raport $raport)
    {
        $raport->update(['diterbitkan' => true, 'diterbitkan_pada' => now()]);
        return back()->with('success', 'Raport berhasil diterbitkan.');
    }
}

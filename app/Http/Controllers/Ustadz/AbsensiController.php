<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Santri;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $ustadz = auth()->user()->ustadz;
        $tahunAktif = TahunAjaran::aktif();

        // Ambil semua jadwal mengajar ustadz ini pada tahun ajaran aktif
        $jadwals = Jadwal::with(['kelas', 'mapel'])
            ->where('ustadz_id', $ustadz?->id)
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->get();

        $selectedTanggal = $request->tanggal ?? now()->toDateString();
        $selectedJadwalId = $request->jadwal_id;

        $santris = collect();
        $jadwalTerpilih = null;

        if ($selectedJadwalId) {
            $jadwalTerpilih = Jadwal::with('kelas')->find($selectedJadwalId);
            if ($jadwalTerpilih) {
                // Ambil santri di kelas jadwal tersebut beserta absensi mereka pada tanggal terpilih
                $santris = Santri::aktif()
                    ->where('kelas_id', $jadwalTerpilih->kelas_id)
                    ->with(['user', 'absensi' => fn($q) => $q
                        ->where('jadwal_id', $selectedJadwalId)
                        ->where('tanggal', $selectedTanggal)
                    ])
                    ->orderBy('nama')
                    ->get();
            }
        }

        return view('ustadz.absensi.index', compact(
            'jadwals', 'santris', 'jadwalTerpilih', 'selectedTanggal', 'tahunAktif'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal,id',
            'tanggal' => 'required|date',
            'absensi' => 'required|array',
        ]);

        $ustadz = auth()->user()->ustadz;
        $jadwal = Jadwal::findOrFail($request->jadwal_id);

        foreach ($request->absensi as $santriId => $data) {
            Absensi::updateOrCreate(
                [
                    'santri_id' => $santriId,
                    'jadwal_id' => $jadwal->id,
                    'tanggal' => $request->tanggal,
                ],
                [
                    'ustadz_id' => $ustadz?->id,
                    'tahun_ajaran_id' => $jadwal->tahun_ajaran_id,
                    'status' => strtolower($data['status'] ?? 'hadir'),
                    'keterangan' => $data['keterangan'] ?? null,
                ]
            );
        }

        return redirect()->back()->with('success', 'Absensi berhasil disimpan.');
    }

    public function scanQr(Request $request)
    {
        try {
            $request->validate([
                'jadwal_id' => 'required|exists:jadwal,id',
                'tanggal' => 'required|date',
                'nisn' => 'required|string',
                'status' => 'required|in:hadir,sakit,izin,alfa,Hadir,Sakit,Izin,Alfa',
                'keterangan' => 'nullable|string',
            ]);

            $ustadz = auth()->user()->ustadz;
            $jadwal = Jadwal::with('kelas')->findOrFail($request->jadwal_id);
            
            // Cari santri aktif berdasarkan NISN
            $santri = Santri::aktif()->where('nisn', $request->nisn)->first();
            if (!$santri) {
                return response()->json([
                    'success' => false,
                    'message' => 'Santri dengan NISN ' . $request->nisn . ' tidak ditemukan atau status tidak aktif.'
                ], 404);
            }

            // Validasi apakah santri tersebut terdaftar di kelas untuk jadwal ini
            if ($santri->kelas_id != $jadwal->kelas_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Santri ' . $santri->nama . ' terdaftar di ' . ($santri->kelas->nama ?? '-') . ', bukan kelas ' . ($jadwal->kelas->nama ?? '-') . '.'
                ], 400);
            }

            $status = strtolower($request->status);

            // Simpan / update absensi
            $absensi = Absensi::updateOrCreate(
                [
                    'santri_id' => $santri->id,
                    'jadwal_id' => $jadwal->id,
                    'tanggal' => $request->tanggal,
                ],
                [
                    'ustadz_id' => $ustadz?->id,
                    'tahun_ajaran_id' => $jadwal->tahun_ajaran_id,
                    'status' => $status,
                    'keterangan' => $request->keterangan,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Absensi ' . $santri->nama . ' (' . ucfirst($status) . ') berhasil dicatat.',
                'santri_id' => $santri->id,
                'nama' => $santri->nama,
                'status' => ucfirst($status),
                'keterangan' => $request->keterangan ?? '',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
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
                ->with(['absensi' => fn($q) => $q->where('tahun_ajaran_id', $selectedTahun)])
                ->orderBy('nama')
                ->get()
                ->map(fn($s) => [
                    'santri' => $s,
                    'hadir' => $s->absensi->where('status', 'hadir')->count(),
                    'sakit' => $s->absensi->where('status', 'sakit')->count(),
                    'izin' => $s->absensi->where('status', 'izin')->count(),
                    'alfa' => $s->absensi->where('status', 'alfa')->count(),
                    'total' => $s->absensi->count(),
                ]);
        }

        $kelas = Kelas::orderBy('nama')->get();
        $tahunAjaran = TahunAjaran::orderByDesc('nama')->get();

        return view('ustadz.absensi.rekap', compact('rekapData', 'kelas', 'tahunAjaran', 'selectedTahun', 'selectedKelas'));
    }
}

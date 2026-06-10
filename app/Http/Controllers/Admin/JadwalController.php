<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Ustadz;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $tahunAktif = TahunAjaran::aktif();
        $tahunAjaranList = TahunAjaran::orderByDesc('nama')->get();
        $kelasList = Kelas::orderBy('nama')->get();
        $selectedKelas = $request->kelas_id ?? $kelasList->first()?->id;
        $selectedTahun = $request->tahun_ajaran_id ?? $tahunAktif?->id;

        $jadwals = Jadwal::with(['kelas', 'mapel', 'ustadz'])
            ->when($selectedTahun, fn($q) => $q->where('tahun_ajaran_id', $selectedTahun))
            ->when($selectedKelas, fn($q) => $q->where('kelas_id', $selectedKelas))
            ->orderBy('jam_mulai')
            ->get();

        return view('admin.jadwal.index', compact('jadwals', 'kelasList', 'tahunAjaranList', 'selectedKelas', 'selectedTahun', 'tahunAktif'));
    }

    public function create()
    {
        $kelasList = Kelas::orderBy('nama')->get();
        $mapels = Mapel::aktif()->orderBy('nama')->get();
        $ustadzs = Ustadz::where('aktif', true)->orderBy('nama')->get();
        $tahunAjarans = TahunAjaran::orderByDesc('nama')->get();
        $aktifTA = TahunAjaran::aktif();
        $hari = Jadwal::urutanHari();
        return view('admin.jadwal.create', compact('kelasList', 'mapels', 'ustadzs', 'tahunAjarans', 'aktifTA', 'hari'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapel,id',
            'ustadz_id' => 'required|exists:ustadz,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Ahad',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        Jadwal::create($request->only('tahun_ajaran_id', 'kelas_id', 'mapel_id', 'ustadz_id', 'hari', 'jam_mulai', 'jam_selesai', 'ruangan'));
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    public function edit(Jadwal $jadwal)
    {
        $kelasList = Kelas::orderBy('nama')->get();
        $mapels = Mapel::aktif()->orderBy('nama')->get();
        $ustadzs = Ustadz::where('aktif', true)->orderBy('nama')->get();
        $tahunAjarans = TahunAjaran::orderByDesc('nama')->get();
        $hari = Jadwal::urutanHari();
        return view('admin.jadwal.edit', compact('jadwal', 'kelasList', 'mapels', 'ustadzs', 'tahunAjarans', 'hari'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapel,id',
            'ustadz_id' => 'required|exists:ustadz,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Ahad',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);
        $jadwal->update($request->only('tahun_ajaran_id', 'kelas_id', 'mapel_id', 'ustadz_id', 'hari', 'jam_mulai', 'jam_selesai', 'ruangan'));
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}

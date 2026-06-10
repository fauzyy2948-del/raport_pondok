<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use App\Models\CatatanPembinaan;
use App\Models\Santri;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class CatatanController extends Controller
{
    public function index(Request $request)
    {
        $ustadz = auth()->user()->ustadz;
        $tahunAktif = TahunAjaran::aktif();

        $catatans = CatatanPembinaan::with(['santri.kelas', 'tahunAjaran'])
            ->where('ustadz_id', $ustadz?->id)
            ->when($request->search, fn($q) => $q->whereHas('santri', fn($q) => $q->where('nama', 'like', "%{$request->search}%")))
            ->when($request->jenis, fn($q) => $q->where('jenis', $request->jenis))
            ->when($request->kelas_id, fn($q) => $q->whereHas('santri', fn($q) => $q->where('kelas_id', $request->kelas_id)))
            ->latest('tanggal')
            ->paginate(15)->withQueryString();

        $kelasList = Kelas::orderBy('nama')->get();

        return view('ustadz.catatan.index', compact('catatans', 'kelasList', 'tahunAktif'));
    }

    public function create()
    {
        $santris = Santri::aktif()->orderBy('nama')->get();
        $tahunAjaran = TahunAjaran::orderByDesc('nama')->get();
        $tahunAktif = TahunAjaran::aktif();
        return view('ustadz.catatan.create', compact('santris', 'tahunAjaran', 'tahunAktif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'jenis' => 'required|in:prestasi,pelanggaran,pembinaan,kesehatan,lainnya',
            'judul' => 'required|string|max:200',
            'isi' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        $ustadz = auth()->user()->ustadz;

        CatatanPembinaan::create([
            'santri_id' => $request->santri_id,
            'ustadz_id' => $ustadz?->id,
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
            'jenis' => $request->jenis,
            'judul' => $request->judul,
            'isi' => $request->isi,
            'tanggal' => $request->tanggal,
            'tindak_lanjut' => $request->tindak_lanjut ?? 'tidak_ada',
        ]);

        return redirect()->route('ustadz.catatan.index')->with('success', 'Catatan pembinaan berhasil disimpan.');
    }

    public function edit(CatatanPembinaan $catatan)
    {
        $ustadz = auth()->user()->ustadz;
        if ($catatan->ustadz_id !== $ustadz?->id) {
            abort(403);
        }
        $santri = Santri::aktif()->orderBy('nama')->get();
        $tahunAjaran = TahunAjaran::orderByDesc('nama')->get();
        return view('ustadz.catatan.edit', compact('catatan', 'santri', 'tahunAjaran'));
    }

    public function update(Request $request, CatatanPembinaan $catatan)
    {
        $catatan->update($request->only('jenis', 'judul', 'isi', 'tanggal', 'tindak_lanjut'));
        return redirect()->route('ustadz.catatan.index')->with('success', 'Catatan berhasil diperbarui.');
    }

    public function destroy(CatatanPembinaan $catatan)
    {
        $catatan->delete();
        return redirect()->route('ustadz.catatan.index')->with('success', 'Catatan berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Ustadz;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::with('waliKelas')->withCount(['santri' => fn($q) => $q->where('status', 'aktif')])
            ->when($request->search, fn($q) => $q->where('nama', 'like', "%{$request->search}%"))
            ->paginate(15)->withQueryString();
        $ustadzList = Ustadz::orderBy('nama')->get();
        return view('admin.kelas.index', compact('kelas', 'ustadzList'));
    }

    public function create()
    {
        $ustadzList = Ustadz::orderBy('nama')->get();
        return view('admin.kelas.create', compact('ustadzList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'tingkat' => 'required|string|max:10',
            'jenis' => 'required|in:diniyah,umum,campuran',
            'kapasitas' => 'required|integer|min:1|max:100',
            'ustadz_id' => 'nullable|exists:ustadz,id',
        ]);
        Kelas::create($request->only('nama', 'tingkat', 'jenis', 'kapasitas', 'keterangan', 'ustadz_id'));
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas)
    {
        $ustadzList = Ustadz::orderBy('nama')->get();
        return view('admin.kelas.edit', compact('kelas', 'ustadzList'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'tingkat' => 'required|string|max:10',
            'jenis' => 'required|in:diniyah,umum,campuran',
            'kapasitas' => 'required|integer|min:1|max:100',
            'ustadz_id' => 'nullable|exists:ustadz,id',
        ]);
        $kelas->update($request->only('nama', 'tingkat', 'jenis', 'kapasitas', 'keterangan', 'ustadz_id'));
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        if ($kelas->santri()->exists()) {
            return back()->with('error', 'Kelas tidak dapat dihapus karena masih memiliki santri.');
        }
        $kelas->delete();
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::withCount(['santri' => fn($q) => $q->where('status', 'aktif')])
            ->when($request->search, fn($q) => $q->where('nama', 'like', "%{$request->search}%"))
            ->paginate(15)->withQueryString();
        return view('admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        return view('admin.kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'tingkat' => 'required|string|max:10',
            'jenis' => 'required|in:diniyah,umum,campuran',
            'kapasitas' => 'required|integer|min:1|max:100',
        ]);
        Kelas::create($request->only('nama', 'tingkat', 'jenis', 'kapasitas', 'keterangan'));
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas)
    {
        return view('admin.kelas.edit', compact('kelas'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'tingkat' => 'required|string|max:10',
            'jenis' => 'required|in:diniyah,umum,campuran',
            'kapasitas' => 'required|integer|min:1|max:100',
        ]);
        $kelas->update($request->only('nama', 'tingkat', 'jenis', 'kapasitas', 'keterangan'));
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

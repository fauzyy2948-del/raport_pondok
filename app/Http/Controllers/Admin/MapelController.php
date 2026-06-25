<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        $mapel = Mapel::when($request->search, fn($q) => $q->where('nama', 'like', "%{$request->search}%")
                ->orWhere('kode', 'like', "%{$request->search}%"))
            ->when($request->kategori, fn($q) => $q->where('kategori', $request->kategori))
            ->paginate(15)->withQueryString();
        return view('admin.mapel.index', compact('mapel'));
    }

    public function create()
    {
        return view('admin.mapel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:mapel,kode|max:20',
            'nama' => 'required|string|max:100',
            'kategori' => 'required|in:diniyah,umum',
            'kkm' => 'required|integer|min:0|max:100',
            'bobot' => 'required|integer|min:1',
        ]);
        Mapel::create($request->only('kode', 'nama', 'kategori', 'kkm', 'bobot', 'keterangan') + ['aktif' => true]);
        return redirect()->route('admin.mapel.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(Mapel $mapel)
    {
        return view('admin.mapel.edit', compact('mapel'));
    }

    public function update(Request $request, Mapel $mapel)
    {
        $request->validate([
            'kode' => ['required', 'max:20', \Illuminate\Validation\Rule::unique('mapel', 'kode')->ignore($mapel->id)],
            'nama' => 'required|string|max:100',
            'kategori' => 'required|in:diniyah,umum',
            'kkm' => 'required|integer|min:0|max:100',
            'bobot' => 'required|integer|min:1',
        ]);
        $mapel->update($request->only('kode', 'nama', 'kategori', 'kkm', 'bobot', 'keterangan', 'aktif'));
        return redirect()->route('admin.mapel.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Mapel $mapel)
    {
        $mapel->update(['aktif' => false]);
        return redirect()->route('admin.mapel.index')->with('success', 'Mata pelajaran berhasil dinonaktifkan.');
    }
}

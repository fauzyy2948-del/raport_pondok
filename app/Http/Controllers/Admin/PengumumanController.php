<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index(Request $request)
    {
        $pengumumen = Pengumuman::with('user')
            ->when($request->search, fn($q) => $q->where('judul', 'like', "%{$request->search}%"))
            ->latest()->paginate(15)->withQueryString();
        return view('admin.pengumuman.index', compact('pengumumen'));
    }

    public function create()
    {
        return view('admin.pengumuman.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:200',
            'isi' => 'required|string',
            'target' => 'required|in:semua,santri,ustadz,wali_santri',
            'prioritas' => 'required|in:rendah,normal,tinggi,urgent',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
        ]);

        $lampiran = null;
        if ($request->hasFile('lampiran')) {
            $lampiran = $request->file('lampiran')->store('pengumuman', 'public');
        }

        Pengumuman::create([
            'user_id' => auth()->id(),
            'judul' => $request->judul,
            'isi' => $request->isi,
            'target' => $request->target,
            'prioritas' => $request->prioritas,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'lampiran' => $lampiran,
            'aktif' => true,
        ]);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function edit(Pengumuman $pengumuman)
    {
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $request->validate([
            'judul' => 'required|string|max:200',
            'isi' => 'required|string',
            'target' => 'required|in:semua,santri,ustadz,wali_santri',
            'prioritas' => 'required|in:rendah,normal,tinggi,urgent',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
        ]);

        $lampiran = $pengumuman->lampiran;
        if ($request->hasFile('lampiran')) {
            if ($lampiran) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($lampiran);
            }
            $lampiran = $request->file('lampiran')->store('pengumuman', 'public');
        }

        $pengumuman->update([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'target' => $request->target,
            'prioritas' => $request->prioritas,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'lampiran' => $lampiran,
            'aktif' => $request->boolean('aktif'),
        ]);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        if ($pengumuman->lampiran) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($pengumuman->lampiran);
        }
        $pengumuman->delete();
        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}

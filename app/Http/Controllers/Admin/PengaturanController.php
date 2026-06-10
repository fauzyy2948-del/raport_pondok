<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanPondok;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $pengaturan = PengaturanPondok::first() ?? new PengaturanPondok();
        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_pondok' => 'required|string|max:200',
            'alamat' => 'required|string',
            'kepala_pondok' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $pondok = PengaturanPondok::first();
        $logoPath = $pondok?->logo;

        if ($request->hasFile('logo')) {
            if ($pondok && $pondok->logo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($pondok->logo);
            }
            $logoPath = $request->file('logo')->store('pondok', 'public');
        }

        $data = $request->only([
            'nama_pondok', 'singkatan', 'alamat', 'kecamatan', 'kabupaten',
            'provinsi', 'kode_pos', 'telepon', 'email', 'website',
            'kepala_pondok', 'nip_kepala', 'nss', 'npsn', 'visi', 'misi',
        ]) + ['logo' => $logoPath];

        PengaturanPondok::updateOrCreate(['id' => $pondok?->id ?? null], $data);

        return redirect()->route('admin.pengaturan.index')->with('success', 'Pengaturan pondok berhasil disimpan.');
    }
}

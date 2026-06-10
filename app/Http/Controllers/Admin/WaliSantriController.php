<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WaliSantri;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class WaliSantriController extends Controller
{
    public function index(Request $request)
    {
        $walis = WaliSantri::with(['user', 'santri'])
            ->when($request->search, fn($q) => $q->where('nama', 'like', "%{$request->search}%"))
            ->withCount('santri')
            ->latest()->paginate(15)->withQueryString();
        return view('admin.wali_santri.index', compact('walis'));
    }

    public function create()
    {
        return view('admin.wali_santri.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'hubungan' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'telepon' => 'required|string|max:20',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'wali_santri',
            ]);

            WaliSantri::create([
                'user_id' => $user->id,
                'nama' => $request->nama,
                'jenis_kelamin' => $request->jenis_kelamin ?? 'L',
                'nik' => $request->nik,
                'pekerjaan' => $request->pekerjaan,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
                'email' => $request->email,
                'hubungan' => $request->hubungan,
            ]);
        });

        return redirect()->route('admin.wali-santri.index')->with('success', 'Data wali santri berhasil ditambahkan.');
    }

    public function edit(WaliSantri $waliSantri)
    {
        return view('admin.wali_santri.edit', compact('waliSantri'));
    }

    public function update(Request $request, WaliSantri $waliSantri)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'hubungan' => 'required|string|max:20',
            'telepon' => 'required|string|max:20',
        ]);

        $waliSantri->user->update(['name' => $request->nama]);
        $waliSantri->update($request->only('nama', 'jenis_kelamin', 'nik', 'pekerjaan', 'alamat', 'telepon', 'hubungan'));
        return redirect()->route('admin.wali-santri.index')->with('success', 'Data wali santri berhasil diperbarui.');
    }

    public function destroy(WaliSantri $waliSantri)
    {
        $waliSantri->user->delete();
        return redirect()->route('admin.wali-santri.index')->with('success', 'Data wali santri berhasil dihapus.');
    }
}

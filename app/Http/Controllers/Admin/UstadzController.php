<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ustadz;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UstadzController extends Controller
{
    public function index(Request $request)
    {
        $query = Ustadz::with('user')
            ->when($request->search, fn($q) => $q->where(function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                  ->orWhere('nip', 'like', "%{$request->search}%");
            }))
            ->when($request->status !== null && $request->status !== '', fn($q) => $q->where('aktif', $request->status))
            ->latest();

        $ustadz = $query->paginate(15)->withQueryString();

        return view('admin.ustadz.index', compact('ustadz'));
    }

    public function create()
    {
        return view('admin.ustadz.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'status' => 'required|in:PNS,GTY,GTT,Honorer',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('ustadz', 'public');
            }

            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'ustadz',
                'foto' => $fotoPath,
            ]);

            Ustadz::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'nama' => $request->nama,
                'gelar_depan' => $request->gelar_depan,
                'gelar_belakang' => $request->gelar_belakang,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
                'email' => $request->email,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'jurusan' => $request->jurusan,
                'status' => $request->status,
                'tanggal_masuk' => $request->tanggal_masuk,
                'foto' => $fotoPath,
                'aktif' => true,
            ]);
        });

        return redirect()->route('admin.ustadz.index')
            ->with('success', 'Data ustadz/guru berhasil ditambahkan.');
    }

    public function show(Ustadz $ustadz)
    {
        $ustadz->load(['jadwal.kelas', 'jadwal.mapel']);
        return view('admin.ustadz.show', compact('ustadz'));
    }

    public function edit(Ustadz $ustadz)
    {
        return view('admin.ustadz.edit', compact('ustadz'));
    }

    public function update(Request $request, Ustadz $ustadz)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'status' => 'required|in:PNS,GTY,GTT,Honorer',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $fotoPath = $ustadz->foto;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('ustadz', 'public');
        }

        $ustadz->user->update([
            'name' => $request->nama,
            'aktif' => $request->boolean('aktif'),
            'foto' => $fotoPath,
        ]);

        $ustadz->update([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'gelar_depan' => $request->gelar_depan,
            'gelar_belakang' => $request->gelar_belakang,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'jurusan' => $request->jurusan,
            'status' => $request->status,
            'tanggal_masuk' => $request->tanggal_masuk,
            'foto' => $fotoPath,
            'aktif' => $request->boolean('aktif'),
        ]);

        return redirect()->route('admin.ustadz.index')
            ->with('success', 'Data ustadz/guru berhasil diperbarui.');
    }

    public function destroy(Ustadz $ustadz)
    {
        $ustadz->user->delete();
        return redirect()->route('admin.ustadz.index')
            ->with('success', 'Data ustadz/guru berhasil dihapus.');
    }
}

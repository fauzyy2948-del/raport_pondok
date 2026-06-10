<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\Kelas;
use App\Models\WaliSantri;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SantriController extends Controller
{
    public function index(Request $request)
    {
        $query = Santri::with(['kelas', 'waliSantri', 'user'])
            ->when($request->search, fn($q) => $q->where(function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                  ->orWhere('nisn', 'like', "%{$request->search}%");
            }))
            ->when($request->kelas_id, fn($q) => $q->where('kelas_id', $request->kelas_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest();

        $santri = $query->paginate(15)->withQueryString();
        $kelas = Kelas::orderBy('nama')->get();

        return view('admin.santri.index', compact('santri', 'kelas'));
    }

    public function create()
    {
        return view('admin.santri.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|unique:santri,nisn',
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_tingkat' => 'nullable|integer|min:1|max:6',
            'tanggal_masuk' => 'required|date',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama_wali' => 'required|string|max:100',
        ]);

        DB::transaction(function () use ($request) {
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('santri', 'public');
            }

            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'santri',
                'foto' => $fotoPath,
            ]);

            $kelasId = null;
            if ($request->kelas_tingkat) {
                $kelas = Kelas::firstOrCreate(
                    ['tingkat' => $request->kelas_tingkat],
                    ['nama' => 'Kelas ' . $request->kelas_tingkat, 'jenis' => 'umum', 'kapasitas' => 30]
                );
                $kelasId = $kelas->id;
            }

            $emailWali = strtolower(str_replace(' ', '', $request->nama_wali)) . rand(1000, 9999) . '@pondok.test';
            $userWali = User::create([
                'name' => $request->nama_wali,
                'email' => $emailWali,
                'password' => Hash::make('password123'),
                'role' => 'wali_santri',
            ]);
            $wali = WaliSantri::create([
                'user_id' => $userWali->id,
                'nama' => $request->nama_wali,
                'telepon' => $request->telepon_wali,
                'pekerjaan' => $request->pekerjaan_wali,
                'alamat' => $request->alamat_wali,
                'hubungan' => $request->hubungan_wali ?? 'Ayah',
            ]);

            Santri::create([
                'user_id' => $user->id,
                'nisn' => $request->nisn,
                'nama' => $request->nama,
                'jenis_kelamin' => $request->jenis_kelamin,
                'kelas_id' => $kelasId,
                'wali_santri_id' => $wali->id,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat_asal' => $request->alamat_asal,
                'telepon' => $request->telepon,
                'asal_sekolah' => $request->asal_sekolah,
                'tanggal_masuk' => $request->tanggal_masuk,
                'status' => $request->status ?? 'aktif',
                'foto' => $fotoPath,
                'catatan' => $request->catatan,
            ]);
        });

        return redirect()->route('admin.santri.index')
            ->with('success', 'Data santri berhasil ditambahkan.');
    }

    public function show(Santri $santri)
    {
        $santri->load(['kelas', 'waliSantri', 'user', 'nilai.mapel', 'absensi', 'catatanPembinaan.ustadz']);
        return view('admin.santri.show', compact('santri'));
    }

    public function edit(Santri $santri)
    {
        return view('admin.santri.edit', compact('santri'));
    }

    public function update(Request $request, Santri $santri)
    {
        $request->validate([
            'nisn' => ['required', Rule::unique('santri', 'nisn')->ignore($santri->id)],
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_tingkat' => 'nullable|integer|min:1|max:6',
            'tanggal_masuk' => 'required|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama_wali' => 'required|string|max:100',
        ]);

        DB::transaction(function () use ($request, $santri) {
            $fotoPath = $santri->foto;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('santri', 'public');
            }

            $santri->user->update([
                'name' => $request->nama,
                'foto' => $fotoPath,
            ]);

            $kelasId = null;
            if ($request->kelas_tingkat) {
                $kelas = Kelas::firstOrCreate(
                    ['tingkat' => $request->kelas_tingkat],
                    ['nama' => 'Kelas ' . $request->kelas_tingkat, 'jenis' => 'umum', 'kapasitas' => 30]
                );
                $kelasId = $kelas->id;
            }

            $wali = $santri->waliSantri;
            if ($wali) {
                $wali->update([
                    'nama' => $request->nama_wali,
                    'telepon' => $request->telepon_wali,
                    'pekerjaan' => $request->pekerjaan_wali,
                    'alamat' => $request->alamat_wali,
                    'hubungan' => $request->hubungan_wali ?? 'Ayah',
                ]);
                $wali->user->update(['name' => $request->nama_wali]);
                $waliId = $wali->id;
            } else {
                $emailWali = strtolower(str_replace(' ', '', $request->nama_wali)) . rand(1000, 9999) . '@pondok.test';
                $userWali = User::create([
                    'name' => $request->nama_wali,
                    'email' => $emailWali,
                    'password' => Hash::make('password123'),
                    'role' => 'wali_santri',
                ]);
                $newWali = WaliSantri::create([
                    'user_id' => $userWali->id,
                    'nama' => $request->nama_wali,
                    'telepon' => $request->telepon_wali,
                    'pekerjaan' => $request->pekerjaan_wali,
                    'alamat' => $request->alamat_wali,
                    'hubungan' => $request->hubungan_wali ?? 'Ayah',
                ]);
                $waliId = $newWali->id;
            }

            $santri->update([
                'nisn' => $request->nisn,
                'nama' => $request->nama,
                'jenis_kelamin' => $request->jenis_kelamin,
                'kelas_id' => $kelasId,
                'wali_santri_id' => $waliId,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat_asal' => $request->alamat_asal,
                'telepon' => $request->telepon,
                'asal_sekolah' => $request->asal_sekolah,
                'tanggal_masuk' => $request->tanggal_masuk,
                'status' => $request->status,
                'foto' => $fotoPath,
                'catatan' => $request->catatan,
            ]);
        });

        return redirect()->route('admin.santri.index')
            ->with('success', 'Data santri berhasil diperbarui.');
    }

    public function destroy(Santri $santri)
    {
        $santri->user->delete();
        return redirect()->route('admin.santri.index')
            ->with('success', 'Data santri berhasil dihapus.');
    }
}

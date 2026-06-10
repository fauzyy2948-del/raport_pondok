<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Santri;
use App\Models\Ustadz;
use App\Models\WaliSantri;
use App\Models\TahunAjaran;
use App\Models\PengaturanPondok;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $roles = ['admin', 'ustadz', 'santri', 'wali_santri'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Pengaturan Pondok
        PengaturanPondok::create([
            'nama_pondok' => 'Pondok Pesantren Subulussalam',
            'alamat' => 'Kabupaten Tangerang, Banten',
            'telepon' => '021-12345678',
            'email' => 'info@subulussalam.id',
            'website' => 'www.subulussalam.id',
            'kepala_pondok' => 'K.H. Ahmad Dahlan',
        ]);

        // Tahun Ajaran
        $ta = TahunAjaran::create([
            'nama' => '2024/2025',
            'semester' => 'ganjil',
            'tanggal_mulai' => '2024-07-01',
            'tanggal_selesai' => '2024-12-31',
            'aktif' => true,
        ]);

        // Admin User
        $admin = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@pondok.test',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
        $admin->assignRole('admin');

        // Wali Santri User
        $waliUser = User::factory()->create([
            'name' => 'Bapak Budi (Wali)',
            'email' => 'wali@pondok.test',
            'password' => Hash::make('password'),
            'role' => 'wali_santri'
        ]);
        $waliUser->assignRole('wali_santri');
        $wali = WaliSantri::create([
            'user_id' => $waliUser->id,
            'nama' => 'Bapak Budi (Wali)',
            'jenis_kelamin' => 'L',
            'nik' => '3671000000000001',
            'pekerjaan' => 'PNS',
            'telepon' => '081234567890',
            'alamat' => 'Tangerang',
            'hubungan' => 'Ayah',
        ]);

        // Kelas
        $kelas = Kelas::create([
            'nama' => '1A - Ibnu Sina',
            'tingkat' => '1',
            'kapasitas' => 30,
        ]);

        // Ustadz User
        $ustadzUser = User::factory()->create([
            'name' => 'Ustadz Ali',
            'email' => 'ustadz@pondok.test',
            'password' => Hash::make('password'),
            'role' => 'ustadz'
        ]);
        $ustadzUser->assignRole('ustadz');
        $ustadz = Ustadz::create([
            'user_id' => $ustadzUser->id,
            'nama' => 'Ustadz Ali',
            'jenis_kelamin' => 'L',
            'nip' => '198001012005011001',
            'status' => 'GTY',
            'telepon' => '081299998888',
        ]);

        // Santri User
        $santriUser = User::factory()->create([
            'name' => 'Ahmad Fulan',
            'email' => 'santri@pondok.test',
            'password' => Hash::make('password'),
            'role' => 'santri'
        ]);
        $santriUser->assignRole('santri');
        $santri = Santri::create([
            'user_id' => $santriUser->id,
            'wali_santri_id' => $wali->id,
            'kelas_id' => $kelas->id,
            'nisn' => '0091234567',
            'nama' => 'Ahmad Fulan',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2009-01-01',
            'jenis_kelamin' => 'L',
            'alamat_asal' => 'Jakarta Selatan',
            'tanggal_masuk' => '2024-07-01',
            'status' => 'aktif',
        ]);

        // Mapel
        Mapel::create(['nama' => 'Aqidah Akhlak', 'kode' => 'AQD', 'kategori' => 'diniyah']);
        Mapel::create(['nama' => 'Fiqih', 'kode' => 'FIQ', 'kategori' => 'diniyah']);
        Mapel::create(['nama' => 'Bahasa Arab', 'kode' => 'ARB', 'kategori' => 'diniyah']);
        Mapel::create(['nama' => 'Matematika', 'kode' => 'MTK', 'kategori' => 'umum']);
    }
}

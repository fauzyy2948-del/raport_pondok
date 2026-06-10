<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanPondok extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_pondok';

    protected $fillable = [
        'nama_pondok', 'singkatan', 'alamat', 'kecamatan', 'kabupaten',
        'provinsi', 'kode_pos', 'telepon', 'email', 'website',
        'logo', 'kepala_pondok', 'nip_kepala', 'nss', 'npsn',
        'visi', 'misi',
    ];

    public function getLogoUrlAttribute(): string
    {
        return $this->logo
            ? asset('storage/' . $this->logo)
            : asset('images/logo.png');
    }

    public static function pengaturan(): ?self
    {
        return static::first();
    }
}

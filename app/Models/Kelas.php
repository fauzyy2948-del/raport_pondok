<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama', 'tingkat', 'jenis', 'kapasitas', 'keterangan',
    ];

    public function santri()
    {
        return $this->hasMany(Santri::class);
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function getJumlahSantriAttribute(): int
    {
        return $this->santri()->where('status', 'aktif')->count();
    }

    public function getNamaKelasAttribute()
    {
        return $this->nama;
    }
}

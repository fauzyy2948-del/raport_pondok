<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $table = 'mapel';

    protected $fillable = [
        'kode', 'nama', 'kategori', 'kkm', 'bobot', 'keterangan', 'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }

    public function raportDetail()
    {
        return $this->hasMany(RaportDetail::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function getBadgeKategoriAttribute(): string
    {
        return match($this->kategori) {
            'diniyah' => '<span class="badge bg-success">Diniyah</span>',
            'umum' => '<span class="badge bg-primary">Umum</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }

    public function getNamaMapelAttribute()
    {
        return $this->nama;
    }
}

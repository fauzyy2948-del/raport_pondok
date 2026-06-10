<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'nama', 'semester', 'tanggal_mulai', 'tanggal_selesai', 'aktif',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
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

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function raport()
    {
        return $this->hasMany(Raport::class);
    }

    public function kalenderAkademik()
    {
        return $this->hasMany(KalenderAkademik::class);
    }

    public static function aktif()
    {
        return static::where('aktif', true)->first();
    }

    public function getLabelAttribute(): string
    {
        return $this->nama . ' - ' . ucfirst($this->semester);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    protected $table = 'santri';

    protected $fillable = [
        'user_id', 'kelas_id', 'wali_santri_id', 'nisn',
        'nama', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
        'alamat_asal', 'telepon', 'asal_sekolah', 'tanggal_masuk',
        'status', 'foto', 'catatan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function waliSantri()
    {
        return $this->belongsTo(WaliSantri::class);
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

    public function catatanPembinaan()
    {
        return $this->hasMany(CatatanPembinaan::class);
    }

    public function getFotoUrlAttribute(): string
    {
        return $this->foto
            ? asset('storage/' . $this->foto)
            : asset('images/default-santri.png');
    }

    public function getUsiaAttribute(): ?string
    {
        if (!$this->tanggal_lahir) return null;
        return $this->tanggal_lahir->age . ' tahun';
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}

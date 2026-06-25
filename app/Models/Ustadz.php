<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ustadz extends Model
{
    use HasFactory;

    protected $table = 'ustadz';

    protected $fillable = [
        'user_id', 'nip', 'nama', 'gelar_depan', 'gelar_belakang',
        'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat',
        'telepon', 'email', 'pendidikan_terakhir', 'jurusan', 'status',
        'tanggal_masuk', 'foto', 'aktif',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'aktif' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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

    public function catatanPembinaan()
    {
        return $this->hasMany(CatatanPembinaan::class);
    }

    public function getNamaLengkapAttribute(): string
    {
        $depan = $this->gelar_depan ? $this->gelar_depan . ' ' : '';
        $belakang = $this->gelar_belakang ? ', ' . $this->gelar_belakang : '';
        return $depan . $this->nama . $belakang;
    }

    public function getFotoUrlAttribute(): string
    {
        return $this->foto
            ? asset('storage/' . $this->foto)
            : asset('images/default-ustadz.png');
    }

    public function kelasWali()
    {
        return $this->hasOne(Kelas::class, 'ustadz_id');
    }

    public function isWaliKelas(): bool
    {
        return $this->kelasWali()->exists();
    }
}

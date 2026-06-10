<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'santri_id', 'jadwal_id', 'ustadz_id', 'tahun_ajaran_id',
        'tanggal', 'status', 'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function ustadz()
    {
        return $this->belongsTo(Ustadz::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function getBadgeStatusAttribute(): string
    {
        return match($this->status) {
            'hadir' => '<span class="badge bg-success">Hadir</span>',
            'sakit' => '<span class="badge bg-warning text-dark">Sakit</span>',
            'izin' => '<span class="badge bg-info text-dark">Izin</span>',
            'alfa' => '<span class="badge bg-danger">Alfa</span>',
            default => '<span class="badge bg-secondary">-</span>',
        };
    }
}

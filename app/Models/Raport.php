<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raport extends Model
{
    use HasFactory;

    protected $table = 'raport';

    protected $fillable = [
        'santri_id', 'tahun_ajaran_id', 'kelas_id',
        'rata_rata', 'peringkat', 'jumlah_siswa',
        'hadir', 'sakit', 'izin', 'alfa',
        'catatan_wali_kelas', 'predikat_akhir',
        'diterbitkan', 'diterbitkan_pada',
    ];

    protected $casts = [
        'rata_rata' => 'float',
        'diterbitkan' => 'boolean',
        'diterbitkan_pada' => 'datetime',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function detail()
    {
        return $this->hasMany(RaportDetail::class);
    }
}

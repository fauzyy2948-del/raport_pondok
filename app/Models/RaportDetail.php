<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaportDetail extends Model
{
    use HasFactory;

    protected $table = 'raport_detail';

    protected $fillable = [
        'raport_id', 'mapel_id',
        'nilai_harian', 'nilai_tugas', 'nilai_uts', 'nilai_uas',
        'nilai_hafalan', 'nilai_adab', 'nilai_akhir', 'predikat', 'catatan',
    ];

    protected $casts = [
        'nilai_harian' => 'float',
        'nilai_tugas' => 'float',
        'nilai_uts' => 'float',
        'nilai_uas' => 'float',
        'nilai_hafalan' => 'float',
        'nilai_adab' => 'float',
        'nilai_akhir' => 'float',
    ];

    public function raport()
    {
        return $this->belongsTo(Raport::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }
}

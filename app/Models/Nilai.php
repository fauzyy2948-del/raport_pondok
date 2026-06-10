<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';

    protected $fillable = [
        'santri_id', 'mapel_id', 'ustadz_id', 'tahun_ajaran_id',
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

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function ustadz()
    {
        return $this->belongsTo(Ustadz::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function hitungNilaiAkhir(): float
    {
        $nh = $this->nilai_harian ?? 0;
        $nt = $this->nilai_tugas ?? 0;
        $nuts = $this->nilai_uts ?? 0;
        $nuas = $this->nilai_uas ?? 0;
        $nhf = $this->nilai_hafalan ?? 0;
        $nad = $this->nilai_adab ?? 0;

        // Bobot: harian 20%, tugas 10%, UTS 25%, UAS 30%, hafalan 10%, adab 5%
        return round(($nh * 0.20) + ($nt * 0.10) + ($nuts * 0.25) + ($nuas * 0.30) + ($nhf * 0.10) + ($nad * 0.05), 2);
    }

    public static function hitungPredikat(float $nilai): string
    {
        return match(true) {
            $nilai >= 90 => 'A',
            $nilai >= 80 => 'B+',
            $nilai >= 75 => 'B',
            $nilai >= 70 => 'C+',
            $nilai >= 60 => 'C',
            $nilai >= 50 => 'D',
            default => 'E',
        };
    }
}

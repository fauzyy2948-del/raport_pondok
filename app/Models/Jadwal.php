<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = [
        'tahun_ajaran_id', 'kelas_id', 'mapel_id', 'ustadz_id',
        'hari', 'jam_mulai', 'jam_selesai', 'ruangan',
    ];

    protected $casts = [
        'jam_mulai' => 'string',
        'jam_selesai' => 'string',
    ];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function ustadz()
    {
        return $this->belongsTo(Ustadz::class);
    }

    public static function urutanHari(): array
    {
        return ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Ahad'];
    }

    public static function listJam(): array
    {
        return [
            '07:15-07:55' => 'Jam ke 1 : 07:15 - 07:55',
            '07:55-08:35' => 'Jam ke 2 : 07:55 - 08:35',
            '09:05-09:45' => 'Jam ke 3 : 09:05 - 09:45',
            '09:45-10:25' => 'Jam ke 4 : 09:45 - 10:25',
            '10:40-11:20' => 'Jam ke 5 : 10:40 - 11:20',
            '11:20-12:00' => 'Jam ke 6 : 11:20 - 12:00',
            '13:15-14:00' => 'Jam ke 7 : 13:15 - 14:00',
        ];
    }

    public static function listJamLengkap(): array
    {
        return [
            '07:15-07:55' => ['type' => 'belajar', 'label' => 'Jam ke 1', 'time' => '07:15 - 07:55'],
            '07:55-08:35' => ['type' => 'belajar', 'label' => 'Jam ke 2', 'time' => '07:55 - 08:35'],
            '08:35-09:05' => ['type' => 'istirahat', 'label' => 'Istirahat', 'time' => '08:35 - 09:05'],
            '09:05-09:45' => ['type' => 'belajar', 'label' => 'Jam ke 3', 'time' => '09:05 - 09:45'],
            '09:45-10:25' => ['type' => 'belajar', 'label' => 'Jam ke 4', 'time' => '09:45 - 10:25'],
            '10:25-10:40' => ['type' => 'istirahat', 'label' => 'Istirahat', 'time' => '10:25 - 10:40'],
            '10:40-11:20' => ['type' => 'belajar', 'label' => 'Jam ke 5', 'time' => '10:40 - 11:20'],
            '11:20-12:00' => ['type' => 'belajar', 'label' => 'Jam ke 6', 'time' => '11:20 - 12:00'],
            '12:00-13:15' => ['type' => 'istirahat', 'label' => 'Ishoma', 'time' => '12:00 - 13:15'],
            '13:15-14:00' => ['type' => 'belajar', 'label' => 'Jam ke 7', 'time' => '13:15 - 14:00'],
        ];
    }
}

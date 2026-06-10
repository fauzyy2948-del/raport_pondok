<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanPembinaan extends Model
{
    use HasFactory;

    protected $table = 'catatan_pembinaan';

    protected $fillable = [
        'santri_id', 'ustadz_id', 'tahun_ajaran_id',
        'jenis', 'judul', 'isi', 'tanggal', 'tindak_lanjut',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function ustadz()
    {
        return $this->belongsTo(Ustadz::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}

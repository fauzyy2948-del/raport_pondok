<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';

    protected $fillable = [
        'user_id', 'judul', 'isi', 'target', 'prioritas',
        'tanggal_mulai', 'tanggal_selesai', 'lampiran', 'aktif',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'aktif' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getBadgePrioritasAttribute(): string
    {
        return match($this->prioritas) {
            'urgent' => '<span class="badge bg-danger">Urgent</span>',
            'tinggi' => '<span class="badge bg-warning text-dark">Penting</span>',
            'normal' => '<span class="badge bg-primary">Normal</span>',
            'rendah' => '<span class="badge bg-secondary">Rendah</span>',
            default => '',
        };
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true)->where('tanggal_mulai', '<=', now()->toDateString());
    }

    public function scopeUntukRole($query, string $role)
    {
        return $query->where(function ($q) use ($role) {
            $q->where('target', 'semua')->orWhere('target', $role);
        });
    }
}

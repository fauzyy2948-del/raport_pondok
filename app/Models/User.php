<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'foto', 'aktif',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'aktif' => 'boolean',
        ];
    }

    public function santri()
    {
        return $this->hasOne(Santri::class);
    }

    public function ustadz()
    {
        return $this->hasOne(Ustadz::class);
    }

    public function waliSantri()
    {
        return $this->hasOne(WaliSantri::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUstadz(): bool
    {
        return $this->role === 'ustadz';
    }

    public function isSantri(): bool
    {
        return $this->role === 'santri';
    }

    public function isWaliSantri(): bool
    {
        return $this->role === 'wali_santri';
    }

    public function getFotoUrlAttribute(): string
    {
        return $this->foto
            ? asset('storage/' . $this->foto)
            : asset('images/default-avatar.png');
    }
}

<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pegawai()
    {
        return $this->hasOne(Pegawai::class);
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function unreadNotifikasi()
    {
        return $this->hasMany(Notifikasi::class)->where('dibaca', false);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCamat(): bool
    {
        return $this->role === 'camat';
    }

    public function isUmpeg(): bool
    {
        return $this->role === 'umpeg';
    }

    public function canDisposisi(): bool
    {
        return in_array($this->role, ['admin', 'camat', 'umpeg']);
    }
}

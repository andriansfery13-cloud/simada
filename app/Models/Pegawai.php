<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $fillable = [
        'user_id',
        'nip',
        'nama',
        'jabatan',
        'pangkat_golongan',
        'unit_kerja',
        'no_hp',
        'foto',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kegiatan()
    {
        return $this->belongsToMany(Kegiatan::class, 'kegiatan_peserta')
            ->withPivot('status_kehadiran', 'catatan')
            ->withTimestamps();
    }

    public function disposisiDiterima()
    {
        return $this->hasMany(Disposisi::class, 'kepada_pegawai_id');
    }

    public function disposisiDikirim()
    {
        return $this->hasMany(Disposisi::class, 'dari_pegawai_id');
    }

    public function getInisialsAttribute(): string
    {
        $parts = explode(' ', $this->nama);
        $initials = '';
        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }
        return $initials;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    use HasFactory;

    protected $table = 'disposisi';

    protected $fillable = [
        'kegiatan_id',
        'dari_pegawai_id',
        'kepada_pegawai_id',
        'catatan',
        'status',
        'dibaca_pada',
    ];

    protected $casts = [
        'dibaca_pada' => 'datetime',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function dariPegawai()
    {
        return $this->belongsTo(Pegawai::class, 'dari_pegawai_id');
    }

    public function kepadaPegawai()
    {
        return $this->belongsTo(Pegawai::class, 'kepada_pegawai_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'dibaca' => 'info',
            'diproses' => 'primary',
            'selesai' => 'success',
            'ditolak' => 'danger',
            default => 'secondary',
        };
    }
}

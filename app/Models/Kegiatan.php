<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan';

    protected $fillable = [
        'created_by',
        'kategori_id',
        'judul',
        'deskripsi',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'tempat',
        'status',
        'prioritas',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriKegiatan::class, 'kategori_id');
    }

    public function peserta()
    {
        return $this->belongsToMany(Pegawai::class, 'kegiatan_peserta')
            ->withPivot('status_kehadiran', 'catatan')
            ->withTimestamps();
    }

    public function disposisi()
    {
        return $this->hasMany(Disposisi::class);
    }

    public function dokumentasi()
    {
        return $this->hasMany(Dokumentasi::class);
    }

    public function getPrioritasBadgeAttribute(): string
    {
        return match ($this->prioritas) {
            'tinggi' => 'danger',
            'sedang' => 'warning',
            'rendah' => 'info',
            default => 'secondary',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'aktif' => 'success',
            'draft' => 'secondary',
            'selesai' => 'primary',
            'batal' => 'danger',
            default => 'secondary',
        };
    }
}

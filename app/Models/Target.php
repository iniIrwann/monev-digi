<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use HasFactory;

    protected $fillable = [
        'bidang_id',
        'kegiatan_id',
        'sub_kegiatan_id',
        'user_id',
        'uraian_keluaran',
        'volume_keluaran',
        'cara_pengadaan',
        'anggaran_target',
        // 'tenaga_kerja',
        'durasi',
        // 'upah',
        'KPM',
        // 'BLT',
        'tahun',
        'sasaran',
    ];

    public function subkegiatan()
    {
        return $this->belongsTo(SubKegiatan::class);
    }
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }
    public function capaian()
    {
        return $this->hasOne(Capaian::class, 'target_id');
    }
    public function realisasi()
    {
        return $this->hasOne(Realisasi::class);
    }
    public function scopeUserOnly($query)
    {
        return $query->where('user_id', auth()->id());
    }
}

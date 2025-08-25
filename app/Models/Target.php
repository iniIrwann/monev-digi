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
        return $this->belongsTo(SubKegiatan::class, 'sub_kegiatan_id', 'id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id', 'id');
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id', 'id');
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
    public function realisasis()
    {
        return $this->hasMany(Realisasi::class, 'target_id'); 
        // pastikan kolom foreign key di tabel realisasis = target_id
    }
}

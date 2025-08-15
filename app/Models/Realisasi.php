<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Realisasi extends Model
{
    protected $fillable = [
        'target_id',
        'bidang_id',
        'kegiatan_id',
        'sub_kegiatan_id',
        'verifikasi_id',
        'user_id',
        'tahap',
        'uraian_keluaran',
        'volume_keluaran',
        'cara_pengadaan',
        'realisasi_keuangan',
        'tenaga_kerja',
        'durasi',
        'upah',
        'KPM',
        'BLT',
        'tahun',
        'keterangan',
    ];
    public function verifikasi()
    {
        return $this->belongsTo(Verifikasi::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
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
    public function scopeUserOnly($query)
    {
        return $query->where('user_id', auth()->id());
    }
    public function target()
    {
        return $this->belongsTo(Target::class, );
    }
}

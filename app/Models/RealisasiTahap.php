<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealisasiTahap extends Model
{
    //
    protected $fillable = [
        'realisasi_id',
        'tahap',
        'volume_keluaran',
        'uraian_keluaran',
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
    public function realisasi()
    {
        return $this->belongsTo(Realisasi::class);
    }
}

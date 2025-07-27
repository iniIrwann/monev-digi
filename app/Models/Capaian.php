<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Capaian extends Model
{
    protected $fillable = [
        'target_id',
        'realisasi_id',
        'user_id',
        'persen_capaian_keluaran',
        'persen_capaian_keuangan',
        'sisa',
    ];
    public function realisasi()
    {
        return $this->belongsTo(Realisasi::class, 'realisasi_id');
    }

    public function target()
    {
        return $this->belongsTo(Target::class, 'target_id');
    }
    public function scopeUserOnly($query)
    {
        return $query->where('user_id', auth()->id());
    }

}

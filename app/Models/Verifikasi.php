<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verifikasi extends Model
{
    protected $fillable = [
        'catatan',
        'tindak_lanjut',
        'rekomendasi',
    ];

}

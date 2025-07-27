<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    use HasFactory;

    protected $fillable = ['kode_rekening', 'user_id', 'nama_bidang', 'keterangan'];

    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class);
    }
    public function targets()
    {
        return $this->hasMany(Target::class);
    }
    public function realisasis()
    {
        return $this->hasMany(Realisasi::class);
    }
    public function scopeUserOnly($query)
    {
        return $query->where('user_id', auth()->id());
    }
}

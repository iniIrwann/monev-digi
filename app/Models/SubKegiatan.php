<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKegiatan extends Model
{
    use HasFactory;

    protected $fillable = ['kegiatan_id', 'user_id', 'kode_rekening', 'nama_subkegiatan'];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
    public function target()
    {
        return $this->hasMany(Target::class);
    }
    public function targets()
    {
        return $this->target();
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


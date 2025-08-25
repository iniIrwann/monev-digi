<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $fillable = ['bidang_id', 'user_id', 'kode_rekening', 'nama_kegiatan', 'kategori'];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    public function targets()
    {
        return $this->hasMany(Target::class);
    }
    public function realisasis()
    {
        return $this->hasMany(Realisasi::class);
    }

    public function subkegiatan()
    {
        return $this->hasMany(SubKegiatan::class);
    }
    // alias biar bisa dipanggil jamak
    public function subkegiatans()
    {
        return $this->subkegiatan();
    }
    public function scopeUserOnly($query)
    {
        return $query->where('user_id', auth()->id());
    }
}

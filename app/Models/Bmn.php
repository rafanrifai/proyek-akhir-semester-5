<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bmn extends Model
{
    use HasFactory;

    protected $table = 'bmns';
    protected $guarded = ['id'];
    protected $casts = [
        'last_sensus_at' => 'datetime',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriBmn::class, 'kategori_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function fotos()
    {
        return $this->hasMany(BmnFoto::class)->latest('id');
    }

    public function riwayatKondisis()
    {
        return $this->hasMany(RiwayatKondisi::class, 'bmn_id')->latest('id');
    }
}

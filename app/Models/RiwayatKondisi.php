<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatKondisi extends Model
{
    use HasFactory;

    protected $table = 'riwayat_kondisis';
    protected $guarded = ['id'];

    public function bmn()
    {
        return $this->belongsTo(Bmn::class, 'bmn_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function inventarisasi()
    {
        return $this->belongsTo(Inventarisasi::class, 'inventarisasi_id');
    }
}

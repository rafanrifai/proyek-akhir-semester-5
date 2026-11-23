<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmnFoto extends Model
{
    use HasFactory;

    protected $table = 'bmn_fotos';
    protected $guarded = ['id'];

    public function bmn()
    {
        return $this->belongsTo(Bmn::class);
    }
}

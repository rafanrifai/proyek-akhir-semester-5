<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KlasifikasiBmn extends Model
{
    use HasFactory;

    protected $table = 'klasifikasi_bmns';
    protected $guarded = ['id'];

    protected $casts = [
        'rule_path' => 'array',
        'confidence' => 'float',
        'usia_barang' => 'integer',
    ];

    public function bmn()
    {
        return $this->belongsTo(Bmn::class, 'bmn_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}


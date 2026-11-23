<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportArchive extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'unit_name',
        'unit_ids',
        'report_date',
        'timezone',
        'html',
        'created_by',
    ];

    protected $casts = [
        'unit_ids' => 'array',
        'report_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

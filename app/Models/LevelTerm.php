<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelTerm extends Model
{
    protected $fillable = [
        'level_id',
        'step',
        'to_level',
        'amount',
        'coin',
        'count',
        'status',
    ];

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function tolevel()
    {
        return $this->belongsTo(Level::class, 'to_level');
    }
}

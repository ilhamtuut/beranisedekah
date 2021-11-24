<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = [
        'name',
        'level',
        'amount',
        'coin',
        'count',
        'status',
    ];

    public function term()
    {
        return $this->hasMany(LevelTerm::class, 'level_id');
    }
}

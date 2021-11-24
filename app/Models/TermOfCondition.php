<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermOfCondition extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'status'
    ];

    public function account()
    {
        return $this->hasMany(PaymentAccount::class, 'payment_method_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'name',
        'username',
        'email',
        'phone_number',
        'address',
        'account_bank_name',
        'account_number',
        'account_name',
        'picture',
        'status',
        'is_priority',
        'session_id',
        'password',
        'trx_password',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'trx_password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function childs()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(User::class, 'parent_id')->with('children');
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function balance()
    {
        return $this->hasMany(Balance::class, 'user_id');
    }

    public function hasRank()
    {
        return $this->hasOne(UserLevel::class, 'user_id');
    }

    public function donation()
    {
        return $this->hasMany(Donation::class, 'user_id');
    }

    public function receive_donation()
    {
        return $this->hasMany(Donation::class, 'receive_id')->where('status',1);
    }

    public function my_donation()
    {
        return Donation::where(function($q) {
            $q->where('user_id',$this->id)
                ->orWhere('receive_id',$this->id);
        });
    }

    public function buy()
    {
        return $this->hasMany(Buy::class, 'user_id');
    }

    public function sell()
    {
        return Sell::where(function($q) {
            $q->where('seller_id',$this->id)
                ->orWhere('buyer_id',$this->id);
        });
    }

    public function total_in()
    {
        return $this->hasMany(Donation::class, 'receive_id')->where('status',2)->sum('amount');
    }

    public function total_out()
    {
        return $this->hasMany(Donation::class, 'user_id')->where('status',2)->sum('amount');
    }

    public function notification()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

}

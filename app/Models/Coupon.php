<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount',
        'expires_at',
        'min_cart_value',
        'usage_limit',
        'times_used'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    //Metodo para verificar se o cumpom é valido
    public function isValid()
    {
        return (!$this->expires_at || $this->expires_at->isFuture()) &&
            (is_null($this->usage_limit) || $this->times_used < $this->usage_limit);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_user', 'coupon_id', 'user_id')
            ->withPivot('discount')
            ->withTimestamps();
    }
}

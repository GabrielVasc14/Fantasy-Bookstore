<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardRedemption extends Model
{
    protected $table = 'rewards_redemptions';

    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'reward_id',
        'redeemed_at'
    ];

    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

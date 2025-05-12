<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    protected $fillable = [
        'title',
        'description',
        'target',
        'period',
        'type',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('progress', 'completed')->withTimestamps();
    }

    public function getProgressAttribute()
    {
        return $this->users()->where('user_id', Auth::id())->first()->pivot->progress ?? 0;
    }

    public function getCompletedAttribute()
    {
        return $this->users()->where('user_id', Auth::id())->first()->pivot->completed ?? false;
    }
}

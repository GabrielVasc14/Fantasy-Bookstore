<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'bio',
        'favorite_books',
        'avatar',
        'banner'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_user', 'user_id', 'coupon_id')->withPivot('discount')->withTimestamps();
    }

    public function likedBooks()
    {
        return $this->belongsToMany(Books::class, 'books_user_likes', 'user_id', 'book_id')->withTimestamps();
    }

    public function redemptions()
    {
        return $this->hasMany(RewardRedemption::class);
    }

    public function challenges()
    {
        return $this->belongsToMany(Challenge::class)->withPivot('progress', 'completed')->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}

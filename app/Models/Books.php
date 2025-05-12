<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'detail',
        'image',
        'price',
        'author',
        'audio_path',
        'ebook_path',
        'price_ebook',
        'price_audio',
        'stock',
        'condition',
        'is_special_edition',
    ];

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'books_user_likes', 'book_id', 'user_id')->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'book_id');
    }

    public function averageRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function orders()
    {
        return $this->hasMany(OrderItem::class, 'book_id');
    }
}

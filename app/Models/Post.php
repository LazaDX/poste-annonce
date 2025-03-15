<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'title_price',
        'type',
        'surface',
        'city',
        'phone',
        'whatsapp',
        'payment_method',
        'moteur',
        'nombre_etages',
        'nombre_chambres',
        'nombre_pieces',
        'nombre_couchages',
        'commodites',
        'location',
        'condition',
        'type_culture',
        'equipements',
        'type_exploitation',
        'description',
        'user_id',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'subcategory_id',
            'outlet',
            'discount',
            'reduced_price'
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function novelties()
    {
        return $this->hasOne(Novelty::class);
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class);
    }

}

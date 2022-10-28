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
        'category_id'
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function novelties()
    {
        return $this->hasOne(Novelty::class);
    }
}

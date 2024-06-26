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
    public function outlet()
    {
        return $this->hasOne(Outlet::class);
    }

    public function novelty()
    {
        return $this->hasOne(Novelty::class);
    }

    public function novelties()
    {
        return $this->hasOne(Novelty::class);
    }

    public function outlets()
    {
        return $this->hasOne(Outlet::class);
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class);
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }


}

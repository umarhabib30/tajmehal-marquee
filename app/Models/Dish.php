<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;
    protected $fillable = [


        'name',
        'price_per_head'
    ];
    public function packages()
    {
        return $this->belongsToMany(DishPackage::class, 'dish_package_dish');
    }
}

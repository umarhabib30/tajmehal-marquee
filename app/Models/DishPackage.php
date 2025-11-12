<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishPackage extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'dish_package_dish', 'dish_package_id', 'dish_id');
    }

    public function dishLinks()
    {
        return $this->hasMany(DishPackageDish::class, 'dish_package_id');
    }
}

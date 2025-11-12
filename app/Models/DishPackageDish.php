<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishPackageDish extends Model
{
    use HasFactory;

    protected $table = 'dish_package_dish';

    protected $fillable = [
        'dish_package_id',
        'dish_id',
    ];

    public function package()
    {
        return $this->belongsTo(DishPackage::class, 'dish_package_id');
    }

    public function dish()
    {
        return $this->belongsTo(Dish::class, 'dish_id');
    }
}

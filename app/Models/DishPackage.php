<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishPackage extends Model
{
    use HasFactory;
    protected $fillable = ['one_dish', 'two_dish'];

    protected $casts = [
        'one_dish' => 'array',
        'two_dish' => 'array',
    ];
}

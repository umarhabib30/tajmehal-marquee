<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category',
        'quantity',
        'price',
        'purchase_date',
        'expiry_date',
        'condition',
        'warranty_period',
        'location',
        'supplier_name',
        'status',
    ];
}

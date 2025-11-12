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
        'quantity_type',
        'quantity',
        
    ];

    public function stocks()
    {
        return $this->hasMany(InventoryStock::class, 'inventory_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'event_type',
        'hall_name',
        'booking_date',
        'time_slot',
        'start_time',
        'end_time',
        'dish_package_id',
        'decoration',
        'decoration_amount',
        'guests_count',
        'price_per_head',
        'tax',
        'total_amount',
        'remaining_amount',
        'customer_signature',
        'manager_signature',
        'notes'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'total_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'price_per_head' => 'decimal:2',
        'tax' => 'decimal:2',
        'decoration_amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function dishPackage()
    {
        return $this->belongsTo(DishPackage::class);
    }
}

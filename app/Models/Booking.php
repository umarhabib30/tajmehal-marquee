<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'customer_phone',
        'customer_idcard',
        'customer_address',
        'event_type',
        'hall_name',
        'event_date',
        'time_slot',
        'start_time',
        'end_time',
        'package_id',  // matches <select name="package_id">
        'dishes',  // stores JSON of selected dish IDs
        'decorations',  // stores array or JSON of decorations
        'decore_price',
        'guests_count',
        'per_head_price',
        'tax_amount',
        'advance_payment',
        'total_amount',
        'remaining_amount',
        'payment_method',
        'customer_signature',
        'manager_signature',
        'notes',
        'booking_date',
        'extra_guests',
        'extra_guest_per_head_price',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function dishPackage()
    {
        return $this->belongsTo(DishPackage::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}

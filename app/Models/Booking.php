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
    'guests_count',
    'booking_date',
    'start_date',
    'end_date',
    'booking_time',
    'time_slot',
    'hall_name',
    'decoration_type',
    'menu_package',
    'total_amount',
    'discount_percent',
    'advance_payment',
    'remaining_amount',
    'payment_status',
    'status',
    'special_request',
        'customer_signature', 
];

   

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
   
}

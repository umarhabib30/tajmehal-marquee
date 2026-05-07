<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'Active';

    public const STATUS_DONE = 'Done';

    public const STATUS_CANCELLED = 'Cancelled';

    /** @return list<string> */
    public static function allowedStatuses(): array
    {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_DONE,
            self::STATUS_CANCELLED,
        ];
    }

    public static function calendarColor(?string $status): string
    {
        $raw = trim((string) $status);

        return match ($raw) {
            self::STATUS_ACTIVE => '#6c757d',
            self::STATUS_DONE => '#28a745',
            self::STATUS_CANCELLED => '#dc3545',
            default => match (strtolower($raw)) {
                'completed' => '#28a745',
                'cancelled', 'canceled' => '#dc3545',
                'active' => '#6c757d',
                default => '#6c757d',
            },
        };
    }

    public function statusBadgeClass(): string
    {
        $raw = trim((string) $this->status);

        return match ($raw) {
            self::STATUS_ACTIVE => 'bg-secondary text-white',
            self::STATUS_DONE => 'bg-success',
            self::STATUS_CANCELLED => 'bg-danger',
            default => match (strtolower($raw)) {
                'completed' => 'bg-success',
                'cancelled', 'canceled' => 'bg-danger',
                'active' => 'bg-secondary text-white',
                default => 'bg-secondary text-white',
            },
        };
    }

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
        'status',

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

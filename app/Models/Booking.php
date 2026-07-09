<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'Pending';

    public const STATUS_ACTIVE = 'Active';

    public const STATUS_DONE = 'Done';

    public const STATUS_CANCELLED = 'Cancelled';

    /** @return list<string> */
    public static function allowedStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_ACTIVE,
            self::STATUS_DONE,
            self::STATUS_CANCELLED,
        ];
    }

    public static function calendarColor(?string $status): string
    {
        $raw = trim((string) $status);

        return match ($raw) {
            self::STATUS_PENDING => '#ffc107',
            self::STATUS_ACTIVE => '#6c757d',
            self::STATUS_DONE => '#28a745',
            self::STATUS_CANCELLED => '#dc3545',
            default => match (strtolower($raw)) {
                'pending' => '#ffc107',
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
            self::STATUS_PENDING => 'badge-warning',
            self::STATUS_ACTIVE => 'badge-secondary',
            self::STATUS_DONE => 'badge-success',
            self::STATUS_CANCELLED => 'badge-danger',
            default => match (strtolower($raw)) {
                'pending' => 'badge-warning',
                'completed' => 'badge-success',
                'cancelled', 'canceled' => 'badge-danger',
                'active' => 'badge-secondary',
                default => 'badge-secondary',
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
        'discount',
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

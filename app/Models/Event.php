<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'color',
        'start',
        'end',
        'status',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    // Automatically set event status
    protected static function booted()
    {
        static::saving(function ($event) {
            if (Carbon::parse($event->end)->isPast()) {
                $event->status = 'passed';
            } else {
                $event->status = 'upcoming';
            }
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
    use HasFactory;

    protected $table = 'attendences';

    protected $fillable = [
        'staff_id',
        'date',
        'entry_time',
        'exit_time',
        'status',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}

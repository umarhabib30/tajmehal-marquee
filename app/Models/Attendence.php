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
        'status', // removed entry_time and exit_time
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;
    protected $fillable = [
        'staff_id',
        'month',
        'year',
        'basic',
        'absent_days',
        'deduction_per_absent',
        'net_salary'
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}

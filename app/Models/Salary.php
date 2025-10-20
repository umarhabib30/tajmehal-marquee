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
        'overtime_hours',
        'absent_days',
        'leave_days',
        'overtime_rate',
        'deduction_per_absent',
        'deduction_per_leave',
        'net_salary'
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}

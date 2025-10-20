<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'role',
        'email',
        'phone',
        'address',
        'dob',
        'gender',
        'salary',
        'experience',
        'joining_date',
        'status'
    ];
    public function attendances()
    {
        return $this->hasMany(Attendence::class);
    }

    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }
}

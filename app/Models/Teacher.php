<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'employee_id',
        'street_address',
        'barangay',
        'municipality',
        'province',
        'phone',
        'birthdate',
        'gender',
        'date_joined',
        'status'
    ];

    protected $casts = [
        'birthdate' => 'date',
        'date_joined' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'teacher_id', 'user_id');
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->street_address,
            $this->barangay,
            $this->municipality,
            $this->province
        ]);

        return implode(', ', $parts);
    }
} 
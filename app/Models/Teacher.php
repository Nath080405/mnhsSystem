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
        'department',
        'position',
        'address',
        'phone',
        'birthdate',
        'gender',
        'date_joined',
        'qualification',
        'specialization',
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
} 
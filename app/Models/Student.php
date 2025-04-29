<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade',
        'section',
        'student_id',
        'address',
        'phone',
        'birthdate',
        'gender',
        'guardian_name',
        'guardian_phone',
        'guardian_email',
        'status'
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

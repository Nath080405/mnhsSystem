<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutomaticEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'grade_level'
    ];

    /**
     * Get the student that owns the enrollment.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'user_id');
    }

    /**
     * Get the subject that owns the enrollment.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
} 
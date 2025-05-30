<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'teacher_id',
        'status',
        'grade_level',
        'parent_id',
        'section_id',
    ];

    /**
     * Get the teacher that owns the subject.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the schedules for the subject.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**

     * Get the parent subject label.
     */
    public function parent()
    {
        return $this->belongsTo(Subject::class, 'parent_id');
    }

    /**
     * Get the child subjects.
     */
    public function children()
    {
        return $this->hasMany(Subject::class, 'parent_id');
    }


    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the grades for the subject.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function automaticEnrollments()
    {
        return $this->hasMany(AutomaticEnrollment::class);
    }
} 
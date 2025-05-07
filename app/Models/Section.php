<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'grade_level',
        'description',
        'status',
        'adviser_id',
    ];

    /**
     * Get the students in this section.
     */
    public function students()
    {
        return $this->hasMany(User::class, 'section_id');
    }

    /**
     * Get the teacher adviser of this section.
     */
    public function adviser()
    {
        return $this->belongsTo(User::class, 'adviser_id');
    }
} 
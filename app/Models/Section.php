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
    ];

    /**
     * Get the students in this section.
     */
    public function students()
    {
        return $this->hasMany(User::class, 'section_id');
    }
} 
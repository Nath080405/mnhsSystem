<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'name',
        'grade_level',
        'description',
        'adviser_id',
    ];

    /**
     * Get the students in this section.
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'section', 'name');
    }

    /**
     * Get the teacher adviser of this section.
     */
    public function adviser()
    {
        return $this->belongsTo(User::class, 'adviser_id');
    }

    /**
     * Generate a unique section ID for a grade level.
     */
    public static function generateSectionId($gradeLevel)
    {
        // Extract just the number from "Grade X"
        $gradeNumber = str_replace('Grade ', '', $gradeLevel);
        
        // Get the last section ID for this grade level
        $lastSection = self::where('grade_level', $gradeLevel)
            ->orderBy('section_id', 'desc')
            ->first();
        
        if ($lastSection) {
            // Extract the sequence number and increment
            $lastSequence = (int) substr($lastSection->section_id, -3);
            $newSequence = $lastSequence + 1;
        } else {
            // Start from 1 for new grade level
            $newSequence = 1;
        }
        
        // Format: G7-001, G12-001, etc.
        return sprintf('G%s-%03d', $gradeNumber, $newSequence);
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($section) {
            if (empty($section->section_id)) {
                $section->section_id = self::generateSectionId($section->grade_level);
            }
        });
    }
} 
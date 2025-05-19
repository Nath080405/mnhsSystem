<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'day',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Mutators to ensure proper time format
    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = $value;
    }

    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = $value;
    }

    // Accessors to format time for display
    public function getStartTimeAttribute($value)
    {
        return $value ? date('H:i', strtotime($value)) : null;
    }

    public function getEndTimeAttribute($value)
    {
        return $value ? date('H:i', strtotime($value)) : null;
    }
}

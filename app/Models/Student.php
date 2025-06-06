<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'student_id',
        'lrn',
        'street_address',
        'barangay',
        'municipality',
        'province',
        'phone',
        'birthdate',
        'gender',
        'grade_level',
        'section',
        'status'
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section', 'section_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function automaticEnrollments()
    {
        return $this->hasMany(AutomaticEnrollment::class, 'student_id', 'user_id');
    }

    public function getFullNameAttribute()
    {
        return $this->user->full_name;
    }

    public function getFormalNameAttribute()
    {
        return $this->user->formal_name;
    }

    public function getFullAddressAttribute()
    {
        $address = [];
        if ($this->street_address) $address[] = $this->street_address;
        if ($this->barangay) $address[] = $this->barangay;
        if ($this->municipality) $address[] = $this->municipality;
        if ($this->province) $address[] = $this->province;
        return implode(', ', $address);
    }
    
}

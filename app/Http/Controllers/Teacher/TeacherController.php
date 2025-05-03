<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Show the teacher dashboard.
     */
    public function dashboard()
    {
        return view('teachers.dashboard'); // Make sure this Blade file exists
    }

    public function createStudent()
{
    return view('teachers.student.index'); // Make sure the Blade file exists
}
public function student()
{
    return view('teachers.student'); // or whatever your actual view name is
}
public function gradeIndex()
{
    return view('teachers.grade'); // make sure you have resources/views/teachers/grade.blade.php
}
public function subjectIndex()
{
    // Load any required data here (e.g., list of subjects)
    return view('teachers.subject'); // Adjust the view path if needed
}
public function event()
{
    return view('teachers.event'); // or whatever Blade view you want to return
}
}

<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display the student dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        return view('student.dashboard');
    }

    /**
     * Display the student gradebook.
     *
     * @return \Illuminate\View\View
     */
    public function gradebook()
    {
        return view('student.gradebook');
    }

    /**
     * Display the student events.
     *
     * @return \Illuminate\View\View
     */
    public function events()
    {
        return view('student.events');
    }

    /**
     * Display the student schedule.
     *
     * @return \Illuminate\View\View
     */
    public function schedule()
    {
        return view('student.schedule');
    }

    /**
     * Display the student assignments.
     *
     * @return \Illuminate\View\View
     */
    public function assignments()
    {
        return view('student.assignments');
    }
} 
<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

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
        // Fetch events that are visible to students
        $events = Event::where(function($query) {
            $query->where('visibility', 'All')
                  ->orWhere('visibility', 'Students');
        })
        ->orderBy('event_date', 'desc')
        ->get();
        
        return view('student.events', compact('events'));
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

    /**
     * Download the student gradebook as PDF.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadGradebookPDF()
    {
        // Get the current school year
        $schoolYear = '2023-2024'; // You might want to get this dynamically

        // Get the grades data
        // This is a sample data structure - replace with your actual data retrieval logic
        $grades = [
            'First Semester' => [
                'Mathematics' => [
                    'first_quarter' => 85,
                    'second_quarter' => 88,
                    'final_grade' => 87
                ],
                'Science' => [
                    'first_quarter' => 90,
                    'second_quarter' => 92,
                    'final_grade' => 91
                ],
                // Add more subjects as needed
            ],
            'Second Semester' => [
                'Mathematics' => [
                    'first_quarter' => 87,
                    'second_quarter' => 89,
                    'final_grade' => 88
                ],
                'Science' => [
                    'first_quarter' => 91,
                    'second_quarter' => 93,
                    'final_grade' => 92
                ],
                // Add more subjects as needed
            ]
        ];

        $pdf = PDF::loadView('student.gradebook-pdf', [
            'schoolYear' => $schoolYear,
            'grades' => $grades
        ]);

        return $pdf->download('gradebook.pdf');
    }

    /**
     * Show the student's profile page.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('student.profile', compact('user'));
    }
}
<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\EventView;
use App\Models\Subject;
use App\Models\Grade;
use App\Services\AutomaticEnrollmentService;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use League\Csv\Reader;

class StudentController extends Controller
{
    protected $enrollmentService;

    public function __construct(AutomaticEnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

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
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Student record not found.');
        }

        // Get all subjects for the student's grade level
        $subjects = Subject::where('grade_level', $student->grade_level)
            ->whereNull('parent_id')  // Only get parent subjects (subject labels)
            ->orderBy('name')
            ->get();

        // Get all grades for the student
        $grades = Grade::where('student_id', $student->user_id)
            ->whereIn('subject_id', $subjects->pluck('id'))
            ->get()
            ->groupBy('subject_id');

        // Calculate semester averages
        $firstSemAverage = 0;
        $secondSemAverage = 0;
        $firstSemCount = 0;
        $secondSemCount = 0;

        foreach ($grades as $subjectGrades) {
            $subjectFirstSem = $subjectGrades->whereIn('grading_period', ['1st', '2nd'])->avg('grade');
            $subjectSecondSem = $subjectGrades->whereIn('grading_period', ['3rd', '4th'])->avg('grade');

            if ($subjectFirstSem) {
                $firstSemAverage += $subjectFirstSem;
                $firstSemCount++;
            }
            if ($subjectSecondSem) {
                $secondSemAverage += $subjectSecondSem;
                $secondSemCount++;
            }
        }

        $firstSemAverage = $firstSemCount > 0 ? round($firstSemAverage / $firstSemCount, 2) : null;
        $secondSemAverage = $secondSemCount > 0 ? round($secondSemAverage / $secondSemCount, 2) : null;

        return view('student.gradebook', compact('subjects', 'grades', 'firstSemAverage', 'secondSemAverage'));
    }

    /**
     * Display the student events.
     *
     * @return \Illuminate\View\View
     */
    public function events()
    {
        $user = Auth::user();
        
        // Get all events visible to students
        $events = Event::where(function($query) {
            $query->where('visibility', 'All')
                  ->orWhere('visibility', 'Students');
        })
        ->orderBy('created_at', 'desc')
        ->get();

        // Get events that user has already viewed
        $viewedEventIds = EventView::where('user_id', $user->id)
            ->pluck('event_id')
            ->toArray();

        // Categorize events
        $recentEvents = $events->whereNotIn('event_id', $viewedEventIds);
        $oldEvents = $events->whereIn('event_id', $viewedEventIds);

        // Mark all recent events as viewed
        foreach ($recentEvents as $event) {
            EventView::updateOrCreate(
                ['user_id' => $user->id, 'event_id' => $event->event_id],
                ['viewed_at' => now()]
            );
        }
        
        // Get count of new events for the sidebar
        $newEventsCount = Event::where(function($query) {
            $query->where('visibility', 'All')
                  ->orWhere('visibility', 'Students');
        })->whereNotIn('event_id', $viewedEventIds)->count();
        
        return view('student.events', compact('recentEvents', 'oldEvents', 'newEventsCount'));
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
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.gradebook')
                ->with('error', 'Student record not found.');
        }

        // Get all subjects for the student's grade level
        $subjects = Subject::where('grade_level', $student->grade_level)
            ->whereNull('parent_id')  // Only get parent subjects (subject labels)
            ->orderBy('name')
            ->get();

        // Get all grades for the student
        $grades = Grade::where('student_id', $student->user_id)
            ->whereIn('subject_id', $subjects->pluck('id'))
            ->get()
            ->groupBy('subject_id');

        // Organize grades by semester
        $semesterGrades = [
            'First Semester' => [],
            'Second Semester' => []
        ];

        foreach ($subjects as $subject) {
            $subjectGrades = $grades[$subject->id] ?? collect();
            
            // First Semester
            $firstQuarter = $subjectGrades->where('grading_period', '1st')->first();
            $secondQuarter = $subjectGrades->where('grading_period', '2nd')->first();
            $firstSemFinal = null;
            if ($firstQuarter && $secondQuarter) {
                $firstSemFinal = round(($firstQuarter->grade + $secondQuarter->grade) / 2, 2);
            }

            $semesterGrades['First Semester'][$subject->name] = [
                'first_quarter' => $firstQuarter ? $firstQuarter->grade : null,
                'second_quarter' => $secondQuarter ? $secondQuarter->grade : null,
                'final_grade' => $firstSemFinal
            ];

            // Second Semester
            $thirdQuarter = $subjectGrades->where('grading_period', '3rd')->first();
            $fourthQuarter = $subjectGrades->where('grading_period', '4th')->first();
            $secondSemFinal = null;
            if ($thirdQuarter && $fourthQuarter) {
                $secondSemFinal = round(($thirdQuarter->grade + $fourthQuarter->grade) / 2, 2);
            }

            $semesterGrades['Second Semester'][$subject->name] = [
                'first_quarter' => $thirdQuarter ? $thirdQuarter->grade : null,
                'second_quarter' => $fourthQuarter ? $fourthQuarter->grade : null,
                'final_grade' => $secondSemFinal
            ];
        }

        // Calculate semester averages
        foreach ($semesterGrades as $semester => $subjects) {
            $total = 0;
            $count = 0;
            foreach ($subjects as $subject) {
                if ($subject['final_grade'] !== null) {
                    $total += $subject['final_grade'];
                    $count++;
                }
            }
            $semesterGrades[$semester]['general_average'] = $count > 0 ? round($total / $count, 2) : null;
        }

        // Get current school year
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $schoolYear = $currentMonth >= 6 ? 
            $currentYear . '-' . ($currentYear + 1) : 
            ($currentYear - 1) . '-' . $currentYear;

        $pdf = PDF::loadView('student.gradebook-pdf', [
            'schoolYear' => $schoolYear,
            'grades' => $semesterGrades,
            'student' => $student
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            // ... existing validation rules ...
        ]);

        DB::beginTransaction();
        try {
            $student = Student::create($validated);
            
            // Automatically enroll the student in subjects matching their grade level
            $this->enrollmentService->enrollStudent($student);
            
            DB::commit();
            return redirect()->route('students.index')->with('success', 'Student created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create student: ' . $e->getMessage());
        }
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        try {
            $csv = Reader::createFromPath($request->file('csv_file')->getPathname());
            $csv->setHeaderOffset(0);
            $records = $csv->getRecords();

            $students = collect();
            foreach ($records as $record) {
                // Create student record
                $student = Student::create([
                    // ... map CSV fields to student fields ...
                ]);
                $students->push($student);
            }

            // Enroll all imported students in their respective subjects
            $this->enrollmentService->enrollStudents($students);

            return redirect()->route('students.index')
                ->with('success', 'Students imported and enrolled successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to import students: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $oldGradeLevel = $student->grade_level;
        $oldSection = $student->section;

        $validated = $request->validate([
            // ... existing validation rules ...
        ]);

        DB::beginTransaction();
        try {
            $student->update($validated);
            
            // Update enrollments if grade level or section changed
            if ($oldGradeLevel !== $student->grade_level || $oldSection !== $student->section) {
                $this->enrollmentService->updateStudentEnrollments($student, $oldGradeLevel, $oldSection);
            }
            
            DB::commit();
            return redirect()->route('students.index')->with('success', 'Student updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update student: ' . $e->getMessage());
        }
    }
}
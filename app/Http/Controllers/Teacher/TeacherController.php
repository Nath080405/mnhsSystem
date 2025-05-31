<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\Subject;
use App\Models\Event;
use App\Models\Grade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use App\Models\EventView;
use League\Csv\Reader;
use League\Csv\Writer;
use Illuminate\Support\Facades\Storage;
use App\Services\AutomaticEnrollmentService;

class TeacherController extends Controller
{
    protected $enrollmentService;

    public function __construct(AutomaticEnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

    /**
     * Show the teacher dashboard.
     */
    public function dashboard()
    {
        return view('teachers.dashboard');
    }

    /**
     * Show the create student form.
     */
    public function createStudent()
    {
        $teacher = auth()->user();
        $section = Section::where('adviser_id', $teacher->id)->first();
        
        if (!$section) {
            return redirect()->route('teachers.student.index')
                ->with('error', 'You are not assigned to any section. Please contact the administrator.');
        }

        return view('teachers.student.create', compact('section'));
    }

    /**
     * Show the student index page.
     */
    public function student()
    {
        $students = User::where('role', 'student')
            ->join('students', 'users.id', '=', 'students.user_id')
            ->select('users.*', 'students.*')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->get();
        return view('teachers.student.index', compact('students'));
    }

    /**
     * Show the grades index page.
     */
    public function gradeIndex()
    {
        return view('teachers.grade.index');
    }

    /**
     * Show the subjects index page.
     */
    public function subjectIndex()
    {
        $teacher = auth()->user();
        
        // Get the teacher's assigned section
        $section = Section::where('adviser_id', $teacher->id)->first();
        
        // Get subjects assigned to this teacher
        $subjects = Subject::with(['schedules', 'grades.user'])
            ->where('teacher_id', Auth::id())
            ->get();

        return view('teachers.subject.index', compact('subjects', 'section'));
    }

    /**
     * Show the list of students.
     */
    public function indexStudent()
    {
        $teacher = auth()->user();
        
        // Get the section where the teacher is assigned as adviser
        $section = Section::where('adviser_id', $teacher->id)->first();
        
        if (!$section) {
            return view('teachers.student.index', [
                'students' => collect(),
                'section' => null,
                'error' => 'You are not assigned to any section. Please contact the administrator.'
            ]);
        }

        // Get active students from the teacher's assigned section with user relationship
        $students = Student::with('user')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('students.section', $section->name)
            ->where('students.status', 'active')
            ->whereNull('students.deleted_at')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->select('students.*')
            ->paginate(10);

        return view('teachers.student.index', compact('students', 'section'));
    }

    public function indexStudentGrade()
    {
        $teacher = auth()->user();
        
        // Get the section where the teacher is assigned as adviser
        $section = Section::where('adviser_id', $teacher->id)->first();
        
        if (!$section) {
            return view('teachers.student.grade.index', [
                'students' => collect(),
                'subjects' => collect(),
                'grades' => collect(),
                'error' => 'You are not assigned to any section. Please contact the administrator.'
            ]);
        }

        // Get students from the teacher's assigned section
        $students = User::where('role', 'student')
            ->join('students', 'users.id', '=', 'students.user_id')
            ->where('students.section', $section->name)
            ->select('users.*', 'students.*')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->paginate(10);

        return view('teachers.student.grade.index', compact('students'));
    }

    public function showStudentGrade($id)
    {
        $teacher = auth()->user();
        
        // Get the student with their user information
        $student = Student::with('user')
            ->where('user_id', $id)
            ->firstOrFail();
            
        // Get all subjects for the student's grade level (not just those assigned to this teacher)
        $subjects = Subject::where('grade_level', $student->grade_level)
            ->orderBy('name')
            ->get();
            
        // Get all grades for this student
        $grades = Grade::where('student_id', $id)
            ->whereIn('subject_id', $subjects->pluck('id'))
            ->get();
            
        return view('teachers.student.grade.show', compact('student', 'subjects', 'grades'));
    }

    /**
     * Show the event page with a list of events.
     */
    public function event()
    {
        // Only show events that are visible to teachers
        $events = Event::where(function($query) {
            $query->where('visibility', 'All')
                  ->orWhere('visibility', 'Teachers');
        })
        ->whereNotIn('visibility', ['Students'])
        ->orderBy('event_date', 'desc')
        ->get();
        
        return view('teachers.event.index', compact('events'));
    }

    /**
     * Search for a student by student_id.
     */
    public function searchStudent(Request $request)
    {
        $search = $request->input('search');
        $student = Student::where('student_id', $search)->first();

        if ($student) {
            $user = User::find($student->user_id);
            return view('teachers.student.search_result', compact('student', 'user'));
        } else {
            return back()->with('error', 'Student not found in the database.');
        }
    }

    /**
     * Show the list of subjects assigned to the authenticated teacher.
     */
    public function index()
    {
        // Get subjects that belong to the authenticated teacher
        $subjects = Subject::where('teacher_id', Auth::id())->get();

        return view('teachers.subject.index', compact('subjects'));
    }

    /**
     * Show a single subject's details.
     */
    public function show($id)
    {
        $student = Student::with('user')->where('user_id', $id)->firstOrFail();
        return view('teachers.student.show', compact('student'));
    }

    /**
     * Show the form for editing a specific student.
     */
    public function edit($id)
    {
        $teacher = auth()->user();
        $section = Section::where('adviser_id', $teacher->id)->first();

        if (!$section) {
            return redirect()->route('teachers.student.index')
                ->with('error', 'You are not assigned to any section. Please contact the administrator.');
        }

        $student = Student::with('user')
            ->where('user_id', $id)
            ->where('section', $section->name)
            ->firstOrFail();

        return view('teachers.student.edit', compact('student', 'section'));
    }

    /**
     * Handle the update of a student.
     */
    public function update(Request $request, $id)
    {
        $teacher = auth()->user();
        $section = Section::where('adviser_id', $teacher->id)->first();

        if (!$section) {
            return redirect()->route('teachers.student.index')
                ->with('error', 'You are not assigned to any section. Please contact the administrator.');
        }

        // Verify the student belongs to the teacher's section
        $student = Student::where('user_id', $id)
            ->where('section', $section->name)
            ->firstOrFail();

        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'email' => 'required|email|unique:users,email,' . $id,
            'gender' => 'required|string|in:Male,Female,Other',
            'birthdate' => 'required|date',
            'lrn' => 'required|string|unique:students,lrn,' . $id . ',user_id',
            'phone' => 'nullable|string|max:20',
            'street_address' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'municipality' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Update user record
            $student->user->update([
                'last_name' => $validated['last_name'],
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'suffix' => $validated['suffix'],
                'email' => $validated['email'],
            ]);

            // Update student record using user_id
            Student::where('user_id', $id)->update([
                'gender' => $validated['gender'],
                'birthdate' => $validated['birthdate'],
                'lrn' => $validated['lrn'],
                'phone' => $validated['phone'],
                'street_address' => $validated['street_address'],
                'barangay' => $validated['barangay'],
                'municipality' => $validated['municipality'],
                'province' => $validated['province'],
                'grade_level' => $section->grade_level, // Keep the section's grade level
                'section' => $section->name, // Keep the section name
            ]);

            DB::commit();
            return redirect()->route('teachers.student.index')->with('success', 'Student updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update student: ' . $e->getMessage());
        }
    }

    /**
     * Show the event creation form.
     */
    public function create()
    {
        return view('teachers.event.create');
    }

    /**
     * Preview the event before saving.
     */
    public function preview(Request $request)
    {
        // Validate the incoming event data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'location' => 'required|string|max:255',
            'venue_image' => 'nullable|image|max:2048', // optional
        ]);

        // Store the venue image if provided
        if ($request->hasFile('venue_image')) {
            $validated['venue_image'] = $request->file('venue_image')->store('venue_images', 'public');
        }

        return view('teachers.event.preview', compact('validated'));
    }

    /**
     * Store the newly created event.
     */
    public function store(Request $request)
    {
        // Validate the event data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'required|string',
            'location' => 'required|string|max:255',
            'venue_image' => 'nullable|image|max:2048', // optional
        ]);

        // Create a new event
        $event = Event::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'event_date' => $request->input('event_date'),
            'event_time' => $request->input('event_time'),
            'location' => $request->input('location'),
            'venue_image' => $request->hasFile('venue_image') ? $request->file('venue_image')->store('venue_images', 'public') : null,
            'teacher_id' => Auth::id(),
        ]);

        return redirect()->route('teachers.event.index')->with('success', 'Event created successfully!');
    }

    /**
     * Delete a specific event.
     */
  
    public function subjects()
    {
        $teacher = Auth::user();
        $subjects = Subject::where('teacher_id', $teacher->id)
            ->whereNull('parent_id')  // Only get parent subjects (subject labels)
            ->with(['automaticEnrollments.student'])
            ->get();

        return view('teachers.subjects.index', compact('subjects'));
    }

    public function showSubject($id)
    {
        $subject = Subject::findOrFail($id);
        $enrolledStudents = $this->enrollmentService->getEnrolledStudents($subject);

        return view('teachers.subjects.show', compact('subject', 'enrolledStudents'));
    }

    public function storeStudent(Request $request)
    {
        // Get the teacher's assigned section first
        $teacher = auth()->user();
        $section = Section::where('adviser_id', $teacher->id)->first();

        if (!$section) {
            return redirect()->route('teachers.student.index')
                ->with('error', 'You are not assigned to any section. Please contact the administrator.');
        }

        // Validate the student data
        $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'email' => 'required|email|unique:users,email',
            'gender' => 'required|string|in:Male,Female,Other',
            'birthdate' => 'required|date',
            'lrn' => 'required|string|unique:students,lrn',
            'phone' => 'nullable|string|max:20',
            'street_address' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'municipality' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Get the latest student ID and generate the next one
            $latestStudent = Student::orderBy('student_id', 'desc')->first();
            $nextId = $latestStudent ? intval(substr($latestStudent->student_id, 3)) + 1 : 1;
            $studentId = 'STU' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            
            // Generate temporary password based on student ID
            $tempPassword = 'TEMP' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

            // Create user record
            $user = User::create([
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'suffix' => $request->suffix,
                'email' => $request->email,
                'username' => $studentId,
                'password' => bcrypt($tempPassword),
                'role' => 'student',
            ]);

            // Create student record with teacher's section and grade level
            Student::create([
                'user_id' => $user->id,
                'student_id' => $studentId,
                'lrn' => $request->lrn,
                'street_address' => $request->street_address,
                'barangay' => $request->barangay,
                'municipality' => $request->municipality,
                'province' => $request->province,
                'phone' => $request->phone,
                'birthdate' => $request->birthdate,
                'gender' => $request->gender,
                'grade_level' => $section->grade_level, // Use section's grade level
                'section' => $section->name, // Use section's name
                'status' => 'active',
            ]);

            DB::commit();
            return redirect()->route('teachers.student.index')
                ->with('success', 'Student added successfully! Temporary password: ' . $tempPassword);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to add student: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            // Find the student record using user_id
            $student = Student::where('user_id', $id)->firstOrFail();
            
            // Soft delete the student record
            $student->delete();
            
            // Update the student's status to 'dropped'
            $student->update(['status' => 'dropped']);
            
            DB::commit();
            return redirect()->route('teachers.student.index')->with('success', 'Student removed from section successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('teachers.student.index')->with('error', 'Failed to remove student: ' . $e->getMessage());
        }
    }

    /**
     * Show the teacher's profile page.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('teachers.profile', compact('user'));
    }

    /**
     * Update the teacher's profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:6', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }

    public function myClass()
    {
        $teacher = auth()->user(); // assuming the teacher is logged in
        $section = $teacher->section;

        if (!$section) {
            return view('teachers.index', [
                'students' => [],
                'error' => 'No section assigned.'
            ]);
        }

        $students = $section->students;

        return view('teachers.index', compact('students'));
    }

    public function storeGrades(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'grading_period' => 'required|in:1st,2nd,3rd,4th',
            'grades' => 'required|array',
            'grades.*.grade' => 'required|numeric|min:0|max:100',
            'grades.*.remarks' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->grades as $studentId => $gradeData) {
                Grade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_id' => $request->subject_id,
                        'grading_period' => $request->grading_period,
                    ],
                    [
                        'grade' => $gradeData['grade'],
                        'remarks' => $gradeData['remarks'] ?? null,
                    ]
                );
            }

            DB::commit();
            return redirect()->route('teachers.student.grade.index')
                ->with('success', 'Grades saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to save grades: ' . $e->getMessage());
        }
    }

    /**
     * Download the CSV template for student import
     */
    public function downloadTemplate()
    {
        $csv = Writer::createFromString('');
        
        // Add headers
        $csv->insertOne([
            'last_name',
            'first_name',
            'middle_name',
            'suffix',
            'email',
            'lrn',
            'street_address',
            'barangay',
            'municipality',
            'province',
            'phone',
            'birthdate',
            'gender'
        ]);

        // Add sample data
        $csv->insertOne([
            'Doe',
            'John',
            'Smith',
            'Jr',
            'john.doe@example.com',
            '123456789012',
            '123 Main St',
            'Sample Barangay',
            'Sample Municipality',
            'Sample Province',
            '09123456789',
            '2000-01-01',
            'Male'
        ]);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="student_update_template.csv"',
        ];

        return response($csv->toString(), 200, $headers);
    }

    /**
     * Import students from CSV file
     */
    public function importStudents(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240' // max 10MB
        ]);

        try {
            DB::beginTransaction();

            $csv = Reader::createFromPath($request->file('csv_file')->getPathname());
            $csv->setHeaderOffset(0);

            $teacher = auth()->user();
            $section = Section::where('adviser_id', $teacher->id)->first();

            if (!$section) {
                throw new \Exception('You are not assigned to any section. Please contact the administrator.');
            }

            $records = $csv->getRecords();
            $updatedCount = 0;
            $errors = [];

            foreach ($records as $index => $record) {
                try {
                    // Validate required fields
                    if (empty($record['last_name']) || empty($record['first_name']) || empty($record['email']) || empty($record['lrn'])) {
                        throw new \Exception('Required fields missing');
                    }

                    // Find existing student by LRN
                    $existingStudent = Student::where('lrn', $record['lrn'])->first();
                    
                    if (!$existingStudent) {
                        throw new \Exception('Student with LRN ' . $record['lrn'] . ' not found');
                    }

                    // Update user record
                    $user = $existingStudent->user;
                    $user->last_name = $record['last_name'];
                    $user->first_name = $record['first_name'];
                    $user->middle_name = $record['middle_name'] ?? $user->middle_name;
                    $user->suffix = $record['suffix'] ?? $user->suffix;
                    $user->email = $record['email'];
                    $user->save();

                    // Convert birthdate from MM/DD/YYYY to YYYY-MM-DD if provided
                    $birthdate = null;
                    if (!empty($record['birthdate'])) {
                        $date = \DateTime::createFromFormat('m/d/Y', $record['birthdate']);
                        if (!$date) {
                            throw new \Exception('Invalid birthdate format. Must be MM/DD/YYYY');
                        }
                        $birthdate = $date->format('Y-m-d');
                    }

                    // Update student record using user_id
                    Student::where('user_id', $existingStudent->user_id)->update([
                        'street_address' => $record['street_address'] ?? $existingStudent->street_address,
                        'barangay' => $record['barangay'] ?? $existingStudent->barangay,
                        'municipality' => $record['municipality'] ?? $existingStudent->municipality,
                        'province' => $record['province'] ?? $existingStudent->province,
                        'phone' => $record['phone'] ?? $existingStudent->phone,
                        'birthdate' => $birthdate ?? $existingStudent->birthdate,
                        'gender' => $record['gender'] ?? $existingStudent->gender,
                        'section' => $section->name
                    ]);

                    $updatedCount++;
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            if (count($errors) > 0) {
                DB::rollBack();
                return redirect()->route('teachers.student.index')
                    ->with('error', 'Import failed with the following errors:<br>' . implode('<br>', $errors));
            }

            DB::commit();
            return redirect()->route('teachers.student.index')
                ->with('success', "Successfully updated {$updatedCount} students.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('teachers.student.index')
                ->with('error', 'Failed to import students: ' . $e->getMessage());
        }
    }
}

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

class TeacherController extends Controller
{
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
        $subjects = Subject::with('schedules')
            ->where('teacher_id', Auth::id())
            ->get();
        return view('teachers.subject.index', compact('subjects'));
    }

    /**
     * Show the list of students.
     */
    public function indexStudent()
    {
        $students = User::where('role', 'student')
            ->join('students', 'users.id', '=', 'students.user_id')
            ->select('users.*', 'students.*')
            ->paginate(10);
        return view('teachers.student.index', compact('students'));
    }

    public function indexStudentGrade()
    {
        // Fetch grades for students
        $grades = Grade::with('student', 'subject')->get();
    
        // Return the view with the grades
        return view('teachers.student.grade.index', compact('grades'));
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
     * Show the form for editing a specific subject.
     */
    public function edit($id)
    {
        $student = Student::with('user')->where('user_id', $id)->firstOrFail();
        return view('teachers.student.edit', compact('student'));
    }

    /**
     * Handle the update of a subject.
     */
    public function update(Request $request, $id)
    {
        $student = Student::with('user')->where('user_id', $id)->firstOrFail();

        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'email' => 'required|email|unique:users,email,' . $id,
            'gender' => 'required|string|in:Male,Female,Other',
            'birthdate' => 'required|date',
            'lrn' => 'required|string|unique:students,lrn,' . $id . ',user_id',
            'grade_level' => 'required|string',
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

            // Update student record
            $student->update([
                'gender' => $validated['gender'],
                'birthdate' => $validated['birthdate'],
                'lrn' => $validated['lrn'],
                'grade_level' => $validated['grade_level'],
                'phone' => $validated['phone'],
                'street_address' => $validated['street_address'],
                'barangay' => $validated['barangay'],
                'municipality' => $validated['municipality'],
                'province' => $validated['province'],
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
    // Fetch subjects for the logged-in teacher (optional)
    $subjects = Subject::where('teacher_id', auth()->id())->get();

    // Return the view
    return view('teachers.subject', compact('subjects'));
}
public function storeStudent(Request $request)
{
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
        'grade_level' => 'required|string',
        'section' => 'required|string',
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

        // Create student record
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
            'grade_level' => $request->grade_level,
            'section' => $request->section,
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
        
        // Find and delete the student record using user_id
        $student = Student::where('user_id', $id)->firstOrFail();
        Student::where('user_id', $id)->delete();
        
        // Delete the associated user record
        $user = User::findOrFail($id);
        $user->delete();
        
        DB::commit();
        return redirect()->route('teachers.student.index')->with('success', 'Student deleted successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('teachers.student.index')->with('error', 'Failed to delete student: ' . $e->getMessage());
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
}

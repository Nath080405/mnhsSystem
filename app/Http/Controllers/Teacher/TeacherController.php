<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\Subject;
use App\Models\Event;

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
        return view('teachers.student.index');
    }

    /**
     * Show the student index page.
     */
    public function student()
    {
        return view('teachers.student.index');
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
        $subjects = Subject::where('teacher_id', Auth::id())->get();
        return view('teachers.subject.index', compact('subjects'));
    }

    /**
     * Show the list of students.
     */
    public function indexStudent()
    {
        $students = Student::all();
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
        $events = Event::all();
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
        $subject = Subject::with('teacher')
            ->where('id', $id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        return view('teachers.subject.show', compact('subject'));
    }

    /**
     * Show the form for editing a specific subject.
     */
    public function edit($id)
    {
        $subject = Subject::where('id', $id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        $teachers = User::where('role', 'teacher')->get(); // Get a list of teachers

        return view('teachers.subject.edit', compact('subject', 'teachers'));
    }

    /**
     * Handle the update of a subject.
     */
    public function update(Request $request, $id)
    {
        // Validate incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code,' . $id,
            'credits' => 'required|integer|min:1',
            'teacher_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
        ]);

        $subject = Subject::where('id', $id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        // Update the subject
        $subject->update([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'credits' => $request->input('credits'),
            'teacher_id' => $request->input('teacher_id'),
            'status' => $request->input('status'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('teachers.subject.index')->with('success', 'Subject updated successfully.');
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
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'student_id' => 'required|string|unique:students,student_id',
        'age' => 'required|integer|min:1',
        'sex' => 'required|string|in:Male,Female,Other',
        'birth_month' => 'required|integer|min:1|max:12',
        'birth_day' => 'required|integer|min:1|max:31',
        'birth_year' => 'required|integer|min:1900|max:' . date('Y'),
        'province' => 'required|string|max:255',
    ]);

    // Create a new student
    Student::create([
        'first_name' => $request->input('first_name'),
        'middle_name' => $request->input('middle_name'),
        'last_name' => $request->input('last_name'),
        'student_id' => $request->input('student_id'),
        'age' => $request->input('age'),
        'sex' => $request->input('sex'),
        'birthdate' => $request->input('birth_year') . '-' . $request->input('birth_month') . '-' . $request->input('birth_day'),
        'province' => $request->input('province'),
        'user_id' => Auth::id(), // Associate the student with the logged-in teacher
    ]);

    // Redirect back with a success message
    return redirect()->route('teachers.student.index')->with('success', 'Student added successfully!');
}
public function destroy($id)
{
    \Log::info("Deleting event with ID: $id");

    $event = Event::findOrFail($id);

    \Log::info("Event found: " . $event);

    $event->delete();

    return redirect()->route('teachers.event.index')->with('success', 'Event deleted successfully.');
}

}

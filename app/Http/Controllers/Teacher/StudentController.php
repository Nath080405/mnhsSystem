<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'student')->paginate(4);
        return view('teachers.student.index', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'student_id' => 'required|string|unique:students,student_id',
            'age' => 'required|integer',
            'sex' => 'required|string',
            'birth_month' => 'required|integer',
            'birth_day' => 'required|integer',
            'birth_year' => 'required|integer',
            'province' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            // Add other fields as needed
        ]);

        $user = new User();
        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = 'student';
        $user->save();

        Student::create([
            'user_id' => $user->id,
            'student_id' => $request->student_id,
            'class' => $request->input('class'),
            'birthdate' => $request->input('birth_year') . '-' . $request->input('birth_month') . '-' . $request->input('birth_day'),
            'age' => $request->age,
            'sex' => $request->sex,
            'province' => $request->province,
            // Add other fields as needed
        ]);

        return redirect()->route('teachers.student.index')->with('success', 'Student added successfully!');
    }

    public function edit($id)
    {
        $student = User::findOrFail($id);
        return view('teachers.student.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $student = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            // Add other fields as needed
        ]);

        $student->update($validated);

        return redirect()->route('teachers.student.index')->with('success', 'Student updated successfully!');
    }

    public function destroy($id)
    {
        $student = User::findOrFail($id);
        $student->delete();

        return redirect()->route('teachers.student.index')->with('success', 'Student deleted successfully.');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $students = User::where('role', 'student')
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->paginate(4);

        return view('teachers.student.index', compact('students', 'search'));
    }

    public function gradesIndex()
    {
        // You can pass dummy data or an empty array for now
        return view('teachers.student.grade.index', [
            'grades' => [],
            'search' => '',
        ]);
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

        // 1. Create the User
        $user = User::create([
            'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'role' => 'student',
            'email' => strtolower($request->input('student_id')) . '@school.local', // or another unique email
            'password' => bcrypt('defaultpassword'), // or generate a random one
        ]);

        // 2. Create the Student
        Student::create([
            'user_id' => $user->id,
            'student_id' => $request->input('student_id'),
            'age' => $request->input('age'),
            'gender' => $request->input('sex'),
            'birthdate' => $request->input('birth_year') . '-' . $request->input('birth_month') . '-' . $request->input('birth_day'),
            'province' => $request->input('province'),
        ]);

        return redirect()->route('teachers.student.index')->with('success', 'Student added successfully!');
    }

    public function indexStudentGrade()
    {
        $teacher = auth()->user();

        // Get the section where the teacher is assigned as adviser
        $section = \App\Models\Section::where('adviser_id', $teacher->id)->first();

        if (!$section) {
            return view('teachers.student.grade.index', [
                'students' => collect(),
                'subjects' => collect(),
                'grades' => collect(),
                'error' => 'You are not assigned to any section. Please contact the administrator.'
            ]);
        }

        // Get students from the teacher's assigned section
        $students = \App\Models\User::where('role', 'student')
            ->join('students', 'users.id', '=', 'students.user_id')
            ->where('students.section', $section->name)
            ->select('users.*', 'students.*')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->get();

        // Get all subjects for this grade level
        $subjects = \App\Models\Subject::where('grade_level', $section->grade_level)
            ->orderBy('name')
            ->get();

        // Get all grades for these students and subjects
        $grades = \App\Models\Grade::whereIn('student_id', $students->pluck('user_id'))
            ->whereIn('subject_id', $subjects->pluck('id'))
            ->get();

        return view('teachers.student.grade.index', compact('students', 'subjects', 'grades'));
    }
}
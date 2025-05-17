<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Section;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'student')
            ->with(['student', 'student.section'])
            ->orderBy('last_name')
            ->orderBy('first_name');

        // Apply filters
        if ($request->filled('status')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if ($request->filled('grade_level')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('grade_level', $request->grade_level);
            });
        }

        if ($request->filled('section')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('section_id', $request->section);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('student', function($q) use ($search) {
                      $q->where('student_id', 'like', "%{$search}%");
                  });
            });
        }

        $students = $query->paginate(10)->withQueryString();
        $sections = Section::orderBy('grade_level')->orderBy('name')->get();

        return view('admin.students.index', compact('students', 'sections'));
    }

    public function create()
    {
        $sections = Section::orderBy('grade_level')->orderBy('name')->get();
        return view('admin.students.create', compact('sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'grade_level' => 'required|string',
            'section' => 'required|string',
            'lrn' => 'required|string|unique:students,lrn',
        ]);

        // Create user record
        $user = new \App\Models\User();
        $user->last_name = $request->last_name;
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->suffix = $request->suffix;
        $user->email = $request->email;
        $user->username = '';
        $user->password = bcrypt($request->password);
        $user->role = 'student';
        $user->save();

        // Generate student ID and set as username
        $studentId = 'STU' . str_pad($user->id, 5, '0', STR_PAD_LEFT);
        $user->username = $studentId;
        $user->save();

        // Create student record
        $student = new \App\Models\Student();
        $student->user_id = $user->id;
        $student->student_id = $user->username;
        $student->lrn = $request->lrn;
        $student->street_address = $request->street_address;
        $student->barangay = $request->barangay;
        $student->municipality = $request->municipality;
        $student->province = $request->province;
        $student->phone = $request->phone;
        $student->birthdate = $request->birthdate;
        $student->gender = $request->gender;
        $student->grade_level = $request->grade_level;
        $student->section = $request->section;
        $student->status = 'active';
        $student->save();

        return redirect()->route('admin.students.index')->with('success', 'Student added successfully! Student ID: ' . $studentId);
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Delete the associated student record first
            if ($user->student) {
                $user->student->delete();
            }

            // Then delete the user record
            $user->delete();

            return redirect()->route('admin.students.index')
                ->with('success', 'Student deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.students.index')
                ->with('error', 'Failed to delete student. Please try again.');
        }
    }

    public function edit($id)
    {
        $student = User::with('student')->findOrFail($id);
        $sections = Section::orderBy('grade_level')->orderBy('name')->get();
        return view('admin.students.edit', compact('student', 'sections'));
    }

    public function update(Request $request, $id)
    {
        $user = User::with('student')->findOrFail($id);

        $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8|confirmed',
            'grade_level' => 'required|string',
            'section_id' => 'required|exists:sections,id',
            'lrn' => 'required|string|unique:students,lrn,' . $user->student->user_id . ',user_id',
        ]);

        // Update user record
        $userData = [
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'suffix' => $request->suffix,
            'email' => $request->email,
        ];

        // Only update password if provided
        if (!empty($request->password)) {
            $userData['password'] = bcrypt($request->password);
        }

        $user->update($userData);

        // Get section details
        $section = Section::findOrFail($request->section_id);

        // Update or create student record
        if ($user->student) {
            $user->student()->update([
                'lrn' => $request->lrn,
                'street_address' => $request->street_address,
                'barangay' => $request->barangay,
                'municipality' => $request->municipality,
                'province' => $request->province,
                'phone' => $request->phone,
                'birthdate' => $request->birthdate,
                'gender' => $request->gender,
                'grade_level' => $section->grade_level,
                'section' => $section->name,
                'status' => $request->status ?? 'active',
            ]);
        } else {
            // Create student record if it doesn't exist
            $user->student()->create([
                'student_id' => $user->username,
                'lrn' => $request->lrn,
                'street_address' => $request->street_address,
                'barangay' => $request->barangay,
                'municipality' => $request->municipality,
                'province' => $request->province,
                'phone' => $request->phone,
                'birthdate' => $request->birthdate,
                'gender' => $request->gender,
                'grade_level' => $section->grade_level,
                'section' => $section->name,
                'status' => $request->status ?? 'active',
            ]);
        }

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully!');
    }

    public function show($id)
    {
        $student = User::with('student')->findOrFail($id);
        return view('admin.students.show', compact('student'));
    }
}


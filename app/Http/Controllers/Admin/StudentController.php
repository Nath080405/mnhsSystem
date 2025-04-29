<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'student')
            ->with('student');

        // Apply filters
        if ($request->filled('grade')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('grade', $request->grade);
            });
        }

        if ($request->filled('status')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('student', function($q) use ($search) {
                      $q->where('student_id', 'like', "%{$search}%");
                  });
            });
        }

        $students = $query->paginate(4)->withQueryString();

        // Get unique grades for filter dropdown
        $grades = User::where('role', 'student')
            ->whereHas('student', function ($q) {
                $q->whereNotNull('grade');
            })
            ->join('students', 'users.id', '=', 'students.user_id')
            ->distinct()
            ->pluck('students.grade');

        return view('admin.students.index', compact('students', 'grades'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'grade' => 'required|string',
            'section' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_email' => 'nullable|email',
        ]);

        // Create user record
        $user = new \App\Models\User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->username = '';
        $user->password = bcrypt($validated['password']);
        $user->role = 'student';
        $user->save();

        // Generate student ID and set as username
        $studentId = 'STU' . str_pad($user->id, 5, '0', STR_PAD_LEFT);
        $user->username = $studentId;
        $user->save();

        // Create student record
        $student = new \App\Models\Student();
        $student->user_id = $user->id;
        $student->grade = $validated['grade'];
        $student->section = $validated['section'];
        $student->student_id = $user->username;
        $student->phone = $validated['phone'] ?? null;
        $student->address = $validated['address'] ?? null;
        $student->birthdate = $validated['birthdate'] ?? null;
        $student->gender = $validated['gender'] ?? null;
        $student->guardian_name = $validated['guardian_name'] ?? null;
        $student->guardian_phone = $validated['guardian_phone'] ?? null;
        $student->guardian_email = $validated['guardian_email'] ?? null;
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
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $user = User::with('student')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'grade' => 'nullable|string',
            'section' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_email' => 'nullable|email',
            'status' => 'nullable|string',
        ]);

        // Update user record
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $userData['password'] = bcrypt($validated['password']);
        }

        $user->update($userData);

        // Update or create student record
        if ($user->student) {
            $user->student()->update([
                'grade' => $validated['grade'],
                'section' => $validated['section'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'birthdate' => $validated['birthdate'],
                'gender' => $validated['gender'],
                'guardian_name' => $validated['guardian_name'],
                'guardian_phone' => $validated['guardian_phone'],
                'guardian_email' => $validated['guardian_email'],
                'status' => $validated['status'] ?? 'active',
            ]);
        } else {
            // Create student record if it doesn't exist
            $user->student()->create([
                'grade' => $validated['grade'],
                'section' => $validated['section'],
                'student_id' => $user->username,
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'birthdate' => $validated['birthdate'],
                'gender' => $validated['gender'],
                'guardian_name' => $validated['guardian_name'],
                'guardian_phone' => $validated['guardian_phone'],
                'guardian_email' => $validated['guardian_email'],
                'status' => $validated['status'] ?? 'active',
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


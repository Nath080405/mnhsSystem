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
            ->with('student')
            ->orderBy('last_name')
            ->orderBy('first_name');

        // Apply filters
        if ($request->filled('status')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('status', $request->status);
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

        $students = $query->paginate(4)->withQueryString();

        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
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
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $user = User::with('student')->findOrFail($id);

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

        // Update or create student record
        if ($user->student) {
            $user->student()->update([
                'street_address' => $request->street_address,
                'barangay' => $request->barangay,
                'municipality' => $request->municipality,
                'province' => $request->province,
                'phone' => $request->phone,
                'birthdate' => $request->birthdate,
                'gender' => $request->gender,
                'grade_level' => $request->grade_level,
                'section' => $request->section,
                'status' => $request->status ?? 'active',
            ]);
        } else {
            // Create student record if it doesn't exist
            $user->student()->create([
                'student_id' => $user->username,
                'street_address' => $request->street_address,
                'barangay' => $request->barangay,
                'municipality' => $request->municipality,
                'province' => $request->province,
                'phone' => $request->phone,
                'birthdate' => $request->birthdate,
                'gender' => $request->gender,
                'grade_level' => $request->grade_level,
                'section' => $request->section,
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


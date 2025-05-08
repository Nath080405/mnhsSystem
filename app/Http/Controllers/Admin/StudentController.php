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

        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'phone' => 'nullable|string|regex:/^09\d{9}$/',
            'address' => 'nullable|string|max:500',
            'birthdate' => 'nullable|date|before:today',
            'gender' => 'required|string|in:Male,Female,Other',
            'guardian_name' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u',
            'guardian_phone' => 'required|string|regex:/^09\d{9}$/',
            'guardian_email' => 'required|email',
        ], [
            'name.required' => 'Please enter the student\'s full name.',
            'name.regex' => 'The name can only contain letters, spaces, and hyphens.',
            'email.required' => 'Please enter the student\'s email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'Please enter a password.',
            'password.min' => 'The password must be at least 8 characters long.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, and one number.',
            'phone.regex' => 'Please enter a valid Philippine mobile number (e.g., 09123456789).',
            'address.max' => 'The address cannot exceed 500 characters.',
            'birthdate.before' => 'The birthdate must be a date before today.',
            'gender.required' => 'Please select a gender.',
            'gender.in' => 'Please select a valid gender.',
            'guardian_name.required' => 'Please enter the guardian\'s name.',
            'guardian_name.regex' => 'The guardian\'s name can only contain letters, spaces, and hyphens.',
            'guardian_phone.required' => 'Please enter the guardian\'s phone number.',
            'guardian_phone.regex' => 'Please enter a valid Philippine mobile number (e.g., 09123456789).',
            'guardian_email.required' => 'Please enter the guardian\'s email address.',
            'guardian_email.email' => 'Please enter a valid email address for the guardian.',
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
        $student->student_id = $user->username;
        $student->phone = $validated['phone'] ?? null;
        $student->address = $validated['address'] ?? null;
        $student->birthdate = $validated['birthdate'] ?? null;
        $student->gender = $validated['gender'];
        $student->guardian_name = $validated['guardian_name'];
        $student->guardian_phone = $validated['guardian_phone'];
        $student->guardian_email = $validated['guardian_email'];
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
            'name' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'phone' => 'nullable|string|regex:/^09\d{9}$/',
            'address' => 'nullable|string|max:500',
            'birthdate' => 'nullable|date|before:today',
            'gender' => 'required|string|in:Male,Female,Other',
            'guardian_name' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u',
            'guardian_phone' => 'required|string|regex:/^09\d{9}$/',
            'guardian_email' => 'required|email',
            'status' => 'nullable|string',
        ], [
            'name.required' => 'Please enter the student\'s full name.',
            'name.regex' => 'The name can only contain letters, spaces, and hyphens.',
            'email.required' => 'Please enter the student\'s email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'password.min' => 'The password must be at least 8 characters long.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, and one number.',
            'phone.regex' => 'Please enter a valid Philippine mobile number (e.g., 09123456789).',
            'address.max' => 'The address cannot exceed 500 characters.',
            'birthdate.before' => 'The birthdate must be a date before today.',
            'gender.required' => 'Please select a gender.',
            'gender.in' => 'Please select a valid gender.',
            'guardian_name.required' => 'Please enter the guardian\'s name.',
            'guardian_name.regex' => 'The guardian\'s name can only contain letters, spaces, and hyphens.',
            'guardian_phone.required' => 'Please enter the guardian\'s phone number.',
            'guardian_phone.regex' => 'Please enter a valid Philippine mobile number (e.g., 09123456789).',
            'guardian_email.required' => 'Please enter the guardian\'s email address.',
            'guardian_email.email' => 'Please enter a valid email address for the guardian.',
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


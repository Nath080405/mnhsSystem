<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'teacher')->with('teacher')->paginate(4);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'date_joined' => 'required|date',
            'qualification' => 'required|string|max:255',
            'specialization' => 'nullable|string',
        ]);

        // Create user record
        $user = new \App\Models\User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->username = '';
        $user->password = bcrypt($validated['password']);
        $user->role = 'teacher';
        $user->save();

        // Generate teacher ID
        $employeeId = 'TCH' . str_pad($user->id, 5, '0', STR_PAD_LEFT);
        $user->username = $employeeId;
        $user->save();

        // Create teacher record
        Teacher::create([
            'user_id' => $user->id,
            'employee_id' => $user->username,
            'department' => $validated['department'],
            'position' => $validated['position'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'birthdate' => $validated['birthdate'],
            'gender' => $validated['gender'],
            'date_joined' => $validated['date_joined'],
            'qualification' => $validated['qualification'],
            'specialization' => $validated['specialization'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher added successfully! Employee ID: ' . $employeeId);
    }

    public function edit($id)
    {
        $user = User::with('teacher')->findOrFail($id);
        $teacher = $user->teacher ?? new Teacher(['user_id' => $user->id]);
        return view('admin.teachers.edit', compact('teacher', 'user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::with('teacher')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'date_joined' => 'required|date',
            'qualification' => 'required|string|max:255',
            'specialization' => 'nullable|string',
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

        // Update or create teacher record
        if ($user->teacher) {
            $user->teacher()->update([
                'department' => $validated['department'],
                'position' => $validated['position'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'birthdate' => $validated['birthdate'],
                'gender' => $validated['gender'],
                'date_joined' => $validated['date_joined'],
                'qualification' => $validated['qualification'],
                'specialization' => $validated['specialization'],
                'status' => $validated['status'],
            ]);
        } else {
            // Create teacher record if it doesn't exist
            $user->teacher()->create([
                'employee_id' => $user->username,
                'department' => $validated['department'],
                'position' => $validated['position'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'birthdate' => $validated['birthdate'],
                'gender' => $validated['gender'],
                'date_joined' => $validated['date_joined'],
                'qualification' => $validated['qualification'],
                'specialization' => $validated['specialization'],
                'status' => $validated['status'],
            ]);
        }

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated successfully!');
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Check if teacher has any assigned subjects
            if ($user->subjects()->exists()) {
                return redirect()->route('admin.teachers.index')
                    ->with('error', 'Cannot delete teacher. They have assigned subjects. Please reassign or delete the subjects first.');
            }

            // Delete the associated teacher record first
            if ($user->teacher) {
                $user->teacher->delete();
            }

            // Then delete the user record
            $user->delete();

            return redirect()->route('admin.teachers.index')
                ->with('success', 'Teacher deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.teachers.index')
                ->with('error', 'Failed to delete teacher. Please try again.');
        }
    }

    public function show($id)
    {
        $teacher = User::with('teacher')->findOrFail($id);
        return view('admin.teachers.show', compact('teacher'));
    }
}
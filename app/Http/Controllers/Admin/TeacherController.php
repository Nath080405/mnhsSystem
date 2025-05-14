<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'teacher')->with('teacher')->paginate(10);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        // Create user record
        $user = new User();
        $user->last_name = $request->last_name;
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->suffix = $request->suffix;
        $user->email = $request->email;
        $user->username = '';
        $user->password = bcrypt($request->password);
        $user->role = 'teacher';
        $user->save();

        // Generate teacher ID and set as username
        $employeeId = 'TCH' . str_pad($user->id, 5, '0', STR_PAD_LEFT);
        $user->username = $employeeId;
        $user->save();

        // Create teacher record
        $teacher = new Teacher();
        $teacher->user_id = $user->id;
        $teacher->employee_id = $user->username;
        $teacher->street_address = $request->street_address;
        $teacher->barangay = $request->barangay;
        $teacher->municipality = $request->municipality;
        $teacher->province = $request->province;
        $teacher->phone = $request->phone;
        $teacher->birthdate = $request->birthdate;
        $teacher->gender = $request->gender;
        $teacher->date_joined = $request->date_joined;
        $teacher->status = 'active';
        $teacher->save();

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
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'street_address' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'municipality' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'gender' => 'required|string|in:Male,Female,Other',
            'status' => 'required|in:active,inactive',
            'date_joined' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // Update user record
            $userData = [
                'last_name' => $validated['last_name'],
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'suffix' => $validated['suffix'],
                'email' => $validated['email'],
            ];

            // Only update password if provided
            if (!empty($validated['password'])) {
                $userData['password'] = bcrypt($validated['password']);
            }

            $user->update($userData);

            // Update or create teacher record
            $teacherData = [
                'street_address' => $validated['street_address'],
                'barangay' => $validated['barangay'],
                'municipality' => $validated['municipality'],
                'province' => $validated['province'],
                'phone' => $validated['phone'],
                'birthdate' => $validated['birthdate'],
                'gender' => $validated['gender'],
                'date_joined' => $validated['date_joined'],
                'status' => $validated['status'],
            ];

            if ($user->teacher) {
                $user->teacher->update($teacherData);
            } else {
                $teacherData['user_id'] = $user->id;
                $teacherData['employee_id'] = $user->username;
                Teacher::create($teacherData);
            }

            DB::commit();
            return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update teacher. Please try again.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
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

            DB::commit();
            return redirect()->route('admin.teachers.index')
                ->with('success', 'Teacher deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
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
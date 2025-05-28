<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use App\Models\EventView;

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
            'lrn' => 'required|string|unique:students,lrn',
        ]);

        // Get the latest student ID and generate the next one
        $latestStudent = \App\Models\Student::orderBy('student_id', 'desc')->first();
        $nextId = $latestStudent ? intval(substr($latestStudent->student_id, 3)) + 1 : 1;
        $studentId = 'STU' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        
        // Generate temporary password based on student ID
        $tempPassword = 'TEMP' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        // Create user record
        $user = new \App\Models\User();
        $user->last_name = $request->last_name;
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->suffix = $request->suffix;
        $user->email = $request->email;
        $user->username = $studentId;
        $user->password = bcrypt($tempPassword);
        $user->role = 'student';
        $user->save();

        // Create student record
        $student = new \App\Models\Student();
        $student->user_id = $user->id;
        $student->student_id = $studentId;
        $student->lrn = $request->lrn;
        $student->street_address = $request->street_address;
        $student->barangay = $request->barangay;
        $student->municipality = $request->municipality;
        $student->province = $request->province;
        $student->phone = $request->phone;
        $student->birthdate = $request->birthdate;
        $student->gender = $request->gender;
        $student->status = 'active';
        $student->save();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student added successfully! Temporary password: ' . $tempPassword);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $user = User::findOrFail($id);

            // Check if student has any grades
            if ($user->student && $user->student->grades()->exists()) {
                return redirect()->route('admin.students.index')
                    ->with('error', 'Cannot delete student. They have existing grades. Please delete the grades first.');
            }

            // Delete related records
            if ($user->student) {
                // Delete event views
                EventView::where('user_id', $user->id)->delete();
                
                // Remove student from section (but don't delete the section)
                if ($user->student->section) {
                    DB::table('students')
                        ->where('user_id', $user->id)
                        ->update(['section' => null]);
                }
                
                // Delete the student record using user_id
                DB::table('students')
                    ->where('user_id', $user->id)
                    ->delete();
            }

            // Delete the user record
            $user->delete();

            DB::commit();
            return redirect()->route('admin.students.index')
                ->with('success', 'Student deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting student: ' . $e->getMessage());
            return redirect()->route('admin.students.index')
                ->with('error', 'Failed to delete student: ' . $e->getMessage());
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

        $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8|confirmed',
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

        // Update student record
        $studentData = [
            'lrn' => $request->lrn,
            'street_address' => $request->street_address,
            'barangay' => $request->barangay,
            'municipality' => $request->municipality,
            'province' => $request->province,
            'phone' => $request->phone,
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'status' => $request->status ?? 'active',
        ];

        \App\Models\Student::where('user_id', $user->id)->update($studentData);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully!');
    }

    public function show($id)
    {
        $student = User::with('student')->findOrFail($id);
        return view('admin.students.show', compact('student'));
    }
}


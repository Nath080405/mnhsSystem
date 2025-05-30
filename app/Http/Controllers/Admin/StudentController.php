<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use App\Models\EventView;
use League\Csv\Reader;
use League\Csv\Writer;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Download the CSV template for student import
     */
    public function downloadTemplate()
    {
        $csv = Writer::createFromString('');
        
        // Add headers
        $csv->insertOne([
            'last_name',
            'first_name',
            'middle_name',
            'suffix',
            'email',
            'lrn',
            'street_address',
            'barangay',
            'municipality',
            'province',
            'phone',
            'birthdate',
            'gender'
        ]);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="student_import_template.csv"',
        ];

        return response($csv->toString(), 200, $headers);
    }

    /**
     * Import students from CSV file
     */
    public function importStudents(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240' // max 10MB
        ]);

        try {
            DB::beginTransaction();

            $csv = Reader::createFromPath($request->file('csv_file')->getPathname());
            $csv->setHeaderOffset(0);

            $records = $csv->getRecords();
            $importedCount = 0;
            $updatedCount = 0;
            $errors = [];

            foreach ($records as $index => $record) {
                try {
                    // Validate required fields
                    if (empty($record['last_name']) || empty($record['first_name']) || empty($record['email']) || empty($record['lrn'])) {
                        throw new \Exception('Required fields missing');
                    }

                    // Check if student exists with this LRN
                    $existingStudent = \App\Models\Student::where('lrn', $record['lrn'])->first();
                    
                    if ($existingStudent) {
                        // Update existing student
                        $user = User::find($existingStudent->user_id);
                        
                        // Update user record
                        $user->last_name = $record['last_name'];
                        $user->first_name = $record['first_name'];
                        $user->middle_name = $record['middle_name'] ?? $user->middle_name;
                        $user->suffix = $record['suffix'] ?? $user->suffix;
                        $user->email = $record['email'];
                        $user->save();

                        // Convert date format from MM/DD/YYYY to YYYY-MM-DD if birthdate is provided
                        $birthdate = null;
                        if (!empty($record['birthdate'])) {
                            try {
                                $date = \DateTime::createFromFormat('m/d/Y', $record['birthdate']);
                                if ($date) {
                                    $birthdate = $date->format('Y-m-d');
                                }
                            } catch (\Exception $e) {
                                // If date conversion fails, keep existing birthdate
                                $birthdate = $existingStudent->birthdate;
                            }
                        }

                        // Update student record using user_id
                        \App\Models\Student::where('user_id', $existingStudent->user_id)->update([
                            'street_address' => $record['street_address'] ?? $existingStudent->street_address,
                            'barangay' => $record['barangay'] ?? $existingStudent->barangay,
                            'municipality' => $record['municipality'] ?? $existingStudent->municipality,
                            'province' => $record['province'] ?? $existingStudent->province,
                            'phone' => $record['phone'] ?? $existingStudent->phone,
                            'birthdate' => $birthdate ?? $existingStudent->birthdate,
                            'gender' => $record['gender'] ?? $existingStudent->gender,
                            'grade_level' => $record['grade_level'] ?? $existingStudent->grade_level,
                            'section' => $record['section'] ?? $existingStudent->section
                        ]);

                        $updatedCount++;
                    } else {
                        // Create new student
                        // Check if email already exists
                        if (User::where('email', $record['email'])->exists()) {
                            throw new \Exception('Email already exists');
                        }

                        // Get the latest student ID and generate the next one
                        $latestStudent = \App\Models\Student::orderBy('student_id', 'desc')->first();
                        $nextId = $latestStudent ? intval(substr($latestStudent->student_id, 3)) + 1 : 1;
                        $studentId = 'STU' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
                        
                        // Generate temporary password based on student ID
                        $tempPassword = 'TEMP' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

                        // Create user record
                        $user = new User();
                        $user->last_name = $record['last_name'];
                        $user->first_name = $record['first_name'];
                        $user->middle_name = $record['middle_name'] ?? null;
                        $user->suffix = $record['suffix'] ?? null;
                        $user->email = $record['email'];
                        $user->username = $studentId;
                        $user->password = bcrypt($tempPassword);
                        $user->role = 'student';
                        $user->save();

                        // Create student record
                        $student = new \App\Models\Student();
                        $student->user_id = $user->id;
                        $student->student_id = $studentId;
                        $student->lrn = $record['lrn'];
                        $student->street_address = $record['street_address'] ?? null;
                        $student->barangay = $record['barangay'] ?? null;
                        $student->municipality = $record['municipality'] ?? null;
                        $student->province = $record['province'] ?? null;
                        $student->phone = $record['phone'] ?? null;
                        $student->birthdate = $record['birthdate'] ?? null;
                        $student->gender = $record['gender'] ?? null;
                        $student->grade_level = $record['grade_level'] ?? null;
                        $student->section = $record['section'] ?? null;
                        $student->status = 'active';
                        $student->save();

                        $importedCount++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            DB::commit();

            $message = [];
            if ($importedCount > 0) {
                $message[] = "Imported {$importedCount} new students";
            }
            if ($updatedCount > 0) {
                $message[] = "Updated {$updatedCount} existing students";
            }
            if (count($errors) > 0) {
                $message[] = "Encountered " . count($errors) . " errors: " . implode(', ', $errors);
            }

            return redirect()->route('admin.students.index')
                ->with('success', implode('. ', $message));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.students.index')
                ->with('error', 'Failed to import students: ' . $e->getMessage());
        }
    }
}


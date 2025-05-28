<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EventView;
use League\Csv\Reader;
use League\Csv\Writer;

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
        // Get the latest teacher ID and generate the next one
        $latestTeacher = Teacher::orderBy('user_id', 'desc')->first();
        $nextId = $latestTeacher ? intval(substr($latestTeacher->employee_id, 3)) + 1 : 1;
        $employeeId = 'TCH' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        // Generate temporary password
        $tempPassword = 'TEMP' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        // Create user record
        $user = new User();
        $user->last_name = $request->last_name;
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->suffix = $request->suffix;
        $user->email = $request->email;
        $user->username = $employeeId;
        $user->password = bcrypt($tempPassword);
        $user->role = 'teacher';
        $user->save();

        // Create teacher record
        $teacher = new Teacher();
        $teacher->user_id = $user->id;
        $teacher->employee_id = $employeeId;
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

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher added successfully! Employee ID: ' . $employeeId . '. Temporary password: ' . $tempPassword);
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
                // Soft delete all subjects
                $user->subjects()->delete();
            }

            // Delete related records
            if ($user->teacher) {
                // Delete event views
                EventView::where('user_id', $user->id)->delete();
                
                // Remove teacher from section if they are an adviser
                DB::table('sections')
                    ->where('adviser_id', $user->id)
                    ->update(['adviser_id' => null]);
                
                // Delete the teacher record using user_id
                Teacher::where('user_id', $user->id)->forceDelete();
            }

            // Delete the user record
            $user->forceDelete();

            DB::commit();
            return redirect()->route('admin.teachers.index')
                ->with('success', 'Teacher deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting teacher: ' . $e->getMessage());
            return redirect()->route('admin.teachers.index')
                ->with('error', 'Failed to delete teacher: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $teacher = User::with('teacher')->findOrFail($id);
        return view('admin.teachers.show', compact('teacher'));
    }

    /**
     * Download the CSV template for teacher import
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
            'street_address',
            'barangay',
            'municipality',
            'province',
            'phone',
            'birthdate',
            'gender',
            'date_joined'
        ]);

        // Add sample data
        $csv->insertOne([
            'Doe',
            'John',
            'Smith',
            'Jr',
            'john.doe@example.com',
            '123 Main St',
            'Sample Barangay',
            'Sample Municipality',
            'Sample Province',
            '09123456789',
            '01/01/1980',
            'Male',
            '05/27/2024'
        ]);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="teacher_import_template.csv"',
        ];

        return response($csv->toString(), 200, $headers);
    }

    /**
     * Import teachers from CSV file
     */
    public function importTeachers(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240' // max 10MB
        ]);

        try {
            DB::beginTransaction();

            $csv = Reader::createFromPath($request->file('csv_file')->getPathname());
            $csv->setHeaderOffset(0);

            // Validate required headers
            $requiredHeaders = [
                'last_name',
                'first_name',
                'email',
                'gender',
                'date_joined'
            ];

            $headers = $csv->getHeader();
            $missingHeaders = array_diff($requiredHeaders, $headers);

            if (!empty($missingHeaders)) {
                return redirect()->route('admin.teachers.index')
                    ->with('error', 'Invalid CSV format. Missing required columns: ' . implode(', ', $missingHeaders) . '. Please download the template and follow the correct format.');
            }

            $records = $csv->getRecords();
            $importedCount = 0;
            $updatedCount = 0;
            $errors = [];
            $rowNumber = 2; // Start from 2 since row 1 is header

            foreach ($records as $record) {
                try {
                    // Validate required fields
                    if (empty($record['last_name']) || empty($record['first_name']) || empty($record['email']) || 
                        empty($record['gender']) || empty($record['date_joined'])) {
                        throw new \Exception('Required fields missing');
                    }

                    // Validate email format
                    if (!filter_var($record['email'], FILTER_VALIDATE_EMAIL)) {
                        throw new \Exception('Invalid email format');
                    }

                    // Validate gender
                    if (!in_array($record['gender'], ['Male', 'Female', 'Other'])) {
                        throw new \Exception('Invalid gender value. Must be Male, Female, or Other');
                    }

                    // Validate date formats
                    $dateFields = ['birthdate', 'date_joined'];
                    foreach ($dateFields as $field) {
                        if (!empty($record[$field])) {
                            $date = \DateTime::createFromFormat('m/d/Y', $record[$field]);
                            if (!$date || $date->format('m/d/Y') !== $record[$field]) {
                                throw new \Exception("Invalid {$field} format. Must be MM/DD/YYYY");
                            }
                        }
                    }

                    // Check if email already exists
                    $existingUser = User::where('email', $record['email'])->first();
                    
                    if ($existingUser) {
                        // Update existing teacher
                        $user = $existingUser;
                        
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
                            $date = \DateTime::createFromFormat('m/d/Y', $record['birthdate']);
                            $birthdate = $date->format('Y-m-d');
                        }

                        // Convert date_joined format
                        $dateJoined = null;
                        if (!empty($record['date_joined'])) {
                            $date = \DateTime::createFromFormat('m/d/Y', $record['date_joined']);
                            $dateJoined = $date->format('Y-m-d');
                        }

                        // Update teacher record
                        if ($user->teacher) {
                            $user->teacher->update([
                                'street_address' => $record['street_address'] ?? $user->teacher->street_address,
                                'barangay' => $record['barangay'] ?? $user->teacher->barangay,
                                'municipality' => $record['municipality'] ?? $user->teacher->municipality,
                                'province' => $record['province'] ?? $user->teacher->province,
                                'birthdate' => $birthdate ?? $user->teacher->birthdate,
                                'phone' => $record['phone'] ?? $user->teacher->phone,
                                'gender' => $record['gender'],
                                'date_joined' => $dateJoined,
                                'status' => 'active'
                            ]);
                        } else {
                            // Create teacher record if it doesn't exist
                            $teacher = new Teacher();
                            $teacher->user_id = $user->id;
                            $teacher->employee_id = $user->username;
                            $teacher->street_address = $record['street_address'] ?? null;
                            $teacher->barangay = $record['barangay'] ?? null;
                            $teacher->municipality = $record['municipality'] ?? null;
                            $teacher->province = $record['province'] ?? null;
                            $teacher->birthdate = $birthdate;
                            $teacher->phone = $record['phone'] ?? null;
                            $teacher->gender = $record['gender'];
                            $teacher->date_joined = $dateJoined;
                            $teacher->status = 'active';
                            $teacher->save();
                        }

                        $updatedCount++;
                    } else {
                        // Get the latest teacher ID and generate the next one
                        $latestTeacher = Teacher::orderBy('user_id', 'desc')->first();
                        $nextId = $latestTeacher ? intval(substr($latestTeacher->employee_id, 3)) + 1 : 1;
                        $employeeId = 'TCH' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

                        // Generate temporary password
                        $tempPassword = 'TEMP' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

                        // Create user record
                        $user = new User();
                        $user->last_name = $record['last_name'];
                        $user->first_name = $record['first_name'];
                        $user->middle_name = $record['middle_name'] ?? null;
                        $user->suffix = $record['suffix'] ?? null;
                        $user->email = $record['email'];
                        $user->username = $employeeId;
                        $user->password = bcrypt($tempPassword);
                        $user->role = 'teacher';
                        $user->save();

                        // Convert date format
                        $birthdate = null;
                        if (!empty($record['birthdate'])) {
                            $date = \DateTime::createFromFormat('m/d/Y', $record['birthdate']);
                            $birthdate = $date->format('Y-m-d');
                        }

                        // Convert date_joined format
                        $dateJoined = null;
                        if (!empty($record['date_joined'])) {
                            $date = \DateTime::createFromFormat('m/d/Y', $record['date_joined']);
                            $dateJoined = $date->format('Y-m-d');
                        }

                        // Create teacher record
                        $teacher = new Teacher();
                        $teacher->user_id = $user->id;
                        $teacher->employee_id = $employeeId;
                        $teacher->street_address = $record['street_address'] ?? null;
                        $teacher->barangay = $record['barangay'] ?? null;
                        $teacher->municipality = $record['municipality'] ?? null;
                        $teacher->province = $record['province'] ?? null;
                        $teacher->birthdate = $birthdate;
                        $teacher->phone = $record['phone'] ?? null;
                        $teacher->gender = $record['gender'];
                        $teacher->date_joined = $dateJoined;
                        $teacher->status = 'active';
                        $teacher->save();

                        $importedCount++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                }
                $rowNumber++;
            }

            if (count($errors) > 0) {
                DB::rollBack();
                return redirect()->route('admin.teachers.index')
                    ->with('error', 'Import failed with the following errors:<br>' . implode('<br>', $errors));
            }

            DB::commit();

            $message = [];
            if ($importedCount > 0) {
                $message[] = "Imported {$importedCount} new teachers";
            }
            if ($updatedCount > 0) {
                $message[] = "Updated {$updatedCount} existing teachers";
            }

            return redirect()->route('admin.teachers.index')
                ->with('success', implode('. ', $message));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.teachers.index')
                ->with('error', 'Failed to import teachers: ' . $e->getMessage());
        }
    }
}
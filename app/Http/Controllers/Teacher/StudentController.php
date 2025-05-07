<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student; // Assuming you have a separate Student model
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'student')->paginate(4);
        return view('student.index', compact('students'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'class' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = new User();
        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = 'student';
        $user->save();

        // If you have a separate Student model and table for extra details
        Student::create([
            'user_id' => $user->id,
            'class' => $request->class,
            'birthdate' => $request->birthdate,
        ]);

        return redirect()->route('student.index')->with('success', 'Student added successfully!');
    }

   
    public function edit($id)
    {
        $student = User::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $student = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $student->update($validated);

        return redirect()->route('student.index')->with('success', 'Student updated successfully!');
    }

    public function destroy($id)
    {
        $student = User::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
    

}

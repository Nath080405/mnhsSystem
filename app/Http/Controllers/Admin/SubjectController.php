<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('teacher')->paginate(10);
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.subjects.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:subjects,code',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1',
            'teacher_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);

        Subject::create($validated);

        return redirect()->route('admin.subjects.index')->with('success', 'Subject added successfully!');
    }

    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.subjects.edit', compact('subject', 'teachers'));
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:subjects,code,' . $id,
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1',
            'teacher_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);

        $subject->update($validated);

        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated successfully!');
    }

    public function destroy($id)
    {
        try {
            $subject = Subject::findOrFail($id);
            $subject->delete();

            return redirect()->route('admin.subjects.index')
                ->with('success', 'Subject deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.subjects.index')
                ->with('error', 'Failed to delete subject. Please try again.');
        }
    }

    public function show($id)
    {
        $subject = Subject::with('teacher')->findOrFail($id);
        return view('admin.subjects.show', compact('subject'));
    }
} 
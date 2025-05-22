<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $selectedGrade = $request->get('grade_level');
        $sections = Section::with('adviser')
            ->withCount('students')
            ->orderBy('grade_level')
            ->orderBy('name');
        
        if ($selectedGrade) {
            $sections->where('grade_level', $selectedGrade);
        }
        
        $sections = $sections->get();
        $gradeLevels = ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'];

        return view('admin.sections.index', compact('sections', 'gradeLevels', 'selectedGrade'));
    }

    public function create()
    {
        $teachers = User::where('role', 'teacher')->orderBy('last_name')->orderBy('first_name')->get();
        $selectedGrade = request('grade_level');
        return view('admin.sections.create', compact('teachers', 'selectedGrade'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|string|in:Grade 7,Grade 8,Grade 9,Grade 10,Grade 11,Grade 12',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'adviser_id' => 'required|exists:users,id',
        ]);

        // Check if a section with the same name already exists in this grade level
        $existingSection = Section::where('grade_level', $validated['grade_level'])
            ->where('name', $validated['name'])
            ->first();

        if ($existingSection) {
            return back()
                ->withInput()
                ->withErrors(['name' => 'A section with this name already exists in ' . $validated['grade_level']]);
        }

        $section = Section::create($validated);

        return redirect()
            ->route('admin.sections.index', ['grade_level' => $section->grade_level])
            ->with('success', 'Section created successfully.');
    }

    public function edit(Section $section)
    {
        $teachers = User::where('role', 'teacher')->orderBy('last_name')->orderBy('first_name')->get();
        return view('admin.sections.edit', compact('section', 'teachers'));
    }

    public function update(Request $request, Section $section)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|string|in:Grade 7,Grade 8,Grade 9,Grade 10,Grade 11,Grade 12',
            'description' => 'nullable|string',
            'adviser_id' => 'nullable|exists:users,id',
        ]);

        $section->update($request->all());

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section updated successfully.');
    }

    public function destroy(Section $section)
    {
        $section->delete();

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section deleted successfully.');
    }
}

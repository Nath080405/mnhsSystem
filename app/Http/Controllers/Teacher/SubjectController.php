<?php
namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    /**
     * Display a listing of the subjects.
     */
    public function index()
    {
        // Fetch subjects where the teacher is the owner (authenticated teacher)
        $subjects = Subject::where('teacher_id', Auth::id())->get();
        return view('teachers.subject.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new subject.
     */
    public function create()
    {
        return view('teachers.subject.create');
    }

    /**
     * Store a newly created subject in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:subjects',
            'description' => 'nullable|string',
        ]);

        $validated['teacher_id'] = Auth::id(); // if needed
        $validated['status'] = 'active'; // default

        $subject = Subject::create($validated);

        return response()->json($subject);
    }

    public function edit($id)
    {
        // Ensure the teacher can only edit their own subjects
        $subject = Subject::where('id', $id)
                          ->where('teacher_id', Auth::id()) // Check teacher ownership
                          ->firstOrFail();

        return view('teachers.subject.create', compact('subject'));
    }

    public function update(Request $request, $id)
    {
        // Ensure the teacher can only update their own subjects
        $subject = Subject::where('id', $id)
                          ->where('teacher_id', Auth::id()) // Check teacher ownership
                          ->firstOrFail();

        // Validate updated subject data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code,' . $id,
            'description' => 'nullable|string',
        ]);

        $subject->update($validated);

        return redirect()->route('teachers.subject.index')->with('success', 'Subject updated successfully!');
    }

    /**
     * Display the specified subject.
     */
    public function show($id)
    {
        // Ensure the teacher can only view their own subjects
        $subject = Subject::with('teacher')
                          ->where('id', $id)
                          ->where('teacher_id', Auth::id()) // Check teacher ownership
                          ->firstOrFail();

        return view('teachers.subject.show', compact('subject'));
    }

    public function destroy($id)
    {
        // Ensure the teacher can only delete their own subjects
        $subject = Subject::where('id', $id)
                          ->where('teacher_id', Auth::id()) // Check teacher ownership
                          ->firstOrFail();

        $subject->delete();

        return redirect()->route('teachers.subject.index')->with('success', 'Subject deleted successfully.');
    }
}

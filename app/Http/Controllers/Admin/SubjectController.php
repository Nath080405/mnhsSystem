<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('teacher')->paginate(10);
        \Log::info('Subjects with teachers:', ['subjects' => $subjects->toArray()]);
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $teachers = User::where('role', 'teacher')->get();
        \Log::info('Teachers found:', ['count' => $teachers->count(), 'teachers' => $teachers->toArray()]);
        return view('admin.subjects.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        \Log::info('Received request data:', $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:subjects,code',
            'description' => 'nullable|string',
            'teacher_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive',
            'schedules' => 'array',
            'schedules.*.day' => 'required|string',
            'schedules.*.start_time' => 'required',
            'schedules.*.end_time' => 'required',
        ]);

        \Log::info('Validated data:', $validated);

        DB::beginTransaction();
        try {
            $subject = Subject::create($validated);

            // Handle schedules
            if ($request->has('schedules')) {
                \Log::info('Processing schedules:', $request->schedules);
                foreach ($request->schedules as $schedule) {
                    if (!isset($schedule['_destroy'])) {
                        $subject->schedules()->create([
                            'day' => $schedule['day'],
                            'start_time' => $schedule['start_time'],
                            'end_time' => $schedule['end_time'],
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.subjects.index')->with('success', 'Subject added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating subject:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to create subject. Please try again.');
        }
    }

    public function edit($id)
    {
        $subject = Subject::with('schedules')->findOrFail($id);
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
            'teacher_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive',
            'schedules' => 'array',
            'schedules.*.day' => 'required|string',
            'schedules.*.start_time' => 'required',
            'schedules.*.end_time' => 'required',
        ]);

        $subject->update($validated);

        // Handle schedules
        if ($request->has('schedules')) {
            // Delete all existing schedules
            $subject->schedules()->delete();

            // Create new schedules
            foreach ($request->schedules as $schedule) {
                if (!isset($schedule['_destroy'])) {
                    $subject->schedules()->create([
                        'day' => $schedule['day'],
                        'start_time' => $schedule['start_time'],
                        'end_time' => $schedule['end_time'],
                    ]);
                }
            }
        }

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
        \Log::info('Subject with teacher:', ['subject' => $subject->toArray()]);
        return view('admin.subjects.show', compact('subject'));
    }
} 
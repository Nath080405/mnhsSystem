<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\User;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $selectedFilter = $request->get('filter');
        $selectedGrade = $request->get('grade');
        
        $query = Subject::with(['teacher', 'schedules'])
            ->whereNull('parent_id')  // Only get parent subjects (subject labels)
            ->orderBy('created_at', 'desc');

        if ($selectedFilter === 'active') {
            $query->where('status', 'active');
        } elseif ($selectedFilter === 'inactive') {
            $query->where('status', 'inactive');
        }

        if ($selectedGrade) {
            $query->where('grade_level', $selectedGrade);
        }

        $subjects = $query->get();

        // Get unique grade levels for the filter
        $gradeLevels = Subject::whereNull('parent_id')  // Only get grade levels from parent subjects
            ->select('grade_level')
            ->distinct()
            ->orderBy('grade_level')
            ->pluck('grade_level');

        return view('admin.subjects.subjectlist.index', compact('subjects', 'selectedFilter', 'selectedGrade', 'gradeLevels'));
    }

    public function create()
    {
        $teachers = User::where('role', 'teacher')->get();
        $gradeLevel = request('grade_level');
        
        // Filter sections based on grade level
        $sections = Section::where('status', 'active')
            ->when($gradeLevel, function($query) use ($gradeLevel) {
                return $query->where('grade_level', $gradeLevel);
            })
            ->orderBy('grade_level')
            ->orderBy('name')
            ->get();
            
        \Log::info('Teachers found:', ['count' => $teachers->count(), 'teachers' => $teachers->toArray()]);
        return view('admin.subjects.subjectlist.create', compact('teachers', 'sections', 'gradeLevel'));
    }

    public function store(Request $request)
    {
        \Log::info('Received request data:', $request->all());

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'grade_level' => 'required|string',
                'parent_id' => 'nullable|exists:subjects,id',
                'teacher_id' => 'nullable|exists:users,id',
                'section_id' => [
                    'nullable',
                    'exists:sections,id',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($value && $request->parent_id) {
                            // Check if there's already a subject with the same parent in this section
                            $existingSubject = Subject::where('parent_id', $request->parent_id)
                                ->where('section_id', $value)
                                ->first();
                            
                            if ($existingSubject) {
                                $parentSubject = Subject::find($request->parent_id);
                                $fail("A subject from '{$parentSubject->name}' is already assigned to this section.");
                            }
                        }
                    },
                ],
                'start_time' => 'nullable',
                'end_time' => 'nullable',
            ]);

            \Log::info('Validated data:', $validated);

            DB::beginTransaction();
            
            // Generate a unique code based on the parent subject label and grade level
            $parentId = $request->input('parent_id');
            if ($parentId) {
                // Get the parent subject (label)
                $parentSubject = Subject::findOrFail($parentId);
                $subjectPrefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $parentSubject->name), 0, 3));
            } else {
                // For subject labels, use their own name
                $subjectPrefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $validated['name']), 0, 3));
            }
            
            $gradeLevel = preg_replace('/[^0-9]/', '', $validated['grade_level']);
            
            // Get the latest subject code for this prefix and grade level
            $latestSubject = Subject::where('code', 'like', $subjectPrefix . $gradeLevel . '%')
                ->orderBy('code', 'desc')
                ->first();
            
            $sequence = 1;
            if ($latestSubject) {
                // Extract the sequence number from the latest code and increment
                $lastSequence = intval(substr($latestSubject->code, -5));
                $sequence = $lastSequence + 1;
            }
            
            $code = $subjectPrefix . $gradeLevel . str_pad($sequence, 5, '0', STR_PAD_LEFT);
            
            $subject = Subject::create([
                'name' => $validated['name'],
                'code' => $code,
                'grade_level' => $validated['grade_level'],
                'status' => 'active',
                'description' => '',
                'parent_id' => $parentId,
                'teacher_id' => $validated['teacher_id'] ?? null,
                'section_id' => $validated['section_id'] ?? null,
            ]);

            // Only create schedules if start_time and end_time are provided
            if ($request->filled(['start_time', 'end_time'])) {
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                foreach ($days as $day) {
                    $subject->schedules()->create([
                        'day' => $day,
                        'start_time' => $validated['start_time'],
                        'end_time' => $validated['end_time'],
                    ]);
                }
            }

            DB::commit();

            // Determine where to redirect based on the previous URL
            $previousUrl = url()->previous();
            
            if (str_contains($previousUrl, '/grade/')) {
                // If coming from grade view, redirect back to grade view
                return redirect()->route('admin.subjects.grade', $validated['grade_level'])
                    ->with('success', 'Subject added successfully!');
            } elseif (isset($validated['parent_id'])) {
                // If adding a child subject, redirect to label subjects view
                return redirect()->route('admin.subjects.label.subjects', $validated['parent_id'])
                    ->with('success', 'Subject added successfully!');
            } else {
                // Default redirect to subject list
                return redirect()->route('admin.subjects.index')
                    ->with('success', 'Subject added successfully!');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error:', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating subject:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to create subject: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $subject = Subject::with(['teacher', 'schedules'])->findOrFail($id);
        $teachers = User::where('role', 'teacher')->get();
        
        // Filter sections based on subject's grade level
        $sections = Section::where('status', 'active')
            ->where('grade_level', $subject->grade_level)
            ->orderBy('name')
            ->get();
        
        // Get the first schedule's time if it exists
        $firstSchedule = $subject->schedules->first();
        $startTime = $firstSchedule ? $firstSchedule->start_time : '';
        $endTime = $firstSchedule ? $firstSchedule->end_time : '';
        
        \Log::info('Subject being edited:', [
            'subject' => $subject->toArray(),
            'schedules' => $subject->schedules->toArray(),
            'start_time' => $startTime,
            'end_time' => $endTime
        ]);
        
        return view('admin.subjects.subjectlist.edit', compact('subject', 'teachers', 'sections', 'startTime', 'endTime'));
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'teacher_id' => 'nullable|exists:users,id',
            'section_id' => [
                'nullable',
                'exists:sections,id',
                function ($attribute, $value, $fail) use ($subject, $request) {
                    if ($value && $subject->parent_id) {
                        // Check if there's already a subject with the same parent in this section
                        // Exclude the current subject from the check
                        $existingSubject = Subject::where('parent_id', $subject->parent_id)
                            ->where('section_id', $value)
                            ->where('id', '!=', $subject->id)
                            ->first();
                        
                        if ($existingSubject) {
                            $parentSubject = Subject::find($subject->parent_id);
                            $fail("A subject from '{$parentSubject->name}' is already assigned to this section.");
                        }
                    }
                },
            ],
            'start_time' => 'nullable',
            'end_time' => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            // Update the subject with all validated fields
            $subject->update([
                'name' => $validated['name'],
                'teacher_id' => $validated['teacher_id'],
                'section_id' => $validated['section_id'],
                'status' => $subject->status,
                'grade_level' => $subject->grade_level
            ]);

            // Update schedules if time is provided
            if ($request->filled(['start_time', 'end_time'])) {
                // Delete existing schedules
                $subject->schedules()->delete();
                
                // Create new schedules for each day
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                foreach ($days as $day) {
                    $subject->schedules()->create([
                        'day' => $day,
                        'start_time' => $validated['start_time'],
                        'end_time' => $validated['end_time'],
                    ]);
                }
            }

            DB::commit();

            // Determine where to redirect based on the previous URL and subject type
            $previousUrl = url()->previous();
            
            if (str_contains($previousUrl, '/grade/')) {
                // If coming from grade view, redirect back to grade view
                return redirect()->route('admin.subjects.grade', $subject->grade_level)
                    ->with('success', 'Subject updated successfully!');
            } elseif ($subject->parent_id) {
                // If editing a child subject, redirect to label subjects view
                return redirect()->route('admin.subjects.label.subjects', $subject->parent_id)
                    ->with('success', 'Subject updated successfully!');
            } else {
                // Default redirect to subject list
                return redirect()->route('admin.subjects.index')
                    ->with('success', 'Subject updated successfully!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating subject:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to update subject: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $subject = Subject::findOrFail($id);
            $parentId = $subject->parent_id;
            $gradeLevel = $subject->grade_level;
            $subject->delete();

            // Determine where to redirect based on the previous URL
            $previousUrl = url()->previous();
            
            if (str_contains($previousUrl, '/grade/')) {
                // If coming from grade view, redirect back to grade view
                return redirect()->route('admin.subjects.grade', $gradeLevel)
                    ->with('success', 'Subject deleted successfully');
            } elseif ($parentId) {
                // If deleting a child subject, redirect to label subjects view
                return redirect()->route('admin.subjects.label.subjects', $parentId)
                    ->with('success', 'Subject deleted successfully');
            } else {
                // Default redirect to subject list
                return redirect()->route('admin.subjects.index')
                    ->with('success', 'Subject deleted successfully');
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting subject:', ['error' => $e->getMessage()]);
            
            // Determine where to redirect based on the previous URL
            $previousUrl = url()->previous();
            
            if (str_contains($previousUrl, '/grade/')) {
                return redirect()->route('admin.subjects.grade', $gradeLevel)
                    ->with('error', 'Failed to delete subject. Please try again.');
            } elseif ($parentId) {
                return redirect()->route('admin.subjects.label.subjects', $parentId)
                    ->with('error', 'Failed to delete subject. Please try again.');
            } else {
                return redirect()->route('admin.subjects.index')
                    ->with('error', 'Failed to delete subject. Please try again.');
            }
        }
    }

    public function show($id)
    {
        $subject = Subject::with('teacher')->findOrFail($id);
        \Log::info('Subject with teacher:', ['subject' => $subject->toArray()]);
        return view('admin.subjects.show', compact('subject'));
    }

    public function plan()
    {
        // Get subjects grouped by grade level
        $juniorHighSubjects = Subject::where('grade_level', 'like', 'Grade%')
            ->where('grade_level', 'not like', 'Grade 11%')
            ->where('grade_level', 'not like', 'Grade 12%')
            ->orderBy('grade_level')
            ->get();

        $seniorHighSubjects = Subject::where('grade_level', 'like', 'Grade 11%')
            ->orWhere('grade_level', 'like', 'Grade 12%')
            ->orderBy('grade_level')
            ->get();

        return view('admin.subjects.subjectplan.plan', compact('juniorHighSubjects', 'seniorHighSubjects'));
    }

    public function gradeSubjects($grade)
    {
        $subjects = Subject::where('grade_level', $grade)
            ->whereNull('parent_id')  // Only get parent subjects (subject labels)
            ->orderBy('name')
            ->get();
        
        $teachers = User::where('role', 'teacher')->get();
        
        return view('admin.subjects.subjectplan.grade', compact('subjects', 'grade', 'teachers'));
    }

    public function labelSubjects($id)
    {
        $subjectLabel = Subject::findOrFail($id);
        $subjects = Subject::with(['teacher', 'section'])
            ->where('parent_id', $id)
            ->orderBy('name')
            ->get();
        
        return view('admin.subjects.subjectlist.label_subjects', compact('subjectLabel', 'subjects'));
    }
} 
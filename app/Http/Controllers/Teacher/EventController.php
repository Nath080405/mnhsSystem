<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    // List events visible to teachers
    public function index()
    {
        $events = Event::where(function ($query) {
            $query->where('visibility', 'All')
                  ->orWhere('visibility', 'Teachers');
        })
        ->orderBy('event_date', 'desc')
        ->paginate(10);

        return view('teachers.event.index', compact('events'));
    }

    // Show the create event form
    public function create()
    {
        return view('teachers.event.create');
    }

    // Store a new event
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'event_date'  => 'required|date',
            'event_time'  => 'nullable|date_format:H:i',
            'location'    => 'required|string|max:255',
            'visibility'  => 'required|string|in:All,Teachers,Students',
        ]);

        $event = new Event($validated);
        $event->teacher_id = auth()->id();
        $event->save();

        return redirect()->route('teachers.event.index')->with('success', 'Event created successfully.');
    }

    // Show the edit form for an event owned by the logged-in teacher
    public function edit($id)
    {
        $event = Event::where('id', $id)->where('teacher_id', auth()->id())->firstOrFail();
        return view('teachers.event.edit', compact('event'));
    }

    // Update an existing event
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'event_date'  => 'required|date',
            'event_time'  => 'nullable|date_format:H:i',
            'location'    => 'required|string|max:255',
            'visibility'  => 'required|string|in:All,Teachers,Students',
        ]);

        $event = Event::where('id', $id)->where('teacher_id', auth()->id())->firstOrFail();
        $event->update($validated);

        return redirect()->route('teachers.event.index')->with('success', 'Event updated successfully.');
    }

    // Show a single event
    public function showEvent($id)
    {
        $event = Event::where('id', $id)
            ->where(function ($query) {
                $query->where('visibility', 'All')
                      ->orWhere('visibility', 'Teachers');
            })
            ->firstOrFail();

        return view('teachers.event.show', compact('event'));
    }

    // (Optional) Delete an event owned by the teacher
    public function destroy($id)
    {
        $event = Event::where('id', $id)->where('teacher_id', auth()->id())->firstOrFail();
        $event->delete();

        return redirect()->route('teachers.event.index')->with('success', 'Event deleted successfully.');
    }
}

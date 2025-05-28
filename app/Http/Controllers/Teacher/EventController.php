<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    /**
     * Display a list of events visible to the teacher (All + Teachers).
     */
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

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        return view('teachers.event.create');
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'event_date'  => 'required|date',
            'event_time'  => 'nullable|date_format:H:i',
            'location'    => 'required|string|max:255',
            'visibility'  => 'required|in:All,Teachers,Students',
        ]);

        $event = new Event($validated);
        $event->teacher_id = auth()->id();
        $event->save();

        return redirect()->route('teachers.event.index')->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified event if visible to teachers.
     */
    public function showEvent($id)
    {
        $event = Event::where('event_id', $id)
                      ->where(function ($query) {
                          $query->where('visibility', 'All')
                                ->orWhere('visibility', 'Teachers');
                      })
                      ->firstOrFail();

        return view('teachers.event.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event owned by the teacher.
     */
    public function edit($id)
    {
        $event = Event::where('event_id', $id)
                      ->where('teacher_id', auth()->id())
                      ->firstOrFail();

        return view('teachers.event.edit', compact('event'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'event_date'  => 'required|date',
            'event_time'  => 'nullable|date_format:H:i',
            'location'    => 'required|string|max:255',
            'visibility'  => 'required|in:All,Teachers,Students',
        ]);

        $event = Event::where('event_id', $id)
                      ->where('teacher_id', auth()->id())
                      ->firstOrFail();

        $event->update($validated);

        return redirect()->route('teachers.event.index')->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified event from storage (must belong to current teacher).
     */
    public function destroy($id)
    {
        $event = Event::where('event_id', $id)
                      ->where('created_by', auth()->id())
                      ->firstOrFail();

        $event->delete();

        return redirect()->route('teachers.event.index')->with('success', 'Event deleted successfully.');
    }
}

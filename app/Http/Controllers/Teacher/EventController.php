<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventView;

class EventController extends Controller
{
    /**
     * Display a list of events visible to the teacher (All + Teachers).
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get events visible to teachers
        $events = Event::where(function ($query) {
                $query->where('visibility', 'All')
                      ->orWhere('visibility', 'Teachers');
            })
            ->orderBy('event_date', 'desc')
            ->get();

        // Get events that user has already viewed
        $viewedEventIds = EventView::where('user_id', $user->id)
            ->pluck('event_id')
            ->toArray();

        // Mark all events as viewed
        foreach ($events as $event) {
            EventView::updateOrCreate(
                ['user_id' => $user->id, 'event_id' => $event->event_id],
                ['viewed_at' => now()]
            );
        }

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
        $user = auth()->user();
        $event = Event::where('event_id', $id)
                      ->where(function ($query) {
                          $query->where('visibility', 'All')
                                ->orWhere('visibility', 'Teachers');
                      })
                      ->firstOrFail();

        // Mark event as viewed
        EventView::updateOrCreate(
            ['user_id' => $user->id, 'event_id' => $event->event_id],
            ['viewed_at' => now()]
        );

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

    /**
     * Archive the specified event.
     */
    public function archive($id)
    {
        $event = Event::where('event_id', $id)
                      ->where('created_by', auth()->id())
                      ->firstOrFail();

        $event->update(['is_archived' => true]);

        return redirect()->route('teachers.event.index')->with('success', 'Event archived successfully.');
    }
}

<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    // Display the list of events
    public function index()
    {
        // Fetch events for the logged-in teacher
        $events = Event::where('teacher_id', auth()->id())->paginate(10);    
        // Pass the events to the view
        return view('teachers.event.index', compact('events'));
    }
    // Show the form to create a new event
    public function create()
    {
        return view('teachers.event.create'); // Ensure this view exists
    }

    public function edit($id)
    {
        // Find the event by ID
        $event = Event::findOrFail($id);
    
        // Return the edit view with the event data
        return view('teachers.event.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable|date_format:H:i',
            'location' => 'required|string|max:255',
            'venue_image' => 'nullable|image|max:2048',
        ]);
    
        $event = Event::findOrFail($id); // Fetch the event by ID
        $event->update($validated); // Update the event with validated data
    
        return redirect()->route('teachers.event.index')->with('success', 'Event updated successfully.');
    }


}
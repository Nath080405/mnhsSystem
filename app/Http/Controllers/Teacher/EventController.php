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
        $events = Event::where('teacher_id', auth()->id())->get();
    
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
        $event = Event::findOrFail($id);
        return view('teachers.event.edit', compact('event')); // Render an edit form
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $event->update($request->all());
        return redirect()->route('teachers.event.index')->with('success', 'Event updated successfully');
    }
 

}
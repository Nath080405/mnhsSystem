<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // Display all events
    public function index()
    {
        $events = Event::all(); // Fetch all events
        return view('teachers.event', compact('events'));
    }

    // Show the form to create an event
    public function create()
    {
        return view('teachers.index'); // Assuming the form is in index.blade.php
    }

    // Store the event in the database
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
        ]);

        Event::create($request->all()); // Save the event

        return redirect()->route('teachers.event.index')->with('success', 'Event created successfully!');
    }
}

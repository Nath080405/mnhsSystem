<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewEventNotification;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('creator')->latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required|string|max:255',
            'visibility' => 'required|in:All,Students,Teachers',
            'status' => 'required|in:Upcoming,Completed,Cancelled'
        ]);

        $validated['created_by'] = Auth::id();

        $event = Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully.');
    }

    public function show($id)
    {
        $event = Event::with('creator')->findOrFail($id);
        return view('admin.events.show', compact('event'));
    }

    public function edit($id)
    {
        $event = Event::with('creator')->findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        try {
            $event = Event::findOrFail($id);
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'event_date' => 'required|date',
                'start_time' => 'required',
                'end_time' => 'required',
                'location' => 'required|string|max:255',
                'visibility' => 'required|in:All,Students,Teachers',
                'status' => 'required|in:Upcoming,Completed,Cancelled'
            ]);

            // Ensure created_by remains unchanged
            $validated['created_by'] = $event->created_by;

            $event->update($validated);

            return redirect()->route('admin.events.index')
                ->with('success', 'Event updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update event. Please try again.');
        }
    }

    public function destroy($id)
    {
        $event = Event::where('event_id', $id)
                      ->where('created_by', auth()->id())
                      ->firstOrFail();

        $event->delete();

        // If AJAX, return JSON
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        // Otherwise, redirect
        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }
} 
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventsController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('event_date', 'desc')->get();
        return view('students.events', compact('events'));
    }

    public function myEvents()
    {
        $events = Event::orderBy('event_date', 'desc')->get();
        return view('students.my-events', compact('events'));
    }
}

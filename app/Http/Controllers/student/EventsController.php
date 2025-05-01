<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function index()
    {
        return view('students.events');
    }

    public function myEvents()
    {
        return view('students.my-events');
    }
}

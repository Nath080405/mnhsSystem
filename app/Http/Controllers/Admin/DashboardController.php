<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subject;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get total users count by role
        $stats['users'] = User::count();
        $stats['students'] = User::where('role', 'student')->count();
        $stats['teachers'] = User::where('role', 'teacher')->count();
        $stats['admins'] = User::where('role', 'admin')->count();
        
        // Get total subjects count
        $stats['subjects'] = Subject::count();
        
        // Get total events count
        $stats['events'] = Event::count();

        // Get student status distribution
        $studentStatuses = \App\Models\Student::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status => $item->count];
            });

        return view('admin.dashboard', compact('stats', 'studentStatuses'));
    }
} 
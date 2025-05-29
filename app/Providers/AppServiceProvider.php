<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\EventView;
use App\Models\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share new events count with student sidebar
        View::composer('layouts.studentSidebar', function ($view) {
            if (Auth::check() && Auth::user()->role === 'student') {
                $user = Auth::user();
                $viewedEventIds = EventView::where('user_id', $user->id)
                    ->pluck('event_id')
                    ->toArray();
                
                $newEventsCount = Event::where(function($query) {
                    $query->where('visibility', 'All')
                          ->orWhere('visibility', 'Students');
                })->whereNotIn('event_id', $viewedEventIds)->count();
                
                $view->with('newEventsCount', $newEventsCount);
            }
        });

        // Share new events count with teacher sidebar
        View::composer('layouts.teacherSidebar', function ($view) {
            if (Auth::check() && Auth::user()->role === 'teacher') {
                $user = Auth::user();
                $viewedEventIds = EventView::where('user_id', $user->id)
                    ->pluck('event_id')
                    ->toArray();
                
                $newEventsCount = Event::where(function($query) {
                    $query->where('visibility', 'All')
                          ->orWhere('visibility', 'Teachers');
                })->whereNotIn('event_id', $viewedEventIds)->count();
                
                $view->with('newEventsCount', $newEventsCount);
            }
        });
    }
}

@extends('layouts.teacherApp')

@section('content')
<div class="bg-light" style="min-height: 100vh;">
    <div class="container py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <p class="mb-1 text-muted small">Overview</p>
                <h3 class="mb-0 fw-bold text-primary">My Events</h3>
            </div>
            <form action="/search" method="GET" class="d-flex gap-2" style="height: 38px;">
                <input type="text" name="search" class="form-control" placeholder="Search..." style="width: 220px;">
                <button type="submit" class="btn btn-outline-dark">Search</button>
            </form>
        </div>

        <!-- Greeting Section -->
        <div class="p-4 rounded bg-white shadow-sm mb-4 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <img src="{{ asset('images/teacher.png') }}" alt="Teacher Avatar" class="img-fluid me-4" style="max-width: 120px;">
                <div>
                    @auth
                        <h4 class="text-primary mb-1">Hello, {{ Auth::user()->name ?? 'Teacher' }} 👋</h4>
                        <p class="text-muted mb-0">Here's a quick look at your events.</p>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif

        <!-- Events Cards Section -->
        <div class="d-flex flex-column gap-3 mt-2">
            @forelse($events as $event)
                <div class="card shadow-sm border-0 w-100" style="background: linear-gradient(145deg, #ffe5ec, #fcd0e4); border-left: 6px solid #ff66a3;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                    <h5 class="0fw-bold text-secondary mb-">{{ $event->title }}</h5>
                                <p class="text-dark small mb-2">{{ Str::limit($event->description, 100, '...') }}</p>
                            </div>
                            <div class="d-flex flex-column align-items-end">
                                <span class="badge {{ $event->status === 'active' ? 'bg-success' : 'bg-secondary' }} px-3 py-2 mb-2">
                                    {{ ucfirst($event->status) }}
                                </span>
                                @if($event->status === 'active')
                                    <form action="{{ route('teachers.event.complete', $event->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-check-circle me-1"></i>Mark as Completed
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        
                        <div class="event-details d-flex flex-wrap gap-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-calendar-event text-primary me-2"></i>
                                <span class="text-dark">
                                    @php
                                        $eventDate = \Carbon\Carbon::parse($event->event_date);
                                        echo $eventDate->format('M d, Y');
                                    @endphp
                                </span>
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock text-primary me-2"></i>
                                <span class="text-dark">
                                    @php
                                        $startTime = \Carbon\Carbon::parse($event->event_time);
                                        $endTime = \Carbon\Carbon::parse($event->end_time);
                                        echo $startTime->format('h:i A') . ' - ' . $endTime->format('h:i A');
                                    @endphp
                                </span>
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <i class="bi bi-geo-alt text-primary me-2"></i>
                                <span class="text-dark">{{ $event->location }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="bi bi-calendar-x" style="font-size: 3rem;"></i>
                    <p class="mt-2 mb-0">No events found.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>


<style>
.event-details {
    font-size: 0.9rem;
}
.event-details i {
    font-size: 1.1rem;
}
.badge {
    font-weight: 500;
    font-size: 0.8rem;
}

/* Card hover effects */
.card {
    transition: all 0.3s ease;
    transform: translateY(0);
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

/* Header section hover effect */
.d-flex.justify-content-between.align-items-center {
    transition: all 0.3s ease;
}

.d-flex.justify-content-between.align-items-center:hover {
    transform: scale(1.01);
}

/* Greeting section hover effect */
.p-4.rounded.bg-white.shadow-sm {
    transition: all 0.3s ease;
}

.p-4.rounded.bg-white.shadow-sm:hover {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

/* Search input focus effect */
.form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(255, 102, 163, 0.25);
    border-color: #ff66a3;
}

/* Button hover effects */
.btn-outline-dark:hover {
    background-color: #ff66a3;
    border-color: #ff66a3;
    color: white;
    transform: translateY(-1px);
}

.btn-outline-dark {
    transition: all 0.3s ease;
}

/* Icon hover effects */
.bi {
    transition: all 0.3s ease;
}

.event-details .bi:hover {
    transform: scale(1.2);
    color: #ff66a3;
}

/* Badge hover effect */
.badge {
    transition: all 0.3s ease;
}

.badge:hover {
    transform: scale(1.1);
}

/* Empty state icon animation */
.bi-calendar-x {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
    100% {
        transform: translateY(0px);
    }
}
</style>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection

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
                        <h4 class="text-primary mb-1">Hi, {{ Auth::user()->name ?? 'Teacher' }}!</h4>
                        <p class="text-muted mb-0">Ready to create events?</p>
                    @endauth
                </div>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
                <i class="bi bi-plus-lg me-1"></i> Add Event
            </button>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif

        <!-- Events Cards Section -->
        <div class="d-flex flex-column gap-3 mt-2">
            @forelse($events as $event)
                <div class="card shadow-sm border-0 w-100" style="background: linear-gradient(145deg, #ffe5ec, #fcd0e4); border-left: 6px solid #ff66a3;">
                    <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                        <div class="mb-2 mb-md-0">
                            <h5 class="mb-1 text-capitalize fw-bold text-dark">{{ $event->title }}</h5>
                            <p class="text-dark small mb-1">{{ Str::limit($event->description, 100, '...') }}</p>
                            <small class="text-muted">
                                @php
                                    $eventDate = \Carbon\Carbon::parse($event->event_date);
                                    $diff = $eventDate->diffInDays(now(), false);
                                    echo $diff === 0 ? 'Today' : ($diff === 1 ? 'Yesterday' : $eventDate->format('M d, Y'));
                                @endphp
                            </small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('teachers.event.edit', $event->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form action="{{ route('teachers.event.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
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

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary" id="addEventModalLabel">
                    <i class="bi bi-calendar-plus me-2"></i>Add New Event
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('teachers.event.preview') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Event Title</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Enter event title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Enter event description"></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="event_date" class="form-label">Event Date</label>
                            <input type="date" name="event_date" id="event_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="event_time" class="form-label">Event Time</label>
                            <input type="time" name="event_time" id="event_time" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" id="location" class="form-control" placeholder="Enter event location" required>
                    </div>
                    <div class="mb-4">
                        <label for="venue_image" class="form-label">Venue Image (Optional)</label>
                        <input type="file" name="venue_image" id="venue_image" class="form-control" accept="image/*">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        <button type="submit" class="btn btn-primary fw-bold">
                            <i class="bi bi-eye me-1"></i> Preview Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

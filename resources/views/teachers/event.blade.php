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
      <!-- Search Bar -->
      <form action="/search" method="GET" class="d-flex gap-2" style="height: 38px;">
        <input type="text" name="search" class="form-control" placeholder="Search..." style="width: 220px;">
        <button type="submit" class="btn btn-outline-dark">Search</button>
      </form>
    </div>

    <!-- Teacher Greeting -->
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
      <button class="btn btn-primary" onclick="document.getElementById('floatingForm').style.display='block'">
        <i class="bi bi-plus-lg me-1"></i> Add Event
      </button>
    </div>

    <!-- Floating Add Event Form -->
    <div id="floatingForm" class="position-absolute top-50 start-50 translate-middle bg-white border border-primary rounded shadow-lg p-4" style="width: 600px; display: none; z-index: 1050;">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-primary mb-0"><i class="bi bi-calendar-plus me-2"></i> Add New Event</h5>
        <button onclick="document.getElementById('floatingForm').style.display='none'" class="btn-close" aria-label="Close"></button>
      </div>

      <form action="{{ route('teachers.event.preview') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
          <label for="title" class="form-label">Event Title</label>
          <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <textarea name="description" id="description" class="form-control" rows="4"></textarea>
        </div>

        <div class="mb-3">
          <label for="event_date" class="form-label">Event Date</label>
          <input type="date" name="event_date" id="event_date" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="event_time" class="form-label">Event Time</label>
          <input type="time" name="event_time" id="event_time" class="form-control">
        </div>

        <div class="mb-3">
          <label for="location" class="form-label">Location</label>
          <input type="text" name="location" id="location" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="venue_image" class="form-label">Venue Image (Optional)</label>
          <input type="file" name="venue_image" id="venue_image" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary w-100">Preview Event</button>
      </form>
    </div>

    <!-- Success Message -->
    @if(session('success'))
      <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <!-- Events Table -->
    <div class="table-responsive mt-4 shadow-sm rounded">
      <table class="table table-hover text-center align-middle bg-white border rounded">
        <thead class="bg-primary text-white">
          <tr>
            <th scope="col" class="py-3">#</th>
            <th scope="col" class="py-3">Title</th>
            <th scope="col" class="py-3">Description</th>
            <th scope="col" class="py-3">Date</th>
            <th scope="col" class="py-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($events as $event)
            <tr>
              <td class="fw-semibold text-secondary">{{ $loop->iteration }}</td>
              <td class="text-capitalize">{{ $event->title }}</td>
              <td class="text-muted small">{{ Str::limit($event->description, 80, '...') }}</td>
              <td>
                <span class="badge bg-light text-dark border">
                  {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                </span>
              </td>
              <td>
                <a href="{{ route('teachers.event.edit', $event->id) }}" class="text-decoration-none me-3" data-bs-toggle="tooltip" title="Edit">
                  <i class="bi bi-pencil-square text-warning" style="font-size: 1.2rem;"></i>
                </a>
                <form action="{{ route('teachers.event.destroy', $event->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this event?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-link p-0" data-bs-toggle="tooltip" title="Delete">
                    <i class="bi bi-trash text-danger" style="font-size: 1.2rem;"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-muted">No events found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>
</div>
@endsection

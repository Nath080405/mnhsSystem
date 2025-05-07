@extends('layouts.teacherApp')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-70 bg-light">
    <div class="container" style="max-width: 300px;">
        <h3 class="fw-bold text-primary mb-4 text-center">Create Event</h3>

        <form action="{{ route('teachers.event.preview') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow">
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
</div>
@endsection

@extends('layouts.teacherApp')

@section('content')
<div class="container py-4">
    <h3 class="mb-4 text-primary">Edit Event</h3>

    <form action="{{ route('teachers.event.update', $event->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Event Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $event->title }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3" required>{{ $event->description }}</textarea>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="event_date" class="form-label">Event Date</label>
                <input type="date" name="event_date" id="event_date" class="form-control" value="{{ $event->event_date }}" required>
            </div>
            <div class="col-md-6">
                <label for="event_time" class="form-label">Event Time</label>
                <input type="time" name="event_time" id="event_time" class="form-control" value="{{ $event->event_time }}">
            </div>
        </div>

        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" name="location" id="location" class="form-control" value="{{ $event->location }}" required>
        </div>

        <div class="mb-4">
            <label for="venue_image" class="form-label">Venue Image (Optional)</label>
            <input type="file" name="venue_image" id="venue_image" class="form-control" accept="image/*">
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('teachers.event.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Event</button>
        </div>
    </form>
</div>
@endsection
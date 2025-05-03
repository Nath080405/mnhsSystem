@extends('layouts.teacherApp')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold text-primary mb-4">Create Event</h3>
    <form action="{{ route('teachers.event.index') }}" method="POST">
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
        <button type="submit" class="btn btn-primary">Create Event</button>
    </form>
</div>
@endsection
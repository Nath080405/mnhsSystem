@extends('layouts.teacherApp')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold text-primary mb-4">Preview Event</h3>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Title: {{ $validated['title'] }}</h5>
            <p class="card-text">Description: {{ $validated['description'] ?? 'No description provided.' }}</p>
            <p class="card-text">Event Date: {{ $validated['event_date'] }}</p>
            <p class="card-text">Event Time: {{ $validated['event_time'] ?? 'Not specified' }}</p>
            <p class="card-text">Location: {{ $validated['location'] }}</p>

            @if(isset($validated['venue_image']))
                <p class="card-text">Venue Image:</p>
                <img src="{{ asset('storage/venue_images/' . $validated['venue_image']) }}" alt="Venue Image" class="img-fluid rounded" style="max-width: 400px;">
            @endif
        </div>
    </div>

    <form action="{{ route('teachers.event.store') }}" method="POST">
        @csrf
        <input type="hidden" name="title" value="{{ $validated['title'] }}">
        <input type="hidden" name="description" value="{{ $validated['description'] }}">
        <input type="hidden" name="event_date" value="{{ $validated['event_date'] }}">
        <input type="hidden" name="event_time" value="{{ $validated['event_time'] }}">
        <input type="hidden" name="location" value="{{ $validated['location'] }}">
        <input type="hidden" name="venue_image" value="{{ $validated['venue_image'] ?? '' }}">

        <button type="submit" class="btn btn-success">Save Event</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Go Back</a>
    </form>
</div>
@endsection

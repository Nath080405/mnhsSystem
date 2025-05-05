@extends('layouts.teacherApp')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold text-primary mb-4">Events</h3>
    <a href="{{ route('teachers.event.create') }}" class="btn btn-primary mb-3">Add Event</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Description</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->description }}</td>
                    <td>{{ $event->event_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@extends('layouts.studentApp')

@section('content')
<div class="container-fluid py-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #0d6efd;">School Events</h2>
            <p class="text-muted mb-0 small">View and track upcoming school events and activities</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            @if($recentEvents->count() > 0 || $oldEvents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th style="color: #0d6efd;">Event Details</th>
                                <th style="color: #0d6efd;">Schedule</th>
                                <th style="color: #0d6efd;">Location</th>
                                <th style="color: #0d6efd;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentEvents as $event)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-calendar-event" style="color: #6f4ef2;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $event->title }}</div>
                                                <div class="small text-muted">{{ Str::limit($event->description, 50) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-info bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-clock text-info"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</div>
                                                <div class="small text-muted">
                                                    {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - 
                                                    {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-geo-alt text-warning"></i>
                                            </div>
                                            <span class="fw-medium">{{ $event->location }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $event->status === 'Upcoming' ? 'success' : ($event->status === 'Completed' ? 'info' : 'danger') }}">
                                            <i class="bi bi-{{ $event->status === 'Upcoming' ? 'clock' : ($event->status === 'Completed' ? 'check-circle' : 'x-circle') }} me-1"></i>
                                            {{ $event->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach

                            @foreach($oldEvents as $event)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-calendar-event" style="color: #6f4ef2;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $event->title }}</div>
                                                <div class="small text-muted">{{ Str::limit($event->description, 50) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-info bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-clock text-info"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</div>
                                                <div class="small text-muted">
                                                    {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - 
                                                    {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-geo-alt text-warning"></i>
                                            </div>
                                            <span class="fw-medium">{{ $event->location }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $event->status === 'Upcoming' ? 'success' : ($event->status === 'Completed' ? 'info' : 'danger') }}">
                                            <i class="bi bi-{{ $event->status === 'Upcoming' ? 'clock' : ($event->status === 'Completed' ? 'check-circle' : 'x-circle') }} me-1"></i>
                                            {{ $event->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-calendar-x" style="font-size: 3rem;"></i>
                    <p class="mt-2 mb-0">No events found.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 1rem;
}
.badge {
    padding: 0.5rem 1rem;
    font-weight: 500;
}
.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
.table > :not(caption) > * > * {
    padding: 1rem;
}
.table-hover tbody tr:hover {
    background-color: rgba(111, 78, 242, 0.05);
}
</style>
@endsection

@extends('layouts.studentApp')

@section('content')
<div class="container-fluid" style="background-color: #f2f0f1; min-height: 100vh;">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-9 col-lg-12 ms-auto">
            <div class="p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2" style="border-color: black !important;">
                    <div>
                        <p class="mb-1 text-black-50" style="font-size: 0.9rem;">Overview</p>
                        <h3 class="mb-0 fw-bold text-black">School Events</h3>
                    </div>
                </div>

                <!-- Events Section -->
                <div class="events-section">
                    @forelse($events as $event)
                        <div class="card shadow-sm border-0 mb-3" style="background: linear-gradient(145deg, #ffe5ec, #fcd0e4); border-left: 6px solid #ff66a3;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="fw-bold text-secondary mb-2">{{ $event->title }}</h5>
                                        <p class="text-dark small mb-2">{{ Str::limit($event->description, 100, '...') }}</p>
                                    </div>
                                    <div class="d-flex flex-column align-items-end">
                                        <span class="badge {{ $event->status === 'Upcoming' ? 'bg-success' : ($event->status === 'Completed' ? 'bg-secondary' : 'bg-danger') }} px-3 py-2 mb-2">
                                            {{ $event->status }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="event-details d-flex flex-wrap gap-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar-event text-primary me-2"></i>
                                        <span class="text-dark">
                                            {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                        </span>
                                    </div>
                                    
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-clock text-primary me-2"></i>
                                        <span class="text-dark">
                                            {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - 
                                            {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
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
    </div>
</div>

<style>
.card {
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}
.badge {
    font-size: 0.875rem;
}
.event-details {
    font-size: 0.9rem;
}
.card-body {
    transition: all 0.3s ease;
}
.card:hover .card-body {
    background: linear-gradient(145deg, #ffd6e4, #f8b8d0);
}
</style>
@endsection

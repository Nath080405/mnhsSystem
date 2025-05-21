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
                <div class="events-section" style="background-color: #ffffff;">
                    <div class="tab-header d-flex justify-content-between align-items-center">
                        <div>
                            <button class="active" data-filter="all">All Events</button>
                            <button data-filter="unread"></button>
                        </div>
                    </div>

                    <div id="events-container">
                        @forelse($events as $event)
                            <div class="event-card {{ !$event->is_read ? 'unread' : '' }}" data-id="{{ $event->id }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong class="fs-5">{{ $event->title }}</strong>
                                        <p class="mb-1 text-muted">{{ $event->description }}</p>
                                        <div class="event-details">
                                            <small class="event-date">
                                                <i class="bi bi-calendar-event me-1"></i>
                                                {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
                                                @if($event->event_time)
                                                    • {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}
                                                @endif
                                            </small>
                                            @if($event->location)
                                                <small class="event-location ms-2">
                                                    <i class="bi bi-geo-alt me-1"></i>
                                                    {{ $event->location }}
                                                </small>
                                            @endif
                                            <small class="ms-2">
                                                <i class="bi bi-person me-1"></i>
                                                Posted by: {{ $event->teacher_name }}
                                            </small>
                                        </div>
                                    </div>
                                    @if($event->venue_image)
                                        <img src="{{ asset('storage/' . $event->venue_image) }}" alt="Venue" class="venue-image">
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <p class="text-muted mb-0">No events found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.events-section {
    border-radius: 10px;
    padding: 20px;
    margin-top: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.tab-header {
    border-bottom: 1px solid #ccc;
    margin-bottom: 20px;
}

.tab-header button {
    background: none;
    border: none;
    padding: 10px 15px;
    font-weight: 600;
    cursor: pointer;
    color: #333;
}

.tab-header .active {
    color:rgb(204, 46, 157);
    border-bottom: 2px solid #2ecc71;
}

.event-card {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 15px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    position: relative;
    transition: all 0.3s ease;
}

.event-card.unread {
    background-color: #f8f9fa;
    border-left: 4px solid #2ecc71;
}

.event-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.event-details {
    margin-top: 10px;
}

.event-date, .event-location {
    font-size: 13px;
    color: #666;
}

.event-location {
    color: #0d6efd;
    font-weight: 500;
}

.venue-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    margin-left: 15px;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const eventsContainer = document.getElementById('events-container');
    const filterButtons = document.querySelectorAll('.tab-header button');
    
    // Filter button click handler
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            const filter = this.dataset.filter;
            
            // Show/hide events based on filter
            const events = document.querySelectorAll('.event-card');
            events.forEach(event => {
                if (filter === 'all' || (filter === 'unread' && event.classList.contains('unread'))) {
                    event.style.display = 'block';
                } else {
                    event.style.display = 'none';
                }
            });

            // Mark unread events as read when "Unread" is clicked
            if (filter === 'unread') {
                const unreadEvents = document.querySelectorAll('.event-card.unread');
                unreadEvents.forEach(event => {
                    event.classList.remove('unread');
                    // Send an AJAX request to mark the event as read in the database
                    const eventId = event.getAttribute('data-id');
                    fetch(`/events/${eventId}/mark-as-read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    });
                });
            }
        });
    });
});
</script>
@endpush
@endsection

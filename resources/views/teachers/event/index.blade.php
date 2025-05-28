@extends('layouts.teacherApp')

@section('content')
<div class="bg-light min-vh-100">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="mb-1 text-muted small">Overview</p>
                <h3 class="mb-0 fw-bold text-primary">My Events</h3>
            </div>
            <div class="d-flex gap-3">
                <div class="dropdown filter-events-dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Filter Events
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" data-filter="all">All Events</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="upcoming">Upcoming</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="completed">Completed</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="cancelled">Cancelled</a></li>
                    </ul>
                </div>
                <form action="/search" method="GET" class="d-flex gap-2" style="height: 38px;">
                    <input type="text" name="search" class="form-control" placeholder="Search..." style="width: 220px;">
                    <button type="submit" class="btn btn-outline-dark">Search</button>
                </form>
            </div>
        </div>

        <div class="row">
            <!-- Events List -->
            <div class="col-md-4">
                <div class="bg-white rounded shadow-sm p-3 overflow-auto" style="height: calc(100vh - 200px);">
                    @forelse($events as $event)
                        @php $eventDate = \Carbon\Carbon::parse($event->event_date); @endphp
                        <div class="card mb-2 event-card" data-event-id="{{ $event->id }}" data-status="{{ strtolower($event->status) }}" style="cursor: pointer;">
                            <div class="card-body d-flex">
                                <div class="me-3 text-center">
                                    <div class="fw-bold text-primary">{{ $eventDate->format('M d') }}</div>
                                    <small class="text-muted">{{ $eventDate->format('Y') }}</small>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold text-dark">{{ $event->title }}</h6>
                                    <p class="text-muted small mb-1">Organizer: {{ $event->creator->name ?? 'N/A' }}</p>
                                    <span class="badge {{ $event->status === 'Upcoming' ? 'bg-success' : ($event->status === 'Completed' ? 'bg-secondary' : 'bg-danger') }}">
                                        {{ $event->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-calendar-x display-4"></i>
                            <p class="mt-2 mb-0">No events found.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Event Details -->
            <div class="col-md-8">
                <div class="bg-white rounded shadow-sm p-4 overflow-auto" style="height: calc(100vh - 200px);">
                    <div id="eventDetails" class="text-center text-muted py-5">
                        <i class="bi bi-calendar-event display-4"></i>
                        <p class="mt-2 mb-0">Select an event to view details</p>
                    </div>
                    @foreach($events as $event)
                        <div id="event-detail-{{ $event->id }}" class="event-details-content d-none">
                            <h3 class="mb-4">{{ $event->title }}</h3>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <p><i class="bi bi-calendar me-2"></i>Date: {{ $event->event_date }}</p>
                                    <p><i class="bi bi-person me-2"></i>Organizer: {{ $event->creator->name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><i class="bi bi-clock me-2"></i>Time: {{ $event->event_time }} - {{ $event->end_time }}</p>
                                    <p><i class="bi bi-geo-alt me-2"></i>Location: {{ $event->location }}</p>
                                </div>
                            </div>
                            <div>
                                <h5>Description</h5>
                                <p class="text-muted">{{ $event->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for interactivity -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const eventCards = document.querySelectorAll('.event-card');
    const eventDetailsPlaceholder = document.getElementById('eventDetails');
    const allEventDetails = document.querySelectorAll('.event-details-content');
    // Only select filter dropdown items, not all .dropdown-item
    const filterLinks = document.querySelectorAll('.filter-events-dropdown .dropdown-item');

    function resetDetailsView() {
        allEventDetails.forEach(detail => detail.classList.add('d-none'));
        eventDetailsPlaceholder.classList.remove('d-none');
    }

    eventCards.forEach(card => {
        card.addEventListener('click', function() {
            eventCards.forEach(c => c.classList.remove('border-primary'));
            this.classList.add('border-primary');
            resetDetailsView();
            const eventId = this.dataset.eventId;
            const detailView = document.getElementById('event-detail-' + eventId);
            if (detailView) {
                detailView.classList.remove('d-none');
                eventDetailsPlaceholder.classList.add('d-none');
            }
        });
    });

    filterLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const filter = this.dataset.filter;
            eventCards.forEach(card => {
                const status = card.dataset.status;
                card.style.display = (filter === 'all' || status === filter) ? 'block' : 'none';
            });
        });
    });
});
</script>
@endsection

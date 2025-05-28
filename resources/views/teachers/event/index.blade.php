@extends('layouts.teacherApp')

@section('content')
<div class="bg-light min-vh-100">
    <div class="container-fluid py-4">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="mb-1 text-muted small">Overview</p>
                <h3 class="mb-0 fw-bold text-primary">My Events</h3>
            </div>
            <div class="d-flex gap-3">
                <!-- Filter Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Filter Events
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" data-filter="all">All Events</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="upcoming">Upcoming</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="completed">Completed</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="cancelled">Cancelled</a></li>
                    </ul>
                </div>

                <!-- Search Form -->
                <form action="/search" method="GET" class="d-flex gap-2" style="height: 38px;">
                    <input type="text" name="search" class="form-control" placeholder="Search..." style="width: 220px;">
                    <button type="submit" class="btn btn-outline-dark">Search</button>
                </form>
            </div>
        </div>

        <div class="row">
            <!-- Events Sidebar -->
            <div class="col-md-4">
                <div class="bg-white rounded shadow-sm p-3 overflow-auto" style="height: calc(100vh - 200px);">
                    @forelse($events as $event)
                        @php $eventDate = \Carbon\Carbon::parse($event->event_date); @endphp
                        <div class="card mb-2 event-card"
                             data-event-id="{{ $event->event_id }}"
                             data-status="{{ strtolower($event->status) }}"
                             style="cursor: pointer;">
                            <div class="card-body d-flex position-relative">
                                <div class="me-3 text-center">
                                    <div class="fw-bold text-primary">{{ $eventDate->format('M d') }}</div>
                                    <small class="text-muted">{{ $eventDate->format('Y') }}</small>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold text-dark">{{ $event->title }}</h6>
                                    <p class="text-muted small mb-1">Organizer: {{ $event->creator->name ?? 'N/A' }}</p>
                                    <span class="badge 
                                        {{ $event->status === 'Upcoming' ? 'bg-success' : 
                                           ($event->status === 'Completed' ? 'bg-secondary' : 'bg-danger') }}">
                                        {{ $event->status }}
                                    </span>
                                </div>
                                <!-- Delete Button always visible -->
                                <form action="{{ route('teachers.event.destroy', $event->event_id) }}" method="POST" 
                                      class="position-absolute top-0 end-0 m-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-0" style="width: 20px; height: 20px; line-height: 1;">
                                        <i class="bi bi-x" style="font-size: 12px;"></i>
                                    </button>
                                </form>
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

            <!-- Event Details Panel -->
            <div class="col-md-8">
                <div class="bg-white rounded shadow-sm p-4 overflow-auto" style="height: calc(100vh - 200px);">
                    <div id="eventDetailsPlaceholder" class="text-center text-muted py-5">
                        <i class="bi bi-calendar-event display-4"></i>
                        <p class="mt-2 mb-0">Select an event to view details</p>
                    </div>

                    @foreach($events as $event)
                        <div class="event-detail d-none" id="event-detail-{{ $event->event_id }}">
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
                            <h5>Description</h5>
                            <p class="text-muted">{{ $event->description }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles -->
<style>
.border-primary {
    border: 2px solid #0d6efd !important;
}
</style>

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const eventCards = document.querySelectorAll('.event-card');
    const eventDetailsPlaceholder = document.getElementById('eventDetailsPlaceholder');
    const eventDetails = document.querySelectorAll('.event-detail');
    const filterLinks = document.querySelectorAll('[data-filter]');

    const resetView = () => {
        eventDetails.forEach(el => el.classList.add('d-none'));
        eventDetailsPlaceholder.classList.remove('d-none');
        eventCards.forEach(c => c.classList.remove('border-primary'));
    };

    const showDetails = card => {
        resetView();
        card.classList.add('border-primary');
        const detail = document.getElementById(`event-detail-${card.dataset.eventId}`);
        if (detail) {
            detail.classList.remove('d-none');
            eventDetailsPlaceholder.classList.add('d-none');
        }
    };

    eventCards.forEach(card =>
        card.addEventListener('click', () => showDetails(card))
    );

    if (eventCards.length > 0) showDetails(eventCards[0]);

    filterLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const filter = link.dataset.filter;
            resetView();

            eventCards.forEach(card => {
                const match = filter === 'all' || card.dataset.status === filter;
                card.style.display = match ? 'block' : 'none';
            });

            const visibleCard = [...eventCards].find(c => c.style.display !== 'none');
            if (visibleCard) showDetails(visibleCard);
        });
    });

    // Add AJAX delete functionality
    document.querySelectorAll('.event-card form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!confirm('Are you sure you want to delete this event?')) return;

            const card = this.closest('.event-card');
            const url = this.action;
            const token = this.querySelector('input[name="_token"]').value;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new URLSearchParams({
                    '_method': 'DELETE',
                    '_token': token
                })
            })
            .then(response => {
                if (response.ok) {
                    // Fade out the card
                    card.style.transition = 'opacity 0.5s';
                    card.style.opacity = 0;
                    setTimeout(() => card.remove(), 500);
                } else {
                    alert('Failed to delete event.');
                }
            })
            .catch(() => alert('Failed to delete event.'));
        });
    });
});
</script>
@endsection

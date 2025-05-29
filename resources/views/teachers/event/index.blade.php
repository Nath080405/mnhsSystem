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
            <div class="d-flex gap-2">
                <!-- Status Filter Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Event Status
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" data-filter="all">All Events</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="upcoming">Upcoming</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="completed">Completed</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="cancelled">Cancelled</a></li>
                    </ul>
                </div>
                <!-- Inbox Filter Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Inbox Filters
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item inbox-filter" href="#" data-inbox="all">All</a></li>
                        <li><a class="dropdown-item inbox-filter" href="#" data-inbox="unread">Unread</a></li>
                        <li><a class="dropdown-item inbox-filter" href="#" data-inbox="starred">Starred</a></li>
                        <li><a class="dropdown-item inbox-filter" href="#" data-inbox="archived">Archived</a></li>
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
                        <div class="card mb-2 event-card {{ $event->is_unread ? 'unread' : '' }} {{ $event->is_starred ? 'starred' : '' }} {{ $event->is_archived ? 'archived d-none' : '' }}"
                             data-event-id="{{ $event->event_id }}" data-status="{{ strtolower($event->status) }}" style="cursor: pointer;">
                            <div class="card-body d-flex position-relative">
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
                                <!-- Action Icons -->
                                <div class="position-absolute top-0 end-0 m-1 d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-outline-warning rounded-circle toggle-star" data-id="{{ $event->event_id }}">
                                        <i class="bi {{ $event->is_starred ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-circle toggle-read {{ $event->is_unread ? 'unread' : '' }}" data-id="{{ $event->event_id }}">
                                        <i class="bi bi-circle-fill {{ $event->is_unread ? 'text-white' : 'text-primary' }}"></i>
                                    </button>
                                    <form action="{{ route('teachers.event.archive', $event->event_id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-dark rounded-circle p-0" style="width: 20px; height: 20px;">
                                            <i class="bi bi-archive"></i>
                                        </button>
                                    </form>
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

            <!-- Event Details Panel -->
            <div class="col-md-8">
                <div class="bg-white rounded shadow-sm p-4 overflow-auto" style="height: calc(100vh - 200px);">
                    <div id="eventDetailsPlaceholder" class="text-center text-muted py-5">
                        <i class="bi bi-calendar-event display-4"></i>
                        <p class="mt-3 mb-0 fs-5">No Event Selected</p>
                        <p class="text-muted small">Select an event from the list to view its details</p>
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

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const eventCards = document.querySelectorAll('.event-card');
    const eventDetailsPlaceholder = document.getElementById('eventDetailsPlaceholder');
    const eventDetails = document.querySelectorAll('.event-detail');

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

    // Show first event by default
    if (eventCards.length > 0) showDetails(eventCards[0]);

    document.querySelectorAll('[data-filter]').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const filter = link.dataset.filter;
            resetView();
            eventCards.forEach(card => {
                const match = filter === 'all' || card.dataset.status === filter;
                card.style.display = match ? 'block' : 'none';
            });
            const visible = [...eventCards].find(c => c.style.display !== 'none');
            if (visible) showDetails(visible);
        });
    });

    document.querySelectorAll('.inbox-filter').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const filter = link.dataset.inbox;
            let hasVisibleCards = false;
            
            eventCards.forEach(card => {
                let show = true;
                if (filter === 'unread' && !card.classList.contains('unread')) show = false;
                if (filter === 'starred' && !card.classList.contains('starred')) show = false;
                if (filter === 'archived' && !card.classList.contains('archived')) show = false;
                if (filter === 'all') show = true;
                
                card.style.display = show ? 'block' : 'none';
                if (show) hasVisibleCards = true;
            });

            // If no cards are visible, show the placeholder
            if (!hasVisibleCards) {
                resetView();
                eventDetailsPlaceholder.innerHTML = `
                    <i class="bi bi-calendar-x display-4"></i>
                    <p class="mt-3 mb-0 fs-5">No Events Found</p>
                    <p class="text-muted small">There are no events in this category</p>
                `;
            } else {
                // Show the first visible card
                const firstVisible = [...eventCards].find(card => card.style.display !== 'none');
                if (firstVisible) showDetails(firstVisible);
            }
        });
    });

    document.querySelectorAll('.toggle-read').forEach(button => {
        button.addEventListener('click', () => {
            const card = button.closest('.event-card');
            card.classList.toggle('unread');
            button.classList.toggle('unread');
            const icon = button.querySelector('i');
            icon.classList.toggle('text-white');
            icon.classList.toggle('text-primary');
        });
    });

    document.querySelectorAll('.toggle-star').forEach(button => {
        button.addEventListener('click', () => {
            const card = button.closest('.event-card');
            const icon = button.querySelector('i');
            card.classList.toggle('starred');
            icon.classList.toggle('bi-star');
            icon.classList.toggle('bi-star-fill');
        });
    });
});
</script>

<style>
.hover-effect {
    transition: all 0.3s ease;
}

.hover-effect:hover {
    background-color: #0d6efd;
    border-color: #0d6efd;
    transform: scale(1.1);
}

.hover-effect:hover i {
    color: white !important;
}

.toggle-read {
    background-color: white;
    transition: all 0.3s ease;
}

.toggle-read.unread {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.toggle-read i {
    transition: color 0.3s ease;
}

.toggle-read.unread i {
    color: white !important;
}
</style>
@endsection

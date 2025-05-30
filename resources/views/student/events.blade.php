@extends('layouts.studentApp')

@section('content')
<div class="bg-light min-vh-100">
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1" style="color: #0d6efd;">Events</h2>
                <p class="text-muted mb-0 small">View and track school events and activities</p>
            </div>
            <div class="d-flex gap-2">
                <!-- Status Filter Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" style="min-width: 140px;">
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
                    <button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" style="min-width: 140px;">
                        Inbox Filters
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item inbox-filter" href="#" data-inbox="all">All</a></li>
                        <li><a class="dropdown-item inbox-filter" href="#" data-inbox="unread">Unread</a></li>
                        <li><a class="dropdown-item inbox-filter" href="#" data-inbox="starred">Starred</a></li>
                        <li><a class="dropdown-item inbox-filter" href="#" data-inbox="archived">Archived</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Events Sidebar -->
            <div class="col-md-4">
                <div class="bg-white rounded shadow-sm p-3 overflow-auto" style="height: calc(100vh - 200px);">
                    <!-- Active Events -->
                    <div class="active-events">
                        @forelse($recentEvents->concat($oldEvents) as $event)
                            @php $eventDate = \Carbon\Carbon::parse($event->event_date); @endphp
                            <div class="card mb-2 event-card {{ !in_array($event->event_id, $viewedEventIds) ? 'unread' : '' }} {{ $event->is_starred ? 'starred' : '' }} {{ in_array($event->event_id, $archivedEventIds) ? 'archived' : '' }}"
                                 data-event-id="{{ $event->event_id }}" 
                                 data-status="{{ strtolower($event->status) }}"
                                 data-archived="{{ in_array($event->event_id, $archivedEventIds) ? 'true' : 'false' }}"
                                 style="cursor: pointer;">
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
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-circle toggle-read {{ !in_array($event->event_id, $viewedEventIds) ? 'unread' : '' }}" data-id="{{ $event->event_id }}">
                                            <i class="bi bi-circle-fill {{ !in_array($event->event_id, $viewedEventIds) ? 'text-white' : 'text-primary' }}"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-dark rounded-circle toggle-archive" data-id="{{ $event->event_id }}">
                                            <i class="bi bi-archive{{ in_array($event->event_id, $archivedEventIds) ? '-fill' : '' }}"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5 no-events-message">
                                <i class="bi bi-calendar-x display-4"></i>
                                <p class="mt-2 mb-0">No active events found.</p>
                            </div>
                        @endforelse
                    </div>
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

                    @foreach($recentEvents->concat($oldEvents)->concat($archivedEvents) as $event)
                        <div class="event-detail d-none" id="event-detail-{{ $event->event_id }}">
                            <h3 class="mb-4">{{ $event->title }}</h3>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <p><i class="bi bi-calendar me-2"></i>Date: {{ $event->event_date }}</p>
                                    <p><i class="bi bi-person me-2"></i>Organizer: {{ $event->creator->name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><i class="bi bi-clock me-2"></i>Time: {{ $event->event_time }}</p>
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
    const activeEvents = document.querySelector('.active-events');
    const noEventsMessage = document.querySelector('.no-events-message');

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
    if (eventCards.length > 0) {
        const firstActiveCard = document.querySelector('.active-events .event-card');
        if (firstActiveCard) showDetails(firstActiveCard);
    }

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
                if (filter === 'archived' && card.dataset.archived !== 'true') show = false;
                if (filter === 'all') show = true;
                
                card.style.display = show ? 'block' : 'none';
                if (show) hasVisibleCards = true;
            });

            if (!hasVisibleCards) {
                resetView();
                eventDetailsPlaceholder.innerHTML = `
                    <i class="bi bi-calendar-x display-4"></i>
                    <p class="mt-3 mb-0 fs-5">No Events Found</p>
                    <p class="text-muted small">There are no events in this category</p>
                `;
            } else {
                const firstVisible = [...eventCards].find(card => card.style.display !== 'none');
                if (firstVisible) showDetails(firstVisible);
            }
        });
    });

    // Replace archive form submission with toggle button
    document.querySelectorAll('.toggle-archive').forEach(button => {
        button.addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent card click event
            const card = button.closest('.event-card');
            const eventId = card.dataset.eventId;
            const isArchived = card.dataset.archived === 'true';
            
            fetch(`/student/events/${eventId}/archive`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Toggle archived state
                    card.dataset.archived = (!isArchived).toString();
                    card.classList.toggle('archived');
                    const icon = button.querySelector('i');
                    icon.classList.toggle('bi-archive');
                    icon.classList.toggle('bi-archive-fill');

                    // Show success message
                    const toast = document.createElement('div');
                    toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed bottom-0 end-0 m-3';
                    toast.setAttribute('role', 'alert');
                    toast.setAttribute('aria-live', 'assertive');
                    toast.setAttribute('aria-atomic', 'true');
                    toast.innerHTML = `
                        <div class="d-flex">
                            <div class="toast-body">
                                Event ${isArchived ? 'unarchived' : 'archived'} successfully
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    `;
                    document.body.appendChild(toast);
                    const bsToast = new bootstrap.Toast(toast);
                    bsToast.show();
                    setTimeout(() => toast.remove(), 3000);

                    // If we're currently filtering by archived, update visibility
                    const currentFilter = document.querySelector('.inbox-filter.active')?.dataset.inbox;
                    if (currentFilter === 'archived') {
                        card.style.display = !isArchived ? 'block' : 'none';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Show error message
                const toast = document.createElement('div');
                toast.className = 'toast align-items-center text-white bg-danger border-0 position-fixed bottom-0 end-0 m-3';
                toast.setAttribute('role', 'alert');
                toast.setAttribute('aria-live', 'assertive');
                toast.setAttribute('aria-atomic', 'true');
                toast.innerHTML = `
                    <div class="d-flex">
                        <div class="toast-body">
                            Failed to ${isArchived ? 'unarchive' : 'archive'} event. Please try again.
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                `;
                document.body.appendChild(toast);
                const bsToast = new bootstrap.Toast(toast);
                bsToast.show();
                setTimeout(() => toast.remove(), 3000);
            });
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
            card.classList.toggle('starred');
            const icon = button.querySelector('i');
            icon.classList.toggle('bi-star');
            icon.classList.toggle('bi-star-fill');
        });
    });
});
</script>

<style>
.event-card {
    transition: all 0.2s ease;
    border: 2px solid transparent;
}

.event-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.event-card.unread {
    background-color: #f8f9fa;
}

.event-card.starred {
    border-color: #ffc107;
}

.event-card.archived {
    opacity: 0.6;
}

.event-card.archived:hover {
    opacity: 0.8;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}

.btn-outline-warning,
.btn-outline-primary,
.btn-outline-dark {
    padding: 0.25rem;
    line-height: 1;
}

.btn-outline-warning:hover,
.btn-outline-primary:hover,
.btn-outline-dark:hover {
    transform: scale(1.1);
}

/* Add styles for filter buttons */
.dropdown-toggle {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem 1rem;
    font-weight: 500;
    height: 38px;
}

.dropdown-toggle::after {
    margin-left: 0.5rem;
}

.dropdown-menu {
    min-width: 140px;
    padding: 0.5rem 0;
    margin: 0;
    border: 1px solid rgba(0,0,0,.15);
    border-radius: 0.5rem;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
}

.dropdown-item {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    color: #5A5A89;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    color: #0d6efd;
}

.dropdown-item.active {
    background-color: #0d6efd;
    color: white;
}
</style>
@endsection


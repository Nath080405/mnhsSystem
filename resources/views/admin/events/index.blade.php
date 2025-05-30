@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-primary">Event Management</h2>
                <p class="text-muted mb-0 small">Manage and monitor school events and activities</p>
            </div>
            <div class="d-flex gap-2">
                <div class="input-group shadow-sm" style="width: 250px;">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search events..."
                        value="{{ request('search') }}">
                </div>
                <a href="{{ route('admin.events.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Add Event
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="card shadow-lg border-0">
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-primary">Event Details</th>
                                <th class="text-primary">Schedule</th>
                                <th class="text-primary">Location</th>
                                <th class="text-primary">Visibility</th>
                                <th class="text-primary">Status</th>
                                <th class="text-primary text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-calendar-event text-primary"></i>
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
                                                <div class="fw-medium">{{ $event->event_date->format('M d, Y') }}</div>
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
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-success bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-eye text-success"></i>
                                            </div>
                                            <span class="fw-medium">{{ $event->visibility }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $event->status === 'Upcoming' ? 'success' : ($event->status === 'Completed' ? 'info' : 'danger') }}">
                                            <i class="bi bi-{{ $event->status === 'Upcoming' ? 'clock' : ($event->status === 'Completed' ? 'check-circle' : 'x-circle') }} me-1"></i>
                                            {{ $event->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.events.edit', $event->event_id) }}"
                                                class="btn btn-xs btn-outline-primary" title="Edit Event">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="{{ route('admin.events.show', $event->event_id) }}"
                                                class="btn btn-xs btn-outline-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.events.destroy', $event->event_id) }}" method="POST"
                                                class="d-inline delete-event-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-xs btn-outline-danger delete-event-btn"
                                                    data-bs-toggle="modal" data-bs-target="#deleteEventModal"
                                                    data-event-id="{{ $event->event_id }}"
                                                    data-event-title="{{ $event->title }}"
                                                    title="Delete Event">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
                                            No events found
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Showing {{ $events->firstItem() ?? 0 }} to {{ $events->lastItem() ?? 0 }} of
                        {{ $events->total() ?? 0 }} entries
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($events->hasPages())
                            @if($events->onFirstPage())
                                <button class="btn btn-outline-secondary pagination-btn" disabled>
                                    <i class="bi bi-chevron-left"></i> Previous
                                </button>
                            @else
                                <a href="{{ $events->previousPageUrl() }}" class="btn btn-outline-secondary pagination-btn">
                                    <i class="bi bi-chevron-left"></i> Previous
                                </a>
                            @endif

                            @if($events->hasMorePages())
                                <a href="{{ $events->nextPageUrl() }}" class="btn btn-outline-primary pagination-btn">
                                    Next <i class="bi bi-chevron-right"></i>
                                </a>
                            @else
                                <button class="btn btn-outline-secondary pagination-btn" disabled>
                                    Next <i class="bi bi-chevron-right"></i>
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteEventModal" tabindex="-1" aria-labelledby="deleteEventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="deleteEventModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="avatar-sm bg-danger bg-opacity-10 rounded-circle mx-auto mb-3">
                            <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
                        </div>
                        <h5 class="mb-1">Are you sure?</h5>
                        <p class="text-muted mb-0">You are about to delete <span class="fw-bold" id="eventTitle"></span>. This action cannot be undone.</p>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="bi bi-trash me-1"></i> Delete Event
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-outline-primary {
            border-width: 2px;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
            min-width: 120px;
        }

        .btn-outline-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card.shadow-sm {
            background-color: #f8f9fa;
        }

        .avatar-sm {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-sm i {
            font-size: 1rem;
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .table td {
            vertical-align: middle;
        }

        .badge {
            padding: 0.5em 0.75em;
            font-weight: 500;
        }

        .alert {
            border: none;
            border-radius: 0.5rem;
        }

        .alert-success {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .btn-xs {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            min-width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-xs i {
            font-size: 0.75rem;
        }

        .pagination-btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0.375rem;
            min-width: 100px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s ease-in-out;
            border-width: 1px;
        }

        .pagination-btn:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .pagination-btn:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }

        .pagination-btn i {
            font-size: 0.875rem;
        }

        .btn-outline-primary.pagination-btn {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }

        .btn-outline-primary.pagination-btn:hover:not(:disabled) {
            background-color: #0b5ed7;
            border-color: #0b5ed7;
        }

        .btn-outline-secondary.pagination-btn {
            background-color: white;
            color: #6c757d;
            border-color: #dee2e6;
        }

        .btn-outline-secondary.pagination-btn:hover:not(:disabled) {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #495057;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.querySelector('input[name="search"]');
            const searchTimeout = 500; // milliseconds
            let timeoutId;

            searchInput.addEventListener('input', function () {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => {
                    const currentUrl = new URL(window.location.href);
                    const searchValue = this.value.trim();

                    if (searchValue) {
                        currentUrl.searchParams.set('search', searchValue);
                    } else {
                        currentUrl.searchParams.delete('search');
                    }

                    window.location.href = currentUrl.toString();
                }, searchTimeout);
            });

            // Delete Event Modal Functionality
            const deleteModal = document.getElementById('deleteEventModal');
            const eventTitleElement = document.getElementById('eventTitle');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            let currentForm = null;

            // When delete button is clicked
            document.querySelectorAll('.delete-event-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const eventTitle = this.getAttribute('data-event-title');
                    eventTitleElement.textContent = eventTitle;
                    currentForm = this.closest('form');
                });
            });

            // When confirm delete is clicked
            confirmDeleteBtn.addEventListener('click', function() {
                if (currentForm) {
                    currentForm.submit();
                }
            });

            // Reset form reference when modal is closed
            deleteModal.addEventListener('hidden.bs.modal', function () {
                currentForm = null;
            });
        });
    </script>
@endsection 
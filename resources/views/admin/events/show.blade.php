@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-primary">{{ $event->title }}</h2>
                <p class="text-muted mb-0 small">Event Details</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.events.edit', $event->event_id) }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-pencil me-1"></i> Edit Event
                </a>
                <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Events
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Event Details Card -->
            <div class="col-md-8">
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Description -->
                            <div class="col-12">
                                <h5 class="text-primary mb-3">Description</h5>
                                <p class="mb-0">{{ $event->description ?: 'No description provided.' }}</p>
                            </div>

                            <!-- Date and Time -->
                            <div class="col-12">
                                <h5 class="text-primary mb-3">Schedule</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-calendar-event me-2 text-primary"></i>
                                            <div>
                                                <small class="text-muted d-block">Date</small>
                                                <strong>{{ $event->event_date->format('F d, Y') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-clock me-2 text-primary"></i>
                                            <div>
                                                <small class="text-muted d-block">Start Time</small>
                                                <strong>{{ $event->start_time->format('h:i A') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-clock-fill me-2 text-primary"></i>
                                            <div>
                                                <small class="text-muted d-block">End Time</small>
                                                <strong>{{ $event->end_time->format('h:i A') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="col-12">
                                <h5 class="text-primary mb-3">Location</h5>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-geo-alt me-2 text-primary"></i>
                                    <span>{{ $event->location }}</span>
                                </div>
                            </div>

                            <!-- Status and Visibility -->
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="text-primary mb-3">Status</h5>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-{{ $event->status === 'Upcoming' ? 'primary' : ($event->status === 'Completed' ? 'success' : 'danger') }} me-2">
                                                {{ $event->status }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="text-primary mb-3">Visibility</h5>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-info me-2">
                                                {{ $event->visibility }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- Event Info Card -->
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-body p-4">
                        <h5 class="text-primary mb-3">Event Information</h5>
                        <div class="d-flex flex-column gap-3">
                            <div>
                                <small class="text-muted d-block">Created By</small>
                                <strong>{{ $event->creator->name ?? 'Unknown' }}</strong>
                            </div>
                            <div>
                                <small class="text-muted d-block">Created On</small>
                                <strong>{{ $event->created_at->format('M d, Y h:i A') }}</strong>
                            </div>
                            <div>
                                <small class="text-muted d-block">Last Updated</small>
                                <strong>{{ $event->updated_at->format('M d, Y h:i A') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="card shadow-lg border-0">
                    <div class="card-body p-4">
                        <h5 class="text-primary mb-3">Actions</h5>
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.events.edit', $event->event_id) }}" class="btn btn-primary">
                                <i class="bi bi-pencil me-1"></i> Edit Event
                            </a>
                            <form action="{{ route('admin.events.destroy', $event->event_id) }}" method="POST" class="d-grid">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this event?')">
                                    <i class="bi bi-trash me-1"></i> Delete Event
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
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
        .text-primary {
            color: #6f4ef2 !important;
        }
        .bg-primary {
            background-color: #6f4ef2 !important;
        }
        .btn-primary {
            background-color: #6f4ef2;
            border-color: #6f4ef2;
        }
        .btn-primary:hover {
            background-color: #5a3fd8;
            border-color: #5a3fd8;
        }
    </style>
@endsection 
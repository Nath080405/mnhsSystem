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
                    <input type="text" class="form-control border-start-0" placeholder="Search events...">
                </div>
                <a href="{{ route('admin.events.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i> Add New Event
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Main Content -->
        <div class="card shadow-lg border-0">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Event List</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-file-earmark-excel me-1"></i> Export
                        </button>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-printer me-1"></i> Print
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-3">Title</th>
                                <th class="border-0 px-3">Date</th>
                                <th class="border-0 px-3">Time</th>
                                <th class="border-0 px-3">Location</th>
                                <th class="border-0 px-3">Visibility</th>
                                <th class="border-0 px-3">Status</th>
                                <th class="border-0 px-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                                <tr>
                                    <td class="px-3">{{ $event->title }}</td>
                                    <td class="px-3">{{ $event->event_date->format('M d, Y') }}</td>
                                    <td class="px-3">
                                        {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - 
                                        {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
                                    </td>
                                    <td class="px-3">{{ $event->location }}</td>
                                    <td class="px-3">
                                        <span class="badge bg-info">{{ $event->visibility }}</span>
                                    </td>
                                    <td class="px-3">
                                        @if($event->status == 'Upcoming')
                                            <span class="badge bg-primary">Upcoming</span>
                                        @elseif($event->status == 'Completed')
                                            <span class="badge bg-success">Completed</span>
                                        @else
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td class="px-3 text-end">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.events.show', $event->event_id) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.events.edit', $event->event_id) }}" class="btn btn-sm btn-outline-secondary" title="Edit Event">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.events.destroy', $event->event_id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this event?')" title="Delete Event">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-3 py-4 text-center text-muted">
                                        <i class="bi bi-calendar-x me-2"></i> No events found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-top-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Showing {{ $events->firstItem() ?? 0 }} to {{ $events->lastItem() ?? 0 }} of {{ $events->total() ?? 0 }} entries
                    </div>
                    <div>
                        {{ $events->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .badge {
            padding: 0.5em 0.75em;
            font-weight: 500;
        }
        .btn-group .btn {
            padding: 0.25rem 0.5rem;
        }
    </style>
@endsection 
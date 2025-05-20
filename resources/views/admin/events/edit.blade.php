@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-primary">Edit Event</h2>
                <p class="text-muted mb-0 small">Update event details and information</p>
            </div>
            <div>
                <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Events
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="card shadow-lg border-0">
            <div class="card-body p-4">
                @if(isset($event) && $event->event_id)
                    <form action="{{ route('admin.events.update', $event->event_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="event_id" value="{{ $event->event_id }}">
                        <div class="row g-4">
                            <!-- Title -->
                            <div class="col-md-12">
                                <label for="title" class="form-label">Event Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                    id="title" name="title" value="{{ old('title', $event->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                    id="description" name="description" rows="3">{{ old('description', $event->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date -->
                            <div class="col-md-4">
                                <label for="event_date" class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('event_date') is-invalid @enderror" 
                                    id="event_date" name="event_date" value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required>
                                @error('event_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Start Time -->
                            <div class="col-md-4">
                                <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                    id="start_time" name="start_time" value="{{ old('start_time', $event->start_time->format('H:i')) }}" required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- End Time -->
                            <div class="col-md-4">
                                <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                    id="end_time" name="end_time" value="{{ old('end_time', $event->end_time->format('H:i')) }}" required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Location -->
                            <div class="col-md-6">
                                <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                    id="location" name="location" value="{{ old('location', $event->location) }}" required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Visibility -->
                            <div class="col-md-3">
                                <label for="visibility" class="form-label">Visibility <span class="text-danger">*</span></label>
                                <select class="form-select @error('visibility') is-invalid @enderror" 
                                    id="visibility" name="visibility" required>
                                    <option value="">Select visibility</option>
                                    <option value="All" {{ old('visibility', $event->visibility) == 'All' ? 'selected' : '' }}>All</option>
                                    <option value="Students" {{ old('visibility', $event->visibility) == 'Students' ? 'selected' : '' }}>Students</option>
                                    <option value="Teachers" {{ old('visibility', $event->visibility) == 'Teachers' ? 'selected' : '' }}>Teachers</option>
                                </select>
                                @error('visibility')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                    <option value="">Select status</option>
                                    <option value="Upcoming" {{ old('status', $event->status) == 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
                                    <option value="Completed" {{ old('status', $event->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Cancelled" {{ old('status', $event->status) == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Creator Info -->
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <small>
                                        <i class="bi bi-info-circle me-1"></i>
                                        Created by: {{ $event->creator->name ?? 'Unknown' }} on {{ $event->created_at->format('M d, Y H:i') }}
                                    </small>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12">
                                <hr class="my-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.events.index') }}" class="btn btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i> Update Event
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="alert alert-danger">
                        Event not found or invalid event ID.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .form-label {
            font-weight: 500;
            color: #5A5A89;
        }
        .form-control, .form-select {
            border-radius: 0.5rem;
            border-color: #E5E5EF;
            padding: 0.6rem 1rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #6f4ef2;
            box-shadow: 0 0 0 0.2rem rgba(111, 78, 242, 0.15);
        }
        .invalid-feedback {
            font-size: 0.875rem;
        }
    </style>
@endsection 
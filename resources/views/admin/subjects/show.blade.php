@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-primary">Subject Details</h2>
            <p class="text-muted mb-0 small">Viewing detailed information for {{ $subject->name }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.subjects.edit', $subject->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i> Edit Subject
            </a>
            <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Subjects
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Information Card -->
        <div class="col-md-8">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="bi bi-book-fill text-primary fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">{{ $subject->name }}</h4>
                            <p class="text-muted mb-0">Code: {{ $subject->code }}</p>
                        </div>
                        <span class="ms-auto badge {{ $subject->status == 'active' ? 'bg-success' : 'bg-danger' }} fs-6">
                            {{ ucfirst($subject->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <h5 class="fw-bold mb-3">Subject Information</h5>
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td style="width: 200px;" class="text-muted">Subject Code</td>
                                            <td class="fw-semibold">{{ $subject->code }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Description</td>
                                            <td class="fw-semibold">
                                                @if($subject->description)
                                                    {{ $subject->description }}
                                                @else
                                                    <span class="text-muted">No description available</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Status</td>
                                            <td>
                                                <span class="badge {{ $subject->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ ucfirst($subject->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Schedules</td>
                                            <td>
                                                @if($subject->schedules->count() > 0)
                                                    <div class="d-flex flex-column gap-2">
                                                        @foreach($subject->schedules as $schedule)
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2">
                                                                    <i class="bi bi-clock text-primary"></i>
                                                                </div>
                                                                <div>
                                                                    <span class="fw-semibold">{{ $schedule->day }}</span>
                                                                    <br>
                                                                    <small class="text-muted">
                                                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - 
                                                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-muted">No schedules set</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Created At</td>
                                            <td class="fw-semibold">{{ $subject->created_at->format('F d, Y h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Last Updated</td>
                                            <td class="fw-semibold">{{ $subject->updated_at->format('F d, Y h:i A') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="col-md-4">
            <!-- Teacher Information -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Assigned Teacher</h5>
                </div>
                <div class="card-body">
                    @if($subject->teacher)
                        <div class="d-flex align-items-center">
                            <div class="avatar-lg bg-info bg-opacity-10 rounded-circle me-3">
                                <i class="bi bi-person-fill text-info fs-3"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $subject->teacher->formal_name }}</h6>
                                <p class="text-muted small mb-0">Teacher</p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="avatar-lg bg-light rounded-circle mx-auto mb-3">
                                <i class="bi bi-person-x text-muted fs-3"></i>
                            </div>
                            <h6 class="text-muted">No Teacher Assigned</h6>
                            <a href="{{ route('admin.subjects.edit', $subject->id) }}" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="bi bi-person-plus me-1"></i> Assign Teacher
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.subjects.edit', $subject->id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i> Edit Subject
                        </a>
                        <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" 
                            onsubmit="return confirm('Are you sure you want to delete this subject? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash me-1"></i> Delete Subject
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
    border-radius: 0.5rem;
}
.avatar-lg {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.badge {
    padding: 0.5rem 1rem;
}
</style>
@endsection 
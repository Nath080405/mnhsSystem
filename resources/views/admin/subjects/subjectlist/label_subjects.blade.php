@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-primary">{{ $subjectLabel->name }}</h2>
            <p class="text-muted mb-0 small">Manage learning areas under this category</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Learning Areas
            </a>
            <a href="{{ route('admin.subjects.create', ['parent_id' => $subjectLabel->id, 'grade_level' => $subjectLabel->grade_level]) }}" 
               class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Add Learning Area
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

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-primary">Name</th>
                            <th class="text-primary">Code</th>
                            <th class="text-primary">Teacher</th>
                            <th class="text-primary">Class Section</th>
                            <th class="text-primary">Schedule</th>
                            <th class="text-primary text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-info bg-opacity-10 rounded-circle me-2">
                                            <i class="bi bi-book text-info"></i>
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $subject->name }}</div>
                                            @if($subject->description)
                                                <div class="small text-muted">{{ Str::limit($subject->description, 50) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $subject->code }}</span>
                                </td>
                                <td>
                                    @if($subject->teacher)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-person text-primary"></i>
                                            </div>
                                            <div>{{ $subject->teacher->formal_name }}</div>
                                        </div>
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </td>
                                <td>
                                    @if($subject->section)
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            <i class="bi bi-bookmark me-1"></i>
                                            {{ $subject->section->name }} (Grade {{ $subject->section->grade_level }})
                                        </span>
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </td>
                                <td>
                                    @if($subject->schedules->isNotEmpty())
                                        @php
                                            $schedule = $subject->schedules->first();
                                            $allDaysSame = $subject->schedules->every(function ($schedule) use ($subject) {
                                                return $schedule->start_time === $subject->schedules->first()->start_time 
                                                    && $schedule->end_time === $subject->schedules->first()->end_time;
                                            });
                                        @endphp
                                        <div class="small">
                                            <div>{{ $allDaysSame ? 'Everyday' : $schedule->day }}</div>
                                            <div class="text-muted">
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - 
                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">No schedule set</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.subjects.edit', $subject) }}" 
                                           class="btn btn-xs btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.subjects.destroy', $subject) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to remove this learning area?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-outline-danger" title="Remove">
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
                                        <i class="bi bi-inbox-fill fs-2 d-block mb-2"></i>
                                        No learning areas found
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.btn-outline-primary {
    border-width: 2px;
    font-weight: 500;
    transition: all 0.2s ease-in-out;
}

.btn-outline-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-primary {
    font-weight: 500;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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

.alert {
    border: none;
    border-radius: 0.5rem;
}

.alert-success {
    background-color: #d1e7dd;
    color: #0f5132;
}

.alert i {
    font-size: 1.1rem;
}

.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
}
</style>
@endsection 
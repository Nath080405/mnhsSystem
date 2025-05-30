<!-- resources/views/dashboard.blade.php -->
@extends('layouts.teacherApp')

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Banner -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4 d-flex flex-wrap align-items-center justify-content-between">
            <div>
                <h2 class="fw-bold text-primary mb-1">Welcome, {{ Auth::user()->name }}!</h2>
                <p class="text-muted mb-0">Here's your teaching overview for today.</p>
            </div>
            <div class="clock-display text-end">
                <div class="time" id="current-time">00:00:00</div>
                <div class="date" id="current-date">Loading...</div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <i class="bi bi-people-fill fs-2 text-primary mb-2"></i>
                    <h5 class="fw-bold mb-0">{{ $studentsCount ?? 0 }}</h5>
                    <div class="text-muted small">Students</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <i class="bi bi-journal-bookmark-fill fs-2 text-success mb-2"></i>
                    <h5 class="fw-bold mb-0">{{ $subjectsCount ?? 0 }}</h5>
                    <div class="text-muted small">Subjects</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <i class="bi bi-pencil-square fs-2 text-warning mb-2"></i>
                    <h5 class="fw-bold mb-0">{{ $pendingGrades ?? 0 }}</h5>
                    <div class="text-muted small">Pending Grades</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <i class="bi bi-calendar-event fs-2 text-info mb-2"></i>
                    <h5 class="fw-bold mb-0">{{ $upcomingEvents ?? 0 }}</h5>
                    <div class="text-muted small">Upcoming Events</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- My Section -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="fw-semibold mb-0"><i class="bi bi-people me-2"></i>My Section</h5>
                </div>
                <div class="card-body">
                    @if(!empty($students) && count($students) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($students as $student)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="bi bi-person-circle text-primary me-2"></i>
                                        {{ $student->last_name }}, {{ $student->first_name }}
                                    </span>
                                    <a href="{{ route('teachers.student.grade.show', $student->user_id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-journal-text me-1"></i> Grades
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-muted text-center py-3">No students found in your section.</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- My Subjects -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="fw-semibold mb-0"><i class="bi bi-journal-bookmark me-2"></i>My Subjects</h5>
                </div>
                <div class="card-body">
                    @if(!empty($subjects) && count($subjects) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($subjects as $subject)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="bi bi-book text-success me-2"></i>
                                        {{ $subject->name }}
                                    </span>
                                    <a href="{{ route('teachers.subject.show', $subject->id) }}" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-people me-1"></i> View
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-muted text-center py-3">No subjects assigned.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Upcoming Events -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="fw-semibold mb-0"><i class="bi bi-calendar-event me-2"></i>Upcoming Events</h5>
                </div>
                <div class="card-body">
                    @if(!empty($events) && count($events) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($events as $event)
                                <li class="list-group-item">
                                    <div class="fw-medium">{{ $event->title }}</div>
                                    <div class="small text-muted">{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-muted text-center py-3">No upcoming events.</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="fw-semibold mb-0"><i class="bi bi-clock-history me-2"></i>Recent Activity</h5>
                </div>
                <div class="card-body">
                    @if(!empty($activities) && count($activities) > 0)
                        <ul class="timeline">
                            @foreach($activities as $activity)
                                <li>
                                    <span class="timeline-dot"></span>
                                    <span class="timeline-content">{{ $activity }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-muted text-center py-3">No recent activity.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles for dashboard -->
<style>
.clock-display {
    text-align: right;
    padding: 0.75rem;
    background: rgba(13, 110, 253, 0.05);
    border-radius: 0.75rem;
    min-width: 180px;
}
.clock-display .time {
    font-size: 1.5rem;
    font-weight: 600;
    color: #0d6efd;
    font-family: 'Courier New', monospace;
    letter-spacing: 1px;
}
.clock-display .date {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 500;
}
.timeline {
    list-style: none;
    padding-left: 0;
    position: relative;
}
.timeline li {
    position: relative;
    padding-left: 1.5rem;
    margin-bottom: 1rem;
}
.timeline-dot {
    position: absolute;
    left: 0;
    top: 0.4rem;
    width: 0.7rem;
    height: 0.7rem;
    background: #0d6efd;
    border-radius: 50%;
    display: inline-block;
}
.timeline-content {
    font-size: 0.95rem;
}
</style>

<!-- Clock Script -->
<script>
function updateClock() {
    const now = new Date();
    const time = now.toLocaleTimeString('en-US', {
        hour12: false,
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
    const date = now.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    document.getElementById('current-time').textContent = time;
    document.getElementById('current-date').textContent = date;
}
updateClock();
setInterval(updateClock, 1000);
</script>
@endsection

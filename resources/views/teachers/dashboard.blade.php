<!-- resources/views/dashboard.blade.php -->
@extends('layouts.teacherApp')

@section('content')
<div class="container-fluid py-3">
    <!-- Welcome Section -->
    <div class="welcome-banner mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-6 fw-bold text-primary mb-2">
                            {{ $dashboardTitle ?? 'Teacher Dashboard' }}
                        </h1>
                        <p class="text-muted mb-0">Welcome back, {{ Auth::user()->name }}! 👋</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <div class="clock-display">
                            <div class="time" id="current-time">00:00:00</div>
                            <div class="date" id="current-date">Loading...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(View::exists('teachers.dashboard.' . Auth::user()->role))
        @include('teachers.dashboard.' . Auth::user()->role)
    @else
    <!-- Quick Actions -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <h5 class="fw-semibold mb-3">Quick Actions</h5>
            <div class="d-flex flex-wrap gap-2">
                @foreach($quickActions ?? [] as $action)
                    <a href="{{ $action['url'] }}" class="btn {{ $action['primary'] ? 'btn-primary' : 'btn-outline-primary' }} shadow-sm">
                        <i class="bi {{ $action['icon'] }} me-2"></i>{{ $action['label'] }}
                    </a>
                @endforeach

                @if(empty($quickActions))
                    <a href="{{ route('teachers.student.index') }}" class="btn btn-pink shadow-sm">
                        <i class="bi bi-person-plus me-2"></i>Add New Student
                    </a>
                    <a href="{{ route('teachers.event.index') }}" class="btn btn-pink shadow-sm">
                        <i class="bi bi-calendar-event me-2"></i>Create New Event
                    </a>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Styles -->
<style>
    .card {
        border-radius: 0.5rem;
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }

    .btn-pink {
        background: linear-gradient(to right, #fcd9e8, #fdeaf3);
        color: #e83e8c;
        border: none;
        border-radius: 12px;
        padding: 0.5rem 1.2rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-pink:hover {
        background: linear-gradient(to right, #fbc6e2, #fdd9ec);
        color: #d63384;
        box-shadow: 0 6px 12px rgba(232, 62, 140, 0.25);
        transform: translateY(-2px);
    }

    .clock-display {
        text-align: center;
        padding: 0.75rem;
        background: rgba(13, 110, 253, 0.05);
        border-radius: 0.75rem;
        display: inline-block;
        min-width: 180px;
    }

    .clock-display .time {
        font-size: 1.75rem;
        font-weight: 600;
        color: #0d6efd;
        line-height: 1;
        margin-bottom: 0.25rem;
        font-family: 'Courier New', monospace;
        letter-spacing: 1px;
    }

    .clock-display .date {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
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

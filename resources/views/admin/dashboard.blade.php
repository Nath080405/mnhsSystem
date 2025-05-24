<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <!-- Welcome Banner -->
        <div class="welcome-banner mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-6 fw-bold text-primary mb-2">Welcome back, {{ Auth::user()->name }}! 👋</h1>
                            <p class="text-muted mb-0">Here's an overview of your school's current status.</p>
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

        <div class="row g-4">
            <!-- Left Column: Stats and Quick Actions -->
            <div class="col-lg-8">
                <!-- Stats Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1 small text-uppercase fw-semibold">Total Users</h6>
                                        <h3 class="mb-0 fw-bold">{{ $stats['users'] ?? 0 }}</h3>
                                    </div>
                                    <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle">
                                        <i class="bi bi-people-fill text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1 small text-uppercase fw-semibold">Total Subjects</h6>
                                        <h3 class="mb-0 fw-bold">{{ $stats['subjects'] ?? 0 }}</h3>
                                    </div>
                                    <div class="avatar-sm bg-success bg-opacity-10 rounded-circle">
                                        <i class="bi bi-book text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1 small text-uppercase fw-semibold">Active Classes</h6>
                                        <h3 class="mb-0 fw-bold">{{ $stats['classes'] ?? 0 }}</h3>
                                    </div>
                                    <div class="avatar-sm bg-info bg-opacity-10 rounded-circle">
                                        <i class="bi bi-mortarboard-fill text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1 small text-uppercase fw-semibold">Total Events</h6>
                                        <h3 class="mb-0 fw-bold">{{ $stats['events'] ?? 0 }}</h3>
                                    </div>
                                    <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle">
                                        <i class="bi bi-calendar-event text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
                                <a href="{{ route('admin.students.create') }}" class="btn btn-primary shadow-sm">
                                    <i class="bi bi-person-plus me-2"></i>Add New Student
                                </a>
                                <a href="#" class="btn btn-outline-primary shadow-sm">
                                    <i class="bi bi-file-earmark-plus me-2"></i>Create New Class
                                </a>
                                <a href="#" class="btn btn-outline-primary shadow-sm">
                                    <i class="bi bi-calendar-plus me-2"></i>Schedule Event
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Recent Activity -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-3">
                        <h5 class="fw-semibold mb-3">Recent Activity</h5>
                        <div class="activity-feed">
                            @forelse($recentActivities ?? [] as $activity)
                                <div class="activity-item d-flex align-items-start mb-3">
                                    <div class="activity-icon me-3">
                                        <div class="avatar-sm bg-{{ $activity['color'] ?? 'primary' }}-bg-opacity-10 rounded-circle">
                                            <i class="bi {{ $activity['icon'] ?? 'bi-bell' }} text-{{ $activity['color'] ?? 'primary' }}"></i>
                                        </div>
                                    </div>
                                    <div class="activity-content">
                                        <p class="mb-1">{{ $activity['message'] }}</p>
                                        <small class="text-muted">{{ $activity['time'] }}</small>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-clock-history display-4 mb-3"></i>
                                    <p class="mb-0">No recent activities</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Welcome Banner Styles */
        .welcome-banner .card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 1rem;
        }

        .welcome-banner .display-6 {
            font-size: 1.75rem;
            line-height: 1.2;
        }

        .welcome-banner .text-muted {
            font-size: 0.95rem;
        }

        .welcome-banner h3 {
            font-size: 1.5rem;
            color: #0d6efd;
        }

        .welcome-banner small {
            font-size: 0.8rem;
        }

        /* Card Styles */
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

        /* Avatar Styles */
        .avatar-sm {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Activity Feed Styles */
        .activity-feed {
            max-height: 400px;
            overflow-y: auto;
        }

        .activity-item {
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: background-color 0.2s ease-in-out;
        }

        .activity-item:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .activity-icon {
            flex-shrink: 0;
        }

        .activity-content {
            flex-grow: 1;
        }

        .activity-content p {
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .activity-content small {
            font-size: 0.75rem;
        }

        /* Custom Scrollbar */
        .activity-feed::-webkit-scrollbar {
            width: 6px;
        }

        .activity-feed::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .activity-feed::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .activity-feed::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Clock Display Styles */
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

    <script>
        function updateClock() {
            const now = new Date();
            
            // Update time
            const time = now.toLocaleTimeString('en-US', { 
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('current-time').textContent = time;
            
            // Update date
            const date = now.toLocaleDateString('en-US', { 
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.getElementById('current-date').textContent = date;
        }

        // Update clock immediately and then every second
        updateClock();
        setInterval(updateClock, 1000);
    </script>
@endsection
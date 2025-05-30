<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
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
                        <div class="card stat-card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1 small text-uppercase fw-semibold">Total Users</h6>
                                        <h3 class="mb-0 fw-bold">{{ $stats['users'] ?? 0 }}</h3>
                                        <div class="d-flex gap-3 mt-2">
                                            <p class="text-primary mb-0 small">
                                                <i class="bi bi-person-fill"></i> {{ $stats['students'] ?? 0 }} Students
                                            </p>
                                            <p class="text-success mb-0 small">
                                                <i class="bi bi-person-badge"></i> {{ $stats['teachers'] ?? 0 }} Teachers
                                            </p>
                                        </div>
                                    </div>
                                    <div class="stat-icon bg-primary bg-opacity-10 rounded-circle">
                                        <i class="bi bi-people-fill text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card stat-card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1 small text-uppercase fw-semibold">Learning Areas</h6>
                                        <h3 class="mb-0 fw-bold">{{ $stats['subjects'] ?? 0 }}</h3>
                                        <div class="d-flex gap-3 mt-2">
                                            <p class="text-info mb-0 small">
                                                <i class="bi bi-bookmark"></i> {{ $stats['subject_labels'] ?? 0 }} Categories
                                            </p>
                                            <p class="text-success mb-0 small">
                                                <i class="bi bi-book"></i> {{ $stats['subjects'] ?? 0 }} Subjects
                                            </p>
                                        </div>
                                    </div>
                                    <div class="stat-icon bg-success bg-opacity-10 rounded-circle">
                                        <i class="bi bi-book text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card stat-card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1 small text-uppercase fw-semibold">Class Sections</h6>
                                        <h3 class="mb-0 fw-bold">{{ $stats['sections'] ?? 0 }}</h3>
                                        <p class="text-success mb-0 mt-2 small">
                                            <i class="bi bi-check-circle"></i> Active sections
                                        </p>
                                    </div>
                                    <div class="stat-icon bg-info bg-opacity-10 rounded-circle">
                                        <i class="bi bi-mortarboard-fill text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card stat-card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1 small text-uppercase fw-semibold">School Events</h6>
                                        <h3 class="mb-0 fw-bold">{{ $stats['events'] ?? 0 }}</h3>
                                        <p class="text-warning mb-0 mt-2 small">
                                            <i class="bi bi-calendar-event"></i> Upcoming events
                                        </p>
                                    </div>
                                    <div class="stat-icon bg-warning bg-opacity-10 rounded-circle">
                                        <i class="bi bi-calendar-event text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-semibold mb-3">Quick Actions</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <a href="{{ route('admin.students.create') }}" class="quick-action-card">
                                    <div class="icon-wrapper bg-primary bg-opacity-10">
                                        <i class="bi bi-person-plus text-primary"></i>
                                    </div>
                                    <div class="content">
                                        <h6 class="mb-1">Add New Student</h6>
                                        <p class="text-muted small mb-0">Register a new student</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('admin.sections.create') }}" class="quick-action-card">
                                    <div class="icon-wrapper bg-success bg-opacity-10">
                                        <i class="bi bi-file-earmark-plus text-success"></i>
                                    </div>
                                    <div class="content">
                                        <h6 class="mb-1">Create New Section</h6>
                                        <p class="text-muted small mb-0">Set up a new class section</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('admin.events.create') }}" class="quick-action-card">
                                    <div class="icon-wrapper bg-warning bg-opacity-10">
                                        <i class="bi bi-calendar-plus text-warning"></i>
                                    </div>
                                    <div class="content">
                                        <h6 class="mb-1">Schedule Event</h6>
                                        <p class="text-muted small mb-0">Plan a new school event</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Student Status Chart -->
            <div class="col-lg-4">
                <!-- Student Status Chart -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-semibold mb-3">Student Status Distribution</h5>
                        <div class="chart-container" style="position: relative; height: 400px;">
                            <canvas id="studentStatusChart"></canvas>
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

        /* Stat Card Styles */
        .stat-card {
            transition: transform 0.2s ease-in-out;
            border-radius: 1rem;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        /* Quick Action Card Styles */
        .quick-action-card {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: #ffffff;
            border-radius: 1rem;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease-in-out;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .quick-action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            color: inherit;
        }

        .quick-action-card .icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.5rem;
        }

        .quick-action-card .content h6 {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        /* Clock Display Styles */
        .clock-display {
            text-align: center;
            padding: 1rem;
            background: rgba(13, 110, 253, 0.05);
            border-radius: 1rem;
            display: inline-block;
            min-width: 200px;
        }

        .clock-display .time {
            font-size: 2rem;
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

        /* Chart Styles */
        .chart-container {
            margin: 1rem 0;
        }
    </style>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Update clock
            function updateClock() {
                const now = new Date();
                const timeElement = document.getElementById('current-time');
                const dateElement = document.getElementById('current-date');
                
                timeElement.textContent = now.toLocaleTimeString();
                dateElement.textContent = now.toLocaleDateString('en-US', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
            }
            
            updateClock();
            setInterval(updateClock, 1000);

            // Student Status Chart
            const studentStatusData = @json($studentStatuses);
            const statusColors = {
                'active': '#198754',
                'inactive': '#ffc107',
                'dropped': '#dc3545',
                'graduated': '#0dcaf0',
                'transferred': '#6c757d'
            };

            const ctx = document.getElementById('studentStatusChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(studentStatusData).map(status => status.charAt(0).toUpperCase() + status.slice(1)),
                        datasets: [{
                            data: Object.values(studentStatusData),
                            backgroundColor: Object.keys(studentStatusData).map(status => statusColors[status] || '#6c757d'),
                            borderWidth: 0,
                            borderRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: {
                                        size: 12
                                    }
                                }
                            }
                        },
                        cutout: '70%'
                    }
                });
            }
        });
    </script>
    @endpush
@endsection
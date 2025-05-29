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
                                        <p class="text-success mb-0 mt-2 small">
                                            <i class="bi bi-arrow-up"></i> All users
                                        </p>
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
                                        <h6 class="text-muted mb-1 small text-uppercase fw-semibold">Total Subjects</h6>
                                        <h3 class="mb-0 fw-bold">{{ $stats['subjects'] ?? 0 }}</h3>
                                        <p class="text-success mb-0 mt-2 small">
                                            <i class="bi bi-arrow-up"></i> Active subjects
                                        </p>
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
                                        <h6 class="text-muted mb-1 small text-uppercase fw-semibold">Active Classes</h6>
                                        <h3 class="mb-0 fw-bold">{{ $stats['classes'] ?? 0 }}</h3>
                                        <p class="text-success mb-0 mt-2 small">
                                            <i class="bi bi-arrow-up"></i> Current classes
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
                                        <h6 class="text-muted mb-1 small text-uppercase fw-semibold">Total Events</h6>
                                        <h3 class="mb-0 fw-bold">{{ $stats['events'] ?? 0 }}</h3>
                                        <p class="text-success mb-0 mt-2 small">
                                            <i class="bi bi-arrow-up"></i> Upcoming events
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
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ route('admin.students.create') }}" class="quick-action-btn btn btn-primary">
                                <i class="bi bi-person-plus me-2"></i>Add New Student
                            </a>
                            <a href="#" class="quick-action-btn btn btn-outline-primary">
                                <i class="bi bi-file-earmark-plus me-2"></i>Create New Class
                            </a>
                            <a href="#" class="quick-action-btn btn btn-outline-primary">
                                <i class="bi bi-calendar-plus me-2"></i>Schedule Event
                            </a>
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
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        /* Quick Action Button Styles */
        .quick-action-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
        }

        .quick-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
    </style>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = Math.round((value / total) * 100);
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        cutout: '70%'
                    }
                });
            }

            // Clock functionality
            function updateClock() {
                const now = new Date();
                const timeElement = document.getElementById('current-time');
                const dateElement = document.getElementById('current-date');
                
                timeElement.textContent = now.toLocaleTimeString('en-US', { 
                    hour12: false, 
                    hour: '2-digit', 
                    minute: '2-digit', 
                    second: '2-digit' 
                });
                
                dateElement.textContent = now.toLocaleDateString('en-US', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
            }

            updateClock();
            setInterval(updateClock, 1000);
        });
    </script>
    @endpush
@endsection
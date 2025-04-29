<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-primary">{{ $dashboardTitle ?? 'Admin Dashboard' }}</h2>
                <p class="text-muted mb-0 small">Welcome back, {{ Auth::user()->name }}</p>
            </div>
        </div>

        @if(View::exists('admin.dashboards.' . Auth::user()->role))
            @include('admin.dashboards.' . Auth::user()->role)
        @else
            <!-- Default Stats Cards -->
            <div class="row mb-4 g-3">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1 small text-uppercase fw-semibold">Total Students</h6>
                                    <h3 class="mb-0 fw-bold">{{ $stats['students'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle">
                                    <i class="bi bi-people-fill text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1 small text-uppercase fw-semibold">Total Teachers</h6>
                                    <h3 class="mb-0 fw-bold">{{ $stats['teachers'] ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm bg-success bg-opacity-10 rounded-circle">
                                    <i class="bi bi-person-workspace text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
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
                <div class="col-md-3">
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
                    <div class="d-flex gap-2">
                        @foreach($quickActions ?? [] as $action)
                            <a href="{{ $action['url'] }}"
                                class="btn {{ $action['primary'] ? 'btn-primary' : 'btn-outline-primary' }} shadow-sm">
                                <i class="bi {{ $action['icon'] }} me-2"></i>{{ $action['label'] }}
                            </a>
                        @endforeach

                        @if(empty($quickActions))

                            <!-- href="{{ route('students.create') }}" -->

                            <a class="btn btn-primary shadow-sm">
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
        @endif
    </div>

    <style>
        .avatar-sm {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-sm i {
            font-size: 1.1rem;
        }

        .card {
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }
    </style>
@endsection
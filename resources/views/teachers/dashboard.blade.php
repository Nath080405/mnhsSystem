<!-- resources/views/dashboard.blade.php -->
@extends('layouts.teacherApp')

@section('content')
    <div class="container-fluid py-3">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-primary">{{ $dashboardTitle ?? 'Teacher Dashboard' }}</h2>
                <p class="text-muted mb-0 small">Welcome back, {{ Auth::user()->name }}</p>
            </div>
        </div>

        @if(View::exists('teachers.dashboard.' . Auth::user()->role))
            @include('teachers.dashboard.' . Auth::user()->role)
        @else
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
                            <a href="{{ route('teachers.student.index') }}" class="btn btn-primary shadow-sm">
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
        .card {
            border-radius: 0.5rem;
        }
        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }
        .avatar-sm {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endsection
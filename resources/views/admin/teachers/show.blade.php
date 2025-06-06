@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="header-section mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold text-primary">Teacher Details</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-light">
                        <i class="bi bi-arrow-left me-1"></i> Back to Teachers
                    </a>
                    <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i> Edit Teacher
                    </a>
                </div>
            </div>
        </div>

        <div class="card main-card">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center mb-4">
                            <div class="avatar-container mb-3">
                                <div class="avatar-wrapper">
                                    <span class="avatar-icon">
                                        <i class="bi bi-person-workspace"></i>
                                    </span>
                                </div>
                            </div>
                            <h4 class="mb-1 fw-bold">{{ $teacher->formal_name }}</h4>
                            <p class="text-muted mb-3">Employee ID: {{ $teacher->teacher?->employee_id ?? 'Not Assigned' }}</p>
                            <div class="d-flex justify-content-center gap-2">
                                <span class="status-badge position-badge">
                                    <i class="bi bi-book me-1"></i> {{ $teacher->teacher?->section?->name ?? 'Not Assigned' }}
                                </span>
                                <span class="status-badge status-{{ $teacher->teacher?->status ?? 'inactive' }}">
                                    <i class="bi bi-{{ 
                                        $teacher->teacher?->status === 'active' ? 'check-circle' : 
                                        ($teacher->teacher?->status === 'inactive' ? 'x-circle' : 
                                        ($teacher->teacher?->status === 'on_leave' ? 'pause-circle' : 'question-circle')) }} me-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $teacher->teacher?->status ?? 'inactive')) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="card-header-custom">
                                        <i class="bi bi-person-vcard me-2"></i>
                                        <span>Personal Information</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-item">
                                            <label>Full Name</label>
                                            <p>{{ $teacher->formal_name }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Email</label>
                                            <p>
                                                <a href="mailto:{{ $teacher->email }}" class="text-decoration-none">{{ $teacher->email }}</a>
                                            </p>
                                        </div>
                                        <div class="info-item">
                                            <label>Phone</label>
                                            <p>{{ $teacher->teacher?->phone ?? 'Not provided' }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Gender</label>
                                            <p>{{ $teacher->teacher?->gender ?? 'Not provided' }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Birthdate</label>
                                            <p>{{ $teacher->teacher?->birthdate ? $teacher->teacher->birthdate->format('M d, Y') : 'Not provided' }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Address</label>
                                            <p>
                                                @if($teacher->teacher?->street_address || $teacher->teacher?->barangay || $teacher->teacher?->municipality || $teacher->teacher?->province)
                                                    {{ $teacher->teacher?->street_address ? $teacher->teacher->street_address . ', ' : '' }}
                                                    {{ $teacher->teacher?->barangay ? $teacher->teacher->barangay . ', ' : '' }}
                                                    {{ $teacher->teacher?->municipality ? $teacher->teacher->municipality . ', ' : '' }}
                                                    {{ $teacher->teacher?->province ? $teacher->teacher->province : '' }}
                                                @else
                                                    Not provided
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="card-header-custom">
                                        <i class="bi bi-briefcase me-2"></i>
                                        <span>Professional Information</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-item">
                                            <label>Employee ID</label>
                                            <p>{{ $teacher->teacher?->employee_id ?? 'Not Assigned' }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Grade Level</label>
                                            <p>{{ $teacher->teacher?->section?->grade_level ?? 'Not Assigned' }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Assigned Section</label>
                                            <p>{{ $teacher->teacher?->section?->name ?? 'Not Assigned' }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Role</label>
                                            <p>{{ ucfirst($teacher->role) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .text-gradient {
            background: linear-gradient(45deg, #2196F3, #4CAF50);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header-section {
            padding: 1rem;
            border-radius: 0.5rem;
            background: linear-gradient(45deg, #f8f9fa, #ffffff);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .main-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .avatar-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto;
        }

        .avatar-wrapper {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(45deg, #2196F3, #4CAF50);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0.5rem 1rem rgba(33, 150, 243, 0.3);
        }

        .avatar-icon {
            color: white;
            font-size: 3rem;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 500;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .position-badge {
            background: rgba(33, 150, 243, 0.1);
            color: #2196F3;
        }

        .status-active {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .status-inactive {
            background-color: #f8d7da;
            color: #842029;
        }

        .status-on_leave {
            background-color: #fff3cd;
            color: #856404;
        }

        .info-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
        }

        .card-header-custom {
            padding: 1rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            font-weight: 600;
            color: #2196F3;
            display: flex;
            align-items: center;
        }

        .info-item {
            margin-bottom: 1rem;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-item label {
            display: block;
            font-size: 0.875rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
        }

        .info-item p {
            margin: 0;
            font-weight: 500;
            color: #212529;
        }

        .btn-light {
            background: #f8f9fa;
            border-color: #f8f9fa;
            color: #212529;
        }

        .btn-light:hover {
            background: #e9ecef;
            border-color: #e9ecef;
            color: #212529;
        }

        .btn-primary {
            background: #0d6efd;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-primary:hover {
            background: #0b5ed7;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
@endsection 
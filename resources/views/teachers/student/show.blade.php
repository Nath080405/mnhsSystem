@extends('layouts.teacherApp')

@section('content')
    <div class="container py-4">
        <div class="header-section mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold text-primary">Student Details</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('teachers.student.index') }}" class="btn btn-light">
                        <i class="bi bi-arrow-left me-1"></i> Back to Students
                    </a>
                    <a href="{{ route('teachers.student.edit', $student->user_id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i> Edit Student
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
                                        <i class="bi bi-person-fill"></i>
                                    </span>
                                </div>
                            </div>
                            <h4 class="mb-1 fw-bold">{{ $student->user->formal_name }}</h4>
                            <p class="text-muted mb-3">Student ID: {{ $student->student_id ?? 'Not Assigned' }}</p>
                            <div class="d-flex justify-content-center gap-2">
                                <span class="status-badge grade-badge">
                                    <i class="bi bi-mortarboard me-1"></i> Grade {{ $student->grade_level ?? 'Not Assigned' }}
                                </span>
                                <span class="status-badge status-active">
                                    <i class="bi bi-check-circle me-1"></i> Active
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
                                            <p>{{ $student->user->formal_name }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Email</label>
                                            <p>
                                                <a href="mailto:{{ $student->user->email }}" class="text-decoration-none">{{ $student->user->email }}</a>
                                            </p>
                                        </div>
                                        <div class="info-item">
                                            <label>Phone</label>
                                            <p>{{ $student->phone ?? 'Not provided' }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Gender</label>
                                            <p>{{ $student->gender ?? 'Not provided' }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Birthdate</label>
                                            <p>{{ $student->birthdate ? $student->birthdate->format('M d, Y') : 'Not provided' }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Address</label>
                                            <p>
                                                @if($student->street_address || $student->barangay || $student->municipality || $student->province)
                                                    {{ $student->street_address }}, {{ $student->barangay }}, {{ $student->municipality }}, {{ $student->province }}
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
                                        <i class="bi bi-book me-2"></i>
                                        <span>Academic Information</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-item">
                                            <label>Student ID</label>
                                            <p>{{ $student->student_id ?? 'Not Assigned' }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>LRN</label>
                                            <p>{{ $student->lrn ?? 'Not Assigned' }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Grade Level</label>
                                            <p>{{ $student->grade_level ? 'Grade ' . $student->grade_level : 'Not Assigned' }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Section</label>
                                            <p>{{ $student->section ? $student->section : 'Not Assigned' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="info-card">
                                    <div class="card-header-custom">
                                        <i class="bi bi-gear me-2"></i>
                                        <span>System Information</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-4">
                                            <div class="col-md-4">
                                                <div class="info-item">
                                                    <label>Role</label>
                                                    <p>{{ ucfirst($student->user->role) }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="info-item">
                                                    <label>Last Updated</label>
                                                    <p>{{ $student->updated_at->format('M d, Y H:i') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="info-item">
                                                    <label>Created At</label>
                                                    <p>{{ $student->created_at->format('M d, Y H:i') }}</p>
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
        </div>
    </div>

    <style>
        .main-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .info-card {
            background: #fff;
            border-radius: 0.75rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        .card-header-custom {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            padding: 1rem;
            border-radius: 0.75rem 0.75rem 0 0;
            font-weight: 500;
            color: #495057;
        }

        .avatar-container {
            width: 120px;
            height: 120px;
            margin: 0 auto;
        }

        .avatar-wrapper {
            width: 100%;
            height: 100%;
            background-color: #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-icon {
            font-size: 3rem;
            color: #6c757d;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .grade-badge {
            background-color: #e7f5ff;
            color: #0d6efd;
        }

        .status-active {
            background-color: #d1e7dd;
            color: #198754;
        }

        .info-item {
            margin-bottom: 1rem;
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
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #212529;
        }

        .btn-light:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
            color: #212529;
        }
    </style>
@endsection
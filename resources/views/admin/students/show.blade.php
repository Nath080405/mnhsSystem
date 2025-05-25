@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="header-section mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold text-primary">Student Details</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-light">
                        <i class="bi bi-arrow-left me-1"></i> Back to Students
                    </a>
                    <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-primary">
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
                            <h4 class="mb-1 fw-bold">{{ $student->formal_name }}</h4>
                            <p class="text-muted mb-3">Student ID: {{ $student->student?->student_id ?? 'Not Assigned' }}</p>
                            <div class="d-flex justify-content-center gap-2">
                                <span class="status-badge grade-badge">
                                    <i class="bi bi-mortarboard me-1"></i> Grade {{ $student->student?->grade_level ?? 'Not Assigned' }}
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
                                            <p>{{ $student->formal_name }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Email</label>
                                            <p>
                                                <a href="mailto:{{ $student->email }}" class="text-decoration-none">{{ $student->email }}</a>
                                            </p>
                                        </div>
                                        <div class="info-item">
                                            <label>Phone</label>
                                            <p>{{ $student->student?->phone ?? 'Not provided' }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Gender</label>
                                            <p>{{ $student->student?->gender ?? 'Not provided' }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Birthdate</label>
                                            <p>{{ $student->student?->birthdate ? $student->student->birthdate->format('M d, Y') : 'Not provided' }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Address</label>
                                            <p>
                                                @if($student->student?->street_address || $student->student?->barangay || $student->student?->municipality || $student->student?->province)
                                                    {{ $student->student->street_address }}, {{ $student->student->barangay }}, {{ $student->student->municipality }}, {{ $student->student->province }}
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
                                            <p>{{ $student->student?->student_id ?? 'Not Assigned' }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>LRN</label>
                                            <p>{{ $student->student?->lrn ?? 'Not Assigned' }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Grade Level</label>
                                            <p>{{ $student->student?->grade_level ? 'Grade ' . $student->student->grade_level : 'Not Assigned' }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Section</label>
                                            <p>{{ $student->student?->section ? 'Section ' . $student->student->section : 'Not Assigned' }}</p>
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
                                                    <p>{{ ucfirst($student->role) }}</p>
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

        .grade-badge {
            background: rgba(33, 150, 243, 0.1);
            color: #2196F3;
        }

        .status-active {
            background: rgba(76, 175, 80, 0.1);
            color: #4CAF50;
        }

        .info-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
        }

        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
        }

        .card-header-custom {
            padding: 1rem 1.5rem;
            background: linear-gradient(45deg, #f8f9fa, #ffffff);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            border-radius: 1rem 1rem 0 0;
            font-weight: 600;
            color: #2c3e50;
            display: flex;
            align-items: center;
        }

        .card-body {
            padding: 1.5rem;
        }

        .info-item {
            margin-bottom: 1.25rem;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-item label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #6c757d;
            margin-bottom: 0.25rem;
        }

        .info-item p {
            margin: 0;
            color: #2c3e50;
            font-weight: 500;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
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

        .btn-light {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
        }
    </style>
@endsection
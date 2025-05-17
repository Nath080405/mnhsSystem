@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Student Details</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back to Students
                </a>
                <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i> Edit Student
                </a>
            </div>
        </div>

        <div class="card shadow-lg" style="box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.2) !important;">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto mb-3">
                                <span class="avatar-title bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-person-fill display-4"></i>
                                </span>
                            </div>
                            <h4 class="mb-1">{{ $student->formal_name }}</h4>
                            <p class="text-muted mb-3">Student ID: {{ $student->student?->student_id ?? 'Not Assigned' }}</p>
                            <div class="d-flex justify-content-center gap-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                    <i class="bi bi-mortarboard me-1"></i> Grade {{ $student->student?->grade_level ?? 'Not Assigned' }}
                                </span>
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                                    <i class="bi bi-check-circle me-1"></i> Active
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card shadow-lg h-100">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-3 small text-uppercase fw-semibold">Personal Information</h6>
                                        <div class="mb-3">
                                            <label class="form-label">Full Name</label>
                                            <p class="mb-0">{{ $student->formal_name }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <p class="mb-0">
                                                <a href="mailto:{{ $student->email }}" class="text-decoration-none">{{ $student->email }}</a>
                                            </p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Phone</label>
                                            <p class="mb-0">{{ $student->student?->phone ?? 'Not provided' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Gender</label>
                                            <p class="mb-0">{{ $student->student?->gender ?? 'Not provided' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Birthdate</label>
                                            <p class="mb-0">{{ $student->student?->birthdate ? $student->student->birthdate->format('M d, Y') : 'Not provided' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Address</label>
                                            <p class="mb-0">
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
                                <div class="card shadow-lg h-100">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-3 small text-uppercase fw-semibold">Academic Information</h6>
                                        <div class="mb-3">
                                            <label class="form-label">Student ID</label>
                                            <p class="mb-0">{{ $student->student?->student_id ?? 'Not Assigned' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">LRN</label>
                                            <p class="mb-0">{{ $student->student?->lrn ?? 'Not Assigned' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Grade Level</label>
                                            <p class="mb-0">{{ $student->student?->grade_level ? 'Grade ' . $student->student->grade_level : 'Not Assigned' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Section</label>
                                            <p class="mb-0">{{ $student->student?->section ? 'Section ' . $student->student->section : 'Not Assigned' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card shadow-lg">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-3 small text-uppercase fw-semibold">System Information</h6>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Role</label>
                                                    <p class="mb-0">{{ ucfirst($student->role) }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Last Updated</label>
                                                    <p class="mb-0">{{ $student->updated_at->format('M d, Y H:i') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Created At</label>
                                                    <p class="mb-0">{{ $student->created_at->format('M d, Y H:i') }}</p>
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
        .avatar-lg {
            width: 120px;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .avatar-title {
            font-size: 24px;
            font-weight: 500;
        }

        .card {
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.2) !important;
        }

        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .shadow-lg {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #6c757d;
        }
    </style>
@endsection
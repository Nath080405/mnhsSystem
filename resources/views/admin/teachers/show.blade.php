@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-primary">Teacher Details</h2>
                <p class="text-muted mb-0 small">View and manage teacher information</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-pencil me-1"></i> Edit Teacher
                </a>
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Teachers
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Teacher Profile Card -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto mb-3">
                                <span class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                    <i class="bi bi-person-workspace display-6"></i>
                                </span>
                            </div>
                            <h4 class="mb-1">{{ $teacher->name }}</h4>
                            <p class="text-muted mb-2">{{ $teacher->teacher?->position ?? 'Teacher' }}</p>
                            <span class="badge bg-{{ $teacher->teacher?->status === 'active' ? 'success' : 'danger' }} text-capitalize px-3 py-1">
                                {{ ucfirst($teacher->teacher?->status ?? 'N/A') }}
                            </span>
                        </div>

                        <div class="border-top pt-4">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2">
                                            <i class="bi bi-envelope text-primary"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted mb-0 small">Email</p>
                                            <a href="mailto:{{ $teacher->email }}" class="text-decoration-none">{{ $teacher->email }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-info bg-opacity-10 rounded-circle me-2">
                                            <i class="bi bi-phone text-info"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted mb-0 small">Phone</p>
                                            <p class="mb-0">{{ $teacher->teacher?->phone ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-success bg-opacity-10 rounded-circle me-2">
                                            <i class="bi bi-building text-success"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted mb-0 small">Department</p>
                                            <p class="mb-0">{{ $teacher->teacher?->department ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle me-2">
                                            <i class="bi bi-mortarboard text-warning"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted mb-0 small">Qualification</p>
                                            <p class="mb-0">{{ $teacher->teacher?->qualification ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teacher Details -->
            <div class="col-md-8">
                <!-- Basic Information -->
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-primary mb-4">
                            <i class="bi bi-person-badge me-2"></i>Basic Information
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-1 small">Employee ID</p>
                                <p class="mb-0 fw-medium">{{ $teacher->teacher?->employee_id ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1 small">Date Joined</p>
                                <p class="mb-0 fw-medium">{{ $teacher->teacher?->date_joined ? $teacher->teacher->date_joined->format('M d, Y') : 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1 small">Gender</p>
                                <p class="mb-0 fw-medium text-capitalize">{{ $teacher->teacher?->gender ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1 small">Date of Birth</p>
                                <p class="mb-0 fw-medium">{{ $teacher->teacher?->birthdate ? $teacher->teacher->birthdate->format('M d, Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-primary mb-4">
                            <i class="bi bi-briefcase me-2"></i>Professional Information
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <p class="text-muted mb-1 small">Position</p>
                                <p class="mb-0 fw-medium">{{ $teacher->teacher?->position ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1 small">Department</p>
                                <p class="mb-0 fw-medium">{{ $teacher->teacher?->department ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1 small">Qualification</p>
                                <p class="mb-0 fw-medium">{{ $teacher->teacher?->qualification ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1 small">Specialization</p>
                                <p class="mb-0 fw-medium">{{ $teacher->teacher?->specialization ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card shadow-lg border-0">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-primary mb-4">
                            <i class="bi bi-geo-alt me-2"></i>Contact Information
                        </h5>
                        <div class="row g-3">
                            <div class="col-12">
                                <p class="text-muted mb-1 small">Address</p>
                                <p class="mb-0 fw-medium">{{ $teacher->teacher?->address ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1 small">Email</p>
                                <p class="mb-0 fw-medium">
                                    <a href="mailto:{{ $teacher->email }}" class="text-decoration-none">{{ $teacher->email }}</a>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1 small">Phone</p>
                                <p class="mb-0 fw-medium">{{ $teacher->teacher?->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .avatar-lg {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-sm {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-title {
            font-size: 1.5rem;
            font-weight: 500;
        }

        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
            border-radius: 0.375rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-weight: 500;
            border-radius: 0.375rem;
        }

        .text-muted {
            font-size: 0.8125rem;
        }

        .fw-medium {
            font-weight: 500;
        }
    </style>
@endsection 
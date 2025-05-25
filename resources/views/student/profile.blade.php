@extends('layouts.studentApp')

@section('content')
<div class="container-fluid py-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-primary">My Profile</h2>
            <p class="text-muted mb-0 small">View your profile summary</p>
        </div>
        <a href="{{ route('student.gradebook') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <div class="row">
        <!-- Profile Summary -->
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="avatar-lg mx-auto mb-3">
                            <span class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                <i class="bi bi-person-workspace display-6"></i>
                            </span>
                        </div>
                        <h4 class="mb-1">{{ $user->first_name }} {{ $user->middle_name ? $user->middle_name . ' ' : '' }}{{ $user->last_name }}{{ $user->suffix ? ' ' . $user->suffix : '' }}</h4>
                        <p class="text-muted mb-2">{{ ucfirst($user->role) }}</p>
                        <span class="badge bg-success text-capitalize px-3 py-1">
                            Active
                        </span>
                    </div>

                    <div class="row g-4">
                        <!-- Academic Information -->
                        <div class="col-md-6">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="text-muted mb-3 small text-uppercase fw-semibold">Academic Information</h6>
                                    <div class="mb-3">
                                        <p class="text-muted mb-1 small">Student ID</p>
                                        <p class="mb-0 fw-medium">{{ $user->student?->student_id ?? 'Not Assigned' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <p class="text-muted mb-1 small">LRN</p>
                                        <p class="mb-0 fw-medium">{{ $user->student?->lrn ?? 'Not Assigned' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <p class="text-muted mb-1 small">Grade Level</p>
                                        <p class="mb-0 fw-medium">{{ $user->student?->grade_level ? 'Grade ' . $user->student->grade_level : 'Not Assigned' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <p class="text-muted mb-1 small">Section</p>
                                        <p class="mb-0 fw-medium">{{ $user->student?->section ? 'Section ' . $user->student->section : 'Not Assigned' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="col-md-6">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="text-muted mb-3 small text-uppercase fw-semibold">Contact Information</h6>
                                    <div class="mb-3">
                                        <p class="text-muted mb-1 small">Email</p>
                                        <p class="mb-0 fw-medium">{{ $user->email }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <p class="text-muted mb-1 small">Phone</p>
                                        <p class="mb-0 fw-medium">{{ $user->student?->phone ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <p class="text-muted mb-1 small">Address</p>
                                        <p class="mb-0 fw-medium">
                                            @if($user->student?->street_address || $user->student?->barangay || $user->student?->municipality || $user->student?->province)
                                                {{ $user->student->street_address }}, {{ $user->student->barangay }}, {{ $user->student->municipality }}, {{ $user->student->province }}
                                            @else
                                                Not provided
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="col-md-6">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="text-muted mb-3 small text-uppercase fw-semibold">Personal Information</h6>
                                    <div class="mb-3">
                                        <p class="text-muted mb-1 small">Gender</p>
                                        <p class="mb-0 fw-medium">{{ $user->student?->gender ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <p class="text-muted mb-1 small">Birthdate</p>
                                        <p class="mb-0 fw-medium">{{ $user->student?->birthdate ? $user->student->birthdate->format('M d, Y') : 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Information -->
                        <div class="col-md-6">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="text-muted mb-3 small text-uppercase fw-semibold">System Information</h6>
                                    <div class="mb-3">
                                        <p class="text-muted mb-1 small">Member Since</p>
                                        <p class="mb-0 fw-medium">{{ $user->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <p class="text-muted mb-1 small">Last Updated</p>
                                        <p class="mb-0 fw-medium">{{ $user->updated_at->format('M d, Y H:i') }}</p>
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
    font-size: 28px;
    font-weight: 500;
}
.card {
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}
.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}
/* Decreased padding and spacing */
.card-body {
    padding: 0.5rem !important;
}
.mb-3 {
    margin-bottom: 0.25rem !important;
}
.mb-4 {
    margin-bottom: 0.35rem !important;
}
.row.g-4 {
    --bs-gutter-y: 0.35rem;
}
.card.bg-light {
    margin-bottom: 0.25rem;
}
.card.bg-light .card-body {
    padding: 0.35rem !important;
}
/* Increased font sizes */
h2.fw-bold {
    font-size: 2rem;
}
h4.mb-1 {
    font-size: 2rem;
}
h6.text-muted {
    font-size: 1.1rem;
}
p.text-muted.mb-1.small {
    font-size: 0.95rem;
}
p.mb-0.fw-medium {
    font-size: 1.1rem;
}
.badge {
    font-size: 0.95rem;
}
</style>
@endsection 
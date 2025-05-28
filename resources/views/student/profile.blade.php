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
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

   <div class="row">
        <!-- Profile Information -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('student.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle-fill me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control bg-light" value="{{ $user->last_name }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control bg-light" value="{{ $user->first_name }}" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Middle Name</label>
                                <input type="text" class="form-control bg-light" value="{{ $user->middle_name }}" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Suffix</label>
                                <input type="text" class="form-control bg-light" value="{{ $user->suffix }}" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Change Password</h5>
                        <p class="text-muted small mb-3">You can change your password here.</p>

                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" class="form-control">
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-key me-1"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Profile Summary -->
        <div class="col-md-4">
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

                    <div class="border-top pt-3">
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
}
.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}
.alert {
    border: none;
    border-radius: 0.5rem;
}
.alert-success {
    background-color: #d1e7dd;
    color: #0f5132;
}
.alert-danger {
    background-color: #f8d7da;
    color: #842029;
}
.form-control[readonly] {
    background-color: #f8f9fa;
    cursor: not-allowed;
}
</style>
@endsection 
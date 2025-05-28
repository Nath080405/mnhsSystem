@extends('layouts.teacherApp')

@section('content')
    <div class="container py-4">
        <div class="header-section mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold text-primary">Edit Student Information</h2>
                <a href="{{ route('teachers.student.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left me-1"></i> Back to Directory
                </a>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card main-card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('teachers.student.update', $student->user_id) }}" id="studentForm">
                    @csrf
                    @method('PUT')
                    <div class="row g-4">
                        <!-- Student Personal Information -->
                        <div class="col-md-6">
                            <div class="info-card h-100">
                                <div class="card-header-custom">
                                    <i class="bi bi-person-vcard me-2"></i>
                                    <span>Personal Information</span>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $student->user->last_name) }}" placeholder="Enter last name" required>
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">First Name <span class="text-danger">*</span></label>
                                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $student->user->first_name) }}" placeholder="Enter first name" required>
                                            @error('first_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Middle Name</label>
                                            <input type="text" name="middle_name" class="form-control @error('middle_name') is-invalid @enderror" value="{{ old('middle_name', $student->user->middle_name) }}" placeholder="Enter middle name">
                                            @error('middle_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Suffix</label>
                                            <input type="text" name="suffix" class="form-control @error('suffix') is-invalid @enderror" value="{{ old('suffix', $student->user->suffix) }}" placeholder="Enter suffix">
                                            @error('suffix')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Gender <span class="text-danger">*</span></label>
                                            <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                                <option value="">Select gender</option>
                                                <option value="Male" {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                                <option value="Other" {{ old('gender', $student->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('gender')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Birthdate <span class="text-danger">*</span></label>
                                            <input type="date" name="birthdate" class="form-control @error('birthdate') is-invalid @enderror" value="{{ old('birthdate', $student->birthdate) }}" required>
                                            @error('birthdate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Student Contact Information -->
                        <div class="col-md-6">
                            <div class="info-card h-100">
                                <div class="card-header-custom">
                                    <i class="bi bi-telephone me-2"></i>
                                    <span>Contact Information</span>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $student->user->email) }}" placeholder="Enter email" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Phone</label>
                                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $student->phone) }}" placeholder="Enter phone number">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label fw-medium">Address</label>
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <input type="text" name="street_address" class="form-control @error('street_address') is-invalid @enderror" value="{{ old('street_address', $student->street_address) }}" placeholder="Street Address">
                                                    @error('street_address')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="barangay" class="form-control @error('barangay') is-invalid @enderror" value="{{ old('barangay', $student->barangay) }}" placeholder="Barangay">
                                                    @error('barangay')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="municipality" class="form-control @error('municipality') is-invalid @enderror" value="{{ old('municipality', $student->municipality) }}" placeholder="Municipality">
                                                    @error('municipality')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="province" class="form-control @error('province') is-invalid @enderror" value="{{ old('province', $student->province) }}" placeholder="Province">
                                                    @error('province')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Academic Information -->
                        <div class="col-md-6">
                            <div class="info-card h-100">
                                <div class="card-header-custom">
                                    <i class="bi bi-mortarboard me-2"></i>
                                    <span>Academic Information</span>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Student ID</label>
                                            <input type="text" class="form-control bg-light" value="{{ $student->student_id }}" disabled>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">LRN <span class="text-danger">*</span></label>
                                            <input type="text" name="lrn" class="form-control @error('lrn') is-invalid @enderror" value="{{ old('lrn', $student->lrn) }}" placeholder="Enter LRN" required>
                                            @error('lrn')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Grade Level <span class="text-danger">*</span></label>
                                            <select name="grade_level" id="grade_level" class="form-select @error('grade_level') is-invalid @enderror" required>
                                                <option value="">Select grade level</option>
                                                @for($i = 7; $i <= 12; $i++)
                                                    <option value="{{ $i }}" {{ old('grade_level', $student->grade_level) == $i ? 'selected' : '' }}>
                                                        Grade {{ $i }}
                                                    </option>
                                                @endfor
                                            </select>
                                            @error('grade_level')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Section</label>
                                            <input type="text" class="form-control bg-light" value="{{ $student->section }}" readonly>
                                            <input type="hidden" name="section" value="{{ $student->section }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Update Student
                        </button>
                    </div>
                </form>
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

        .btn-outline-primary {
            border-width: 2px;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
            min-width: 120px;
        }

        .btn-outline-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

        .form-label {
            font-weight: 500;
            color: #495057;
        }

        .form-control, .form-select {
            border-color: #dee2e6;
            border-radius: 0.5rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .btn-xs {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            line-height: 1.5;
            border-radius: 0.25rem;
        }
    </style>
@endsection

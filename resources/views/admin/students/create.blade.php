@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="header-section mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold text-primary">Add New Student</h2>
                <a href="{{ route('admin.students.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left me-1"></i> Back to Students
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
                <form method="POST" action="{{ route('admin.students.store') }}" id="studentForm">
                    @csrf
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
                                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" placeholder="Enter last name" required>
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">First Name <span class="text-danger">*</span></label>
                                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" placeholder="Enter first name" required>
                                            @error('first_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Middle Name</label>
                                            <input type="text" name="middle_name" class="form-control @error('middle_name') is-invalid @enderror" value="{{ old('middle_name') }}" placeholder="Enter middle name">
                                            @error('middle_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Suffix</label>
                                            <input type="text" name="suffix" class="form-control @error('suffix') is-invalid @enderror" value="{{ old('suffix') }}" placeholder="Enter suffix">
                                            @error('suffix')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Gender <span class="text-danger">*</span></label>
                                            <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                                <option value="">Select gender</option>
                                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('gender')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Birthdate <span class="text-danger">*</span></label>
                                            <input type="date" name="birthdate" class="form-control @error('birthdate') is-invalid @enderror" value="{{ old('birthdate') }}" required>
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
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Enter email" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Phone</label>
                                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Enter phone number">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label fw-medium">Address</label>
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <input type="text" name="street_address" class="form-control @error('street_address') is-invalid @enderror" value="{{ old('street_address') }}" placeholder="Street Address">
                                                    @error('street_address')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="barangay" class="form-control @error('barangay') is-invalid @enderror" value="{{ old('barangay') }}" placeholder="Barangay">
                                                    @error('barangay')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="municipality" class="form-control @error('municipality') is-invalid @enderror" value="{{ old('municipality') }}" placeholder="Municipality">
                                                    @error('municipality')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="province" class="form-control @error('province') is-invalid @enderror" value="{{ old('province') }}" placeholder="Province">
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
                                            <input type="text" class="form-control bg-light" value="Auto-generated" disabled>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">LRN <span class="text-danger">*</span></label>
                                            <input type="text" name="lrn" class="form-control @error('lrn') is-invalid @enderror" value="{{ old('lrn') }}" placeholder="Enter LRN" required>
                                            @error('lrn')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Grade Level <span class="text-danger">*</span></label>
                                            <select name="grade_level" id="grade_level" class="form-select @error('grade_level') is-invalid @enderror" required>
                                                <option value="">Select grade level</option>
                                                @for($i = 7; $i <= 12; $i++)
                                                    <option value="{{ $i }}" {{ old('grade_level') == $i ? 'selected' : '' }}>
                                                        Grade {{ $i }}
                                                    </option>
                                                @endfor
                                            </select>
                                            @error('grade_level')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Section <span class="text-danger">*</span></label>
                                            <select name="section" id="section" class="form-select @error('section') is-invalid @enderror" required>
                                                <option value="">Select section</option>
                                                @foreach($sections as $section)
                                                    <option value="{{ $section->name }}" 
                                                        data-grade="{{ $section->grade_level }}"
                                                        {{ old('section') == $section->name ? 'selected' : '' }}>
                                                        {{ $section->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('section')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Information -->
                        <div class="col-md-6">
                            <div class="info-card h-100">
                                <div class="card-header-custom">
                                    <i class="bi bi-shield-lock me-2"></i>
                                    <span>Account Information</span>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Password <span class="text-danger">*</span></label>
                                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Confirm Password <span class="text-danger">*</span></label>
                                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Save Student
                        </button>
                    </div>
                </form>
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

        .form-label {
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }

        .form-control, .form-select {
            border-radius: 0.5rem;
            border: 1px solid #dee2e6;
            padding: 0.625rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #2196F3;
            box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.25);
        }

        .btn {
            padding: 0.625rem 1.25rem;
            font-weight: 500;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
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

        .btn-outline-secondary {
            border-color: #dee2e6;
            color: #6c757d;
        }

        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #495057;
        }

        .invalid-feedback {
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        @media (max-width: 991.98px) {
            .card-body {
                padding: 1.25rem;
            }

            .form-control, .form-select {
                padding: 0.5rem 0.875rem;
            }
        }
    </style>

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const gradeSelect = document.getElementById('grade_level');
                const sectionSelect = document.getElementById('section');
                const sectionOptions = sectionSelect.getElementsByTagName('option');

                function filterSections() {
                    const selectedGrade = gradeSelect.value;
                    
                    // Hide all sections first
                    for (let option of sectionOptions) {
                        if (option.value === '') continue;
                        option.style.display = 'none';
                    }

                    // Show only sections for the selected grade
                    if (selectedGrade) {
                        for (let option of sectionOptions) {
                            if (option.dataset.grade === selectedGrade) {
                                option.style.display = '';
                            }
                        }
                    }

                    // Reset section selection
                    sectionSelect.value = '';
                }

                // Initial filter
                filterSections();

                // Filter when grade changes
                gradeSelect.addEventListener('change', filterSections);
            });
        </script>
    @endsection
@endsection
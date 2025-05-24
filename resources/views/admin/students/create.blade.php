@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary">Add New Student</h2>
            <a href="{{ route('admin.students.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-1"></i> Back to Students
            </a>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.students.store') }}" id="studentForm">
            @csrf
            <div class="row g-4">
                <!-- Student Personal Information -->
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-person-vcard me-2"></i>Student Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" placeholder="Enter last name" required>
                                    <div class="validation-message mt-1"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" placeholder="Enter first name" required>
                                    <div class="validation-message mt-1"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Middle Name</label>
                                    <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name') }}" placeholder="Enter middle name">
                                    <div class="validation-message mt-1"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Suffix</label>
                                    <input type="text" name="suffix" class="form-control" value="{{ old('suffix') }}" placeholder="Enter suffix (e.g., Jr., Sr., III)">
                                    <div class="validation-message mt-1"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Gender <span class="text-danger">*</span></label>
                                    <select name="gender" class="form-select" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <div class="validation-message mt-1"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Birthdate <span class="text-danger">*</span></label>
                                    <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate') }}" required>
                                    <div class="validation-message mt-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Contact Information -->
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-telephone me-2"></i>Student Contact Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Enter email address" required>
                                    <div class="validation-message mt-1"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Phone</label>
                                    <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="Enter phone number">
                                    <div class="validation-message mt-1"></div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-medium">Address</label>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <input type="text" name="street_address" class="form-control" value="{{ old('street_address') }}" placeholder="Street Address">
                                            <div class="validation-message mt-1"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="barangay" class="form-control" value="{{ old('barangay') }}" placeholder="Barangay">
                                            <div class="validation-message mt-1"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="municipality" class="form-control" value="{{ old('municipality') }}" placeholder="Municipality">
                                            <div class="validation-message mt-1"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="province" class="form-control" value="{{ old('province') }}" placeholder="Province">
                                            <div class="validation-message mt-1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-mortarboard me-2"></i>Academic Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Student ID</label>
                                    <input type="text" class="form-control bg-light" value="Auto-generated" disabled>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">LRN <span class="text-danger">*</span></label>
                                    <input type="text" name="lrn" class="form-control" value="{{ old('lrn') }}" placeholder="Enter LRN" required>
                                    <div class="validation-message mt-1"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Grade Level <span class="text-danger">*</span></label>
                                    <select name="grade_level" id="grade_level" class="form-select" required>
                                        <option value="">Select Grade Level</option>
                                        @for($i = 7; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ old('grade_level') == $i ? 'selected' : '' }}>
                                                Grade {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    <div class="validation-message mt-1"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Section <span class="text-danger">*</span></label>
                                    <select name="section" id="section" class="form-select" required>
                                        <option value="">Select Section</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->name }}" 
                                                data-grade="{{ $section->grade_level }}"
                                                {{ old('section') == $section->name ? 'selected' : '' }}>
                                                {{ $section->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="validation-message mt-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-shield-lock me-2"></i>Account Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                                    <div class="validation-message mt-1"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
                                    <div class="validation-message mt-1"></div>
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

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('studentForm');
                const gradeSelect = document.getElementById('grade_level');
                const sectionSelect = document.getElementById('section');
                const sectionOptions = sectionSelect.getElementsByTagName('option');

                // Validation rules for each field
                const validationRules = {
                    'last_name': {
                        pattern: /^[A-Za-z\s\-']+$/,
                        message: 'Last name can only contain letters, spaces, hyphens, and apostrophes'
                    },
                    'first_name': {
                        pattern: /^[A-Za-z\s\-']+$/,
                        message: 'First name can only contain letters, spaces, hyphens, and apostrophes'
                    },
                    'middle_name': {
                        pattern: /^[A-Za-z\s\-']*$/,
                        message: 'Middle name can only contain letters, spaces, hyphens, and apostrophes'
                    },
                    'suffix': {
                        pattern: /^[A-Za-z\.]*$/,
                        message: 'Suffix can only contain letters and periods'
                    },
                    'email': {
                        pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                        message: 'Please enter a valid email address'
                    },
                    'phone': {
                        pattern: /^[0-9]{11}$/,
                        message: 'Phone number must be exactly 11 digits'
                    },
                    'lrn': {
                        pattern: /^[0-9]{12}$/,
                        message: 'LRN must be exactly 12 digits'
                    },
                    'password': {
                        pattern: /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/,
                        message: 'Password must be at least 8 characters with both letters and numbers'
                    }
                };

                // Function to validate a single input
                function validateInput(input) {
                    const rule = validationRules[input.name];
                    const messageDiv = input.nextElementSibling;
                    let isValid = true;
                    let errorMessage = '';

                    // Skip validation for empty optional fields
                    if (!input.required && !input.value) {
                        messageDiv.textContent = '';
                        messageDiv.className = 'validation-message mt-1';
                        input.classList.remove('is-invalid', 'is-valid');
                        return;
                    }

                    // Check if field is required and empty
                    if (input.required && !input.value) {
                        isValid = false;
                        errorMessage = 'This field is required';
                    }
                    // Check pattern if rule exists
                    else if (rule && rule.pattern && !rule.pattern.test(input.value)) {
                        isValid = false;
                        errorMessage = rule.message;
                    }
                    // Special handling for password confirmation
                    else if (input.name === 'password_confirmation') {
                        const password = form.querySelector('input[name="password"]');
                        if (password.value !== input.value) {
                            isValid = false;
                            errorMessage = 'Passwords do not match';
                        }
                    }
                    // Special handling for birthdate
                    else if (input.name === 'birthdate') {
                        const selectedDate = new Date(input.value);
                        const today = new Date();
                        if (selectedDate > today) {
                            isValid = false;
                            errorMessage = 'Birthdate cannot be in the future';
                        }
                    }

                    // Update input classes and message
                    input.classList.toggle('is-invalid', !isValid);
                    input.classList.toggle('is-valid', isValid && input.value !== '');

                    if (!isValid && errorMessage) {
                        messageDiv.textContent = errorMessage;
                        messageDiv.className = 'validation-message mt-1 text-danger small';
                    } else if (isValid && input.value) {
                        messageDiv.textContent = '✓ Valid';
                        messageDiv.className = 'validation-message mt-1 text-success small';
                    } else {
                        messageDiv.textContent = '';
                        messageDiv.className = 'validation-message mt-1';
                    }

                    return isValid;
                }

                // Add input event listeners to all form inputs
                form.querySelectorAll('input, select').forEach(input => {
                    // Initial validation
                    validateInput(input);

                    // Live validation on input/change
                    input.addEventListener('input', () => {
                        validateInput(input);
                        // If this is the password field, also validate confirmation
                        if (input.name === 'password') {
                            const confirmPassword = form.querySelector('input[name="password_confirmation"]');
                            if (confirmPassword.value) {
                                validateInput(confirmPassword);
                            }
                        }
                    });

                    input.addEventListener('change', () => validateInput(input));
                    input.addEventListener('blur', () => validateInput(input));
                });

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
                    validateInput(sectionSelect);
                }

                // Initial filter
                filterSections();

                // Filter when grade changes
                gradeSelect.addEventListener('change', () => {
                    filterSections();
                    validateInput(gradeSelect);
                });

                // Form submission validation
                form.addEventListener('submit', function(event) {
                    let isFormValid = true;
                    
                    // Validate all inputs
                    form.querySelectorAll('input, select').forEach(input => {
                        if (!validateInput(input)) {
                            isFormValid = false;
                        }
                    });

                    if (!isFormValid) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                });
            });
        </script>
    @endsection
@endsection
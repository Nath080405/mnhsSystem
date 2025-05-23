@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary">Edit Student</h2>
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

        <form method="POST" action="{{ route('admin.students.update', $student->id) }}">
            @csrf
            @method('PUT')
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
                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $student->last_name) }}" placeholder="Enter last name">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $student->first_name) }}" placeholder="Enter first name">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Middle Name</label>
                                    <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $student->middle_name) }}" placeholder="Enter middle name">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Suffix</label>
                                    <input type="text" name="suffix" class="form-control" value="{{ old('suffix', $student->suffix) }}" placeholder="Enter suffix (e.g., Jr., Sr., III)">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Gender <span class="text-danger">*</span></label>
                                    <select name="gender" class="form-select">
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender', $student->student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender', $student->student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender', $student->student->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Birthdate</label>
                                    <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate', $student->student->birthdate?->format('Y-m-d')) }}">
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
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $student->email) }}" placeholder="Enter email address">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Phone</label>
                                    <input type="tel" name="phone" class="form-control" value="{{ old('phone', $student->student->phone) }}" placeholder="Enter phone number">
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-medium">Address</label>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <input type="text" name="street_address" class="form-control" value="{{ old('street_address', $student->student->street_address) }}" placeholder="Street Address">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="barangay" class="form-control" value="{{ old('barangay', $student->student->barangay) }}" placeholder="Barangay">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="municipality" class="form-control" value="{{ old('municipality', $student->student->municipality) }}" placeholder="Municipality">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="province" class="form-control" value="{{ old('province', $student->student->province) }}" placeholder="Province">
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
                                    <input type="text" class="form-control bg-light" value="{{ $student->student?->student_id }}" disabled>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">LRN <span class="text-danger">*</span></label>
                                    <input type="text" name="lrn" class="form-control @error('lrn') is-invalid @enderror" value="{{ old('lrn', $student->student?->lrn) }}" placeholder="Enter LRN" required>
                                    @error('lrn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Grade Level <span class="text-danger">*</span></label>
                                    <select name="grade_level" id="grade_level" class="form-select" required>
                                        <option value="">Select Grade Level</option>
                                        @for($i = 7; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ old('grade_level', $student->student->grade_level) == $i ? 'selected' : '' }}>
                                                Grade {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Section <span class="text-danger">*</span></label>
                                    <select name="section" id="section" class="form-select" required>
                                        <option value="">Select Section</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->name }}" 
                                                data-grade="{{ $section->grade_level }}"
                                                {{ old('section', $student->student->section) == $section->name ? 'selected' : '' }}>
                                                {{ $section->name }}
                                            </option>
                                        @endforeach
                                    </select>
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
                                    <label class="form-label fw-medium">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
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
                    <i class="bi bi-check-circle me-1"></i> Update Student
                </button>
            </div>
        </form>
    </div>

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
                        if (option.value === '') continue; // Skip the default option
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

                    // Reset section selection if current section doesn't match grade
                    const currentSection = sectionSelect.value;
                    if (currentSection) {
                        const currentOption = sectionSelect.querySelector(`option[value="${currentSection}"]`);
                        if (currentOption && currentOption.dataset.grade !== selectedGrade) {
                            sectionSelect.value = '';
                        }
                    }
                }

                // Initial filter
                filterSections();

                // Filter when grade changes
                gradeSelect.addEventListener('change', filterSections);
            });
        </script>
    @endsection
@endsection

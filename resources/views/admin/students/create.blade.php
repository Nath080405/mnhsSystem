@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Add New Student</h2>
            <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
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

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.students.store') }}">
                    @csrf

                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Basic Information</h5>
                            <div class="mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       required value="{{ old('name') }}" placeholder="Enter full name"
                                       pattern="[A-Za-z\s\-]+" title="Only letters, spaces, and hyphens are allowed">
                                <div class="form-text">Enter the student's full name (letters, spaces, and hyphens only).</div>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       required value="{{ old('email') }}" placeholder="Enter email address">
                                <div class="form-text">Enter a valid email address.</div>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone') }}" placeholder="Enter phone number (e.g., 09123456789)"
                                       pattern="09[0-9]{9}" maxlength="11">
                                <div class="form-text">Enter a valid Philippine mobile number starting with 09 followed by 9 digits.</div>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                          rows="2" placeholder="Enter complete address" maxlength="500">{{ old('address') }}</textarea>
                                <div class="form-text">Enter the complete address (maximum 500 characters).</div>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <div class="form-text">Select the student's gender.</div>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Birthdate</label>
                                <input type="date" name="birthdate" class="form-control @error('birthdate') is-invalid @enderror" 
                                       value="{{ old('birthdate') }}" max="{{ date('Y-m-d') }}">
                                <div class="form-text">Select the student's birthdate (must be before today).</div>
                                @error('birthdate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Academic Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Academic Information</h5>
                            <div class="mb-3">
                                <label class="form-label">Student ID</label>
                                <input type="text" class="form-control" value="Auto-generated" disabled>
                                <div class="form-text">Student ID will be automatically generated upon saving.</div>
                            </div>

                            <h5 class="mb-3 mt-4">Guardian Information</h5>
                            <div class="mb-3">
                                <label class="form-label">Guardian Name <span class="text-danger">*</span></label>
                                <input type="text" name="guardian_name" class="form-control @error('guardian_name') is-invalid @enderror"
                                    required value="{{ old('guardian_name') }}" placeholder="Enter guardian's full name"
                                    pattern="[A-Za-z\s\-]+" title="Only letters, spaces, and hyphens are allowed">
                                <div class="form-text">Enter the guardian's full name (letters, spaces, and hyphens only).</div>
                                @error('guardian_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Guardian Phone <span class="text-danger">*</span></label>
                                <input type="tel" name="guardian_phone" class="form-control @error('guardian_phone') is-invalid @enderror"
                                    required value="{{ old('guardian_phone') }}" placeholder="Enter guardian's phone number (e.g., 09123456789)"
                                    pattern="09[0-9]{9}" maxlength="11">
                                <div class="form-text">Enter a valid Philippine mobile number starting with 09 followed by 9 digits.</div>
                                @error('guardian_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Guardian Email <span class="text-danger">*</span></label>
                                <input type="email" name="guardian_email" class="form-control @error('guardian_email') is-invalid @enderror"
                                    required value="{{ old('guardian_email') }}" placeholder="Enter guardian's email address">
                                @error('guardian_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                       required placeholder="Enter password" minlength="8"
                                       pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$">
                                <div class="form-text">Password must be at least 8 characters long and contain uppercase, lowercase, and numbers.</div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" 
                                       required placeholder="Confirm password" minlength="8">
                                <div class="form-text">Please confirm your password.</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Save Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const gradeSelect = document.getElementById('grade');
                const sectionSelect = document.getElementById('section');

                function filterSections() {
                    const selectedGrade = gradeSelect.value;
                    const options = sectionSelect.getElementsByTagName('option');

                    // Hide all sections first
                    for (let option of options) {
                        if (option.value === '') continue; // Skip the default option
                        option.style.display = 'none';
                    }

                    // Show only sections for the selected grade
                    if (selectedGrade) {
                        const gradeNumber = selectedGrade.split(' ')[1];
                        const gradeClass = `grade-${gradeNumber}`;
                        const gradeOptions = sectionSelect.getElementsByClassName(gradeClass);

                        for (let option of gradeOptions) {
                            option.style.display = '';
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
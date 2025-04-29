@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Edit Student</h2>
        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Students
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.students.update', ['id' => $student->id]) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Basic Information</h5>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name', $student->name) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required value="{{ old('email', $student->email) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="tel" name="phone" class="form-control" value="{{ old('phone', $student->student?->phone) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address', $student->student?->address) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender', $student->student?->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $student->student?->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender', $student->student?->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Birthdate</label>
                            <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate', $student->student?->birthdate ? $student->student->birthdate->format('Y-m-d') : '') }}">
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Academic Information</h5>
                        <div class="mb-3">
                            <label class="form-label">Grade Level</label>
                            <select name="grade" id="grade" class="form-select">
                                <option value="">Select Grade Level</option>
                                <option value="Grade 7" {{ old('grade', $student->student?->grade) == 'Grade 7' ? 'selected' : '' }}>Grade 7</option>
                                <option value="Grade 8" {{ old('grade', $student->student?->grade) == 'Grade 8' ? 'selected' : '' }}>Grade 8</option>
                                <option value="Grade 9" {{ old('grade', $student->student?->grade) == 'Grade 9' ? 'selected' : '' }}>Grade 9</option>
                                <option value="Grade 10" {{ old('grade', $student->student?->grade) == 'Grade 10' ? 'selected' : '' }}>Grade 10</option>
                                <option value="Grade 11" {{ old('grade', $student->student?->grade) == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                <option value="Grade 12" {{ old('grade', $student->student?->grade) == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Section</label>
                            <select name="section" id="section" class="form-select">
                                <option value="">Select Section</option>
                                <!-- Grade 7 Sections -->
                                <option value="Grade 7 - Section A" class="grade-7" {{ old('section', $student->student?->section) == 'Grade 7 - Section A' ? 'selected' : '' }}>Section A</option>
                                <option value="Grade 7 - Section B" class="grade-7" {{ old('section', $student->student?->section) == 'Grade 7 - Section B' ? 'selected' : '' }}>Section B</option>
                                <option value="Grade 7 - Section C" class="grade-7" {{ old('section', $student->student?->section) == 'Grade 7 - Section C' ? 'selected' : '' }}>Section C</option>
                            </select>
                        </div>

                        <h5 class="mb-3 mt-4">Guardian Information</h5>
                        <div class="mb-3">
                            <label class="form-label">Guardian Name</label>
                            <input type="text" name="guardian_name" class="form-control" value="{{ old('guardian_name', $student->student?->guardian_name) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Guardian Phone</label>
                            <input type="tel" name="guardian_phone" class="form-control" value="{{ old('guardian_phone', $student->student?->guardian_phone) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Guardian Email</label>
                            <input type="email" name="guardian_email" class="form-control" value="{{ old('guardian_email', $student->student?->guardian_email) }}">
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control">
                            <small class="text-muted">Leave blank to keep current password</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Update Student
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

                // Reset section selection if it doesn't match the grade
                if (sectionSelect.value && !sectionSelect.value.includes(selectedGrade)) {
                    sectionSelect.value = '';
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

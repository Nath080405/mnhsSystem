@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 text-primary">
                        @if($selectedGrade)
                            Add New Section for {{ $selectedGrade }}
                        @else
                            Create New Section
                        @endif
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.sections.store') }}" method="POST">
                        @csrf
                        
                        @if($selectedGrade)
                            <input type="hidden" name="grade_level" value="{{ $selectedGrade }}">
                            <div class="alert alert-info mb-4">
                                <i class="bi bi-info-circle me-2"></i>
                                Creating section for {{ $selectedGrade }}
                            </div>
                        @else
                            <div class="mb-3">
                                <label for="grade_level" class="form-label">Grade Level</label>
                                <select name="grade_level" id="grade_level" class="form-select @error('grade_level') is-invalid @enderror" required>
                                    <option value="">Select Grade Level</option>
                                    @foreach(['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'] as $grade)
                                        <option value="{{ $grade }}" {{ old('grade_level') == $grade ? 'selected' : '' }}>
                                            {{ $grade }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('grade_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label">Section Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="e.g., Section A" required>
                            <div class="form-text">Enter a unique name for this section (e.g., Section A, Section B)</div>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="section_id" class="form-label">Section ID</label>
                            <input type="text" class="form-control @error('section_id') is-invalid @enderror" 
                                   id="section_id" name="section_id" value="{{ old('section_id') }}" readonly>
                            <div class="form-text">Section ID will be automatically generated based on the grade level (e.g., G7-001, G8-001)</div>
                            @error('section_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="adviser_id" class="form-label">Section Adviser</label>
                            <select name="adviser_id" id="adviser_id" class="form-select @error('adviser_id') is-invalid @enderror" required>
                                <option value="">Select Adviser</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('adviser_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->last_name }}, {{ $teacher->first_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Select a teacher to be the adviser of this section</div>
                            @error('adviser_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Enter any additional information about this section">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <div class="form-text">Set the section's current status</div>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.sections.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i> Create Section
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const gradeLevelSelect = document.getElementById('grade_level');
    const sectionIdInput = document.getElementById('section_id');
    const nameInput = document.getElementById('name');

    function updateSectionId() {
        const gradeLevel = gradeLevelSelect ? gradeLevelSelect.value : '{{ $selectedGrade }}';
        if (gradeLevel) {
            // Extract just the number from "Grade X"
            const gradeNumber = gradeLevel.replace('Grade ', '');
            sectionIdInput.value = `G${gradeNumber}-001`;
        } else {
            sectionIdInput.value = '';
        }
    }

    // Update section ID when grade level changes
    if (gradeLevelSelect) {
        gradeLevelSelect.addEventListener('change', updateSectionId);
    }
    
    // Initial update
    updateSectionId();
});
</script>
@endpush
@endsection 
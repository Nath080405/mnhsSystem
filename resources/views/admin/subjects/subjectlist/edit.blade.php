@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-primary">Edit Subject</h2>
            <p class="text-muted mb-0 small">Update subject information</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('admin.subjects.update', $subject->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="mb-3">Basic Information</h5>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name', $subject->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="code" class="form-label">Subject Code</label>
                            <input type="text" class="form-control bg-light" 
                                id="code" value="{{ $subject->code }}" readonly>
                            <div class="form-text">Subject codes cannot be modified</div>
                        </div>

                        <input type="hidden" name="code" value="{{ $subject->code }}">
                    </div>
                    
                    <div class="col-md-6">
                        <h5 class="mb-3">Additional Information</h5>
                        
                        <div class="mb-3">
                            <label for="teacher_id" class="form-label">Assign Teacher</label>
                            <select class="form-select @error('teacher_id') is-invalid @enderror" id="teacher_id" name="teacher_id">
                                <option value="">Select a teacher</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $subject->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->formal_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="section_id" class="form-label">Assign Section</label>
                            <select class="form-select @error('section_id') is-invalid @enderror" id="section_id" name="section_id">
                                <option value="">Select a section</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ old('section_id', $subject->section_id) == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($sections->isEmpty())
                                <div class="form-text text-warning">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    No sections available for Grade {{ $subject->grade_level }}
                                </div>
                            @endif
                            @error('section_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Schedule Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="mb-3">Schedule Information</h5>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Update the daily schedule for this subject.
                        </div>
                        
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Start Time <span class="text-danger">*</span></label>
                                            <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                                name="start_time" value="{{ old('start_time', $startTime) }}" required>
                                            @error('start_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">End Time <span class="text-danger">*</span></label>
                                            <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                                name="end_time" value="{{ old('end_time', $endTime) }}" required>
                                            @error('end_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Update Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 0.5rem;
}
.form-label {
    font-weight: 500;
}
.schedule-item {
    background-color: #f8f9fa;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('schedules-container');
    const form = document.querySelector('form');

    // Validate time conflicts
    const validateTimeConflicts = () => {
        const schedules = container.querySelectorAll('.schedule-item');
        const conflicts = [];

        schedules.forEach((schedule, i) => {
            const day = schedule.querySelector(`select[name="schedules[${i}][day]"]`).value;
            const startTime = schedule.querySelector(`input[name="schedules[${i}][start_time]"]`).value;
            const endTime = schedule.querySelector(`input[name="schedules[${i}][end_time]"]`).value;

            if (day && startTime && endTime) {
                schedules.forEach((otherSchedule, j) => {
                    if (i !== j) {
                        const otherDay = otherSchedule.querySelector(`select[name="schedules[${j}][day]"]`).value;
                        const otherStartTime = otherSchedule.querySelector(`input[name="schedules[${j}][start_time]"]`).value;
                        const otherEndTime = otherSchedule.querySelector(`input[name="schedules[${j}][end_time]"]`).value;

                        if (day === otherDay && 
                            ((startTime >= otherStartTime && startTime < otherEndTime) ||
                             (endTime > otherStartTime && endTime <= otherEndTime) ||
                             (startTime <= otherStartTime && endTime >= otherEndTime))) {
                            conflicts.push(`Schedule ${i + 1} conflicts with Schedule ${j + 1}`);
                        }
                    }
                });
            }
        });

        return conflicts;
    };

    // Add validation before form submission
    if (form) {
        form.addEventListener('submit', function(e) {
            const conflicts = validateTimeConflicts();
            if (conflicts.length > 0) {
                e.preventDefault();
                alert('Schedule conflicts detected:\n' + conflicts.join('\n'));
            }
        });
    }
});
</script>
@endpush
@endsection 
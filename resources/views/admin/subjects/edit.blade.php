@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-primary">Edit Subject</h2>
            <p class="text-muted mb-0 small">Update subject information</p>
        </div>
        <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Subjects
        </a>
    </div>

    <!-- Form Card -->
    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
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
                            <label for="code" class="form-label">Subject Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                id="code" name="code" value="{{ old('code', $subject->code) }}" required>
                            <div class="form-text">A unique code to identify the subject (e.g., MATH101)</div>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
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
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status', $subject->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $subject->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="4">{{ old('description', $subject->description) }}</textarea>
                            @error('description')
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
                            Update the schedule for this subject.
                        </div>
                    </div>
                    <div id="schedules-container">
                        @foreach($subject->schedules as $index => $schedule)
                        <div class="schedule-item border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Day <span class="text-danger">*</span></label>
                                        <select class="form-select @error('schedules.'.$index.'.day') is-invalid @enderror" 
                                            name="schedules[{{ $index }}][day]" required>
                                            <option value="">Select Day</option>
                                            <option value="Monday" {{ old('schedules.'.$index.'.day', $schedule->day) == 'Monday' ? 'selected' : '' }}>Monday</option>
                                            <option value="Tuesday" {{ old('schedules.'.$index.'.day', $schedule->day) == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                                            <option value="Wednesday" {{ old('schedules.'.$index.'.day', $schedule->day) == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                                            <option value="Thursday" {{ old('schedules.'.$index.'.day', $schedule->day) == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                                            <option value="Friday" {{ old('schedules.'.$index.'.day', $schedule->day) == 'Friday' ? 'selected' : '' }}>Friday</option>
                                            <option value="Saturday" {{ old('schedules.'.$index.'.day', $schedule->day) == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                                        </select>
                                        @error('schedules.'.$index.'.day')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Start Time <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control @error('schedules.'.$index.'.start_time') is-invalid @enderror" 
                                            name="schedules[{{ $index }}][start_time]" 
                                            value="{{ old('schedules.'.$index.'.start_time', $schedule->start_time) }}" required>
                                        @error('schedules.'.$index.'.start_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">End Time <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control @error('schedules.'.$index.'.end_time') is-invalid @enderror" 
                                            name="schedules[{{ $index }}][end_time]" 
                                            value="{{ old('schedules.'.$index.'.end_time', $schedule->end_time) }}" required>
                                        @error('schedules.'.$index.'.end_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="schedules[{{ $index }}][id]" value="{{ $schedule->id }}">
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">Cancel</a>
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
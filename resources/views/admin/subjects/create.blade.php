@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-primary">Add New Subject</h2>
            <p class="text-muted mb-0 small">Create a new subject record</p>
        </div>
        <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Subjects
        </a>
    </div>

    <!-- Form Card -->
    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            <form action="{{ route('admin.subjects.store') }}" method="POST">
                @csrf
                
                <!-- Basic Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="mb-3">Basic Information</h5>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="code" class="form-label">Subject Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                id="code" name="code" value="{{ old('code') }}" required>
                            <div class="form-text">A unique code to identify the subject (e.g., MATH101)</div>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="credits" class="form-label">Credits <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('credits') is-invalid @enderror" 
                                id="credits" name="credits" value="{{ old('credits', 1) }}" min="1" required>
                            @error('credits')
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
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
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
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Save Subject
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
</style>
@endsection 
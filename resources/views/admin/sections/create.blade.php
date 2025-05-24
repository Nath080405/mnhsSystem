@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-primary">Add New Section</h2>
            <p class="text-muted mb-0 small">Create a new section for a grade level</p>
        </div>
        <a href="{{ route('admin.sections.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Sections
        </a>
    </div>

    <!-- Form Card -->
    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            <form action="{{ route('admin.sections.store') }}" method="POST">
                @csrf
                
                <!-- Basic Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Grade Level</label>
                            <select name="grade_level" class="form-select @error('grade_level') is-invalid @enderror" required>
                                <option value="">Select Grade Level</option>
                                @for($i = 7; $i <= 12; $i++)
                                    <option value="Grade {{ $i }}" {{ old('grade_level') == "Grade $i" ? 'selected' : '' }}>
                                        Grade {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('grade_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Section Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                value="{{ old('name') }}" placeholder="e.g., Section A" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Teacher Adviser</label>
                            <select name="adviser_id" class="form-select @error('adviser_id') is-invalid @enderror">
                                <option value="">Select Teacher Adviser</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('adviser_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->last_name }}, {{ $teacher->first_name }} 
                                        @if($teacher->email)
                                            ({{ $teacher->email }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Select a teacher to be the adviser of this section. The adviser will be responsible for managing the section.
                            </div>
                            @error('adviser_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Create Section
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 
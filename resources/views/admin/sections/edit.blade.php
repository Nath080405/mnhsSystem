@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 text-primary">Edit Section for {{ $section->grade_level }}</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.sections.update', $section) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <input type="hidden" name="grade_level" value="{{ $section->grade_level }}">
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            Editing section for {{ $section->grade_level }}
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Section Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $section->name) }}" required>
                            <div class="form-text">Enter a unique name for this section (e.g., Section A, Section B)</div>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="section_id" class="form-label">Section ID</label>
                            <input type="text" class="form-control" 
                                   id="section_id" value="{{ $section->section_id }}" readonly>
                            <div class="form-text">Section ID cannot be modified as it is automatically generated.</div>
                        </div>

                        <div class="mb-3">
                            <label for="adviser_id" class="form-label">Section Adviser</label>
                            <select name="adviser_id" id="adviser_id" class="form-select @error('adviser_id') is-invalid @enderror" required>
                                <option value="">Select Adviser</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('adviser_id', $section->adviser_id) == $teacher->id ? 'selected' : '' }}>
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
                                      placeholder="Enter any additional information about this section">{{ old('description', $section->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.sections.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Update Section
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
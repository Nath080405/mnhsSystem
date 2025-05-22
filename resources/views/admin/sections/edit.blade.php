@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 text-primary">Edit Section</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.sections.update', $section) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="grade_level" class="form-label">Grade Level</label>
                            <select name="grade_level" id="grade_level" class="form-select @error('grade_level') is-invalid @enderror" required>
                                <option value="">Select Grade Level</option>
                                @foreach(['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'] as $grade)
                                    <option value="{{ $grade }}" {{ old('grade_level', $section->grade_level) == $grade ? 'selected' : '' }}>
                                        {{ $grade }}
                                    </option>
                                @endforeach
                            </select>
                            @error('grade_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Section Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $section->name) }}" required>
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
                            @error('adviser_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $section->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', $section->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $section->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.sections.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Section</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-primary">Sections Management</h2>
            <p class="text-muted mb-0 small">Manage sections for each grade level</p>
        </div>
        <a href="{{ route('admin.sections.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add New Section
        </a>
    </div>

    <!-- Sections Table -->
    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Grade Level</th>
                            <th>Section Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sections as $section)
                            <tr>
                                <td>{{ $section->grade_level }}</td>
                                <td>{{ $section->name }}</td>
                                <td>{{ $section->description ?? 'No description' }}</td>
                                <td>
                                    <span class="badge bg-{{ $section->status === 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($section->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.sections.edit', $section) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.sections.destroy', $section) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this section?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No sections found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
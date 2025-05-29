@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-primary">{{ $grade }} Subject Labels</h2>
            <p class="text-muted mb-0 small">Manage subject labels for {{ $grade }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.subjects.plan') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Subject Plan
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                <i class="bi bi-plus-circle me-1"></i> Add Subject Label
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Content -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="row g-4">
                @forelse($subjects as $subject)
                    <div class="col-md-4 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm hover-card">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title mb-0">{{ $subject->name }}</h5>
                                    <div class="dropdown">
                                        <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.subjects.edit', $subject->id) }}">
                                                    <i class="bi bi-pencil me-2"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" 
                                                            onclick="return confirm('Are you sure you want to delete this subject?')">
                                                        <i class="bi bi-trash me-2"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        {{ $subject->code }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox-fill fs-1 d-block mb-3"></i>
                                No subject labels found for this grade level
                            </div>
                            <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                                <i class="bi bi-plus-circle me-1"></i> Add Subject Label
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Subject Label</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.subjects.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="grade_level" value="{{ $grade }}">
                    <input type="hidden" name="status" value="active">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Subject Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.hover-card {
    transition: all 0.3s ease;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
.card {
    border-radius: 0.5rem;
}
.card-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
}
.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
}
.alert {
    border: none;
    border-radius: 0.5rem;
}
.dropdown-menu {
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border-radius: 0.5rem;
    padding: 0.5rem;
}
.dropdown-item {
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    transition: all 0.2s ease;
}
.dropdown-item:hover {
    background-color: rgba(13, 110, 253, 0.1);
}
.dropdown-item.text-danger:hover {
    background-color: rgba(220, 53, 69, 0.1);
}
.dropdown-item i {
    font-size: 0.875rem;
}
.btn-link {
    text-decoration: none;
}
.btn-link:hover {
    opacity: 0.8;
}
.modal-content {
    border: none;
    border-radius: 0.5rem;
}
.modal-header {
    border-bottom: 1px solid rgba(0,0,0,0.05);
}
.modal-footer {
    border-top: 1px solid rgba(0,0,0,0.05);
}
</style>
@endsection 
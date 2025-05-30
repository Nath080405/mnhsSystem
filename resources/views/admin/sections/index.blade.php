@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-primary">Sections Management</h2>
                <p class="text-muted mb-0 small">Manage sections for each grade level</p>
            </div>
            <div class="d-flex gap-2">
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="gradeLevelDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-mortarboard me-1"></i>
                        {{ $selectedGrade ? $selectedGrade : 'All Grades' }}
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="gradeLevelDropdown">
                        <li>
                            <a class="dropdown-item {{ !$selectedGrade ? 'active' : '' }}" href="{{ route('admin.sections.index') }}">
                                <i class="bi bi-grid-3x3-gap me-2"></i> All Grades
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        @foreach($gradeLevels as $grade)
                            <li>
                                <a class="dropdown-item {{ $selectedGrade == $grade ? 'active' : '' }}" 
                                   href="{{ route('admin.sections.index', ['grade_level' => $grade]) }}">
                                    <i class="bi bi-mortarboard me-2"></i> {{ $grade }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <a href="{{ route('admin.sections.create', ['grade_level' => $selectedGrade]) }}" 
                   class="btn btn-primary {{ !$selectedGrade ? 'disabled' : '' }}"
                   @if(!$selectedGrade) onclick="return false;" @endif>
                    <i class="bi bi-plus-lg me-1"></i> Add Section
                </a>
            </div>
        </div>

        <!-- Sections Table -->
        <div class="card shadow-lg border-0">
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-primary">Section ID</th>
                                <th class="text-primary">Name</th>
                                <th class="text-primary">Grade Level</th>
                                <th class="text-primary">Adviser</th>
                                <th class="text-primary">Students</th>
                                <th class="text-primary text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sections as $section)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-hash text-primary"></i>
                                            </div>
                                            <span class="fw-medium">{{ $section->section_id }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-info bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-book text-info"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $section->name }}</div>
                                                @if($section->description)
                                                    <div class="small text-muted">{{ Str::limit($section->description, 30) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-mortarboard me-1"></i>
                                            {{ $section->grade_level }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($section->adviser)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-success bg-opacity-10 rounded-circle me-2">
                                                    <i class="bi bi-person-fill text-success"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $section->adviser->last_name }}, {{ $section->adviser->first_name }}</div>
                                                    @if($section->adviser->email)
                                                        <div class="small text-muted">{{ $section->adviser->email }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">
                                                <i class="bi bi-person-x me-1"></i> Not Assigned
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-people-fill text-warning"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $section->students_count ?? 0 }}</div>
                                                <div class="small text-muted">Enrolled</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.sections.students', $section) }}"
                                                class="btn btn-xs btn-outline-info" title="View Students">
                                                <i class="bi bi-people"></i>
                                            </a>
                                            <a href="{{ route('admin.sections.edit', $section) }}"
                                                class="btn btn-xs btn-outline-primary" title="Edit Section">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.sections.destroy', $section) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this section?')"
                                                    title="Delete Section">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox-fill fs-2 d-block mb-2"></i>
                                            No sections found
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Showing {{ $sections->firstItem() ?? 0 }} to {{ $sections->lastItem() ?? 0 }} of {{ $sections->total() ?? 0 }} entries
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($sections->hasPages())
                            @if($sections->onFirstPage())
                                <button class="btn btn-outline-secondary pagination-btn" disabled>
                                    <i class="bi bi-chevron-left"></i> Previous
                                </button>
                            @else
                                <a href="{{ $sections->previousPageUrl() }}" class="btn btn-outline-secondary pagination-btn">
                                    <i class="bi bi-chevron-left"></i> Previous
                                </a>
                            @endif

                            @if($sections->hasMorePages())
                                <a href="{{ $sections->nextPageUrl() }}" class="btn btn-outline-primary pagination-btn">
                                    Next <i class="bi bi-chevron-right"></i>
                                </a>
                            @else
                                <button class="btn btn-outline-secondary pagination-btn" disabled>
                                    Next <i class="bi bi-chevron-right"></i>
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-outline-primary {
            border-width: 2px;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
            min-width: 120px;
        }

        .btn-outline-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .btn-primary {
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .card.shadow-sm {
            background-color: #f8f9fa;
        }

        .avatar-sm {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-sm i {
            font-size: 1rem;
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .table td {
            vertical-align: middle;
        }

        .badge {
            padding: 0.5em 0.75em;
            font-weight: 500;
        }

        .alert {
            border: none;
            border-radius: 0.5rem;
        }

        .alert-success {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .alert i {
            font-size: 1.1rem;
        }

        /* Enhanced Dropdown Styles */
        .dropdown-menu {
            border: 1px solid rgba(0,0,0,0.1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 0.5rem;
            padding: 0.5rem;
            min-width: 200px;
            background-color: white;
        }

        .dropdown-item {
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
            margin: 0.125rem 0;
            font-weight: 500;
            color: #495057;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #0d6efd;
        }

        .dropdown-item.active {
            background-color: #0d6efd;
            color: white;
        }

        .dropdown-item i {
            width: 1.25rem;
            text-align: center;
            margin-right: 0.5rem;
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-color: rgba(0,0,0,0.1);
        }

        /* Dropdown Button Enhancement */
        .dropdown-toggle::after {
            margin-left: 0.5rem;
            vertical-align: middle;
        }

        .btn-outline-primary.dropdown-toggle {
            padding-right: 1rem;
            padding-left: 1rem;
        }

        /* Action Button Size Enhancement */
        .btn-xs {
            padding: 0.25rem 0.4rem;
            font-size: 0.75rem;
            line-height: 1;
            border-radius: 0.25rem;
            min-width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-xs i {
            font-size: 0.75rem;
        }

        .btn-outline-primary.btn-xs:hover,
        .btn-outline-danger.btn-xs:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .pagination-btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0.375rem;
            min-width: 100px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s ease-in-out;
            border-width: 1px;
        }

        .pagination-btn:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .pagination-btn:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }

        .pagination-btn i {
            font-size: 0.875rem;
        }

        .btn-outline-primary.pagination-btn {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }

        .btn-outline-primary.pagination-btn:hover:not(:disabled) {
            background-color: #0b5ed7;
            border-color: #0b5ed7;
        }

        .btn-outline-secondary.pagination-btn {
            background-color: white;
            color: #6c757d;
            border-color: #dee2e6;
        }

        .btn-outline-secondary.pagination-btn:hover:not(:disabled) {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #495057;
        }
    </style>
@endsection
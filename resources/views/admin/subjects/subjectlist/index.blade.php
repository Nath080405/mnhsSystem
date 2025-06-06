@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-primary">Learning Areas</h2>
                <p class="text-muted mb-0 small">Manage learning areas and their corresponding grade levels</p>
            </div>
            <div class="d-flex gap-2">
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="gradeFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-mortarboard me-1"></i>
                        {{ $selectedGrade ? $selectedGrade : 'All Grades' }}
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="gradeFilterDropdown">
                        <li>
                            <a class="dropdown-item {{ !$selectedGrade ? 'active' : '' }}" 
                               href="{{ route('admin.subjects.index') }}">
                                <i class="bi bi-grid-3x3-gap me-2"></i> All Grades
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        @foreach($gradeLevels as $grade)
                            <li>
                                <a class="dropdown-item {{ $selectedGrade == $grade ? 'active' : '' }}" 
                                   href="{{ route('admin.subjects.index', ['grade' => $grade]) }}">
                                    <i class="bi bi-mortarboard me-2"></i> {{ $grade }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content -->
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
                                <th class="text-primary">Name</th>
                                <th class="text-primary">Grade Level</th>
                                <th class="text-primary text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subjects as $subject)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-info bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-book text-info"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $subject->name }}</div>
                                                @if($subject->description)
                                                    <div class="small text-muted">{{ $subject->description }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            <i class="bi bi-mortarboard me-1"></i>
                                            {{ $subject->grade_level }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.subjects.label.subjects', $subject) }}"
                                                class="btn btn-xs btn-outline-primary" title="Manage Learning Areas">
                                                <i class="bi bi-gear"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox-fill fs-2 d-block mb-2"></i>
                                            No learning areas found
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
        }
    </style>
@endsection 
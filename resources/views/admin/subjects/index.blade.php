@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-primary">Subject Management</h2>
                <p class="text-muted mb-0 small">Manage and monitor subject records and information</p>
            </div>
            <div class="d-flex gap-2">
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="subjectFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-funnel me-1"></i>
                        {{ $selectedFilter ? $selectedFilter : 'All Subjects' }}
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="subjectFilterDropdown">
                        <li>
                            <a class="dropdown-item {{ !$selectedFilter ? 'active' : '' }}" href="{{ route('admin.subjects.index') }}">
                                <i class="bi bi-grid-3x3-gap me-2"></i> All Subjects
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item {{ $selectedFilter == 'active' ? 'active' : '' }}" 
                               href="{{ route('admin.subjects.index', ['filter' => 'active']) }}">
                                <i class="bi bi-check-circle me-2"></i> Active Subjects
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ $selectedFilter == 'inactive' ? 'active' : '' }}" 
                               href="{{ route('admin.subjects.index', ['filter' => 'inactive']) }}">
                                <i class="bi bi-x-circle me-2"></i> Inactive Subjects
                            </a>
                        </li>
                    </ul>
                </div>
                <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Add Subject
                </a>
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
                                <th class="text-primary">Code</th>
                                <th class="text-primary">Name</th>
                                <th class="text-primary">Teacher</th>
                                <th class="text-primary">Schedule</th>
                                <th class="text-primary">Status</th>
                                <th class="text-primary text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subjects as $subject)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-hash text-primary"></i>
                                            </div>
                                            <span class="fw-medium">{{ $subject->code }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-info bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-book text-info"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $subject->name }}</div>
                                                @if($subject->description)
                                                    <div class="small text-muted">{{ Str::limit($subject->description, 30) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($subject->teacher)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-success bg-opacity-10 rounded-circle me-2">
                                                    <i class="bi bi-person-fill text-success"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $subject->teacher->formal_name }}</div>
                                                    @if($subject->teacher->email)
                                                        <div class="small text-muted">{{ $subject->teacher->email }}</div>
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
                                        @if($subject->schedules->count() > 0)
                                            <div class="d-flex flex-column gap-1">
                                                @foreach($subject->schedules as $schedule)
                                                    <div class="small">
                                                        <span class="badge bg-light text-dark">{{ $schedule->day }}</span>
                                                        <span class="text-muted">{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">
                                                <i class="bi bi-clock me-1"></i> No schedule set
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $subject->status === 'active' ? 'success' : 'danger' }}">
                                            <i class="bi bi-{{ $subject->status === 'active' ? 'check-circle' : 'x-circle' }} me-1"></i>
                                            {{ ucfirst($subject->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.subjects.edit', $subject) }}"
                                                class="btn btn-xs btn-outline-primary" title="Edit Subject">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.subjects.destroy', $subject) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this subject?')"
                                                    title="Delete Subject">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox-fill fs-2 d-block mb-2"></i>
                                            No subjects found
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
    </style>
@endsection 
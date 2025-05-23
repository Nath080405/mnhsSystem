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
                <div class="input-group shadow-sm" style="width: 250px;">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" placeholder="Search by name, code...">
                </div>
                <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i> Add New Subject
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Main Content -->
        <div class="card shadow-lg border-0">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Subject List</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-file-earmark-excel me-1"></i> Export
                        </button>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-printer me-1"></i> Print
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-3">Code</th>
                                <th class="border-0 px-3">Name</th>
                                <th class="border-0 px-3">Teacher</th>
                                <th class="border-0 px-3">Schedule</th>
                                <th class="border-0 px-3">Status</th>
                                <th class="border-0 px-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subjects as $subject)
                                <tr>
                                    <td class="px-3">{{ $subject->code }}</td>
                                    <td class="px-3">{{ $subject->name }}</td>
                                    <td class="px-3">
                                        @if($subject->teacher)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-info bg-opacity-10 rounded-circle me-2">
                                                    <i class="bi bi-person-fill text-info"></i>
                                                </div>
                                                {{ $subject->teacher->formal_name }}
                                            </div>
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </td>
                                    <td class="px-3">
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
                                            <span class="text-muted">No schedule set</span>
                                        @endif
                                    </td>
                                    <td class="px-3">
                                        @if($subject->status == 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-3 text-end">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.subjects.show', $subject->id) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.subjects.edit', $subject->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit Subject">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this subject?')" title="Delete Subject">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-4 text-center text-muted">
                                        <i class="bi bi-inbox-fill me-2"></i> No subjects found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-top-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Showing {{ $subjects->firstItem() ?? 0 }} to {{ $subjects->lastItem() ?? 0 }} of {{ $subjects->total() ?? 0 }} entries
                    </div>
                    <div>
                        {{ $subjects->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .avatar-sm {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .avatar-sm i {
            font-size: 1.1rem;
        }
        .card {
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }
        .shadow-lg {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        .table > :not(caption) > * > * {
            padding: 1rem 0.75rem;
        }
        .btn-group .btn {
            padding: 0.25rem 0.5rem;
        }
        .badge {
            padding: 0.5em 0.75em;
            font-weight: 500;
        }
    </style>
@endsection 
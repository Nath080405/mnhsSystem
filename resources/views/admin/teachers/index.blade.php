@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-primary">Teacher Management</h2>
                <p class="text-muted mb-0 small">Manage and monitor teacher records and information</p>
            </div>
            <div class="d-flex gap-2">
                <div class="input-group shadow-sm" style="width: 250px;">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" placeholder="Search by name, email, or ID...">
                </div>
                <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i> Add New Teacher
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="row mb-4 g-3">
            <div class="col-md-3">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1 small text-uppercase fw-semibold">Total Teachers</h6>
                                <h3 class="mb-0 fw-bold">{{ $teachers->count() }}</h3>
                            </div>
                            <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle shadow">
                                <i class="bi bi-person-workspace text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1 small text-uppercase fw-semibold">Active Teachers</h6>
                                <h3 class="mb-0 fw-bold">{{ $teachers->where('teacher.status', 'active')->count() }}</h3>
                            </div>
                            <div class="avatar-sm bg-success bg-opacity-10 rounded-circle shadow">
                                <i class="bi bi-check-circle-fill text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1 small text-uppercase fw-semibold">New This Month</h6>
                                <h3 class="mb-0 fw-bold">0</h3>
                            </div>
                            <div class="avatar-sm bg-info bg-opacity-10 rounded-circle shadow">
                                <i class="bi bi-calendar-check-fill text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1 small text-uppercase fw-semibold">Subjects Taught</h6>
                                <h3 class="mb-0 fw-bold">0</h3>
                            </div>
                            <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle shadow">
                                <i class="bi bi-book-fill text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="card shadow-lg border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="border-0 ps-3 py-3 text-uppercase small fw-semibold">ID</th>
                                <th scope="col" class="border-0 py-3 text-uppercase small fw-semibold">Teacher Information</th>
                                <th scope="col" class="border-0 py-3 text-uppercase small fw-semibold">Department</th>
                                <th scope="col" class="border-0 py-3 text-uppercase small fw-semibold">Contact</th>
                                <th scope="col" class="border-0 py-3 text-uppercase small fw-semibold">Status</th>
                                <th scope="col" class="border-0 text-end pe-3 py-3 text-uppercase small fw-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teachers as $teacher)
                                <tr class="shadow-sm">
                                    <td class="ps-3 py-3">
                                        <span
                                            class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 fw-medium shadow-sm">{{ $teacher->teacher?->employee_id ?? 'N/A' }}</span>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2 shadow">
                                                <span class="avatar-title bg-primary bg-opacity-10 text-primary">
                                                    <i class="bi bi-person"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-medium">{{ $teacher->name }}</h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-clock me-1"></i>
                                                    Updated {{ $teacher->updated_at->format('M d, Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 fw-medium shadow-sm">
                                            <i class="bi bi-building me-1"></i>
                                            {{ $teacher->teacher?->department ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-envelope text-muted me-2"></i>
                                            <a href="mailto:{{ $teacher->email }}" class="text-decoration-none">
                                                {{ $teacher->email }}
                                            </a>
                                        </div>
                                        @if($teacher->teacher?->phone)
                                        <div class="d-flex align-items-center mt-1">
                                            <i class="bi bi-phone text-muted me-2"></i>
                                            <span>{{ $teacher->teacher?->phone }}</span>
                                        </div>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-{{ $teacher->teacher?->status === 'active' ? 'success' : 'danger' }} text-capitalize px-2 py-1 fw-medium shadow-sm">
                                            {{ ucfirst($teacher->teacher?->status ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-3 py-3">
                                        <div class="btn-group shadow" role="group">
                                            <a href="{{ route('admin.teachers.edit', ['id' => $teacher->id]) }}"
                                                class="btn btn-sm btn-outline-primary px-2" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Edit Teacher">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="{{ route('admin.teachers.show', ['id' => $teacher->id]) }}"
                                                class="btn btn-sm btn-outline-info px-2"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.teachers.destroy', ['id' => $teacher->id]) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this teacher?')"
                                                style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-2"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Teacher">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <div class="mb-3">
                                            <i class="bi bi-person-workspace display-1 text-muted"></i>
                                        </div>
                                        <h5 class="fw-medium">No teacher records found</h5>
                                        <p class="mb-0">Add your first teacher by clicking the "Add New Teacher" button above
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted small">
                Showing <span class="fw-semibold">{{ $teachers->firstItem() }}</span> to <span
                    class="fw-semibold">{{ $teachers->lastItem() }}</span> of <span
                    class="fw-semibold">{{ $teachers->total() }}</span> entries
            </div>
            <div class="pagination">
                @if ($teachers->previousPageUrl())
                    <a href="{{ $teachers->previousPageUrl() }}" class="btn btn-outline-primary btn-sm me-2">Previous</a>
                @else
                    <button class="btn btn-outline-secondary btn-sm me-2" disabled>Previous</button>
                @endif

                @if ($teachers->nextPageUrl())
                    <a href="{{ $teachers->nextPageUrl() }}" class="btn btn-outline-primary btn-sm">Next</a>
                @else
                    <button class="btn btn-outline-secondary btn-sm" disabled>Next</button>
                @endif
            </div>
        </div>
    </div>

    <style>
        .avatar-sm {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-title {
            font-size: 14px;
            font-weight: 500;
        }

        .input-group-text {
            border-right: none;
        }

        .form-control.border-start-0 {
            border-left: none;
        }

        .table> :not(caption)>*>* {
            padding: 0.75rem;
        }

        .card {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }

        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
            border-radius: 0.375rem;
        }

        .btn-group {
            border-radius: 0.375rem;
        }

        .btn-group .btn {
            border-radius: 0;
        }

        .btn-group .btn:first-child {
            border-top-left-radius: 0.375rem;
            border-bottom-left-radius: 0.375rem;
        }

        .btn-group .btn:last-child {
            border-top-right-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
        }

        /* Table Styles with Rounded Edges */
        .table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th:first-child {
            border-top-left-radius: 0.5rem;
        }

        .table thead th:last-child {
            border-top-right-radius: 0.5rem;
        }

        .table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 0.5rem;
        }

        .table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 0.5rem;
        }

        .table thead th {
            background-color: #f8f9fa;
            border: none;
        }

        .table tbody td {
            border: none;
        }

        /* Simple Hover Effect */
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        /* Pagination Styles */
        .pagination {
            margin-bottom: 0;
        }

        .pagination .btn {
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
        }

        .pagination .btn:not(:disabled):hover {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        /* Stats Card Styles */
        .avatar-sm.rounded-circle {
            width: 40px;
            height: 40px;
        }

        .avatar-sm.rounded-circle i {
            font-size: 1.1rem;
        }

        .card-body h3 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .card-body h6 {
            font-size: 0.8125rem;
        }

        /* Professional Enhancements */
        .table th {
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .table td {
            font-size: 0.875rem;
        }

        .badge {
            font-size: 0.75rem;
        }

        .btn-sm {
            font-size: 0.75rem;
        }

        .text-muted {
            font-size: 0.8125rem;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        })
    </script>
@endsection 
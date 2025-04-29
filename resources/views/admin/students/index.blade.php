@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-primary">Student Management</h2>
                <p class="text-muted mb-0 small">Manage and monitor student records and information</p>
            </div>
            <div class="d-flex gap-3 align-items-center">
                <form action="{{ route('admin.students.index') }}" method="GET" class="search-form mb-0">
                    <div class="search-wrapper">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" 
                                   name="search" 
                                   class="form-control border-start-0" 
                                   placeholder="Search students..." 
                                   value="{{ request('search') }}"
                                   autocomplete="off">
                            @if(request()->has('search'))
                                <a href="{{ route('admin.students.index') }}" 
                                   class="btn btn-link text-muted px-2" 
                                   title="Clear search">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
                <a href="{{ route('admin.students.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i> Add New Student
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

        <!-- Stats Cards -->
        <div class="row mb-4 g-3">
            <div class="col-md-3">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1 small text-uppercase fw-semibold">Total Students</h6>
                                <h3 class="mb-0 fw-bold">{{ $students->count() }}</h3>
                            </div>
                            <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle shadow">
                                <i class="bi bi-people-fill text-primary"></i>
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
                                <h6 class="text-muted mb-1 small text-uppercase fw-semibold">Active Students</h6>
                                <h3 class="mb-0 fw-bold">{{ $students->where('student.status', 'active')->count() }}</h3>
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
                                <h6 class="text-muted mb-1 small text-uppercase fw-semibold">Average Age</h6>
                                <h3 class="mb-0 fw-bold">18</h3>
                            </div>
                            <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle shadow">
                                <i class="bi bi-graph-up text-warning"></i>
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
                                <th scope="col" class="border-0 py-3 text-uppercase small fw-semibold">Student Information</th>
                                <th scope="col" class="border-0 py-3 text-uppercase small fw-semibold">Class/Grade</th>
                                <th scope="col" class="border-0 py-3 text-uppercase small fw-semibold">Contact</th>
                                <th scope="col" class="border-0 py-3 text-uppercase small fw-semibold">Guardian</th>
                                <th scope="col" class="border-0 py-3 text-uppercase small fw-semibold">Status</th>
                                <th scope="col" class="border-0 text-end pe-3 py-3 text-uppercase small fw-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr class="shadow-sm">
                                    <td class="ps-3 py-3">
                                        <span
                                            class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 fw-medium shadow-sm">{{ $student->student?->student_id ?? 'N/A' }}</span>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2 shadow">
                                                <span class="avatar-title bg-primary bg-opacity-10 text-primary">
                                                    <i class="bi bi-person"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-medium">{{ $student->name }}</h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-clock me-1"></i>
                                                    Updated {{ $student->updated_at->format('M d, Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 fw-medium shadow-sm">
                                            <i class="bi bi-mortarboard me-1"></i>
                                            {{ $student->student?->grade ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-envelope text-muted me-2"></i>
                                            <a href="mailto:{{ $student->email }}" class="text-decoration-none">
                                                {{ $student->email }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-person-fill text-muted me-2"></i>
                                            <div>
                                                <h6 class="mb-1 fw-medium">{{ $student->student?->guardian_name ?? 'N/A' }}</h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-phone me-1"></i>
                                                    {{ $student->student?->guardian_phone ?? 'N/A' }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-{{ $student->student?->status === 'active' ? 'success' : 'danger' }} text-capitalize px-2 py-1 fw-medium shadow-sm">
                                            {{ ucfirst($student->student?->status ?? 'inactive') }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-3 py-3">
                                        <div class="btn-group shadow" role="group">
                                            <a href="{{ route('admin.students.edit', $student->id) }}"
                                                class="btn btn-sm btn-outline-primary px-2" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Edit Student">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="{{ route('admin.students.show', $student->id) }}"
                                                class="btn btn-sm btn-outline-info px-2"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this student?')"
                                                style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-2"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Student">
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
                                            <i class="bi bi-people display-1 text-muted"></i>
                                        </div>
                                        <h5 class="fw-medium">No student records found</h5>
                                        <p class="mb-0">Add your first student by clicking the "Add New Student" button above
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
                Showing <span class="fw-semibold">{{ $students->firstItem() }}</span> to <span
                    class="fw-semibold">{{ $students->lastItem() }}</span> of <span
                    class="fw-semibold">{{ $students->total() }}</span> entries
            </div>
            <div class="pagination">
                @if ($students->previousPageUrl())
                    <a href="{{ $students->previousPageUrl() }}" class="btn btn-outline-primary btn-sm me-2">Previous</a>
                @else
                    <button class="btn btn-outline-secondary btn-sm me-2" disabled>Previous</button>
                @endif

                @if ($students->nextPageUrl())
                    <a href="{{ $students->nextPageUrl() }}" class="btn btn-outline-primary btn-sm">Next</a>
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

        .shadow-lg {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
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

        /* Search Styles */
        .search-form {
            max-width: 250px;
            margin: 0;
        }

        .search-wrapper {
            position: relative;
        }

        .search-wrapper .input-group {
            border-radius: 0.5rem;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .search-wrapper .input-group-text {
            padding: 0.5rem 0.75rem;
            font-size: 1rem;
        }

        .search-wrapper .form-control {
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            height: 38px;
        }

        .search-wrapper .form-control:focus {
            box-shadow: none;
        }

        .search-wrapper .btn-link {
            text-decoration: none;
            padding: 0.5rem 0.75rem;
            font-size: 1rem;
        }

        .search-wrapper .btn-link:hover {
            color: #dc3545 !important;
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
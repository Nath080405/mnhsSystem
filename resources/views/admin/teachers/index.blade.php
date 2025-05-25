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
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search teachers..." value="{{ request('search') }}">
                </div>
                <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Add Teacher
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

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-primary">ID</th>
                                <th class="text-primary">Name</th>
                                <th class="text-primary">Email</th>
                                <th class="text-primary">Status</th>
                                <th class="text-primary text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teachers as $teacher)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-person-badge text-primary"></i>
                                            </div>
                                            <span class="fw-medium">{{ $teacher->teacher?->employee_id ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-info bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-person text-info"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $teacher->formal_name }}</div>
                                                <div class="small text-muted">{{ $teacher->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-envelope text-warning"></i>
                                            </div>
                                            <a href="mailto:{{ $teacher->email }}" class="text-decoration-none">
                                                {{ $teacher->email }}
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $teacher->teacher?->status === 'active' ? 'success' : 'danger' }}">
                                            <i class="bi bi-{{ $teacher->teacher?->status === 'active' ? 'check-circle' : 'x-circle' }} me-1"></i>
                                            {{ ucfirst($teacher->teacher?->status ?? 'inactive') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.teachers.edit', $teacher->id) }}"
                                                class="btn btn-xs btn-outline-primary" title="Edit Teacher">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="{{ route('admin.teachers.show', $teacher->id) }}"
                                                class="btn btn-xs btn-outline-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-xs btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $teacher->id }}"
                                                title="Delete Teacher">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade" id="deleteModal{{ $teacher->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $teacher->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $teacher->id }}">
                                                            <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                                                            Confirm Deletion
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-0">Are you sure you want to delete the teacher <strong>{{ $teacher->formal_name }}</strong>?</p>
                                                        <p class="text-danger small mt-2">
                                                            <i class="bi bi-info-circle-fill me-1"></i>
                                                            This action cannot be undone. All associated data will be permanently deleted.
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                            <i class="bi bi-x-circle me-1"></i> Cancel
                                                        </button>
                                                        <form action="{{ route('admin.teachers.destroy', $teacher->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="bi bi-trash me-1"></i> Delete Teacher
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-person-workspace fs-2 d-block mb-2"></i>
                                            No teachers found
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Showing {{ $teachers->firstItem() ?? 0 }} to {{ $teachers->lastItem() ?? 0 }} of {{ $teachers->total() ?? 0 }} entries
                    </div>
                    <div>
                        {{ $teachers->links() }}
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

        .alert-danger {
            background-color: #f8d7da;
            color: #842029;
        }

        .input-group-text {
            border-radius: 0.375rem 0 0 0.375rem;
        }

        .input-group .form-control {
            border-radius: 0 0.375rem 0.375rem 0;
        }

        .btn-xs {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            min-width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-xs i {
            font-size: 0.75rem;
        }

        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }

        .modal-header {
            padding: 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            padding: 1.5rem;
        }

        .modal-title {
            font-weight: 600;
            color: #2c3e50;
        }

        .modal .btn {
            padding: 0.5rem 1rem;
            font-weight: 500;
        }

        .modal .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .modal .btn-danger:hover {
            background-color: #bb2d3b;
            border-color: #b02a37;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="search"]');
            const searchTimeout = 500; // milliseconds
            let timeoutId;

            searchInput.addEventListener('input', function() {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => {
                    const currentUrl = new URL(window.location.href);
                    const searchValue = this.value.trim();
                    
                    if (searchValue) {
                        currentUrl.searchParams.set('search', searchValue);
                    } else {
                        currentUrl.searchParams.delete('search');
                    }
                    
                    window.location.href = currentUrl.toString();
                }, searchTimeout);
            });
        });
    </script>
@endsection
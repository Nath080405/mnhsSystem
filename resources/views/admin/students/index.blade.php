@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-primary">Student Management</h2>
                <p class="text-muted mb-0 small">Manage and monitor student records and information</p>
            </div>
            <div class="d-flex gap-2">
                <div class="input-group shadow-sm" style="width: 250px;">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search students..."
                        value="{{ request('search') }}">
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="studentFilterDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-funnel me-1"></i>
                        {{ request('grade_level') ? 'Grade ' . request('grade_level') : 'All Students' }}
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="studentFilterDropdown">
                        <li>
                            <a class="dropdown-item {{ !request('grade_level') ? 'active' : '' }}"
                                href="{{ route('admin.students.index', ['search' => request('search')]) }}">
                                <i class="bi bi-grid-3x3-gap me-2"></i> All Students
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        @for($i = 7; $i <= 12; $i++)
                            <li>
                                <a class="dropdown-item {{ request('grade_level') == $i ? 'active' : '' }}"
                                    href="{{ route('admin.students.index', ['grade_level' => $i, 'search' => request('search')]) }}">
                                    <i class="bi bi-mortarboard me-2"></i> Grade {{ $i }}
                                </a>
                            </li>
                        @endfor
                    </ul>
                </div>
                <a href="{{ route('admin.students.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Add Student
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
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-primary">Student ID</th>
                                <th class="text-primary">Name</th>
                                <th class="text-primary">Grade Level</th>
                                <th class="text-primary">Section</th>
                                <th class="text-primary">Status</th>
                                <th class="text-primary text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-person-badge text-primary"></i>
                                            </div>
                                            <span class="fw-medium">{{ $student->student?->student_id ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-info bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-person text-info"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $student->formal_name }}</div>
                                                <div class="small text-muted">{{ $student->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-mortarboard text-warning"></i>
                                            </div>
                                            <span
                                                class="fw-medium">{{ $student->student?->grade_level ? 'Grade ' . $student->student->grade_level : 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-success bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-people text-success"></i>
                                            </div>
                                            <span class="fw-medium">{{ $student->student?->section ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $student->student?->status === 'active' ? 'success' : 'danger' }}">
                                            <i
                                                class="bi bi-{{ $student->student?->status === 'active' ? 'check-circle' : 'x-circle' }} me-1"></i>
                                            {{ ucfirst($student->student?->status ?? 'inactive') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.students.edit', $student->id) }}"
                                                class="btn btn-xs btn-outline-primary" title="Edit Student">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="{{ route('admin.students.show', $student->id) }}"
                                                class="btn btn-xs btn-outline-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST"
                                                class="d-inline delete-student-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-xs btn-outline-danger delete-student-btn"
                                                    data-bs-toggle="modal" data-bs-target="#deleteStudentModal"
                                                    data-student-id="{{ $student->id }}"
                                                    data-student-name="{{ $student->formal_name }}"
                                                    title="Delete Student">
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
                                            <i class="bi bi-people fs-2 d-block mb-2"></i>
                                            No students found
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Showing {{ $students->firstItem() ?? 0 }} to {{ $students->lastItem() ?? 0 }} of
                        {{ $students->total() ?? 0 }} entries
                    </div>
                    <div>
                        {{ $students->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteStudentModal" tabindex="-1" aria-labelledby="deleteStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="deleteStudentModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="avatar-sm bg-danger bg-opacity-10 rounded-circle mx-auto mb-3">
                            <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
                        </div>
                        <h5 class="mb-1">Are you sure?</h5>
                        <p class="text-muted mb-0">You are about to delete <span class="fw-bold" id="studentName"></span>. This action cannot be undone.</p>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="bi bi-trash me-1"></i> Delete Student
                    </button>
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

        .dropdown-item.active {
            background-color: #0d6efd;
            color: white;
        }

        .dropdown-item i {
            width: 1.25rem;
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.querySelector('input[name="search"]');
            const searchTimeout = 500; // milliseconds
            let timeoutId;

            searchInput.addEventListener('input', function () {
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

            // Delete Student Modal Functionality
            const deleteModal = document.getElementById('deleteStudentModal');
            const studentNameElement = document.getElementById('studentName');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            let currentForm = null;

            // When delete button is clicked
            document.querySelectorAll('.delete-student-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const studentName = this.getAttribute('data-student-name');
                    studentNameElement.textContent = studentName;
                    currentForm = this.closest('form');
                });
            });

            // When confirm delete is clicked
            confirmDeleteBtn.addEventListener('click', function() {
                if (currentForm) {
                    currentForm.submit();
                }
            });

            // Reset form reference when modal is closed
            deleteModal.addEventListener('hidden.bs.modal', function () {
                currentForm = null;
            });
        });
    </script>
@endsection
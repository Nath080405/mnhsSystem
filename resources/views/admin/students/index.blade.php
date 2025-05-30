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

                <!-- CSV Import Button -->
                <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadCsvModal">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i> Import CSV
                </button>

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
                                <th class="text-primary">Email</th>
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
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-envelope text-warning"></i>
                                            </div>
                                            <a href="mailto:{{ $student->email }}" class="text-decoration-none">{{ $student->email }}</a>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $student->student?->status === 'active' ? 'success' : 
                                            ($student->student?->status === 'inactive' ? 'warning' : 
                                            ($student->student?->status === 'dropped' ? 'danger' : 
                                            ($student->student?->status === 'graduated' ? 'info' : 
            ($student->student?->status === 'transferred' ? 'secondary' : 'secondary')))) }}">
                                            <i class="bi bi-{{ 
                                                $student->student?->status === 'active' ? 'check-circle' : 
                                                ($student->student?->status === 'inactive' ? 'pause-circle' : 
                                                ($student->student?->status === 'dropped' ? 'x-circle' : 
                                                ($student->student?->status === 'graduated' ? 'mortarboard' : 
            ($student->student?->status === 'transferred' ? 'arrow-right-circle' : 'question-circle')))) }} me-1"></i>
                                            {{ ucfirst($student->student?->status ?? 'inactive') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-xs btn-outline-primary" title="Edit Student">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="{{ route('admin.students.show', $student->id) }}" class="btn btn-xs btn-outline-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" class="d-inline delete-student-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-xs btn-outline-danger delete-student-btn" data-bs-toggle="modal" data-bs-target="#deleteStudentModal" data-student-id="{{ $student->id }}" data-student-name="{{ $student->formal_name }}" title="Delete Student">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
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

    <!-- CSV Upload Modal -->
    <div class="modal fade" id="uploadCsvModal" tabindex="-1" aria-labelledby="uploadCsvModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadCsvModalLabel">Import Students from CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="csvFile" class="form-label">Select CSV File</label>
                            <input type="file" class="form-control" id="csvFile" name="csv_file" accept=".csv" required>
                            <div class="form-text">
                                The CSV file should have the following columns:
                                <ul class="mb-0 mt-1">
                                    <li><strong>Required:</strong> last_name, first_name, email, lrn, gender, birthdate</li>
                                    <li><strong>Optional:</strong> middle_name, suffix, street_address, barangay, municipality, province, phone</li>
                                </ul>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Note:</strong>
                            <ul class="mb-0 mt-1">
                                <li>Make sure your CSV file follows the required format</li>
                                <li>Dates must be in MM/DD/YYYY format</li>
                                <li>Gender must be one of: Male, Female, Other</li>
                                <li>Email addresses must be valid</li>
                            </ul>
                        </div>
                        <a href="{{ route('admin.students.template') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-download me-1"></i> Download Template
                        </a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
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

        /* Search bar styles */
        .input-group .form-control:focus {
            box-shadow: none;
            border-color: #dee2e6;
        }

        .input-group:hover .form-control {
            border-color: #adb5bd;
            transition: border-color 0.2s ease-in-out;
        }

        .input-group:hover .input-group-text {
            border-color: #adb5bd;
            transition: border-color 0.2s ease-in-out;
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
            const searchTimeout = 150; // Reduced from 300ms to 150ms for faster response
            let timeoutId;
            let currentRequest = null;

            searchInput.addEventListener('input', function () {
                clearTimeout(timeoutId);
                
                // Cancel any ongoing request
                if (currentRequest) {
                    currentRequest.abort();
                }

                const searchValue = this.value.trim();
                
                // If search is empty, clear results immediately
                if (!searchValue) {
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.delete('search');
                    fetchResults(currentUrl.toString());
                    return;
                }

                timeoutId = setTimeout(() => {
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('search', searchValue);
                    fetchResults(currentUrl.toString());
                }, searchTimeout);
            });

            function fetchResults(url) {
                // Create a new AbortController for this request
                const controller = new AbortController();
                currentRequest = controller;

                // Fetch the results
                fetch(url, {
                    signal: controller.signal,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    // Update only the table body
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTableBody = doc.querySelector('tbody');
                    const currentTableBody = document.querySelector('tbody');
                    if (newTableBody && currentTableBody) {
                        currentTableBody.innerHTML = newTableBody.innerHTML;
                    }
                })
                .catch(error => {
                    if (error.name !== 'AbortError') {
                        console.error('Search error:', error);
                    }
                })
                .finally(() => {
                    currentRequest = null;
                });
            }

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
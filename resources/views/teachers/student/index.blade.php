@extends('layouts.teacherApp')

@section('content')
    <div class="container-fluid py-3">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-primary">Class Roster</h2>
                <p class="text-muted mb-0 small">View and manage your enrolled students</p>
            </div>
            <div class="d-flex gap-2">
                <div class="input-group shadow-sm" style="width: 250px;">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0"
                        placeholder="Search by name or ID..." value="{{ request('search') }}">
                </div>
                <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadCsvModal">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i> Import CSV
                </button>
                <a href="{{ route('teachers.student.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Add Student
                </a>
            </div>
        </div>
        
    <!-- Section Overview -->
    @if($section)
      <div class="mb-5">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <h4 class="fw-bold text-primary mb-1">Your Assigned Section</h4>
                <p class="text-muted mb-0">{{ $section->name }} - {{ $section->grade_level }}</p>
              </div>
              <span class="badge bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-people-fill me-1"></i>
                {{ $students->count() }} Students
              </span>
            </div>
          </div>
        </div>
      </div>
    @endif

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

                @if(isset($error))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ $error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(!isset($error))
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="text-primary">Student ID</th>
                                                <th class="text-primary">LRN</th>
                                                <th class="text-primary">Full Name</th>
                                                <th class="text-primary">Gender</th>
                                                <th class="text-primary">Date of Birth</th>
                                                <th class="text-primary">Address</th>
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
                                                            <span class="fw-medium">{{ $student->student_id ?? 'N/A' }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-info bg-opacity-10 rounded-circle me-2">
                                                                <i class="bi bi-person-badge text-info"></i>
                                                            </div>
                                                            <span class="fw-medium">{{ $student->lrn ?? 'N/A' }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-info bg-opacity-10 rounded-circle me-2">
                                                                <i class="bi bi-person text-info"></i>
                                                            </div>
                                                            <div>
                                                                <div class="fw-medium">{{ $student->last_name }}, {{ $student->first_name }}
                                                                    {{ $student->middle_name ? $student->middle_name[0] . '.' : '' }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-{{ $student->gender === 'Male' ? 'primary' : ($student->gender === 'Female' ? 'danger' : 'secondary') }}">
                                                            {{ $student->gender }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle me-2">
                                                                <i class="bi bi-calendar text-warning"></i>
                                                            </div>
                                                            <span
                                                                class="fw-medium">{{ $student->birthdate ? date('M d, Y', strtotime($student->birthdate)) : 'N/A' }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-success bg-opacity-10 rounded-circle me-2">
                                                                <i class="bi bi-geo-alt text-success"></i>
                                                            </div>
                                                            <div>
                                                                <div class="fw-medium">{{ $student->street_address ?? 'N/A' }}</div>
                                                                <div class="small text-muted">
                                                                    {{ $student->barangay ?? '' }}
                                                                    {{ $student->municipality ? ', ' . $student->municipality : '' }}
                                                                    {{ $student->province ? ', ' . $student->province : '' }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-end gap-2">
                                                            <a href="{{ route('teachers.student.edit', $student->user_id) }}"
                                                                class="btn btn-xs btn-outline-primary" title="Edit Student">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                            <a href="{{ route('teachers.student.show', $student->user_id) }}"
                                                                class="btn btn-xs btn-outline-info" title="View Details">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <form action="{{ route('teachers.student.destroy', $student->user_id) }}"
                                                                method="POST" class="d-inline delete-student-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="btn btn-xs btn-outline-danger delete-student-btn"
                                                                    data-bs-toggle="modal" data-bs-target="#deleteStudentModal"
                                                                    data-student-id="{{ $student->user_id }}"
                                                                    data-student-name="{{ $student->last_name }}, {{ $student->first_name }}"
                                                                    title="Remove Student">
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
                                                            No enrolled students found
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
                                        {{ $students->total() ?? 0 }} enrolled students
                                    </div>
                                    <div>
                                        {{ $students->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                @endif

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteStudentModal" tabindex="-1" aria-labelledby="deleteStudentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="deleteStudentModalLabel">Remove Student from Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Step 1: Reasons Form -->
                    <div id="reasonsStep" class="text-center mb-4">
                        <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle mx-auto mb-3">
                            <i class="bi bi-clipboard-text text-warning fs-4"></i>
                        </div>
                        <h5 class="mb-3">Reason for Removal</h5>
                        <p class="text-muted mb-3">Please select the reason for removing <span class="fw-bold" id="studentName"></span> from your section:</p>
                        
                        <div class="form-group text-start">
                            <select class="form-select mb-3" id="removalReason">
                                <option value="">Select a reason...</option>
                                <option value="transferred">Transferred to another school</option>
                                <option value="dropped">Dropped</option>
                                <option value="disciplinary">Disciplinary action</option>
                                <option value="academic">Academic performance</option>
                                <option value="attendance">Poor attendance</option>
                                <option value="other">Other reason</option>
                            </select>
                            
                            <div id="otherReasonDiv" class="mb-3" style="display: none;">
                                <label for="otherReason" class="form-label">Please specify:</label>
                                <textarea class="form-control" id="otherReason" rows="2" placeholder="Enter the specific reason..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Final Confirmation -->
                    <div id="confirmationStep" class="text-center mb-4" style="display: none;">
                        <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle mx-auto mb-3">
                            <i class="bi bi-exclamation-triangle-fill text-warning fs-4"></i>
                        </div>
                        <h5 class="mb-1">Confirm Removal</h5>
                        <p class="text-muted mb-0">Are you sure you want to mark <span class="fw-bold" id="studentNameConfirm"></span> as <span id="statusText"></span>? This action will update the student's status but preserve their records in the system.</p>
                        <div class="alert alert-info mt-3 text-start">
                            <strong>Reason:</strong> <span id="selectedReason"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="nextStepBtn">
                        <i class="bi bi-arrow-right me-1"></i> Next
                    </button>
                    <button type="button" class="btn btn-warning" id="confirmDeleteBtn" style="display: none;">
                        <i class="bi bi-person-x me-1"></i> Remove Student
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
                    <h5 class="modal-title" id="uploadCsvModalLabel">Update Students from CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('teachers.student.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="csvFile" class="form-label">Select CSV File</label>
                            <input type="file" class="form-control" id="csvFile" name="csv_file" accept=".csv" required>
                            <div class="form-text">
                                The CSV file should have the following columns:
                                <ul class="mb-0 mt-1">
                                    <li><strong>Required:</strong> last_name, first_name, email, lrn</li>
                                    <li><strong>Optional:</strong> middle_name, suffix, street_address, barangay, municipality, province, phone, birthdate, gender</li>
                                </ul>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Note:</strong>
                            <ul class="mb-0 mt-1">
                                <li>This will only update existing students. New students cannot be created through CSV import.</li>
                                <li>Make sure your CSV file follows the required format</li>
                                <li>Dates must be in YYYY-MM-DD format</li>
                                <li>Gender must be one of: Male, Female, Other</li>
                                <li>Email addresses must be valid</li>
                                <li>LRN must match an existing student record</li>
                            </ul>
                        </div>
                        <a href="{{ route('teachers.student.template') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-download me-1"></i> Download Template
                        </a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Students</button>
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
            const studentNameConfirmElement = document.getElementById('studentNameConfirm');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            const nextStepBtn = document.getElementById('nextStepBtn');
            const reasonsStep = document.getElementById('reasonsStep');
            const confirmationStep = document.getElementById('confirmationStep');
            const removalReason = document.getElementById('removalReason');
            const otherReasonDiv = document.getElementById('otherReasonDiv');
            const otherReason = document.getElementById('otherReason');
            const selectedReason = document.getElementById('selectedReason');
            const statusText = document.getElementById('statusText');
            let currentForm = null;

            // Show/hide other reason textarea
            removalReason.addEventListener('change', function() {
                if (this.value === 'other') {
                    otherReasonDiv.style.display = 'block';
                } else {
                    otherReasonDiv.style.display = 'none';
                }
            });

            // When delete button is clicked
            document.querySelectorAll('.delete-student-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const studentName = this.getAttribute('data-student-name');
                    studentNameElement.textContent = studentName;
                    studentNameConfirmElement.textContent = studentName;
                    currentForm = this.closest('form');
                    
                    // Reset the form
                    removalReason.value = '';
                    otherReason.value = '';
                    otherReasonDiv.style.display = 'none';
                    reasonsStep.style.display = 'block';
                    confirmationStep.style.display = 'none';
                    nextStepBtn.style.display = 'block';
                    confirmDeleteBtn.style.display = 'none';
                });
            });

            // When next button is clicked
            nextStepBtn.addEventListener('click', function() {
                const reason = removalReason.value;
                if (!reason) {
                    alert('Please select a reason for removal');
                    return;
                }
                if (reason === 'other' && !otherReason.value.trim()) {
                    alert('Please specify the other reason');
                    return;
                }

                // Display the selected reason
                let reasonText = removalReason.options[removalReason.selectedIndex].text;
                if (reason === 'other') {
                    reasonText = otherReason.value;
                }
                selectedReason.textContent = reasonText;

                // Update status text based on reason
                switch(reason) {
                    case 'transferred':
                        statusText.textContent = 'transferred';
                        break;
                    case 'dropped':
                        statusText.textContent = 'dropped';
                        break;
                    case 'disciplinary':
                        statusText.textContent = 'removed due to disciplinary action';
                        break;
                    case 'academic':
                        statusText.textContent = 'removed due to academic performance';
                        break;
                    case 'attendance':
                        statusText.textContent = 'removed due to poor attendance';
                        break;
                    case 'other':
                        statusText.textContent = 'removed';
                        break;
                    default:
                        statusText.textContent = 'removed';
                }

                // Show confirmation step
                reasonsStep.style.display = 'none';
                confirmationStep.style.display = 'block';
                nextStepBtn.style.display = 'none';
                confirmDeleteBtn.style.display = 'block';
            });

            // When confirm delete is clicked
            confirmDeleteBtn.addEventListener('click', function() {
                if (currentForm) {
                    // Add the reason to the form
                    const reasonInput = document.createElement('input');
                    reasonInput.type = 'hidden';
                    reasonInput.name = 'removal_reason';
                    reasonInput.value = removalReason.value === 'other' ? otherReason.value : removalReason.options[removalReason.selectedIndex].text;
                    currentForm.appendChild(reasonInput);
                    
                    currentForm.submit();
                }
            });

            // Reset form reference when modal is closed
            deleteModal.addEventListener('hidden.bs.modal', function() {
                currentForm = null;
            });
        });
    </script>
@endsection
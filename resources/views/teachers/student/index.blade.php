@extends('layouts.teacherApp')

@section('content')
<div class="py-4" style="background: linear-gradient(to right, #fcd9e8, #fdeaf3); min-height: 100vh;">
    <div class="container">
        <div class="mx-auto p-4" style="max-width: 1100px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <p class="mb-1 text-muted small">Overview</p>
                    <h3 class="fw-bold text-primary m-0">My Class</h3>
                </div>
                <form action="{{ route('teachers.student.search') }}" method="GET" class="d-flex align-items-center gap-2" style="height: 38px;">
                    <input 
                        type="text" 
                        name="search" 
                        class="form-control form-control-sm shadow-sm" 
                        placeholder="Search by Student ID..." 
                        style="width: 200px; border-radius: 10px;"
                    >
                    <button type="submit" class="btn btn-sm btn-outline-dark shadow-sm rounded-pill">Search</button>
                </form>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Class Box -->
            <div class="bg-white p-4 rounded-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold text-secondary mb-0">Student List</h5>
                    <button class="btn btn-primary rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                        <i class="bi bi-plus-lg me-1"></i> Add Student
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle small">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th colspan="3">NAME</th>
                                <th rowspan="2">STUDENT ID</th>
                                <th rowspan="2">AGE</th>
                                <th rowspan="2">SEX</th>
                                <th colspan="3">BIRTHDATE</th>
                                <th rowspan="2">PROVINCE</th>
                            </tr>
                            <tr>
                                <th>LAST NAME</th>
                                <th>FIRST NAME</th>
                                <th>MIDDLE NAME</th>
                                <th>MONTH</th>
                                <th>DATE</th>
                                <th>YEAR</th>
                            </tr>
                        </thead>
<tbody>
    @forelse($students as $student)
        <tr>
            <td>{{ strtoupper($student->last_name) }}</td>
            <td>{{ strtoupper($student->first_name) }}</td>
            <td>{{ strtoupper($student->middle_name) }}</td>
            <td>{{ $student->student_id }}</td>
            <td>{{ $student->age }}</td>
            <td>{{ strtoupper($student->sex) }}</td>
            <td>{{ strtoupper(date('F', mktime(0, 0, 0, $student->birth_month, 1))) }}</td>
            <td>{{ $student->birth_day }}</td>
            <td>{{ $student->birth_year }}</td>
            <td>{{ strtoupper($student->province) }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="10" class="text-muted">No students found for your assigned section.</td>
        </tr>
    @endforelse
</tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4">
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title text-primary fw-bold" id="addStudentModalLabel">Add New Student</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('teachers.student.store') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" id="first_name" name="first_name" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-4">
                            <label for="middle_name" class="form-label">Middle Name</label>
                            <input type="text" id="middle_name" name="middle_name" class="form-control rounded-3">
                        </div>
                        <div class="col-md-4">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" id="last_name" name="last_name" class="form-control rounded-3" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="student_id" class="form-label">Student ID</label>
                            <input type="text" id="student_id" name="student_id" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-3">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" id="age" name="age" class="form-control rounded-3" min="1" required>
                        </div>
                        <div class="col-md-3">
                            <label for="sex" class="form-label">Sex</label>
                            <select id="sex" name="sex" class="form-select rounded-3" required>
                                <option selected disabled value="">Choose...</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="birth_month" class="form-label">Birth Month</label>
                            <select id="birth_month" name="birth_month" class="form-select rounded-3" required>
                                <option selected disabled value="">Month</option>
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="birth_day" class="form-label">Birth Day</label>
                            <input type="number" id="birth_day" name="birth_day" class="form-control rounded-3" min="1" max="31" required>
                        </div>
                        <div class="col-md-4">
                            <label for="birth_year" class="form-label">Birth Year</label>
                            <input type="number" id="birth_year" name="birth_year" class="form-control rounded-3" min="1900" max="{{ date('Y') }}" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-12">
                            <label for="province" class="form-label">Province</label>
                            <input type="text" id="province" name="province" class="form-control rounded-3" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-outline-secondary rounded-pill">Reset</button>
                        <button type="submit" class="btn btn-primary rounded-pill">Add Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons CDN (if not already included) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@extends('layouts.teacherApp')

@section('content')
<div class="py-4" style="background-color: #f2f0f1; min-height: 100vh;">
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
                        class="form-control form-control-sm" 
                        placeholder="Search by Student ID..." 
                        style="width: 200px;"
                    >
                    <button type="submit" class="btn btn-sm btn-outline-dark">Search</button>
                </form>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="bg-white p-4 rounded shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold text-secondary mb-0">Student List</h5>
                    <!-- Button to open the modal -->
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                        <i class="bi bi-plus-lg me-1"></i> Add Student
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle small">
                        <thead class="table-secondary">
                        <tr>
                               <th colspan="3">NAME</th>
                               <th rowspan="2">STUDENT ID</th> <!-- Updated -->
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
                            <tr>
                                <td>DOE</td>
                                <td>JOHN</td>
                                <td>SMITH</td>
                                <td>119545120054</td>
                                <td>18</td>
                                <td>MALE</td>
                                <td>AUGUST</td>
                                <td>23</td>
                                <td>2005</td>
                                <td>CEBU</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal for Add Student Form -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Add New Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form to add student -->
 <form method="POST" action="{{ route('teachers.student.store') }}">
    @csrf
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" id="first_name" name="first_name" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label for="middle_name" class="form-label">Middle Name</label>
            <input type="text" id="middle_name" name="middle_name" class="form-control">
        </div>
        <div class="col-md-4">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" id="last_name" name="last_name" class="form-control" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="student_id" class="form-label">Student ID</label>
            <input type="text" id="student_id" name="student_id" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label for="age" class="form-label">Age</label>
            <input type="number" id="age" name="age" class="form-control" min="1" required>
        </div>
        <div class="col-md-3">
            <label for="sex" class="form-label">Sex</label>
            <select id="sex" name="sex" class="form-select" required>
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
            <select id="birth_month" name="birth_month" class="form-select" required>
                <option selected disabled value="">Month</option>
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="birth_day" class="form-label">Birth Day</label>
            <input type="number" id="birth_day" name="birth_day" class="form-control" min="1" max="31" required>
        </div>
        <div class="col-md-4">
            <label for="birth_year" class="form-label">Birth Year</label>
            <input type="number" id="birth_year" name="birth_year" class="form-control" min="1900" max="{{ date('Y') }}" required>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <label for="province" class="form-label">Province</label>
            <input type="text" id="province" name="province" class="form-control" required>
        </div>
    </div>
    <div class="d-flex gap-3 mt-4">
        <button type="reset" class="btn btn-secondary">Reset</button>
        <button type="submit" class="btn btn-primary">Add Student</button>
    </div>
</form>

            </div>
        </div>
    </div>
</div>

@endsection

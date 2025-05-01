@extends('layouts.teacherApp')

@section('content')
<div class="py-4" style="background-color: #f2f0f1; min-height: 100vh;">
    <div class="container">
        <div class="mx-auto p-4" style="max-width: 1100px;">

            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <p class="mb-1 text-muted small">Overview</p>
                    <h3 class="fw-bold text-primary m-0">My Class</h3>
                </div>

                <!-- Search Form -->
                <form action="/search" method="GET" class="d-flex align-items-center gap-2" style="height: 38px;">
                    <input 
                        type="text" 
                        name="search" 
                        class="form-control form-control-sm" 
                        placeholder="Search student..." 
                        style="width: 200px;"
                    >
                    <button type="submit" class="btn btn-sm btn-outline-dark">Search</button>
                </form>
            </div>

            <!-- Card-style Container -->
            <div class="bg-white p-4 rounded shadow-sm">

                <!-- Title + Add Button -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold text-secondary mb-0">Student List</h5>
                    <a href="{{ route('teachers.student.index') }}" class="btn btn-sm btn-primary rounded-pill">
                        <i class="bi bi-plus-lg me-1"></i> Add Student
                    </a>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle small">
                        <thead class="table-secondary">
                            <tr>
                                <th colspan="3">NAME</th>
                                <th rowspan="2">LRN</th>
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
                            <!-- Sample Data Row -->
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
                            <!-- More rows go here -->
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection

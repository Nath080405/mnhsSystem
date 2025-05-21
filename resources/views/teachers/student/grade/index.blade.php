@extends('layouts.teacherApp')

@section('content')
<div class="col-md-9 col-lg-10 mx-auto p-4">

  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-5">
    <div>
      <p class="mb-1 text-muted" style="font-size: 0.9rem;">Overview</p>
      <h3 class="fw-bold text-primary m-0">Student Grades</h3>
    </div>

    <!-- Search Bar -->
    <form action="{{ route('teachers.student.grade.search') }}" method="GET" class="d-flex align-items-center gap-2" style="height: 38px;">
      <input type="text" class="form-control" name="search" placeholder="Search..." style="height: 100%; width: 220px;">
      <button type="submit" class="btn btn-outline-dark">Search</button>
    </form>
  </div>

  <!-- Main Content -->
  <div class="container-fluid rounded shadow-sm d-flex flex-column py-4 px-3 bg-white" style="min-height: calc(100vh - 180px); background: linear-gradient(145deg, #ffe5ec, #fcd0e4); border-left: 6px solid #ff66a3;">

    <!-- Top Action Buttons -->
    <div class="d-flex justify-content-end mb-4">
      <a href="#" class="btn btn-outline-secondary me-2">Print</a>
      <a href="#" class="btn btn-outline-secondary">Export</a>
    </div>

    <!-- Filters Table -->
    <div class="table-responsive mb-4">
      <table class="table table-bordered align-middle text-center bg-white">
        <thead class="table-light">
          <tr>
            <th>Grade Level</th>
            <th>Section</th>
            <th>Subjects</th>
            <th>Quarter</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><select class="form-select"><option>Grade 7</option><option>Grade 8</option><option>Grade 9</option><option>Grade 10</option><option>Grade 11</option><option>Grade 12</option></select></td>
            <td><select class="form-select"><option>Section A</option><option>Section B</option><option>Section C</option><option>Section D</option></select></td>
            <td><select class="form-select"><option>Filipino</option><option>English</option><option>Math</option><option>Science</option></select></td>
            <td><select class="form-select"><option>1st Quarter</option><option>2nd Quarter</option><option>3rd Quarter</option><option>4th Quarter</option></select></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Bottom Action Buttons -->
    <div class="d-flex justify-content-end pt-4 mt-auto">
      <a href="#" class="btn btn-light me-2">Save Drafts</a>
      <a href="#" class="btn btn-dark">Post Grades</a>
    </div>

  </div> <!-- End Main Container -->

</div> <!-- End inner content -->

<style>
/* Adapted from Events Design */
.table thead th {
  background-color: #fff0f5;
}

.form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(255, 102, 163, 0.25);
    border-color: #ff66a3;
}

.btn-outline-dark {
    transition: all 0.3s ease;
}

.btn-outline-dark:hover {
    background-color: #ff66a3;
    border-color: #ff66a3;
    color: white;
    transform: translateY(-1px);
}

.btn {
    transition: all 0.3s ease;
}

.table {
    background-color: white;
}

.table th, .table td {
    vertical-align: middle;
}

/* Container hover effect */
.container-fluid.shadow-sm {
    transition: all 0.3s ease;
}

.container-fluid.shadow-sm:hover {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection

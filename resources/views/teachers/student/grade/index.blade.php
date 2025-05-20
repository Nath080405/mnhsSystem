@extends('layouts.teacherApp')

@section('content')
<div style="background-color: rgb(242, 240, 241); min-height: 100vh; padding-top: 30px;">
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
    <div class="container-fluid bg-white rounded shadow-sm d-flex flex-column py-4 px-3" style="min-height: calc(100vh - 180px);">

      <!-- Top Action Buttons -->
      <div class="d-flex justify-content-end mb-4">
        <a href="#" class="btn btn-outline-secondary me-2">Print</a>
        <a href="#" class="btn btn-outline-secondary">Export</a>
      </div>

      <!-- Filters Table -->
      <div class="table-responsive mb-4">
        <table class="table table-bordered align-middle text-center">
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
              <td>
                <select class="form-select">
                  <option>Grade 7</option>
                  <option>Grade 8</option>
                  <option>Grade 9</option>
                  <option>Grade 10</option>
                  <option>Grade 11</option>
                  <option>Grade 12</option>
                </select>
              </td>
              <td>
                <select class="form-select">
                  <option>Section A</option>
                  <option>Section B</option>
                  <option>Section C</option>
                  <option>Section D</option>
                </select>
              </td>
              <td>
                <select class="form-select">
                  <option>Filipino</option>
                  <option>English</option>
                  <option>Math</option>
                  <option>Science</option>
                </select>
              </td>
              <td>
                <select class="form-select">
                  <option>1st Quarter</option>
                  <option>2nd Quarter</option>
                  <option>3rd Quarter</option>
                  <option>4th Quarter</option>
                </select>
              </td>
            </tr>
          </tbody>
        </table>
      </div> <!-- End Filters Table -->
      
      <!-- Bottom Action Buttons -->
      <div class="d-flex justify-content-end pt-4 mt-auto">
        <a href="#" class="btn btn-light me-2">Save Drafts</a>
        <a href="#" class="btn btn-dark">Post Grades</a>
      </div>

    </div> <!-- End Main Container -->

  </div> <!-- End inner content -->
</div> <!-- End background -->
@endsection

@extends('layouts.teacherApp')

@section('content')
<div class="bg-light min-vh-100">
  <div class="container py-4">

    <!-- Welcome Box -->
    <div class="bg-white rounded-4 shadow-sm p-4 mb-5">
      <div class="d-flex align-items-center">
        <img src="{{ asset('images/teacher.png') }}" alt="Avatar" class="me-4" style="width: 60px;">
        <div>
          <h5 class="mb-1 text-primary">Hello, {{ Auth::user()->name ?? 'Teacher' }} 👋</h5>
          <p class="text-muted mb-0">Here's a quick look at your assigned subjects.</p>
        </div>
      </div>
    </div>

    <!-- Subject Cards -->
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($subjects->isEmpty())
      <div class="alert alert-warning">No subjects assigned yet.</div>
    @else
      <div class="row g-4">
      @foreach($subjects as $subject)
  <div class="col-md-4 col-sm-6">
    <div class="card subject-card p-4 h-80" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#subjectModal{{ $subject->id }}">
      <div class="card-body d-flex flex-column">
        <div class="d-flex align-items-center mb-3">
          <div class="icon-circle me-3">
            <i class="bi bi-journal-bookmark-fill text-white"></i>
          </div>
          <div>
            <h5 class="fw-bold text-secondary mb-1">{{ $subject->name }}</h5>
            <div class="d-flex align-items-center gap-2">
              <small class="text-muted">{{ $subject->code }}</small>
              <span class="badge bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-people-fill me-1"></i>
                {{ $subject->grades->count() }} Students
              </span>
            </div>
          </div>
        </div>

        <p class="text-secondary small flex-grow-1" style="line-height: 1.6; max-height: 5.2em; overflow: hidden;">
          {{ $subject->description }}
          <br>
          @if($subject->schedules->count() > 0)
            <i class="bi bi-clock me-1 text-primary"></i>
            <span class="text-secondary">
              Monday-Friday {{ \Carbon\Carbon::parse($subject->schedules->first()->start_time)->format('h:i A') }}
              - {{ \Carbon\Carbon::parse($subject->schedules->first()->end_time)->format('h:i A') }}
            </span>
          @else
            <i class="bi bi-info-circle me-1 text-muted"></i>
            <span class="text-muted">No schedule yet</span>
          @endif
        </p>

      </div>
    </div>

    <!-- Subject Modal -->
    <div class="modal fade" id="subjectModal{{ $subject->id }}" tabindex="-1" aria-labelledby="subjectModalLabel{{ $subject->id }}" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="subjectModalLabel{{ $subject->id }}">
              {{ $subject->name }} - {{ $subject->code }}
              <span class="badge bg-primary bg-opacity-10 text-primary ms-2">
                <i class="bi bi-people-fill me-1"></i>
                {{ $subject->grades->count() }} Students
              </span>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-4">
              <h6 class="text-primary mb-3">Subject Details</h6>
              <p class="text-secondary">{{ $subject->description }}</p>
              @if($subject->schedules->count() > 0)
                <div class="mt-3">
                  <h6 class="text-primary mb-2">Schedule</h6>
                  <div class="d-flex align-items-center">
                    <i class="bi bi-clock me-2 text-primary"></i>
                    <span class="text-secondary">
                      Monday-Friday {{ \Carbon\Carbon::parse($subject->schedules->first()->start_time)->format('h:i A') }}
                      - {{ \Carbon\Carbon::parse($subject->schedules->first()->end_time)->format('h:i A') }}
                    </span>
                  </div>
                </div>
              @endif
            </div>

            <div class="mt-4">
              <h6 class="text-primary mb-3">Enrolled Students</h6>
              @if($subject->grades->count() > 0)
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Grade</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($subject->grades as $grade)
                        <tr>
                          <td>{{ $grade->user->student_id }}</td>
                          <td>{{ $grade->user->first_name }} {{ $grade->user->last_name }}</td>
                          <td>{{ $grade->grade ?? 'Not graded' }}</td>
                          <td>
                            <a href="{{ route('teachers.student.grade.index') }}" class="btn btn-sm btn-primary">
                              <i class="bi bi-pencil-square"></i> Grade
                            </a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @else
                <div class="alert alert-info">
                  <i class="bi bi-info-circle me-2"></i>
                  No students enrolled in this subject yet.
                </div>
              @endif
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <a href="{{ route('teachers.student.grade.index') }}" class="btn btn-primary">
              <i class="bi bi-pencil-square me-1"></i> Manage Grades
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endforeach

      </div>
    @endif

  </div>
</div>

<!-- Custom Styling -->
<style>
.subject-card {
  background: linear-gradient(to right, #fcd9e8, #fdeaf3);
  border: none;
  border-radius: 20px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
  transition: all 0.3s ease;
}
.subject-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 28px rgba(0, 0, 0, 0.1);
}
.icon-circle {
  width: 45px;
  height: 45px;
  background-color: #e83e8c;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}
.schedule-section {
  border-top: 1px solid #e3e6f0;
  padding-top: 1rem;
  margin-top: 1rem;
}
.schedule-item {
  font-size: 0.9rem;
}
.schedule-item i {
  font-size: 1rem;
}

/* Modal Styles */
.modal-content {
  border: none;
  border-radius: 15px;
}
.modal-header {
  border-bottom: 1px solid #e3e6f0;
  background: #f8f9fc;
  border-radius: 15px 15px 0 0;
}
.modal-footer {
  border-top: 1px solid #e3e6f0;
  background: #f8f9fc;
  border-radius: 0 0 15px 15px;
}
.table {
  margin-bottom: 0;
}
.table th {
  font-weight: 600;
  color: #4e73df;
}
.btn-sm {
  padding: 0.4rem 0.8rem;
  font-size: 0.875rem;
}
.badge {
  padding: 0.5em 0.75em;
  font-weight: 500;
  font-size: 0.75rem;
}
</style>

<!-- Bootstrap Icons (if not already loaded) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection

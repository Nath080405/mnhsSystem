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
          <p class="text-muted mb-0">Here’s a quick look at your assigned subjects.</p>
        </div>
      </div>
    </div>

    <!-- Subject Cards -->
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($subjects->isEmpty())
      <div class="alert alert-warning">No subjects assigned yet. Please add one.</div>
    @else
      <div class="row g-4">
        @foreach($subjects as $subject)
          <div class="col-md-4 col-sm-6">
            <div class="card subject-card p-4 h-100">
              <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-center mb-3">
                  <div class="icon-circle me-3">
                    <i class="bi bi-journal-bookmark-fill text-white"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-secondary mb-0">{{ $subject->name }}</h5>
                    <small class="text-muted">{{ $subject->code }}</small>
                  </div>
                </div>
                <p class="text-secondary small flex-grow-1" style="line-height: 1.6; max-height: 5.2em; overflow: hidden;">
                  {{ $subject->description }}
                </p>
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
</style>

<!-- Bootstrap Icons (if not already loaded) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection

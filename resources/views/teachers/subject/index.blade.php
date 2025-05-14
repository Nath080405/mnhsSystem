@extends('layouts.teacherApp')

@section('content')
<div class="bg-light min-vh-100">
  <div class="container py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
      <div>
        <p class="mb-1 text-muted small">Overview</p>
        <h3 class="fw-bold text-primary">My Subjects</h3>
      </div>
      <form action="/search" method="GET" class="d-flex gap-2">
        <input type="text" name="search" class="form-control shadow-sm" placeholder="Search subjects..." style="max-width: 240px;">
        <button type="submit" class="btn btn-outline-dark shadow-sm">Search</button>
      </form>
    </div>

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
      <div class="row g-3">
        @foreach($subjects as $subject)
          <div class="col-md-3 col-sm-6">
            <div class="card subject-card p-3 h-100">
              <div class="card-body d-flex flex-column">
                <div class="mb-2">
                  <h6 class="fw-semibold text-dark mb-1">{{ $subject->name }}</h6>
                  <small class="text-muted">{{ $subject->code }}</small>
                </div>
                <p class="text-secondary small flex-grow-1" style="line-height: 1.4; max-height: 4.2em; overflow: hidden;">
                  {{ $subject->description }}
                </p>
                <div class="d-flex justify-content-end gap-2 mt-auto">
                  <a href="{{ route('teachers.subject.show', $subject->id) }}" class="btn btn-sm btn-outline-info px-2">
                    Show
                  </a>
                  <a href="{{ route('teachers.subject.edit', $subject->id) }}" class="btn btn-sm btn-outline-warning px-2">
                    Edit
                  </a>
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
  background:rgb(252, 239, 246);
  border: 1px solid rgb(247, 208, 221);
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  transition: all 0.2s ease-in-out;
}
.subject-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
}
</style>
@endsection

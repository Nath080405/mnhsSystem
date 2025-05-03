@extends('layouts.teacherApp')

@section('content')
<div style="background-color: rgb(242, 240, 241); min-height: 100vh; padding-top: 30px;">
  <div class="col-md-9 col-lg-10 mx-auto p-4">

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-5">
      <div class="me-auto">
        <p class="mb-1 text-muted" style="font-size: 0.9rem;">Overview</p>
        <h3 class="mb-0 fw-bold text-primary">My subjects</h3>
      </div>

      <!-- Search Bar -->
      <form action="/search" method="GET" class="d-flex align-items-center gap-2" style="height: 38px;">
        <input type="text" class="form-control" name="search" placeholder="Search..." style="height: 100%; width: 220px;">
        <button type="submit" class="btn btn-outline-dark">Search</button>
      </form>
    </div>

    <!-- Teacher Greeting -->
    <div class="content p-4 rounded bg-light shadow-sm mb-4 d-flex align-items-center justify-content-between"> 
      <div class="d-flex align-items-center">
        <img src="{{ asset('images/teacher.png') }}" alt="Teacher" class="img-fluid me-4" style="max-width: 120px;">
        <div>
          @if (Auth::check())
            <h4 class="text-primary mb-1">Hi, {{ Auth::user()->name ?? 'Guest' }}!</h4>
            <p class="text-muted mb-0">Ready to manage your subjects?</p>
          @endif
        </div>
      </div>
      <div>
        <a href="{{ route('teachers.subject.index') }}" class="btn btn-primary bt-lg rounded-pill shadow-sm">
          <i class="bi bi-plus-lg me-2"></i> Add Subject
        </a>
      </div>
    </div>

    <!-- Cards Section -->
    <div class="row g-4">
      @for ($i = 0; $i < 6; $i++)
        <div class="col-md-4">
          <div class="card shadow-sm border-0 rounded-4 h-100 hover-effect" style="min-height: 150px;">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
              <i class="bi bi-journal-text text-primary" style="font-size: 2.5rem;"></i>
              <h5 class="mt-3">Subject {{ $i + 1 }}</h5>
              <p class="text-muted small mb-0">View details</p>
            </div>
          </div>
        </div>
      @endfor
    </div>

  </div>
</div>

<!-- Add this inside your Blade file or in your CSS -->
<style>
.hover-effect {
  transition: all 0.3s ease;
}
.hover-effect:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}
</style>
@endsection

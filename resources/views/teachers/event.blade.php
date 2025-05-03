@extends('layouts.teacherApp')

@section('content')
<div style="background-color:rgb(242, 240, 241); min-height: 100vh;">
<div class="col-md-9 col-lg-10 mx-auto p-4">

<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-5">
  <div class="me-auto">
    <p class="mb-1 text-muted" style="font-size: 0.9rem;">Overview</p>
    <h3 class="mb-0 fw-bold text-primary">My Events</h3>
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
            <p class="text-muted mb-0">Ready to create Events?</p>
          @endif
        </div>
      </div>
      <div>
        <a href="{{ route('teachers.event.index') }}" class="btn btn-primary bt-lg rounded-pill shadow-sm">
          <i class="bi bi-plus-lg me-2"></i> Add Events
        </a>
      </div>
    </div>

  </div>
</div>
@endsection

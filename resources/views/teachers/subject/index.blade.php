@extends('layouts.teacherApp')

@section('content')
<div class="bg-light" style="min-height: 100vh;">
  <div class="container py-4">

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-5">
      <div>
        <p class="mb-1 text-muted small">Overview</p>
        <h3 class="mb-0 fw-bold text-primary">My Subjects</h3>
      </div>
      <!-- Search Bar -->
      <form action="/search" method="GET" class="d-flex gap-2" style="height: 38px;">
        <input type="text" name="search" class="form-control" placeholder="Search..." style="width: 220px;">
        <button type="submit" class="btn btn-outline-dark">Search</button>
      </form>
    </div>

    <!-- Teacher Greeting -->
    <div class="p-4 rounded bg-white shadow-sm mb-4 d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center">
        <img src="{{ asset('images/teacher.png') }}" alt="Teacher Avatar" class="img-fluid me-4" style="max-width: 120px;">
        <div>
          @auth
            <h4 class="text-primary mb-1">Hi, {{ Auth::user()->name ?? 'Teacher' }}!</h4>
            <p class="text-muted mb-0">Here are your assigned subjects.</p>
          @endauth
        </div>
      </div>
    </div>
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($subjects->isEmpty())  <!-- Check if subjects are available -->
        <div class="alert alert-warning">No subjects available. Please add a new subject.</div>
    @else
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Subject Name</th>
                    <th>Code</th>
                    <th>Description</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subjects as $subject)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $subject->name }}</td>
                        <td><span class="badge bg-primary">{{ $subject->code }}</span></td>
                        <td>{{ Str::limit($subject->description, 60) }}</td>
                        <td class="text-center">
                            <a href="{{ route('teachers.subject.show', $subject->id) }}" class="btn btn-sm btn-info text-white me-1">
                                <i class="bi bi-eye"></i> Show
                            </a>
                            <a href="{{ route('teachers.subject.edit', $subject->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

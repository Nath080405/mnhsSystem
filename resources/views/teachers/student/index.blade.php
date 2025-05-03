@extends('layouts.teacherApp')

@section('content')

<!-- Centered Container for the Card -->
<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: #f2f0f1;">

    <!-- Card: Add New Student -->
    <div class="card shadow-lg rounded border-0" style="width: 100%; max-width: 400px; max-height: 500px;">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Add New Student</h4>
        </div>

        <div class="card-body p-4" style="height: auto; overflow: auto;">

            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('teachers.student') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('teachers.student.index') }}">
                @csrf

                <div class="row mb-3">
                    <div class="col-12">
                        <label for="first_name" class="form-label">First Name</label>
                        <input 
                            type="text" 
                            id="first_name" 
                            name="first_name" 
                            class="form-control" 
                            required
                        >
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input 
                            type="text" 
                            id="last_name" 
                            name="last_name" 
                            class="form-control" 
                            required
                        >
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label for="email" class="form-label">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control" 
                            required
                        >
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label for="class" class="form-label">Class / Section</label>
                        <input 
                            type="text" 
                            id="class" 
                            name="class" 
                            class="form-control" 
                            required
                        >
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <label for="birthdate" class="form-label">Birthdate</label>
                        <input 
                            type="date" 
                            id="birthdate" 
                            name="birthdate" 
                            class="form-control" 
                            required
                        >
                    </div>
                </div>

                <!-- Button Section with adjusted spacing -->
                <div class="d-flex gap-3 mt-4">
                    <!-- Delete Button -->
                    <button type="reset" class="btn btn-danger">
                        Delete
                    </button>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">
                        Add Student
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>

@endsection

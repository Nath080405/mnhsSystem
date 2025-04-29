@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Add New Teacher</h2>
            <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Teachers
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.teachers.store') }}">
                    @csrf

                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Basic Information</h5>
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Birthdate</label>
                                <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate') }}">
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Professional Information</h5>
                            <div class="mb-3">
                                <label class="form-label">Department</label>
                                <input type="text" name="department" class="form-control" required value="{{ old('department') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Position</label>
                                <input type="text" name="position" class="form-control" required value="{{ old('position') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Qualification</label>
                                <input type="text" name="qualification" class="form-control" required value="{{ old('qualification') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Specialization</label>
                                <input type="text" name="specialization" class="form-control" value="{{ old('specialization') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Date Joined</label>
                                <input type="date" name="date_joined" class="form-control" required value="{{ old('date_joined') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Save Teacher
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection 
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Edit Teacher</h2>
            <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Teachers
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.teachers.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Basic Information</h5>
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $teacher->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $teacher->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender', $teacher->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $teacher->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender', $teacher->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Birthdate</label>
                                <input type="date" name="birthdate" class="form-control @error('birthdate') is-invalid @enderror" value="{{ old('birthdate', $teacher->birthdate ? $teacher->birthdate->format('Y-m-d') : '') }}">
                                @error('birthdate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Professional Information</h5>
                            <div class="mb-3">
                                <label class="form-label">Department</label>
                                <input type="text" name="department" class="form-control @error('department') is-invalid @enderror" required value="{{ old('department', $teacher->department) }}">
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Position</label>
                                <input type="text" name="position" class="form-control @error('position') is-invalid @enderror" required value="{{ old('position', $teacher->position) }}">
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Qualification</label>
                                <input type="text" name="qualification" class="form-control @error('qualification') is-invalid @enderror" required value="{{ old('qualification', $teacher->qualification) }}">
                                @error('qualification')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Specialization</label>
                                <input type="text" name="specialization" class="form-control @error('specialization') is-invalid @enderror" value="{{ old('specialization', $teacher->specialization) }}">
                                @error('specialization')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Date Joined</label>
                                <input type="date" name="date_joined" class="form-control @error('date_joined') is-invalid @enderror" required value="{{ old('date_joined', $teacher->date_joined ? $teacher->date_joined->format('Y-m-d') : '') }}">
                                @error('date_joined')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="active" {{ old('status', $teacher->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $teacher->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                <small class="text-muted">Leave blank to keep current password</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Update Teacher
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection 
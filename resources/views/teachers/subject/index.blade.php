@extends('layouts.teacherApp')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-lg border-0" style="width: 500px;">


        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Oops!</strong> Please fix the following errors:<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Updated Form Action -->
            <form action="{{ route('teachers.subject.index') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Subject Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Mathematics" value="{{ old('name') }}">
                </div>

                <div class="mb-3">
                    <label for="code" class="form-label">Subject Code</label>
                    <input type="text" name="code" class="form-control" placeholder="e.g. MATH101" value="{{ old('code') }}">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Optional">{{ old('description') }}</textarea>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <a href="{{ route('teachers.subject') }}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">Add Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

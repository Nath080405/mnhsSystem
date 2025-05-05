@extends('layouts.teacherApp')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold text-primary mb-4">Subjects</h3>
    <a href="{{ route('teachers.subject.create') }}" class="btn btn-primary mb-3">Add New Subject</a>

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

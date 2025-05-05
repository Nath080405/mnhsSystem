@extends('layouts.teacherApp')

@section('content')
<div class="container mt-4">
    <h4>Search Result</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Student ID</th>
                <th>Grade</th>
                <th>Section</th>
                <th>Gender</th>
                <th>Birthdate</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $student->student_id }}</td>
                <td>{{ $student->grade }}</td>
                <td>{{ $student->section }}</td>
                <td>{{ $student->gender }}</td>
                <td>{{ $student->birthdate }}</td>
                <td>{{ $student->address }}</td>
            </tr>
        </tbody>
    </table>
    <a href="{{ route('teachers.student') }}" class="btn btn-secondary">Back</a>
</div>
@endsection

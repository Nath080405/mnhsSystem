@extends('layouts.teacherApp')

@section('content')
<div class="container">
    <h1>Student Grade Details</h1>

    @if(isset($student) && isset($subject) && isset($grade))
        <div>
            <h2>Student: {{ $student->first_name }} {{ $student->last_name }} ({{ $student->student_id }})</h2>
            <h3>Subject: {{ $subject->name }} ({{ $subject->code }})</h3>
            
            <h4>Grades:</h4>
            <p>Written Work: {{ $grade->written_work ?? 'N/A' }}</p>
            <p>Performance Task: {{ $grade->performance_task ?? 'N/A' }}</p>
            <p>Quarterly Assessment: {{ $grade->quarterly_assessment ?? 'N/A' }}</p>
            <p>Initial Grade: {{ $grade->initial_grade ?? 'N/A' }}</p>
            <p>Transmuted Grade: {{ $grade->transmuted_grade ?? 'N/A' }}</p>

            {{-- Add more details or editing options here --}}
        </div>
    @else
        <div class="alert alert-warning">
            Could not load grade details.
        </div>
    @endif

    <a href="{{ route('teachers.student.grade.index') }}" class="btn btn-secondary mt-3">Back to Grades List</a>
</div>
@endsection

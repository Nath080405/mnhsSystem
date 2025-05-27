@extends('layouts.teacherApp')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-primary">Student Grades</h2>
            <p class="text-muted mb-0">Manage and post grades for your students</p>
        </div>
        <div class="d-flex gap-2">
            <div class="input-group shadow-sm" style="width: 250px;">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search students..."
                    value="{{ request('search') }}">
            </div>
        </div>
    </div>

    @if(isset($error))
        <div class="alert alert-warning">{{ $error }}</div>
    @else
        <!-- Subject Selection -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form id="gradeForm" method="POST" action="{{ route('teachers.student.grade.store') }}">
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Select Subject</label>
                            <select name="subject_id" class="form-select" required>
                                <option value="">Choose a subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Grading Period</label>
                            <select name="grading_period" class="form-select" required>
                                <option value="1st">First Quarter</option>
                                <option value="2nd">Second Quarter</option>
                                <option value="3rd">Third Quarter</option>
                                <option value="4th">Fourth Quarter</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-save me-1"></i> Save Grades
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Grades Table -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-primary">Student ID</th>
                                <th class="text-primary">Name</th>
                                <th class="text-primary">Grade</th>
                                <th class="text-primary">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-person-badge text-primary"></i>
                                            </div>
                                            <span class="fw-medium">{{ $student->student_id }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-info bg-opacity-10 rounded-circle me-2">
                                                <i class="bi bi-person text-info"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $student->last_name }}, {{ $student->first_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" 
                                               name="grades[{{ $student->user_id }}][grade]" 
                                               class="form-control" 
                                               min="0" 
                                               max="100" 
                                               step="0.01"
                                               value="{{ $grades->where('student_id', $student->user_id)->first()?->grade ?? '' }}"
                                               required>
                                    </td>
                                    <td>
                                        <input type="text" 
                                               name="grades[{{ $student->user_id }}][remarks]" 
                                               class="form-control"
                                               value="{{ $grades->where('student_id', $student->user_id)->first()?->remarks ?? '' }}"
                                               placeholder="Enter remarks">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-people fs-2 d-block mb-2"></i>
                                            No students found in your section
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Custom Styling -->
<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.avatar-sm i {
    font-size: 1rem;
}
</style>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection

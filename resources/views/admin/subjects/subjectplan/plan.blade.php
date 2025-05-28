@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Subject Labels by Grade Level</h4>
                </div>
                <div class="card-body">
                    <!-- Junior High School Section -->
                    <div class="mb-5">
                        <h5 class="text-primary mb-4">Junior High School</h5>
                        <div class="row">
                            @foreach(['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'] as $grade)
                                <div class="col-md-6 col-lg-3 mb-4">
                                    <div class="card h-100">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ $grade }}</h6>
                                            <a href="{{ route('admin.subjects.grade', ['grade' => $grade]) }}" class="btn btn-primary btn-sm">
                                                <i class="bi bi-gear-fill me-1"></i> Manage
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            @php
                                                $gradeSubjects = $juniorHighSubjects->where('grade_level', $grade)->whereNull('parent_id');
                                            @endphp
                                            @if($gradeSubjects->count() > 0)
                                                <ul class="list-group list-group-flush">
                                                    @foreach($gradeSubjects as $subject)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <div class="fw-medium">{{ $subject->name }}</div>
                                                                <small class="text-muted">Subject Label</small>
                                                            </div>
                                                            <span class="badge bg-primary rounded-pill">{{ $subject->code }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-muted mb-0">No subject labels assigned</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Senior High School Section -->
                    <div>
                        <h5 class="text-primary mb-4">Senior High School</h5>
                        <div class="row">
                            @foreach(['Grade 11', 'Grade 12'] as $grade)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ $grade }}</h6>
                                            <a href="{{ route('admin.subjects.grade', ['grade' => $grade]) }}" class="btn btn-primary btn-sm">
                                                <i class="bi bi-gear-fill me-1"></i> Manage
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            @php
                                                $gradeSubjects = $seniorHighSubjects->where('grade_level', $grade)->whereNull('parent_id');
                                            @endphp
                                            @if($gradeSubjects->count() > 0)
                                                <ul class="list-group list-group-flush">
                                                    @foreach($gradeSubjects as $subject)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <div class="fw-medium">{{ $subject->name }}</div>
                                                                <small class="text-muted">Subject Label</small>
                                                            </div>
                                                            <span class="badge bg-primary rounded-pill">{{ $subject->code }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-muted mb-0">No subject labels assigned</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-5px);
}
.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #eee;
}
.list-group-item {
    border: none;
    padding: 0.75rem 0;
}
.badge {
    font-size: 0.8rem;
}
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
@endsection 
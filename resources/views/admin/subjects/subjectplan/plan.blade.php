@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-primary">Academic Program Structure</h2>
            <p class="text-muted mb-0 small">Manage learning areas and their distribution across academic levels</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Junior High School Section -->
        <div class="col-12 mb-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-mortarboard me-2"></i>Junior High School Program
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        @foreach(['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'] as $grade)
                            <div class="col-md-6 col-lg-3">
                                <div class="card h-100 border-0 shadow-sm hover-card grade-card">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                                        <h6 class="mb-0 fw-semibold">{{ $grade }}</h6>
                                        <a href="{{ route('admin.subjects.grade', ['grade' => $grade]) }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-gear-fill me-1"></i> Manage Program
                                        </a>
                                    </div>
                                    <div class="card-body p-3">
                                        @php
                                            $gradeSubjects = $juniorHighSubjects->where('grade_level', $grade)->whereNull('parent_id');
                                        @endphp
                                        @if($gradeSubjects->count() > 0)
                                            <ul class="list-group list-group-flush subject-list">
                                                @foreach($gradeSubjects->take(5) as $subject)
                                                    <li class="list-group-item px-0">
                                                        <div class="fw-medium">{{ $subject->name }}</div>
                                                        @if($subject->description)
                                                            <div class="small text-muted">{{ Str::limit($subject->description, 50) }}</div>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div class="text-center py-3">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox-fill fs-4 d-block mb-2"></i>
                                                    No learning areas assigned to this level
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Senior High School Section -->
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-mortarboard me-2"></i>Senior High School Program
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        @foreach(['Grade 11', 'Grade 12'] as $grade)
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm hover-card grade-card">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                                        <h6 class="mb-0 fw-semibold">{{ $grade }}</h6>
                                        <a href="{{ route('admin.subjects.grade', ['grade' => $grade]) }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-gear-fill me-1"></i> Manage Program
                                        </a>
                                    </div>
                                    <div class="card-body p-3">
                                        @php
                                            $gradeSubjects = $seniorHighSubjects->where('grade_level', $grade)->whereNull('parent_id');
                                        @endphp
                                        @if($gradeSubjects->count() > 0)
                                            <ul class="list-group list-group-flush subject-list">
                                                @foreach($gradeSubjects->take(5) as $subject)
                                                    <li class="list-group-item px-0">
                                                        <div class="fw-medium">{{ $subject->name }}</div>
                                                        @if($subject->description)
                                                            <div class="small text-muted">{{ Str::limit($subject->description, 50) }}</div>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div class="text-center py-3">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox-fill fs-4 d-block mb-2"></i>
                                                    No learning areas assigned to this level
                                                </div>
                                            </div>
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

<style>
.hover-card {
    transition: all 0.3s ease;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
.card {
    border-radius: 0.5rem;
}
.card-header {
    border-bottom: 1px solid rgba(0,0,0,0.05);
}
.list-group-item {
    border: none;
    padding: 0.75rem 0;
}
.list-group-item:not(:last-child) {
    border-bottom: 1px solid rgba(0,0,0,0.05);
}
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
.text-primary {
    color: #0d6efd !important;
}
.bg-primary {
    background-color: #0d6efd !important;
}
.grade-card {
    height: 300px !important;
}
.subject-list {
    max-height: 200px;
    overflow-y: auto;
}
.subject-list::-webkit-scrollbar {
    width: 4px;
}
.subject-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}
.subject-list::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}
.subject-list::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
@endsection 
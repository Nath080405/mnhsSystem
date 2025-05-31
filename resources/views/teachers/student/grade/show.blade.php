@extends('layouts.teacherApp')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-primary">Student Grades</h2>
            <p class="text-muted mb-0">
                {{ $student->last_name }}, {{ $student->first_name }} - {{ $student->student_id }}
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('teachers.student.grade.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Grades Table -->
    <form action="{{ route('teachers.student.grade.store') }}" method="POST">
        @csrf
        <input type="hidden" name="student_id" value="{{ $student->user_id }}">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-primary">Subject</th>
                                <th class="text-primary text-center">1st Quarter</th>
                                <th class="text-primary text-center">2nd Quarter</th>
                                <th class="text-primary text-center">3rd Quarter</th>
                                <th class="text-primary text-center">4th Quarter</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subjects as $subject)
                                @php
                                    $subjectGrades = $grades->where('subject_id', $subject->id);
                                    $firstQuarter = $subjectGrades->where('grading_period', '1st')->first();
                                    $secondQuarter = $subjectGrades->where('grading_period', '2nd')->first();
                                    $thirdQuarter = $subjectGrades->where('grading_period', '3rd')->first();
                                    $fourthQuarter = $subjectGrades->where('grading_period', '4th')->first();
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-medium">{{ $subject->name }}</div>
                                        <div class="small text-muted">{{ $subject->code }}</div>
                                        <input type="hidden" name="grades[{{ $subject->id }}][subject_id]" value="{{ $subject->id }}">
                                    </td>
                                    <td class="text-center">
                                        <input type="number" class="form-control form-control-sm text-center" name="grades[{{ $subject->id }}][1st]" min="0" max="100" step="0.01" value="{{ $firstQuarter?->grade }}">
                                    </td>
                                    <td class="text-center">
                                        <input type="number" class="form-control form-control-sm text-center" name="grades[{{ $subject->id }}][2nd]" min="0" max="100" step="0.01" value="{{ $secondQuarter?->grade }}">
                                    </td>
                                    <td class="text-center">
                                        <input type="number" class="form-control form-control-sm text-center" name="grades[{{ $subject->id }}][3rd]" min="0" max="100" step="0.01" value="{{ $thirdQuarter?->grade }}">
                                    </td>
                                    <td class="text-center">
                                        <input type="number" class="form-control form-control-sm text-center" name="grades[{{ $subject->id }}][4th]" min="0" max="100" step="0.01" value="{{ $fourthQuarter?->grade }}">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-book fs-2 d-block mb-2"></i>
                                            No subjects found
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-end">Quarter Average</th>
                                <th class="text-center"><span id="avg-1st" class="badge bg-secondary">N/A</span></th>
                                <th class="text-center"><span id="avg-2nd" class="badge bg-secondary">N/A</span></th>
                                <th class="text-center"><span id="avg-3rd" class="badge bg-secondary">N/A</span></th>
                                <th class="text-center"><span id="avg-4th" class="badge bg-secondary">N/A</span></th>
                            </tr>
                            <tr>
                                <th class="text-end text-success">Total Average</th>
                                <th colspan="4" class="text-center">
                                    <span id="total-average" class="badge bg-warning">N/A</span>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Save All Grades
                    </button>
                </div>
            </div>
        </div>
    </form>
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

.badge {
    padding: 0.5em 0.75em;
    font-weight: 500;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
}
</style>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateQuarterAverages() {
        let subjectRows = document.querySelectorAll('tbody tr');
        let quarterTotals = [0, 0, 0, 0];
        let quarterCounts = [0, 0, 0, 0];

        subjectRows.forEach(function(row) {
            const inputs = row.querySelectorAll('input[type="number"]');
            if (inputs.length === 4) {
                inputs.forEach((input, idx) => {
                    const val = parseFloat(input.value);
                    if (!isNaN(val)) {
                        quarterTotals[idx] += val;
                        quarterCounts[idx]++;
                    }
                });
            }
        });

        let quarterAverages = [];
        ['1st', '2nd', '3rd', '4th'].forEach((label, idx) => {
            let avgElem = document.getElementById('avg-' + label);
            if (quarterCounts[idx] > 0) {
                let avg = (quarterTotals[idx] / quarterCounts[idx]).toFixed(2);
                avgElem.textContent = avg;
                avgElem.classList.remove('bg-secondary', 'bg-danger', 'bg-success');
                avgElem.classList.add(parseFloat(avg) >= 75 ? 'bg-success' : 'bg-danger');
                quarterAverages.push(parseFloat(avg));
            } else {
                avgElem.textContent = 'N/A';
                avgElem.classList.remove('bg-success', 'bg-danger');
                avgElem.classList.add('bg-secondary');
                quarterAverages.push(null);
            }
        });

        // Calculate total average if all quarters have an average
        let totalAvgElem = document.getElementById('total-average');
        if (quarterAverages.every(avg => avg !== null)) {
            let totalAvg = (quarterAverages.reduce((a, b) => a + b, 0) / 4).toFixed(2);
            totalAvgElem.textContent = totalAvg;
            totalAvgElem.classList.remove('bg-secondary', 'bg-danger', 'bg-success');
            totalAvgElem.classList.add(parseFloat(totalAvg) >= 75 ? 'bg-success' : 'bg-danger');
        } else {
            totalAvgElem.textContent = 'N/A';
            totalAvgElem.classList.remove('bg-warning', 'bg-danger', 'bg-secondary');
            totalAvgElem.classList.add('bg-warning');
        }
    }

    // Attach event listeners
    document.querySelectorAll('tbody tr input[type="number"]').forEach(function(input) {
        input.addEventListener('input', updateQuarterAverages);
    });

    // Initial call
    updateQuarterAverages();
});
</script>
@endsection

@extends('layouts.teacherApp')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-primary">Student Grades</h2>
            <p class="text-muted mb-0">Select a student to view and manage their grades</p>
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
        <!-- Students List -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-primary">Student ID</th>
                                <th class="text-primary">Name</th>
                                <th class="text-primary">Section</th>
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
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            {{ $student->section }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('teachers.student.grade.show', $student->user_id) }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-journal-text me-1"></i>
                                            View Grades
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-people fs-2 d-block mb-2"></i>
                                            No students found
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Showing {{ $students->firstItem() ?? 0 }} to {{ $students->lastItem() ?? 0 }} of
                        {{ $students->total() ?? 0 }} students
                    </div>
                    <div>
                        {{ $students->links() }}
                    </div>
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
    function updateAllAverages() {
        let subjectRows = document.querySelectorAll('tbody tr');
        let total = 0;
        let count = 0;
        subjectRows.forEach(function(row) {
            const inputs = row.querySelectorAll('input[type="number"]');
            const avgBadge = row.querySelector('.badge');
            const remarksCell = row.querySelector('.remarks-cell');
            if (inputs.length === 4 && avgBadge && remarksCell) {
                let values = [];
                inputs.forEach(i => {
                    const val = parseFloat(i.value);
                    if (!isNaN(val)) values.push(val);
                });
                if (values.length === 4) {
                    let avg = (values.reduce((a, b) => a + b, 0) / 4).toFixed(2);
                    avgBadge.textContent = avg;
                    avgBadge.classList.remove('bg-success', 'bg-danger');
                    if (parseFloat(avg) >= 75) {
                        avgBadge.classList.add('bg-success');
                        remarksCell.textContent = 'Passed';
                        remarksCell.className = 'text-center remarks-cell text-success';
                    } else {
                        avgBadge.classList.add('bg-danger');
                        remarksCell.textContent = 'Failed';
                        remarksCell.className = 'text-center remarks-cell text-danger';
                    }
                    total += parseFloat(avg);
                    count++;
                } else {
                    avgBadge.textContent = 'N/A';
                    avgBadge.classList.remove('bg-success');
                    avgBadge.classList.add('bg-danger');
                    remarksCell.textContent = 'N/A';
                    remarksCell.className = 'text-center remarks-cell text-secondary';
                }
            }
        });
        // Update total average and remarks
        let totalAvgElem = document.getElementById('total-average');
        let totalRemarksElem = document.getElementById('total-remarks');
        if (count > 0) {
            let totalAvg = (total / count).toFixed(2);
            totalAvgElem.textContent = totalAvg;
            totalAvgElem.classList.remove('bg-success', 'bg-danger', 'bg-secondary');
            if (parseFloat(totalAvg) >= 75) {
                totalAvgElem.classList.add('bg-success');
                totalRemarksElem.textContent = 'Passed';
                totalRemarksElem.className = 'badge bg-success';
            } else {
                totalAvgElem.classList.add('bg-danger');
                totalRemarksElem.textContent = 'Failed';
                totalRemarksElem.className = 'badge bg-danger';
            }
        } else {
            totalAvgElem.textContent = 'N/A';
            totalAvgElem.classList.remove('bg-success', 'bg-danger');
            totalAvgElem.classList.add('bg-secondary');
            totalRemarksElem.textContent = 'N/A';
            totalRemarksElem.className = 'badge bg-secondary';
        }
    }

    // Attach event listeners
    document.querySelectorAll('tbody tr input[type="number"]').forEach(function(input) {
        input.addEventListener('input', updateAllAverages);
    });

    // Initial call
    updateAllAverages();
});
</script>
@endsection

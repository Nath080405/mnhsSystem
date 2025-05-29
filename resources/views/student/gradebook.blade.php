@extends('layouts.studentApp')

@section('content')
<div class="container-fluid py-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #0d6efd;">My Grades</h2>
            <p class="text-muted mb-0 small">View and track your academic performance</p>
        </div>
        <a href="{{ route('student.gradebook.pdf') }}" class="btn btn-primary">
            <i class="bi bi-download me-1"></i> Download PDF
        </a>
    </div>

    <!-- Main Content -->
    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            <!-- Semester Selection -->
            <div class="mb-4">
                <label class="form-label fw-medium" style="color: #0d6efd;">Select Semester</label>
                <select class="form-select" style="max-width: 250px;" id="semesterSelect">
                    <option value="1st">1st Semester</option>
                    <option value="2nd">2nd Semester</option>
                </select>
            </div>

            <!-- Grades Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle grades-table">
                    <thead class="bg-light">
                        <tr>
                            <th style="color: #0d6efd;">Subjects</th>
                            <th class="first-sem" style="color: #0d6efd;">1st Quarter</th>
                            <th class="first-sem" style="color: #0d6efd;">2nd Quarter</th>
                            <th class="second-sem" style="display: none; color: #0d6efd;">3rd Quarter</th>
                            <th class="second-sem" style="display: none; color: #0d6efd;">4th Quarter</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $subject)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2">
                                        <i class="bi bi-book" style="color: #0d6efd;"></i>
                                    </div>
                                    <div class="fw-medium">{{ $subject->name }}</div>
                                </div>
                            </td>
                            <td class="first-sem">
                                @php
                                    $firstQuarter = $grades[$subject->id] ?? collect();
                                    $firstQuarterGrade = $firstQuarter->where('grading_period', '1st')->first();
                                @endphp
                                <span class="badge bg-{{ $firstQuarterGrade && $firstQuarterGrade->grade >= 75 ? 'success' : 'danger' }}">
                                    {{ $firstQuarterGrade ? $firstQuarterGrade->grade : 'N/A' }}
                                </span>
                            </td>
                            <td class="first-sem">
                                @php
                                    $secondQuarter = $grades[$subject->id] ?? collect();
                                    $secondQuarterGrade = $secondQuarter->where('grading_period', '2nd')->first();
                                @endphp
                                <span class="badge bg-{{ $secondQuarterGrade && $secondQuarterGrade->grade >= 75 ? 'success' : 'danger' }}">
                                    {{ $secondQuarterGrade ? $secondQuarterGrade->grade : 'N/A' }}
                                </span>
                            </td>
                            <td class="second-sem" style="display: none;">
                                @php
                                    $thirdQuarter = $grades[$subject->id] ?? collect();
                                    $thirdQuarterGrade = $thirdQuarter->where('grading_period', '3rd')->first();
                                @endphp
                                <span class="badge bg-{{ $thirdQuarterGrade && $thirdQuarterGrade->grade >= 75 ? 'success' : 'danger' }}">
                                    {{ $thirdQuarterGrade ? $thirdQuarterGrade->grade : 'N/A' }}
                                </span>
                            </td>
                            <td class="second-sem" style="display: none;">
                                @php
                                    $fourthQuarter = $grades[$subject->id] ?? collect();
                                    $fourthQuarterGrade = $fourthQuarter->where('grading_period', '4th')->first();
                                @endphp
                                <span class="badge bg-{{ $fourthQuarterGrade && $fourthQuarterGrade->grade >= 75 ? 'success' : 'danger' }}">
                                    {{ $fourthQuarterGrade ? $fourthQuarterGrade->grade : 'N/A' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="first-sem">
                            <td colspan="2" class="text-end">
                                <div class="d-flex align-items-center justify-content-end">
                                    <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2">
                                        <i class="bi bi-calculator" style="color: #0d6efd;"></i>
                                    </div>
                                    <strong>General Average for 1st Semester</strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $firstSemAverage ?? 'N/A' }}</span>
                            </td>
                        </tr>
                        <tr class="second-sem" style="display: none;">
                            <td colspan="2" class="text-end">
                                <div class="d-flex align-items-center justify-content-end">
                                    <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2">
                                        <i class="bi bi-calculator" style="color: #0d6efd;"></i>
                                    </div>
                                    <strong>General Average for 2nd Semester</strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $secondSemAverage ?? 'N/A' }}</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Grading Scale -->
            <div class="mt-5">
                <div class="card border-0 bg-light">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3" style="color: #0d6efd;">
                            <i class="bi bi-info-circle me-2"></i>Learners Progress and Achievement
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center mb-0">
                                <thead class="bg-white">
                                    <tr>
                                        <th style="color: #0d6efd;">Descriptors</th>
                                        <th style="color: #0d6efd;">Grading Scale</th>
                                        <th style="color: #0d6efd;">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>Outstanding</td><td>90-100</td><td><span class="badge bg-success">Passed</span></td></tr>
                                    <tr><td>Very Satisfactory</td><td>85-89</td><td><span class="badge bg-success">Passed</span></td></tr>
                                    <tr><td>Satisfactory</td><td>80-84</td><td><span class="badge bg-success">Passed</span></td></tr>
                                    <tr><td>Fairly Satisfactory</td><td>75-79</td><td><span class="badge bg-success">Passed</span></td></tr>
                                    <tr><td>Did Not Meet Expectation</td><td>Below 75</td><td><span class="badge bg-danger">Failed</span></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 1rem;
    transition: all 0.3s ease;
}
.shadow-lg {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
.badge {
    padding: 0.5rem 1rem;
    font-weight: 500;
}
.table > :not(caption) > * > * {
    padding: 1rem;
}
.table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
}
.form-select {
    border-radius: 0.5rem;
    border-color: #dee2e6;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}
.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
.btn-primary {
    padding: 0.5rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
}
.grades-table th {
    font-weight: 600;
    background-color: #f8f9fa;
}
.grades-table td {
    vertical-align: middle;
}
.bg-light {
    background-color: #f8f9fa !important;
}
</style>

@push('scripts')
<!-- Include html2pdf library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get the semester select element
    const semesterSelect = document.getElementById('semesterSelect');
    if (!semesterSelect) {
        console.error('Semester select element not found');
        return;
    }

    // Function to update table display
    function updateTableDisplay() {
        const selectedSemester = semesterSelect.value;
        console.log('Selected semester:', selectedSemester); // Debug log

        // Get all semester-specific elements
        const firstSemHeaders = document.querySelectorAll('th.first-sem');
        const firstSemCells = document.querySelectorAll('td.first-sem');
        const secondSemHeaders = document.querySelectorAll('th.second-sem');
        const secondSemCells = document.querySelectorAll('td.second-sem');
        const firstSemFooter = document.querySelector('tr.first-sem');
        const secondSemFooter = document.querySelector('tr.second-sem');

        console.log('Found elements:', { // Debug log
            firstSemHeaders: firstSemHeaders.length,
            firstSemCells: firstSemCells.length,
            secondSemHeaders: secondSemHeaders.length,
            secondSemCells: secondSemCells.length,
            firstSemFooter: !!firstSemFooter,
            secondSemFooter: !!secondSemFooter
        });

        if (selectedSemester === '1st') {
            // Show first semester elements
            firstSemHeaders.forEach(el => el.style.display = '');
            firstSemCells.forEach(el => el.style.display = '');
            if (firstSemFooter) firstSemFooter.style.display = '';
            
            // Hide second semester elements
            secondSemHeaders.forEach(el => el.style.display = 'none');
            secondSemCells.forEach(el => el.style.display = 'none');
            if (secondSemFooter) secondSemFooter.style.display = 'none';
        } else {
            // Show second semester elements
            secondSemHeaders.forEach(el => el.style.display = '');
            secondSemCells.forEach(el => el.style.display = '');
            if (secondSemFooter) secondSemFooter.style.display = '';
            
            // Hide first semester elements
            firstSemHeaders.forEach(el => el.style.display = 'none');
            firstSemCells.forEach(el => el.style.display = 'none');
            if (firstSemFooter) firstSemFooter.style.display = 'none';
        }
    }

    // Set initial semester based on current date
    const currentMonth = new Date().getMonth() + 1; // 1-12
    const defaultSemester = (currentMonth >= 6 && currentMonth <= 10) ? '1st' : '2nd';
    semesterSelect.value = defaultSemester;
    console.log('Initial semester set to:', defaultSemester); // Debug log

    // Initial display
    updateTableDisplay();

    // Update display when semester changes
    semesterSelect.addEventListener('change', function() {
        console.log('Semester changed to:', this.value); // Debug log
        updateTableDisplay();
    });
});

// PDF download function
function downloadPDF() {
    const element = document.querySelector('.gradebook-content');
    const opt = {
        margin:       0.5,
        filename:     'gradebook.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
    };

    html2pdf().set(opt).from(element).save();
}
</script>
@endpush
@endsection

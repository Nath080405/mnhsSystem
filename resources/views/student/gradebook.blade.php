@extends('layouts.studentApp')

@section('content')
<div class="container-fluid" style="background-color: #f2f0f1; min-height: 100vh;">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-9 col-lg-12 ms-auto">
            <div class="p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2" style="border-color: black !important;">
                    <div>
                        <p class="mb-1 text-black-50" style="font-size: 0.9rem;">Overview</p>
                        <h3 class="mb-0 fw-bold text-black">My Grades</h3>
                    </div>
                </div>

                <!-- Gradebook Content with Background -->
                <div class="gradebook-content" style="background-color: #ffffff; max-width: 1200px; margin: 0 auto;">
                    <!-- Semester Selection -->
                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <select class="form-select" style="max-width: 250px;" id="semesterSelect">
                            <option value="1st">1st Semester</option>
                            <option value="2nd">2nd Semester</option>
                        </select>
                    </div>

                    <table class="table table-bordered grades-table">
                        <thead>
                            <tr>
                                <th>Subjects</th>
                                <th class="first-sem">1st Quarter</th>
                                <th class="first-sem">2nd Quarter</th>
                                <th class="second-sem" style="display: none;">3rd Quarter</th>
                                <th class="second-sem" style="display: none;">4th Quarter</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Mathematics</td><td class="first-sem"></td><td class="first-sem"></td><td class="second-sem" style="display: none;"></td><td class="second-sem" style="display: none;"></td></tr>
                            <tr><td>Science</td><td class="first-sem"></td><td class="first-sem"></td><td class="second-sem" style="display: none;"></td><td class="second-sem" style="display: none;"></td></tr>
                            <tr><td>Araling Panlipunan</td><td class="first-sem"></td><td class="first-sem"></td><td class="second-sem" style="display: none;"></td><td class="second-sem" style="display: none;"></td></tr>
                            <tr><td>English</td><td class="first-sem"></td><td class="first-sem"></td><td class="second-sem" style="display: none;"></td><td class="second-sem" style="display: none;"></td></tr>
                            <tr><td>Filipino</td><td class="first-sem"></td><td class="first-sem"></td><td class="second-sem" style="display: none;"></td><td class="second-sem" style="display: none;"></td></tr>
                            <tr><td>TLE</td><td class="first-sem"></td><td class="first-sem"></td><td class="second-sem" style="display: none;"></td><td class="second-sem" style="display: none;"></td></tr>
                        </tbody>
                        <tfoot>
                            <tr class="first-sem">
                                <td colspan="2" class="text-end"><strong>General Average for 1st Semester</strong></td>
                                <td></td>
                            </tr>
                            <tr class="second-sem" style="display: none;">
                                <td colspan="2" class="text-end"><strong>General Average for 2nd Semester</strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="mt-5">
                        <h6><em>Learners Progress and Achievement</em></h6>
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Descriptors</th>
                                    <th>Grading Scale</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Outstanding</td><td>90-100</td><td>Passed</td></tr>
                                <tr><td>Very Satisfactory</td><td>85-89</td><td>Passed</td></tr>
                                <tr><td>Satisfactory</td><td>80-84</td><td>Passed</td></tr>
                                <tr><td>Fairly Satisfactory</td><td>75-79</td><td>Passed</td></tr>
                                <tr><td>Did Not Meet Expectation</td><td>Below 75</td><td>Failed</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.gradebook-content {
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.grades-table {
    width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
}

.grades-table th,
.grades-table td {
    padding: 0.75rem;
    vertical-align: top;
    border: 1px solid #dee2e6;
}

.grades-table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
    background-color: #ffffff;
}

.grades-table tbody tr:nth-of-type(odd) {
    background-color: #ffffff;
}

.grades-table tbody tr:nth-of-type(even) {
    background-color: #f8f9fa;
}

.grades-table tfoot tr {
    background-color: #ffffff;
}

.form-select {
    background-color: #ffffff;
}

.table-bordered {
    border-color: #dee2e6;
}

.table-bordered th,
.table-bordered td {
    border-color: #dee2e6;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const semesterSelect = document.getElementById('semesterSelect');
    const firstSemElements = document.querySelectorAll('.first-sem');
    const secondSemElements = document.querySelectorAll('.second-sem');
    
    function updateTableDisplay() {
        const selectedSemester = semesterSelect.value;
        
        if (selectedSemester === '1st') {
            firstSemElements.forEach(el => el.style.display = '');
            secondSemElements.forEach(el => el.style.display = 'none');
        } else {
            firstSemElements.forEach(el => el.style.display = 'none');
            secondSemElements.forEach(el => el.style.display = '');
        }
    }
    
    updateTableDisplay();
    semesterSelect.addEventListener('change', updateTableDisplay);
});
</script>
@endpush
@endsection

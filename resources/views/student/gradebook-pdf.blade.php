<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gradebook</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .school-logo {
            height: 100px;
            margin-bottom: 10px;
        }
        .student-info {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .student-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .student-info-item {
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .semester-header {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .grade-scale {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('MedellinLogo.png') }}" alt="MNHS Logo" class="school-logo">
        <h1>Medellin National High School</h1>
        <h2>Student Grade Report</h2>
        <p>School Year: {{ $schoolYear ?? '2023-2024' }}</p>
    </div>

    <div class="student-info">
        <h3>Student Information</h3>
        <div class="student-info-grid">
            <div class="student-info-item">
                <p><strong>Full Name:</strong> {{ Auth::user()->last_name }}, {{ Auth::user()->first_name }}, {{ Auth::user()->middle_name ? Auth::user()->middle_name . ' ' : '' }}{{ Auth::user()->suffix ? ' ' . Auth::user()->suffix : '' }}</p>
                <p><strong>Student ID:</strong> {{ Auth::user()->student?->student_id ?? 'N/A' }}</p>
                <p><strong>Grade Level & Section:</strong> {{ Auth::user()->student?->grade_level ?? 'N/A' }} - {{ Auth::user()->student?->section?->name ?? 'N/A' }}</p>
            </div>
            <div class="student-info-item">
                <p><strong>Adviser:</strong> {{ Auth::user()->student?->section?->adviser?->name ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    @foreach($grades as $semester => $semesterGrades)
        <h2>{{ $semester }}</h2>
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>1st Quarter</th>
                    <th>2nd Quarter</th>
                    <th>Final Grade</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($semesterGrades as $subject => $grades)
                    <tr>
                        <td>{{ $subject }}</td>
                        <td>{{ $grades['first_quarter'] ?? 'N/A' }}</td>
                        <td>{{ $grades['second_quarter'] ?? 'N/A' }}</td>
                        <td>{{ $grades['final_grade'] ?? 'N/A' }}</td>
                        <td>{{ isset($grades['final_grade']) ? ($grades['final_grade'] >= 75 ? 'Passed' : 'Failed') : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>General Average:</strong></td>
                    <td colspan="2"><strong>{{ $semesterGrades['general_average'] ?? 'N/A' }}</strong></td>
                </tr>
            </tfoot>
        </table>
    @endforeach

    <div class="grade-scale">
        <h3>Grading Scale</h3>
        <table>
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

    <div class="footer">
        <p>This document is computer-generated and does not require a signature.</p>
        <p>Generated on: {{ now()->format('F d, Y h:i A') }}</p>
    </div>
</body>
</html>
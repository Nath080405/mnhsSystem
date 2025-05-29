<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gradebook</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .school-logo {
            height: 100px;
            margin-bottom: 15px;
        }
        .school-name {
            color: #0d6efd;
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }
        .report-title {
            color: #333;
            font-size: 20px;
            margin: 10px 0;
        }
        .school-year {
            color: #666;
            font-size: 16px;
            margin: 5px 0;
        }
        .student-info {
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        .student-info h3 {
            color: #0d6efd;
            margin-bottom: 15px;
            font-size: 18px;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 5px;
        }
        .student-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .student-info-item {
            margin-bottom: 10px;
        }
        .student-info-item p {
            margin: 5px 0;
            font-size: 14px;
        }
        .student-info-item strong {
            color: #0d6efd;
            font-weight: 600;
        }
        .semester-section {
            margin: 30px 0;
            page-break-inside: avoid;
        }
        .semester-header {
            background: #0d6efd;
            color: white;
            padding: 10px 15px;
            border-radius: 5px 5px 0 0;
            font-weight: bold;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }
        th {
            background-color: #f8f9fa;
            color: #0d6efd;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .grade-scale {
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        .grade-scale h3 {
            color: #0d6efd;
            margin-bottom: 15px;
            font-size: 18px;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 5px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .badge-danger {
            background-color: #f8d7da;
            color: #842029;
        }
        .general-average {
            font-weight: bold;
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('MedellinLogo.png') }}" alt="MNHS Logo" class="school-logo">
        <div class="school-name">Medellin National High School</div>
        <div class="report-title">Student Grade Report</div>
        <div class="school-year">School Year: {{ $schoolYear }}</div>
    </div>

    <div class="student-info">
        <h3>Student Information</h3>
        <div class="student-info-grid">
            <div class="student-info-item">
                <p><strong>Full Name:</strong> {{ $student->user->last_name }}, {{ $student->user->first_name }}{{ $student->user->middle_name ? ' ' . $student->user->middle_name : '' }}{{ $student->user->suffix ? ' ' . $student->user->suffix : '' }}</p>
                <p><strong>Student ID:</strong> {{ $student->student_id }}</p>
                <p><strong>LRN:</strong> {{ $student->lrn }}</p>
            </div>
            <div class="student-info-item">
                <p><strong>Grade Level:</strong> {{ $student->grade_level }}</p>
                <p><strong>Section:</strong> {{ $student->section }}</p>
            </div>
        </div>
    </div>

    @foreach($grades as $semester => $semesterGrades)
        <div class="semester-section">
            <div class="semester-header">{{ $semester }}</div>
            <table>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>First Quarter</th>
                        <th>Second Quarter</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($semesterGrades as $subject => $grades)
                        @if($subject !== 'general_average')
                            <tr>
                                <td>{{ $subject }}</td>
                                <td>{{ $grades['first_quarter'] ?? 'N/A' }}</td>
                                <td>{{ $grades['second_quarter'] ?? 'N/A' }}</td>
                                <td>
                                    @if(isset($grades['first_quarter']) && isset($grades['second_quarter']))
                                        @php
                                            $average = round(($grades['first_quarter'] + $grades['second_quarter']) / 2, 2);
                                        @endphp
                                        <span class="badge {{ $average >= 75 ? 'badge-success' : 'badge-danger' }}">
                                            {{ $average >= 75 ? 'Passed' : 'Failed' }}
                                        </span>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align: right;"><strong>General Average:</strong></td>
                        <td colspan="2" class="general-average">
                            {{ $semesterGrades['general_average'] ?? 'N/A' }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
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
                <tr>
                    <td>Outstanding</td>
                    <td>90-100</td>
                    <td><span class="badge badge-success">Passed</span></td>
                </tr>
                <tr>
                    <td>Very Satisfactory</td>
                    <td>85-89</td>
                    <td><span class="badge badge-success">Passed</span></td>
                </tr>
                <tr>
                    <td>Satisfactory</td>
                    <td>80-84</td>
                    <td><span class="badge badge-success">Passed</span></td>
                </tr>
                <tr>
                    <td>Fairly Satisfactory</td>
                    <td>75-79</td>
                    <td><span class="badge badge-success">Passed</span></td>
                </tr>
                <tr>
                    <td>Did Not Meet Expectation</td>
                    <td>Below 75</td>
                    <td><span class="badge badge-danger">Failed</span></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>This document is computer-generated and does not require a signature.</p>
        <p>Generated on: {{ now()->format('F d, Y h:i A') }}</p>
    </div>
</body>
</html>
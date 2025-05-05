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
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
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
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('MedellinLogo.png') }}" alt="MNHS Logo" class="school-logo">
        <h1>MNHS Student Information Portal</h1>
        <p>School Year: {{ $schoolYear ?? '2023-2024' }}</p>
    </div>

    <div class="student-info">
        <h3>Student Information</h3>
        <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
        <p><strong>Student ID:</strong> {{ Auth::user()->student?->student_id ?? 'N/A' }}</p>
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
                </tr>
            </thead>
            <tbody>
                @foreach($semesterGrades as $subject => $grades)
                    <tr>
                        <td>{{ $subject }}</td>
                        <td>{{ $grades['first_quarter'] ?? 'N/A' }}</td>
                        <td>{{ $grades['second_quarter'] ?? 'N/A' }}</td>
                        <td>{{ $grades['final_grade'] ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>
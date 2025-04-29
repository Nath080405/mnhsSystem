<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sidebar Layout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .main-content {
            margin-left: 250px;
            padding: 1.5rem;
            min-height: 100vh;
        }
        .container-fluid {
            max-width: 1400px;
            margin: 0 auto;
        }
        .nav-link {
            color: #000;
        }
        .nav-link:hover, .nav-link.active {
            background-color: #f1f1f1;
            font-weight: bold;
        }
    </style>
</head>
<body>

    @include('layouts.sidebar')

    <div class="main-content">
        <div class="container-fluid">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - File Manager</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=grid_view" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=view_list" />

    <!-- Custom CSS -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body,
        html {
            height: 100%;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .main-layout {
            display: flex;
            width: 100vw;
            height: 100vh;
        }

        /* Sidebar styling */
        .sidebar {
            width: 70px;
            background-color: white;
        }

        /*container*/
        .container {
            flex: 1;
            background-color: #f1f1f1;
            padding: 20px;
            overflow-y: auto;
        }

        .employee-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .filter-buttons .btn-filter {
            margin-right: 10px;
        }

        .zoom-slider-container {
            display: flex;
            align-items: center;
        }

        .zoom-slider-button {
            background: none;
            border: none;
            margin: 0 5px;
        }

        .zoom-slider {
            width: 100px;
            margin: 0 10px;
        }

        .file-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .file-card {
            background-color: #fff;
            border-radius: 5px;
            padding: 10px;
            width: 150px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .file-card img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .file-actions .btn {
            margin-top: 10px;
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
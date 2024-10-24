<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - Dashboard</title>

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
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
        }

        /* Sidebar styling */
        .sidebar {
            width: 250px;
            background-color: #f8f9fa;
            padding: 20px;
            height: 100vh;
        }

        .sidebar h3 {
            text-align: center;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .menu ul {
            list-style-type: none;
            padding: 0;
        }

        .menu ul li {
            margin-bottom: 15px;
        }

        .menu ul li a {
            text-decoration: none;
            font-weight: 600;
            color: #31511E;
        }

        /* Main content styling */
        .main-content {
            flex-grow: 1;
            background-color: #ffffff;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .add-folder-btn {
            padding: 10px 20px;
        }

        <style>.tools {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-top: 20px;
        }

        .tools .add-folder-btn {
            background-color: #007bff;
            color: white;
            border-radius: 8px;
            padding: 8px 15px;
            transition: background-color 0.3s;
        }

        .tools .add-folder-btn:hover {
            background-color: #0056b3;
        }

        .layout-tools {
            display: flex;
            gap: 10px;
        }

        .layout-tools .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .layout-tools .btn .material-icons {
            font-size: 24px;
            color: #6c757d;
        }

        /* Tampilan saat tombol di-hover */
        .layout-tools .btn:hover {
            background-color: #e2e6ea;
            transform: scale(1.1);
        }

        /* Tampilan tombol aktif (Grid atau List yang sedang dipilih) */
        .layout-tools .btn.active {
            background-color: #007bff;
            color: white;
        }

        .layout-tools .btn.active .material-icons {
            color: white;
        }

        /* Untuk penampilan lebih menarik */
        .tools .layout-tools .btn:hover .material-icons {
            color: #495057;
        }
    </style>

    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar -->


    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
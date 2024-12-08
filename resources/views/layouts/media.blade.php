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
        /* Global reset */
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

        /* Main content area */
        .employee-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        /* Filter buttons styling */
        .filter-buttons .btn-filter {
            margin-right: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            color: #495057;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .filter-buttons .btn-filter.active {
            background-color: #007bff;
            color: #fff;
        }

        /* File grid layout */
        .file-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        /* File card container */
        .file-card {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
            width: 200px;
        }

        .file-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        /* Image container for media preview */
        .image-container {
            width: 100%;
            aspect-ratio: 4 / 3;
            /* Set aspect ratio for consistent display */
            overflow: hidden;
        }

        .media-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Dropdown item styling */
        .dropdown-item {
            font-size: 14px;
            /* Ukuran font seragam */
            font-weight: normal;
            /* Berat font seragam */
            color: black;
            /* Warna default */
            padding: 5px 10px;
            /* Jarak padding seragam */
            cursor: pointer;
        }

        /* Khusus untuk tombol Delete */
        .dropdown-item.delete {
            color: red;
            /* Warna teks merah */
            font-weight: bold;
            /* Berat font khusus */
        }

        /* Hover styling */
        .dropdown-item:hover {
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }


        /* Tombol tiga titik */
        .action-menu button {
            padding: 15;
            border: none;
            background: none;
            cursor: pointer;
            color: white;
            /* Warna ikon */
            font-size: 15px;
            /* Ukuran ikon */
            display: flex;
            align-items: center;
            justify: center;
        }

        /* Menghilangkan tanda panah bawaan */
        .action-menu button::after {
            display: none;
            /* Hilangkan tanda panah dropdown */
        }

        /* File info section */
        .file-info {
            padding: 10px;
            text-align: center;
        }

        .file-info p {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Zoom slider container */
        .zoom-slider-container {
            display: flex;
            align-items: center;
        }

        .zoom-slider-button {
            background: none;
            border: none;
            margin: 0 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .zoom-slider {
            width: 100px;
            margin: 0 10px;
        }

        /* Menambahkan gaya untuk folder */
        .folder-card {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .folder-name {
            font-weight: bold;
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
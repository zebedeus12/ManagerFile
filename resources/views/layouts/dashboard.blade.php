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

        /* Sidebar styling */
        .sidebar {
            width: 70px;
            background-color: white;
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

        .tools {
            display: flex;
            align-items: center;
        }

        .tools .add-folder-btn {
            background-color: #007bff;
            color: white;
            border-radius: 8px;
            padding: 8px 15px;
            transition: background-color 0.3s;
            margin-right: 10px;
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
            margin-right: 5px;
        }

        .layout-tools .btn .material-icons {
            font-size: 24px;
            color: #6c757d;
        }

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

        .tools .layout-tools .btn:hover .material-icons {
            color: #495057;
        }

        .layout-tools .btn:last-child {
            margin-right: 0;
            /* Menghapus margin kanan pada tombol terakhir */
        }

        .folder-date-group {
            margin-bottom: 30px;
        }

        .folder-date-group h4 {
            margin-bottom: 20px;
            font-size: 18px;
        }

        .file-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .file-card,
        .sub-folder-card {
            position: relative;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            text-align: center;
            transition: transform 0.2s;
        }

        .file-card:hover,
        .sub-folder-card:hover {
            transform: translateY(-5px);
        }

        .icon-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 10px;
            text-decoration: none;
        }

        .folder-icon {
            font-size: 48px;
            color: #FFB400;
        }

        .file-icon {
            width: 40px;
            height: 40px;
        }

        .file-info {
            font-size: 14px;
            color: #6c757d;
        }

        .add-folder,
        .add-file {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .add-folder:hover,
        .add-file:hover {
            background-color: #0056b3;
        }

        .header .buttons {
            margin-left: auto;
            /* Menarik tombol Add Folder ke kanan */
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
<script>
    document.querySelector('.grid-layout').addEventListener('click', function () {
        document.querySelector('.file-grid').style.display = 'grid';
        this.classList.add('active');
        document.querySelector('.list-layout').classList.remove('active');
    });

    document.querySelector('.list-layout').addEventListener('click', function () {
        document.querySelector('.file-grid').style.display = 'block';
        this.classList.add('active');
        document.querySelector('.grid-layout').classList.remove('active');
    });


</script>

</html>
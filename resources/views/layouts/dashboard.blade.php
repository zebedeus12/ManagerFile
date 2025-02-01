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

        .file-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
            /* Adjust column size */
            gap: 20px;
            margin-top: 20px;
        }

        /* Folder Card Styling */
        .file-card {
            background-color: #e8f5e9;
            border-radius: 12px;
            padding: 12px;
            /* Reduced padding */
            min-height: 100px;
            /* Reduced minimum height */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .file-card:hover {
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
            transform: translateY(-3px);
        }

        .icon-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: -1px;
            /* Adjusted spacing */
        }

        .folder-icon {
            font-size: 40px;
            /* Smaller icon size */
            color: #43a047;
            margin-bottom: 8px;
        }

        /* Ensure anchor tags don't have an underline */
        .file-card a {
            text-decoration: none;
            /* Remove underline from link */
        }

        /* File info styling */
        .file-info {
            font-size: 14px;
            /* Smaller font size */
            font-weight: bold;
            color: #1b5e20;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            max-width: 100%;
        }

        .header .buttons {
            margin-left: auto;
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
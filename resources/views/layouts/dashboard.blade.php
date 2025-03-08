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
            margin: 0;
            padding: 0;
            display: flex;
        }

        .dashboard-content {
            background-image: url('{{ asset('img/dashboard.jpeg') }}');
            background-size: cover;
            /* Menyesuaikan gambar dengan ukuran layar */
            background-position: center;
            /* Menjaga gambar tetap di tengah */
            background-repeat: no-repeat;
        }

        /* Menyelaraskan tombol dan filter tanggal */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: -40px;
            padding: 20px;

            /* Memberikan ruang lebih di bawah header */
        }

        .date-filter {
            display: flex;
            align-items: center;
        }

        .date-filter select {
            max-width: 180px;
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            background-color: #f9f9f9;
            margin-left: -1px;
        }

        .layout-buttons {
            display: flex;
            align-items: center;
        }

        .layout-buttons button {
            margin-left: 10px;
            padding: 3px 3px 3px 3px;
            font-size: 14px;
            background-color: #43a047;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .layout-buttons button:hover {
            background-color: #388e3c;
            transform: scale(1.05);
        }

        .date-filter::after {
            content: '';
            width: 1px;
            height: 30px;
            background-color: #ccc;
            margin-left: 10px;
        }

        .date-group h3 {
            font-size: 16px;
            margin-bottom: -5px;
            padding-left: 10px;
        }

        .date-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
            border-left: 2px solid #ccc;
            padding-left: 20px;
        }

        .date-group:last-child {
            margin-bottom: 40px;
        }

        /* grid */
        .file-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
            /* Adjust column size */
            gap: 20px;
            margin-top: 5px;
        }

        /* Folder Card Styling */
        .file-card {
            background-color: #e8f5e9;
            border-radius: 12px;
            padding: 12px;
            min-height: 100px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            overflow: hidden;
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
            font-weight: bold;
            color: #1b5e20;
            font-size: 14px;
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

        /* Responsive styling */
        @media (max-width: 768px) {
            .file-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .layout-buttons {
                margin-top: 10px;
            }
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
    document.addEventListener('DOMContentLoaded', function () {
        const layoutToggle = document.getElementById('layout-toggle');
        const layoutIcon = document.getElementById('layout-icon');
        const fileGrid = document.querySelector('.file-grid');
        let isGrid = true; // Default tampilan adalah grid

        layoutToggle.addEventListener('click', function () {
            if (isGrid) {
                fileGrid.style.display = 'block'; // Mengubah ke list
                layoutIcon.textContent = 'view_list'; // Ganti ikon
            } else {
                fileGrid.style.display = 'grid'; // Mengembalikan ke grid
                fileGrid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(170px, 1fr))';
                layoutIcon.textContent = 'grid_view'; // Ganti ikon
            }
            isGrid = !isGrid;
        });
    });


    document.getElementById('date-filter').addEventListener('change', function () {
        const selectedDate = this.value;
        const allDateGroups = document.querySelectorAll('.date-group');

        allDateGroups.forEach(group => {
            const groupDate = group.id.replace('date-', '');
            if (groupDate === selectedDate || selectedDate === '') {
                group.style.display = 'block';
            } else {
                group.style.display = 'none';
            }
        });
    });
</script>


</html>
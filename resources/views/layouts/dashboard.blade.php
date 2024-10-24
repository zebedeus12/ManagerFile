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

        .tools {
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

        /* style employee */
        /* From Uiverse.io by gharsh11032000 */
        .menu {
            font-size: 16px;
            line-height: 1.6;
            color: #000000;
            width: fit-content;
            display: flex;
            list-style: none;
        }

        .menu a {
            text-decoration: none;
            color: inherit;
            font-family: inherit;
            font-size: inherit;
            line-height: inherit;
        }

        .menu .link {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 12px 36px;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.48s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .menu .link::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #0a3cff;
            z-index: -1;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.48s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .menu .link svg {
            width: 14px;
            height: 14px;
            fill: #000000;
            transition: all 0.48s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .menu .item {
            position: relative;
        }

        .menu .item .submenu {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: absolute;
            top: 100%;
            border-radius: 0 0 16px 16px;
            left: 0;
            width: 100%;
            overflow: hidden;
            border: 1px solid #cccccc;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-12px);
            transition: all 0.48s cubic-bezier(0.23, 1, 0.32, 1);
            z-index: 1;
            pointer-events: none;
            list-style: none;
        }

        .menu .item:hover .submenu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
            border-top: transparent;
            border-color: #0a3cff;
        }

        .menu .item:hover .link {
            color: #ffffff;
            border-radius: 16px 16px 0 0;
        }

        .menu .item:hover .link::after {
            transform: scaleX(1);
            transform-origin: right;
        }

        .menu .item:hover .link svg {
            fill: #ffffff;
            transform: rotate(-180deg);
        }

        .submenu .submenu-item {
            width: 100%;
            transition: all 0.48s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .submenu .submenu-link {
            display: block;
            padding: 12px 24px;
            width: 100%;
            position: relative;
            text-align: center;
            transition: all 0.48s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .submenu .submenu-item:last-child .submenu-link {
            border-bottom: none;
        }

        .submenu .submenu-link::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            transform: scaleX(0);
            width: 100%;
            height: 100%;
            background-color: #0a3cff;
            z-index: -1;
            transform-origin: left;
            transition: transform 0.48s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .submenu .submenu-link:hover:before {
            transform: scaleX(1);
            transform-origin: right;
        }

        .submenu .submenu-link:hover {
            color: #ffffff;
        }

        /* menu */
        /* From Uiverse.io by JulanDeAlb */
        .hamburger {
            cursor: pointer;
        }

        .hamburger input {
            display: none;
        }

        .hamburger svg {
            /* The size of the SVG defines the overall size */
            height: 3em;
            /* Define the transition for transforming the SVG */
            transition: transform 600ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        .line {
            fill: none;
            stroke: white;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-width: 3;
            /* Define the transition for transforming the Stroke */
            transition: stroke-dasharray 600ms cubic-bezier(0.4, 0, 0.2, 1),
                stroke-dashoffset 600ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        .line-top-bottom {
            stroke-dasharray: 12 63;
        }

        .hamburger input:checked+svg {
            transform: rotate(-45deg);
        }

        .hamburger input:checked+svg .line-top-bottom {
            stroke-dasharray: 20 300;
            stroke-dashoffset: -32.42;
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
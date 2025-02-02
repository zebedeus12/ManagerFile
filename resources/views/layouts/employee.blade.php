<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - Employee</title>

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
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            /* Mencegah scroll di body */
        }

        .main-content {
            flex-grow: 1;
            background-color: #ffffff;
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

        .employee-content {
            margin-left: 260px;
            padding: 20px;
            height: calc(100vh - 60px);
            overflow-y: auto;
            overflow-x: hidden;
            background-image: url('{{ asset('img/dashboard.jpeg') }}');
            width: calc(100% - 240px);
            box-sizing: border-box;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Warna background tabel lebih gelap agar kontras dengan halaman */
        .table {
            background-color: #7cbf7c;
            /* Hijau tua */
            border-radius: 8px;
            /* Membuat sudut lebih lembut */
            overflow: hidden;
            border-collapse: separate;
            border-spacing: 0;
        }

        /* Header tabel dengan warna hijau lebih gelap */
        .table th {
            background-color: rgb(114, 180, 114);
            /* Hijau lebih gelap untuk header */
            color: white;
            padding: 12px;
            text-align: left;
        }

        /* Warna sel tabel */
        .table td {
            background-color: #a3d9a5;
            /* Hijau tua lebih terang dari header */
            color: black;
            padding: 10px;
        }

        /* Tambahkan border putih untuk lebih rapi */
        .table-bordered th,
        .table-bordered td {
            border: 1px solid white;
        }

        /* Efek hover agar lebih interaktif */
        .table tbody tr:hover {
            background-color: rgb(141, 202, 141);
            /* Hijau sedikit lebih gelap saat hover */
        }

        .search-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            /* Jarak antara tombol search dan tombol add employee */
        }

        .search-form {
            display: flex;
            align-items: center;
            gap: 0;
            /* Rapatkan tombol search ke input */
        }

        .search-container {
            background-color: #d4f8d4;
            border-radius: 50px;
            padding: 10px 15px;
            width: 200px;
            /* Lebih kecil agar proporsional */
            display: flex;
            align-items: center;
        }

        .search-input {
            border: none;
            background: transparent;
            outline: none;
            flex-grow: 1;
            font-size: 14px;
        }

        .search-button {
            background-color: #a5e6a5;
            border: none;
            border-radius: 50%;
            width: 38px;
            /* Ukuran disesuaikan dengan add employee */
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
            margin-left: -5px;
            /* Sedikit tumpang tindih untuk merapatkan */
        }

        .search-button .material-icons {
            font-size: 20px;
            color: #2c662d;
        }

        .add-employee-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            background-color: #a5e6a5;
            border-radius: 50%;
            text-decoration: none;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
            transition: background 0.3s ease;
        }

        .add-employee-button:hover {
            background-color: #b8e6b8;
        }

        .add-employee-button .material-icons {
            font-size: 24px;
            color: #2c662d;
        }

        /* Membuat tabel lebih rapat dan tidak terlalu besar */
        .table-container {
            max-width: 100%;
            margin: 0 auto;
            /* Posisi tabel di tengah */
            padding: 15px;
            background-color: #5a9c5a;
            /* Hijau tua lebih gelap */
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Warna background tabel */
        .table {
            background-color: #6bbf6b;
            /* Hijau sedikit lebih gelap */
            border-radius: 8px;
            overflow: hidden;
            border-collapse: collapse;
            width: 100%;
        }

        /* Header tabel lebih gelap */
        .table th {
            background-color: #4d8f4d;
            color: white;
            padding: 12px;
            text-align: left;
        }

        /* Warna sel tabel */
        .table td {
            background-color: #a3d9a5;
            /* Hijau lebih terang */
            color: black;
            padding: 10px;
        }

        /* Tambahkan border untuk tampilan lebih jelas */
        .table-bordered th,
        .table-bordered td {
            border: 1px solid white;
        }

        /* Hover efek agar lebih terlihat */
        .table tbody tr:hover {
            background-color: #5fa85f;
        }

        /* Mengatur ukuran kolom Action agar tidak terlalu besar */
        .table td:last-child,
        .table th:last-child {
            width: 90px;
            text-align: center;
        }

        /* Tombol Edit dan Delete lebih kecil agar tidak melebar */
        .action-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background-color: #d4f8d4;
            border-radius: 50%;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* Hover efek */
        .action-button:hover {
            background-color: #b8e6b8;
        }

        /* Ukuran ikon di dalam tombol */
        .action-button .material-icons {
            font-size: 18px;
            color: #2c662d;
        }

        /* Style spesifik untuk tombol Delete */
        .delete-button {
            background-color: #f8d4d4;
        }

        .delete-button:hover {
            background-color: #e6b8b8;
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
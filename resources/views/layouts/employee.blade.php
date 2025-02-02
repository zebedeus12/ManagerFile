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
            margin-left: 240px;
            /* Sama dengan lebar sidebar */
            padding: 20px;
            height: calc(100vh - 60px);
            /* Sesuaikan dengan tinggi header jika ada */
            overflow-y: auto;
            /* Aktifkan scroll vertikal */
            overflow-x: hidden;
            /* Hilangkan scroll horizontal */
            background-image: url('{{ asset('img/dashboard.jpeg') }}');
            width: calc(100% - 240px);
            /* Ambil sisa lebar layar */
            box-sizing: border-box;
            background-size: cover;
            /* Menyesuaikan gambar dengan ukuran layar */
            background-position: center;
            /* Menjaga gambar tetap di tengah */
            background-repeat: no-repeat;
            /* Sertakan padding dalam ukuran total elemen */
        }

        .table {
            width: 100%;
            /* Table mengambil seluruh lebar kontainer *
        }

        .table th,
        .table td {
            white-space: nowrap;
            /* Mencegah wrapping */
            overflow: hidden;
            /* Sembunyikan konten yang meluap */
            text-overflow: ellipsis;
            /* Tampilkan ... untuk konten panjang */
        }

        .table thead {
            /* position: sticky; Sticky header */
            top: 0;
            background-color: #ffffff;
            z-index: 1;
        }

        .table .password-column {
            width: 150px;
            /* Lebar kolom Password tetap */
            max-width: 150px;
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
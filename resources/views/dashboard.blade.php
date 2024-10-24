@extends('layouts.dashboard') <!-- Menggunakan layout khusus dashboard -->

@section('title', 'Dashboard')

@section('content')
<!-- Navbar -->
<nav class="navbar navbar-expand-lg" style="background-color: #d3d3d3;">
    <div class="container-fluid align-items-center">
        <!-- Ikon bulat dan logo -->
        <div class="d-flex align-items-center">
            <div style="width: 40px; height: 40px; background-color: black; border-radius: 50%; margin-right: 10px;"></div>
            <a class="navbar-brand fw-bold" href="#">BBSPJIS File Manager</a>
        </div>

        <!-- Search Bar -->
        <div class="mx-auto" style="width: 50%;">
            <input type="text" class="form-control" placeholder="Search" />
        </div>

        <!-- Nama User Dropdown -->
        <div>
            <span class="fw-bold">Nama User</span>
            <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Layout Sidebar dan Konten -->
<div class="main-layout">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile-section text-center p-3">
            <h3>Super Admin</h3>
            <p>Employee</p>
        </div>
        <nav class="menu">
            <ul class="list-unstyled">
                <li><a href="#">File</a></li>
                <li><a href="#">Media</a></li>
            </ul>
        </nav>
    </div>

    <!-- Konten Dashboard -->
    <div class="dashboard-content">
        <!-- Header -->
        <div class="header">
            <h2>Dashboard</h2>
            <div class="tools">
                <button class="add-folder-btn btn btn-outline-secondary">Add Folder</button>

                <!-- Tools for Grid & List Layout -->
                <div class="layout-tools ms-2">
                    <button class="btn grid-layout active">
                        <span class="material-symbols-outlined">
                            </span>
                    </button>
                    <button class="btn btn-outline-secondary list-layout">
                        <span class="material-symbols-outlined">
                            view_list
                            </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- File Grid -->
        <div class="file-grid mt-4">
            <div class="file-card">
                <img src="{{ asset('images/foto-icon.png') }}" alt="Foto" class="mb-2" />
                <p class="fw-bold">Foto</p>
                <span class="text-muted">20Mb</span><br>
                <span class="text-muted">20/10/2024</span>
            </div>
            <div class="file-card">
                <img src="{{ asset('images/pdf-icon.png') }}" alt="File" class="mb-2" />
                <p class="fw-bold">File</p>
                <span class="text-muted">20Mb</span><br>
                <span class="text-muted">20/10/2024</span>
            </div>
        </div>
    </div>
</div>

<!-- Styling Khusus -->
<style>
    /* Reset margin dan padding */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html, body {
        height: 100%;
        overflow: hidden;
    }

    /* Layout utama */
    .main-layout {
        display: flex;
        width: 100vw;
        height: 100vh;
    }

    /* Sidebar */
    .sidebar {
        width: 250px;
        background-color: #343a40;
        color: white;
    }

    .profile-section {
        padding: 20px;
    }

    .menu ul {
        padding: 0;
    }

    .menu ul li {
        list-style: none;
        margin: 15px 0;
    }

    .menu ul li a {
        color: #adb5bd;
        text-decoration: none;
        padding: 10px 20px;
        display: block;
    }

    .menu ul li a:hover {
        background-color: #495057;
        color: white;
    }

    /* Konten Dashboard */
    .dashboard-content {
        flex: 1;
        background-color: #f1f1f1;
        padding: 20px;
        overflow-y: auto;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .tools {
        display: flex;
        align-items: center;
    }

    .layout-tools button {
        border-radius: 0;
        margin-left: 5px;
        padding: 5px;
    }

    .layout-tools img {
        display: block;
    }

    .layout-tools .active {
        background-color: #6c757d;
        color: white;
    }

    .file-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .file-card {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .file-card img {
        width: 50px;
        height: auto;
        margin-bottom: 10px;
    }
</style>

<!-- JavaScript untuk Toggle Layout -->
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
@endsection

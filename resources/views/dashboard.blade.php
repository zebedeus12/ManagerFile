@extends('layouts.dashboard') <!-- Menggunakan layout khusus dashboard -->

@section('title', 'Dashboard')

@section('content')
<!-- Navbar -->
<nav class="navbar navbar-expand-lg" style="background-color: #d3d3d3;">
    <div class="container-fluid align-items-center">
        <!-- Ikon bulat dan logo -->
        <div class="d-flex align-items-center">
            <div style="width: 40px; height: 40px; background-color: black; border-radius: 50%; margin-right: 10px;">
            </div>
            <a class="navbar-brand fw-bold" href="#">BBSPJIS File Manager</a>
        </div>

        <!-- Search Bar -->
        <div class="mx-auto" style="width: 50%;">
            <input type="text" class="form-control" placeholder="Search" />
        </div>

        <!-- Nama User Dropdown -->
        <div>
            <span class="fw-bold">Nama User</span>
            <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            </button>
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
            <button class="add-folder-btn btn btn-outline-secondary">Add Folder</button>
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
    /* Menghapus margin dan padding global */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html,
    body {
        height: 100%;
        overflow: hidden;
        /* Menjaga konten tetap dalam layar tanpa scroll kecuali pada konten utama */
    }

    body {
        font-family: 'Poppins', sans-serif;
    }

    /* Layout Utama: Sidebar dan Konten */
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
        height: 100%;
    }

    .profile-section {
        padding: 20px;
    }

    .sidebar h3 {
        color: #f8f9fa;
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
        flex-grow: 1;
        background-color: #f1f1f1;
        padding: 20px;
        overflow-y: auto;
        /* Scroll hanya pada konten jika lebih panjang dari viewport */
    }

    .file-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-top: 20px;
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

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
</style>
</style>
@endsection
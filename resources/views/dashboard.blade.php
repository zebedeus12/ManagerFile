@extends('layouts.dashboard') <!-- Menggunakan layout khusus dashboard -->

@section('title', 'Dashboard')

@section('content')
<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid align-items-center">
        <div class="d-flex align-items-center">
            <img src="{{ asset('img/logo.png') }}" alt="Logo"
                style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px;">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">BBSPJIS File Manager</a>
        </div>
        <div>
            <span class="fw-bold">Nama User</span>
            <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false"></button>
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
        <nav class="menu">
            <ul class="list-unstyled">
                <li>
                <li>
                    <a href="{{ route('employees.index') }}" class="icon-link">
                        <span class="material-icons">admin_panel_settings</span>
                    </a>
                </li>

                </li>
                <li>
                    <a href="{{ route('folder.form') }}" class="icon-link">
                        <span class="material-icons">folder</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('media.index') }}" class="icon-link">
                        <span class="material-icons">perm_media</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Konten Dashboard -->
    <div class="dashboard-content">
        <div class="header d-flex align-items-center justify-content-between">
            <h2 class="mb-0">Dashboard</h2>
            <div class="tools d-flex align-items-center">
                <button class="add-folder-btn btn btn-outline-secondary me-3">Add Folder</button>
                <div class="layout-tools d-flex align-items-center">
                    <button class="btn btn-outline-secondary grid-layout active me-2" title="Grid Layout">
                        <span class="material-icons">grid_view</span>
                    </button>
                    <button class="btn btn-outline-secondary list-layout" title="List Layout">
                        <span class="material-icons">view_list</span>
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
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html,
    body {
        height: 100%;
        overflow: hidden;
    }

    .main-layout {
        display: flex;
        width: 100vw;
        height: 100vh;
    }

    /* Sidebar */
    .sidebar {
        width: 80px;
        height: 100vh;
        background: linear-gradient(180deg, #188A98, #5CCED1);
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 20px;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        border-right: 1px solid #e0e0e0;
    }

    .icon-link {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 60px;
        text-decoration: none;
        color: white;
        font-size: 28px;
        /* Ukuran ikon */
    }

    .icon-link:hover {
        background: none;
        /* Tidak ada efek hover */
    }


    .material-icons {
        font-size: 28px;
    }


    .menu ul {
        width: 100%;
        padding: 0;
        list-style: none;
    }

    .menu ul li {
        margin: 20px 0;
    }

    .icon-link:hover {
        background-color: #145d65;
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
@extends('layouts.dashboard')

@section('title', 'File Manager')

@section('content')
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid align-items-center">
        <div class="d-flex align-items-center">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">BBSPJIS File Manager</a>
        </div>
        <div class="d-flex align-items-center user-info-notification ms-auto">
            @if(Auth::check())
                <span class="fw-bold me-3">{{ Auth::user()->nama_user }}</span>
            @else
                <span class="fw-bold me-3">Guest</span>
            @endif
            <a href="#" class="notification-link me-3" title="Notifications">
                <span class="material-icons">notifications</span>
                <span class="notification-count">3</span> <!-- Bisa diubah sesuai jumlah notifikasi -->
            </a>
        </div>
    </div>
</nav>

<div class="main-layout">
    <div class="sidebar">
        <nav class="menu">
            <ul class="list-unstyled">
                <li><a href="{{ route('employees.index') }}" class="icon-link"><span
                            class="material-icons">admin_panel_settings</span></a></li>
                <li><a href="{{ route('file.index') }}" class="icon-link"><span class="material-icons">folder</span></a>
                </li>
                <li><a href="{{ route('media.index') }}" class="icon-link"><span
                            class="material-icons">perm_media</span></a></li>
                <li>
                    <!-- Tambahkan Logout Button di Sidebar -->
                    <form action="{{ route('logout') }}" method="POST" class="d-flex justify-content-center mt-3">
                        @csrf
                        <button type="submit" class="btn btn-link icon-link" title="Logout">
                            <span class="material-icons">logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
    <div class="container content-container">
        <div class="header d-flex align-items-center justify-content-between mb-4">
            <h1 class="mb-0">File Manager</h1>
            <button class="add-folder ms-auto" onclick="location.href='{{ route('folder.create') }}'">Add
                Folder</button>
        </div>
        <p class="text-muted">Terdapat {{ $folders->count() }} Folders, {{ $files->count() }} File.
        </p>
        <div class="row">
            {{-- Grid Folders --}}
            @foreach ($folders as $folder)
                <div class="file-card">
                    <a href="{{ route('folder.show', $folder->id) }}" class="folder-link">
                        <div class="icon-container">
                            <span class="material-icons folder-icon">folder</span>
                        </div>
                        <div class="file-info">
                            <span class="fw-bold">{{ $folder->name }}</span>
                            <span class="text-muted">Updated at: {{ $folder->updated_at->format('d/m/Y') }}</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
<style>
    .logo {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 15px;
    }

    .navbar {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    .notification-link {
        position: relative;
        color: #333;
        font-size: 24px;
        text-decoration: none;
        transition: color 0.3s;
    }

    .notification-link:hover {
        color: #188A98;
    }

    .notification-count {
        position: absolute;
        top: -5px;
        right: -10px;
        background-color: #ff5e5e;
        color: white;
        font-size: 12px;
        border-radius: 50%;
        padding: 2px 6px;
        font-weight: bold;
    }

    .main-layout {
        display: flex;
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

    /* File Manager Grid */
    .content-container {
        padding: 30px;
        background-color: #f8f9fa;
    }

    .file-card {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .file-card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.15);
    }

    .icon-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .folder-icon {
        font-size: 48px;
        color: #FFB400;
    }

    .file-icon {
        width: 50px;
        height: 50px;
    }

    .add-folder {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .add-folder:hover {
        background-color: #0056b3;
    }
</style>
@endsection
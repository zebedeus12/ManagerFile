@extends('dashboard')

@section('title', 'File Manager')

@section('content')
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid align-items-center">
        <div class="d-flex align-items-center">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo">
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

<div class="main-layout">
    <div class="sidebar">
        <nav class="menu">
            <ul class="list-unstyled">
                <li><a href="{{ route('employees.index') }}" class="icon-link"><span
                            class="material-icons">admin_panel_settings</span></a></li>
                <li><a href="{{ route('file.index') }}" class="icon-link"><span class="material-icons">folder</span></a>
                </li>
                <li><a href="#" class="icon-link"><span class="material-icons">perm_media</span></a></li>
            </ul>
        </nav>
    </div>
    <div class="container">
        <div class="header">
            <h1>File</h1>
            <div class="buttons">
                <button class="add-file" onclick="location.href='{{ route('files.create') }}'">Add File</button>
                <button class="add-folder" onclick="location.href='{{ route('folder.form') }}'">Add Folder</button>
            </div>
        </div>
        <p>Terdapat {{ $folders->count() }} Folders, {{ $files->count() }} File,
            {{ $files->whereIn('type', ['jpg', 'png', 'gif'])->count() }} Media.
        </p>
        <div class="grid">
            @foreach ($folders as $folder)
                <div class="item">
                    <span class="material-icons">folder</span>
                    <span>{{ $folder->name }}</span>
                </div>
            @endforeach
            @foreach ($files as $file)
                <div class="item">
                    <img src="{{ asset('icons/' . $file->type . '.png') }}" alt="{{ $file->type }} icon">
                    <span>{{ $file->name }}</span>
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

    .main-layout {
        display: flex;
        height: 100vh;
    }

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
</style>
@endsection
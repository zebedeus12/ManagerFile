@extends('dashboard')

@section('title', 'Tambah Folder')

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
        <h2>Buat Folder Baru</h2>
        <form action="{{ route('folder.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="folder_name" class="form-label">Nama Folder</label>
                <input type="text" class="form-control" id="folder_name" name="folder_name" required>
            </div>
            <div class="mb-3">
                <label for="accessibility" class="form-label">Aksesibilitas</label>
                <select class="form-select" id="accessibility" name="accessibility" required>
                    <option value="public">Publik</option>
                    <option value="private">Rahasia</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Folder</button>
        </form>
    </div>
</div>

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
        background-color: #145d65;
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

    /*container*/
    .container {
        flex: 1;
        background-color: #f1f1f1;
        padding: 20px;
        overflow-y: auto;
    }
</style>
@endsection
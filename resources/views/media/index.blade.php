@extends('layouts.dashboard')

@section('title', 'Media')

@section('content')
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid align-items-center">
        <div class="d-flex align-items-center">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo">
            <a class="navbar-brand fw-bold" href="#">BBSPJIS File Manager</a>
        </div>
        <div>
            <span class="fw-bold">Nama User</span>
            <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
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
                <li><a href="{{ route('employees.index') }}" class="icon-link"><span class="material-icons">admin_panel_settings</span></a></li>
                <li><a href="#" class="icon-link"><span class="material-icons">folder</span></a></li>
                <li><a href="{{ route('media.index') }}" class="icon-link"><span class="material-icons">perm_media</span></a></li>
            </ul>
        </nav>
    </div>

    <div class="employee-content p-4">
        <div class="header d-flex align-items-center justify-content-between mb-4">
            <h2>Media Manager</h2>
            <a href="{{ route('media.create') }}" class="btn btn-primary">Add Media</a>
        </div>
    
        <div class="file-grid mt-4">
            @foreach($mediaItems as $media)
                <div class="file-card">
                    <img src="{{ asset($media->path) }}" alt="{{ $media->name }}" class="mb-2" />
                    <p class="fw-bold">{{ $media->name }}</p>
                    <span class="text-muted">{{ $media->type }}</span>
                    <div class="d-flex mt-2">
                        <a href="{{ route('media.edit', $media->id) }}" class="btn btn-warning btn-sm me-2">Edit</a>
                        <form action="{{ route('media.destroy', $media->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
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
    }

    .icon-link {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 60px;
        color: white;
        font-size: 28px;
    }

    .icon-link:hover {
        background-color: #145d65;
    }

    .employee-content {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
    }

    .table th, .table td {
        vertical-align: middle;
    }
</style>
@endsection

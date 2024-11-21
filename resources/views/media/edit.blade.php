@extends('layouts.dashboard')

@section('title', 'Edit Media')

@section('content')
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid align-items-center">
        <div class="d-flex align-items-center">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px;">
            <a class="navbar-brand fw-bold" href="#">BBSPJIS File Manager</a>
        </div>
        <div class="d-flex align-items-center user-info-notification">
            @if(Auth::check())
                <span class="fw-bold">{{ Auth::user()->nama_user }}</span>
            @else
                <span class="fw-bold">Guest</span>
            @endif
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
                    <a href="{{ route('employees.index') }}" class="icon-link">
                        <span class="material-icons">admin_panel_settings</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('file.index') }}" class="icon-link">
                        <span class="material-icons">folder</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('media.index') }}" class="icon-link">
                        <span class="material-icons">perm_media</span>
                    </a>
                </li>
                <li>
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

    <!-- Konten Utama -->
    <div class="container mt-4">
        <h2 class="mb-4">Edit Media</h2>

        <!-- Form untuk Mengedit Media -->
        <form action="{{ route('media.update', ['media' => $media->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Input Nama Media -->
            <div class="mb-3">
                <label for="name" class="form-label">Media Name</label>
                <input type="text" name="name" value="{{ $media->name }}" required>
                <input type="hidden" value="{{ $media->type }}" name="type">
            </div>
            <button type="submit" class="btn btn-primary">Update Media</button>
        </form>

        <!-- Form untuk Menghapus Media -->
        <form action="{{ route('media.destroy', ['media' => $media->id]) }}" method="POST" class="mt-2">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger"
                onclick="return confirm('Are you sure you want to delete this media?')">Delete Media</button>
        </form>
        
        <!-- Notifikasi Sukses -->
        @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <!-- Style Khusus -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            height: 100%;
            overflow: hidden;
        }
        .main-layout {
            display: flex;
            width: 100vw;
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
    </style>

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

@extends('layouts.dashboard')

@section('title', 'Edit Employee')

@section('content')
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid align-items-center">
        <div class="d-flex align-items-center">
            <img src="{{ asset('img/logo.png') }}" alt="Logo"
                style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px;">
            <a class="navbar-brand fw-bold" href="#">BBSPJIS File Manager</a>
        </div>
        <div>
            <span class="fw-bold">Nama User</span>
        </div>
    </div>
</nav>

<div class="main-layout">
    <div class="sidebar">
        <nav class="menu">
            <ul class="list-unstyled">
                <li>
                    <a href="{{ route('employees.index') }}" class="icon-link">
                        <span class="material-icons">admin_panel_settings</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('file.index') }}" class="icon-link"><span class="material-icons">folder</span></a>
                </li>
                <li>
                    <a href="{{ route('media.index') }}" class="icon-link"><span
                            class="material-icons">perm_media</span></a>
                </li>
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

    <div class="container mt-4">
        <h2 class="mb-4">Edit Employee</h2>

        <form action="{{ route('employees.update', $employee) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nama_user" class="form-label">Nama User</label>
                <input type="text" name="nama_user" class="form-control" value="{{ $employee->nama_user }}" required>
            </div>

            <div class="mb-3">
                <label for="login" class="form-label">Login</label>
                <input type="text" name="login" class="form-control" value="{{ $employee->login }}" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password (Biarkan kosong jika tidak ingin mengubah)</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" class="form-control" required>
                    <option value="user" {{ $employee->role == 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ $employee->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="super_admin" {{ $employee->role == 'super_admin' ? 'selected' : '' }}>Super Admin
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<style>
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
        background-color: #145d65;
    }
</style>
@endsection
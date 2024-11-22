@extends('layouts.dashboard')

@section('title', 'Edit Employee')

@section('content')
<!-- Navbar -->
@include('layouts.navbar')

<div class="main-layout">
    @include('layouts.sidebar')
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
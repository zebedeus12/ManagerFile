@extends('dashboard')

@section('title', 'Employees')

@section('content')
<!-- Navbar -->
@include('layouts.navbar')
<div class="main-layout">
    @include('layouts.sidebar')
    <div class="employee-content p-4">
        <div class="header d-flex align-items-center justify-content-between mb-4">
            <h2>Employee List</h2>
            <div class="d-flex">
                <form action="{{ route('employees.index') }}" method="GET" class="d-flex me-3">
                    <input type="text" name="search" class="form-control" placeholder="Search..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary ms-2">Search</button>
                </form>
                <a href="{{ route('employees.create') }}" class="btn btn-primary">Add Employee</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama User</th>
                    <th>Login</th>
                    <th>Password</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $employee->nama_user }}</td>
                        <td>{{ $employee->login }}</td>
                        <td>
                            <span class="password-mask" data-password="{{ $employee->password }}">******</span>
                            <button type="button" class="btn btn-link toggle-password" onclick="togglePassword(this)">
                                <span class="material-icons">visibility</span>
                            </button>
                        </td>
                        <td>{{ ucfirst($employee->role) }}</td>
                        <td>
                            <a href="{{ route('employees.edit', ['employee' => $employee->id_user]) }}"
                                class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('employees.destroy', $employee) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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

    .employee-content {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }
</style>
<script>
    function togglePassword(button) {
        const span = button.previousElementSibling; // Find the span with class 'password-mask'
        if (span.textContent === '******') {
            span.textContent = span.getAttribute('data-password');
            button.querySelector('.material-icons').textContent = 'visibility_off';
        } else {
            span.textContent = '******';
            button.querySelector('.material-icons').textContent = 'visibility';
        }
    }
</script>
@endsection
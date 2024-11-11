@extends('dashboard')

@section('title', 'Employees')

@section('content')
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid align-items-center">
        <div class="d-flex align-items-center">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">BBSPJIS File Manager</a>
        </div>
        <div>
            <a href="#" class="notification-link me-3" title="Notifications">
                <span class="material-icons">notifications</span>
                <span class="notification-count">3</span> <!-- Bisa diubah sesuai jumlah notifikasi -->
            </a>
            @if(Auth::check())
                <span class="fw-bold">{{ Auth::user()->nama_user }}</span>
                <span class="text-muted d-block margin-left">{{ Auth::user()->role }}</span>
            @else
                <span class="fw-bold">Guest</span>
            @endif

        </div>
    </div>
</nav>

<div class="main-layout">
    <div class="sidebar">
        <nav class="menu">
            <ul class="list-unstyled">
                <li><a href="{{ route('employees.index') }}" class="icon-link"><span
                            class="material-icons">admin_panel_settings</span></a></li>
                <li>
                    <a href="{{ route('file.index') }}" class="icon-link">
                        <span class="material-icons">folder</span>
                    </a>
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
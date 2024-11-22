@extends('layouts.employee')

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
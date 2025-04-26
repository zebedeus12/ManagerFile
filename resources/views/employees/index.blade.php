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
                <div class="d-flex search-wrapper">
                    <form action="{{ route('employees.index') }}" method="GET" class="search-form">
                        <div class="search-container">
                            <input type="text" name="search" class="search-input" placeholder="Search Karyawan..."
                                value="{{ request('search') }}">
                        </div>
                        <button type="submit" class="search-button">
                            <span class="material-icons">search</span>
                        </button>
                    </form>
                    <!-- Tombol Tambah -->
                    <button class="add-employee-button" data-bs-toggle="modal" data-bs-target="#createEmployeeModal">
                        <span class="material-icons">person_add</span>
                    </button>
                </div>
            </div>

            @include('layouts.index')

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
                            </td>
                            <td>{{ ucfirst($employee->role) }}</td>
                            <td>
                                <a href="#" class="action-button edit-button" onclick="editEmployee({{ $employee }})"
                                    data-bs-toggle="modal" data-bs-target="#editEmployeeModal">
                                    <span class="material-icons">edit</span>
                                </a>

                                <a href="#" class="action-button delete-button"
                                    onclick="confirmDelete({{ $employee->id_user }})" data-bs-toggle="modal"
                                    data-bs-target="#deleteEmployeeModal">
                                    <span class="material-icons">person_remove</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Modal Create Employee -->
            <div class="modal fade" id="createEmployeeModal" tabindex="-1" aria-labelledby="createEmployeeModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Employee</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="createEmployeeForm" action="{{ route('employees.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Nama User</label>
                                    <input type="text" name="nama_user" id="create_nama_user" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Login</label>
                                    <input type="text" name="login" id="create_login" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" id="create_password" class="form-control"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Role</label>
                                    <select name="role" class="form-control" required>
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                        <option value="super_admin">Super Admin</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">ID Struktural Tim</label>
                                    <input type="number" name="id_struktural_tim" class="form-control" required>
                                    <small class="form-text text-muted">Isi bila ketua tim, jika bukan isi 0 atau 1.</small>
                                </div>

                                <button type="submit" class="btn btn-success">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Edit Employee -->
            <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Employee</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editEmployeeForm" action="#" method="POST">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="id_user" id="edit_id_user">

                                <div class="mb-3">
                                    <label class="form-label">Nama User</label>
                                    <input type="text" name="nama_user" id="edit_nama_user" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Login</label>
                                    <input type="text" name="login" id="edit_login" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Password (Biarkan kosong jika tidak ingin mengubah)</label>
                                    <input type="password" name="password" id="edit_password" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Role</label>
                                    <select name="role" id="edit_role" class="form-control" required>
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                        <option value="super_admin">Super Admin</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-success">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Delete Employee -->
            <div class="modal fade" id="deleteEmployeeModal" tabindex="-1" aria-labelledby="deleteEmployeeModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Employee</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin menghapus karyawan ini?</p>
                        </div>
                        <div class="modal-footer">
                            <form id="deleteEmployeeForm" action="#" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function editEmployee(employee) {
            document.getElementById("editEmployeeForm").action = `/employees/${employee.id_user}`;
            document.getElementById("edit_id_user").value = employee.id_user;
            document.getElementById("edit_nama_user").value = employee.nama_user;
            document.getElementById("edit_login").value = employee.login;
            document.getElementById("edit_role").value = employee.role;
        }

        function confirmDelete(id_user) {
            document.getElementById("deleteEmployeeForm").action = `/employees/${id_user}`;
        }
    </script>

@endsection
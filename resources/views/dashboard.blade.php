@extends('layouts.dashboard') <!-- Menggunakan layout khusus dashboard -->

@section('title', 'Dashboard')

@section('content')
    <!-- Navbar -->
    @include('layouts.navbar')

    <!-- Layout Sidebar dan Konten -->
    <div class="main-layout">
        @include('layouts.sidebar')
        <!-- Konten Dashboard -->
        <div class="dashboard-content">
            <h2 class="mb-0">Dashboard</h2>
            <div class="header d-flex justify-content-between align-items-center mb-4">
                <div class="date-filter">
                    <select class="form-select" aria-label="Pilih Tanggal" id="date-filter">
                        <option selected>Pilih Tanggal</option>
                        @foreach ($foldersGroupedByDate as $date => $folders)
                            <option value="{{ $date }}">{{ \Carbon\Carbon::parse($date)->format('d F Y') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="layout-buttons">
                    <button id="layout-toggle" class="btn">
                        <span id="layout-icon" class="material-icons">grid_view</span>
                    </button>
                </div>
            </div>
            @foreach ($foldersGroupedByDate as $date => $folders)
                <div class="date-group" id="date-{{ $date }}">
                    <h3>{{ \Carbon\Carbon::parse($date)->format('d F Y') }}</h3>
                    <div class="file-grid mt-4">
                        @foreach ($folders as $folder)
                            <div class="file-card">
                                <!-- Untuk Folder -->
                                @if ($folder instanceof App\Models\Folder)
                                    <a href="{{ route('folder.show', $folder->id) }}" class="folder-link">
                                        <div class="icon-container">
                                            <span class="material-icons folder-icon">folder</span>
                                        </div>
                                        <div class="file-info">
                                            <span class="fw-bold">{{ $folder->name }}</span>
                                        </div>
                                    </a>
                                @endif
                                <!-- Untuk MediaFolder -->
                                @if ($folder instanceof App\Models\MediaFolder)
                                    <a href="{{ route('media.folder.show', $folder->id) }}" class="folder-link">
                                        <div class="icon-container">
                                            <span class="material-icons folder-icon">folder</span>
                                        </div>
                                        <div class="file-info">
                                            <span class="fw-bold">{{ $folder->name }}</span>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Tampilan List (Disembunyikan Awal) -->
                    <div class="file-list mt-4" style="display: none;">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Created At</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($folders as $folder)
                                    <tr>
                                        <td>{{ $folder->name }}</td>
                                        <td>{{ date('d M Y', strtotime($folder->created_at)) }}</td>
                                        <td>{{ $folder->description ?? 'Tidak ada keterangan' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Styling Khusus -->
    <style>
        .table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        .table th,
        .table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .table th {
            background: #4CAF50;
            color: white;
            text-align: left;
        }

        .main-layout {
            display: flex;
            width: 100vw;
            height: 100vh;

        }

        /* Sidebar styling */
        .sidebar {
            background-color: #188A98;
            color: white;
            width: 250px;
            /* Lebar sidebar tetap */
            position: fixed;
            height: 100vh;
            box-shadow: 2px 0px 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        /* Konten Dashboard */
        .dashboard-content {
            margin-left: 240px;
            /* Sama dengan lebar sidebar */
            padding: 20px;
            height: calc(100vh - 60px);
            /* Sesuaikan dengan tinggi header jika ada */
            overflow-y: auto;
            /* Aktifkan scroll vertikal */
            overflow-x: hidden;
            /* Hilangkan scroll horizontal */
            background-color: #ffffff;
            width: calc(100% - 240px);
            /* Ambil sisa lebar layar */
            box-sizing: border-box;
            /* Sertakan padding dalam ukuran total elemen */
        }

        .sidebar.collapsed~.dashboard-content {
            margin-left: 70px;
        }

        /* Tambahkan margin untuk konten */
        .dashboard-content h2,
        .dashboard-content .date-group,
        .dashboard-content .file-grid {
            margin-left: 20px;
        }

        .file-card:hover {
            transform: translateY(-5px);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.dashboard-content');

            document.querySelector('.toggle-sidebar').addEventListener('click', function () {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('collapsed');
            });
        });
    </script>
@endsection
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

        @foreach ($foldersGroupedByDate as $date => $folders)
            <div class="date-group">
                <h3>{{ $date }}</h3>
                <div class="file-grid mt-4">
                    @foreach ($folders as $folder)
                        <div class="file-card">
                            <a href="{{ route('folder.show', $folder->id) }}" class="folder-link">
                                <div class="icon-container">
                                    <span class="material-icons folder-icon">folder</span>
                                </div>
                                <div class="file-info">
                                    <span class="fw-bold">{{ $folder->name }}</span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Styling Khusus -->
<style>
    .main-layout {
        display: flex;
        width: 100vw;
        height: 100vh;
    }

    /* Sidebar styling */
    .sidebar {
        width: 70px;
        transition: all 0.3s ease;
    }

    .sidebar.collapsed {
        width: 70px;
    }

    /* Konten Dashboard */
    .dashboard-content {
        flex: 1;
        background-color: white;
        padding: 20px;
        margin-left: 240px;
        transition: margin-left 0.3s ease;
    }

    .sidebar.collapsed ~ .dashboard-content {
        margin-left: 70px;
    }

    /* Tambahkan margin untuk konten */
    .dashboard-content h2,
    .dashboard-content .date-group,
    .dashboard-content .file-grid {
        margin-left: 20px;
    }

    .file-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .file-card {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        padding: 15px;
        text-align: center;
        transition: transform 0.2s;
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
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

    /* Konten Dashboard */
    .dashboard-content {
        flex: 1;
        background-color: #f1f1f1;
        padding: 20px;
        overflow-y: auto;
    }
</style>
@endsection
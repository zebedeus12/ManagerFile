@extends('layouts.dashboard')

@section('title', 'Media')

@section('content')
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid align-items-center">
        <div class="d-flex align-items-center">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">BBSPJIS File Manager</a>
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
                <li><a href="{{ route('employees.index') }}" class="icon-link"><span
                            class="material-icons">admin_panel_settings</span></a></li>
                <li><a href="{{ route('file.index') }}" class="icon-link"><span class="material-icons">folder</span></a>
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
            <h2>Media Manager</h2>
            <form action="{{ route('media.search') }}" method="GET" class="d-flex">
                <input type="text" name="query" class="form-control me-2" placeholder="Search media..." required>
                <button type="submit" class="btn btn-outline-primary">Search</button>
            </form>
            <a href="{{ route('media.create') }}" class="btn btn-primary">Add Folder</a>
            <a href="{{ route('media.create') }}" class="btn btn-primary">Add Media</a>
        </div>

        <!-- Filter and View Options -->
        <div class="filter-options mb-3 d-flex align-items-center justify-content-between">
            <div class="filter-buttons d-flex align-items-center">
                <button class="btn btn-filter" data-filter="all">All items</button>
                <button class="btn btn-filter" data-filter="photo">Photo</button>
                <button class="btn btn-filter" data-filter="video">Video</button>
            </div>


            <div class="zoom-slider-container d-flex align-items-center">
                <!-- Zoom Out Button -->
                <button class="zoom-slider-button" id="zoom-out">
                    <span class="zoom-icon">
                        <svg viewBox="0 0 24 24" fill="none" width="24" height="24" aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M11.75 4C6.535 4 4 6.535 4 11.75s2.535 7.75 7.75 7.75 7.75-2.535 7.75-7.75S16.965 4 11.75 4Zm0 14c-4.322 0-6.25-1.927-6.25-6.25 0-4.322 1.928-6.25 6.25-6.25 4.323 0 6.25 1.928 6.25 6.25 0 4.323-1.927 6.25-6.25 6.25Z"
                                fill="currentColor"></path>
                            <path d="M9 11h5.5v1.5H9V11Z" fill="currentColor"></path>
                        </svg>
                    </span>
                </button>

                <!-- Zoom Slider -->
                <input aria-label="Photo Zoom Slider" id="zoom-slider" class="zoom-slider" type="range" min="100"
                    max="200" value="100">

                <!-- Zoom In Button -->
                <button class="zoom-slider-button" id="zoom-in">
                    <span class="zoom-icon">
                        <svg viewBox="0 0 24 24" fill="none" width="24" height="24" aria-hidden="true">
                            <path
                                d="M11.75 4C6.535 4 4 6.535 4 11.75s2.535 7.75 7.75 7.75 7.75-2.535 7.75-7.75S16.965 4 11.75 4Zm0 14c-4.322 0-6.25-1.927-6.25-6.25 0-4.322 1.928-6.25 6.25-6.25 4.323 0 6.25 1.928 6.25 6.25 0 4.323-1.927 6.25-6.25 6.25Z"
                                fill="currentColor"></path>
                            <path d="M12.5 9H11v2H9v1.5h2v2h1.5v-2h2V11h-2V9Z" fill="currentColor"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </div>

        <!-- Media Grid Display -->
        <div class="file-grid mt-4" id="media-container">
            @foreach($mediaItems as $media)
                <div class="file-card">
                    <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $media->name }}" class="media-preview mb-2" />
                    <div class="file-info">
                        <p class="fw-bold">{{ $media->name }}</p>
                        <span class="text-muted">{{ ucfirst($media->type) }}</span>
                    </div>
                    <div class="file-actions">
                        <a href="{{ route('media.edit', $media->id) }}" class="btn btn-warning btn-sm me-2">Edit</a>
                        <button type="button" class="delete-button btn btn-danger btn-sm"
                            data-url="{{ route('media.destroy', ['media' => $media->id]) }}">Delete</button>
                    </div>
                </div>
            @endforeach
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>
</div>

<script>
    $(document).ready(function () {
        const zoomSlider = $('#zoom-slider');
        const mediaContainer = $('#media-container');

        // Adjust zoom level on slider input
        zoomSlider.on('input', function () {
            const zoomLevel = $(this).val() / 100;
            mediaContainer.find('.file-card').css({
                'transform': `scale(${zoomLevel})`
            });
        });

        // Zoom out button
        $('#zoom-out').on('click', function () {
            let currentValue = parseInt(zoomSlider.val());
            if (currentValue > 100) {
                zoomSlider.val(currentValue - 10).trigger('input');
            }
        });

        // Zoom in button
        $('#zoom-in').on('click', function () {
            let currentValue = parseInt(zoomSlider.val());
            if (currentValue < 200) {
                zoomSlider.val(currentValue + 10).trigger('input');
            }
        });
    });
</script>

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
        color: white;
        font-size: 28px;
        text-decoration: none;
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

    .filter-buttons .btn-filter {
        margin-right: 10px;
    }

    .zoom-slider-container {
        display: flex;
        align-items: center;
    }

    .zoom-slider-button {
        background: none;
        border: none;
        margin: 0 5px;
    }

    .zoom-slider {
        width: 100px;
        margin: 0 10px;
    }

    .file-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .file-card {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 10px;
        width: 200px;
        text-align: center;
    }

    .media-preview {
        width: 100%;
        border-radius: 8px;
        max-height: 150px;
        object-fit: cover;
    }

    .file-actions {
        margin-top: 10px;
    }

    .delete-button {
        background-color: #e74c3c;
    }
</style>
@endsection
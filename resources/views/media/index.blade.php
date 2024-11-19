@extends('layouts.dashboard')

@section('title', 'Media')

@section('content')
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid align-items-center">
        <div class="d-flex align-items-center">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">BBSPJIS File Manager</a>
        </div>
        <div class="d-flex align-items-center user-info-notification">
            @if(Auth::check())
                <span class="fw-bold">{{ Auth::user()->nama_user }}</span>
            @else
                <span class="fw-bold">Guest</span>
            @endif
            <a href="#" class="notification-link me-3" title="Notifications">
                <span class="material-icons">notifications</span>
                <span class="notification-count">3</span> <!-- Bisa diubah sesuai jumlah notifikasi -->
            </a>
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
            <a href="{{ route('media.create') }}" class="btn btn-primary">Create Media</a>
        </div>

        <!-- Filter and View Options -->
        <div class="filter-options mb-3 d-flex align-items-center justify-content-between">
            <div class="filter-buttons d-flex align-items-center">
                <button class="btn btn-filter active" data-filter="all">All items</button>
                <button class="btn btn-filter" data-filter="photo">Photo</button>
                <button class="btn btn-filter" data-filter="video">Video</button>
            </div>

            <div class="zoom-slider-container d-flex align-items-center">
                <button class="zoom-slider-button" id="zoom-out">
                    <span class="zoom-icon">
                        <!-- SVG for zoom out -->
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 12H4" stroke="black" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                </button>
                <input aria-label="Photo Zoom Slider" id="zoom-slider" class="zoom-slider" type="range" min="100"
                    max="200" value="100">
                <button class="zoom-slider-button" id="zoom-in">
                    <span class="zoom-icon">
                        <!-- SVG for zoom in -->
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5V19" stroke="black" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M5 12H19" stroke="black" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                </button>
            </div>
        </div>

        <!-- Media Grid Display -->
        <div class="file-grid mt-4" id="media-container">
            @foreach($mediaItems as $media)
                <div class="file-card" data-type="{{ $media->type }}">
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
        // Filter media items
        $('.btn-filter').on('click', function () {
            const filter = $(this).data('filter');
            $('.btn-filter').removeClass('active');
            $(this).addClass('active');

            $('.file-card').each(function () {
                const type = $(this).data('type');
                if (filter === 'all' || type === filter) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Zoom and other existing code
        const zoomSlider = $('#zoom-slider');
        const mediaContainer = $('#media-container');

        // Adjust zoom level on slider input
        zoomSlider.on('input', function () {
            const zoomLevel = $(this).val() / 100;
            mediaContainer.find('.file-card').css({
            'transform': `scale(${zoomLevel})`,
            'transition': 'transform 0.3s ease' // Smooth transition
        });
        });

        $('#zoom-out').on('click', function () {
            let currentValue = parseInt(zoomSlider.val());
            if (currentValue > zoomSlider.attr('min')) {
                zoomSlider.val(currentValue - 10).trigger('input');
            }
        });

        $('#zoom-in').on('click', function () {
            let currentValue = parseInt(zoomSlider.val());
            if (currentValue < zoomSlider.attr('max')) {
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
        border-radius: 5px;
        padding: 10px;
        width: 150px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .file-card img {
        width: 100%;
        height: auto;
        border-radius: 5px;
    }

    .file-actions .btn {
        margin-top: 10px;
    }
</style>
@endsection
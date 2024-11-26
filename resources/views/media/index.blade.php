@extends('layouts.media')

@section('title', 'Media')

@section('content')
<!-- Navbar -->
@include('layouts.navbar')

<div class="main-layout">
    @include('layouts.sidebar')
    <div class="employee-content p-4">
        <div class="header d-flex align-items-center justify-content-between mb-4">
            <h2>Media Manager</h2>
            <form action="{{ route('media.search') }}" method="GET" class="d-flex">
                <input type="text" name="query" class="form-control me-2" placeholder="Search media..." required>
                <button type="submit" class="btn btn-outline-primary">Search</button>
            </form>
            <button class="add-folder ms-auto" onclick="location.href='{{ route('folder.create') }}'">Add
                Folder</button>
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
                        <form action="{{ route('media.destroy', ['media' => $media->id]) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-button btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure you want to delete this media?')">Delete Media</button>
                        </form>
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
</script>
@endsection
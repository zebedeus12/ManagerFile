@extends('layouts.media')

@section('title', 'Media Manager')

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
            <!-- Button untuk Add Folder -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFolderModal">Add Folder</button>
            <a href="{{ route('media.create') }}" class="btn btn-primary">Create Media</a>
        </div>


        <!-- Modal untuk Add Folder -->
        <div class="modal fade" id="addFolderModal" tabindex="-1" aria-labelledby="addFolderModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addFolderModalLabel">Add Folder</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('media.folder.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="folderName">Folder Name</label>
                                <input type="text" class="form-control" name="name" id="folderName" required>
                            </div>
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary">Create Folder</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and View Options -->
        <div class="filter-options mb-3 d-flex align-items-center justify-content-between">
            <div class="filter-buttons d-flex align-items-center">
                <button class="btn btn-filter active" data-filter="all">All items</button>
                <button class="btn btn-filter" data-filter="photo">Photo</button>
                <button class="btn btn-filter" data-filter="video">Video</button>
            </div>
        </div>

        <!-- Media Grid Display -->
        <div class="file-grid mt-4" id="media-container">
            @foreach($mediaItems as $media)
                <div class="file-card position-relative" data-type="{{ $media->type }}">
                    <!-- Dropdown Menu (Three Dots) -->
                    <div class="dropdown action-menu position-absolute">
                        <button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <span class="material-icons">more_vert</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu">
                            <li>
                                <a href="{{ route('media.edit', $media->id) }}" class="dropdown-item">Edit</a>
                            </li>
                            <li>
                                <form action="{{ route('media.destroy', ['media' => $media->id]) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger"
                                        onclick="return confirm('Are you sure you want to delete this media?')">Delete</button>
                                </form>
                            </li>
                        </ul>
                    </div>

                    <!-- Image Container -->
                    <div class="image-container">
                        <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $media->name }}" class="media-preview" />
                    </div>

                    <!-- File Info -->
                    <div class="file-info">
                        <p class="fw-bold text-truncate">{{ $media->name }}</p>
                    </div>
                </div>

                <!-- Folder -->
                <div class="folder-list">
                    @foreach($folders as $folder)
                        <div class="folder-card">
                            <a href="{{ route('media.folder.show', $folder->id) }}" class="folder-link">
                                <div class="folder-icon">
                                    <!-- Ikon folder atau gambar -->
                                </div>
                                <div class="folder-name">{{ $folder->name }}</div>
                            </a>
                        </div>
                    @endforeach
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

@endsection

@section('styles')
<style>
    /* Style utama untuk media card */
    .file-card {
        position: relative;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #fff;
        padding: 0;
        /* Hilangkan padding agar gambar memenuhi container */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    /* Atur ukuran gambar */
    .image-container {
        width: 100%;
        aspect-ratio: 4 / 3;
        /* Rasio aspek gambar (4:3 atau sesuaikan kebutuhan) */
        overflow: hidden;
    }

    .media-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Gambar memenuhi area tanpa distorsi */
        display: block;
    }

    /* Posisi ikon tiga titik di luar gambar */
    .action-menu {
        top: 8px;
        right: 8px;
        z-index: 10;
    }

    /* Dropdown button styling */
    .action-menu button {
        padding: 4px;
        background-color: rgba(255, 255, 255, 0.8);
        border: none;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    /* Informasi file */
    .file-info {
        text-align: center;
        padding: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #333;
    }

    /* Hilangkan teks "Image" */
    .file-info span {
        display: none;
    }

    /* Efek hover pada card */
    .file-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }
</style>
@endsection
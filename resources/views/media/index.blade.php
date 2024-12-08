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

        <!-- Media Grid Display -->
        <div class="file-grid mt-4" id="media-container">
            @foreach($folders as $folder)
                <div class="folder-card">
                    <a href="{{ route('media.folder.show', $folder->id) }}" class="folder-link">
                        <div class="folder-name">{{ $folder->name }}</div>
                    </a>
                </div>

                <!-- Menampilkan Subfolder jika ada -->
                @foreach($folder->subfolders as $subfolder)
                    <div class="folder-card ms-4">
                        <a href="{{ route('media.folder.show', $subfolder->id) }}" class="folder-link">
                            <div class="folder-name">{{ $subfolder->name }}</div>
                        </a>
                    </div>
                @endforeach
            @endforeach

            <!-- Media Items -->
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
@endsection
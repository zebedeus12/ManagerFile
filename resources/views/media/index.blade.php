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
                <div class="folder-card position-relative">
                    <a href="{{ route('media.folder.show', $folder->id) }}" class="folder-link">
                        <div class="folder-icon">
                            <span class="material-icons">folder</span>
                        </div>
                        <div class="folder-name text-center">{{ $folder->name }}</div>
                    </a>

                    <!-- Tombol Titik Tiga -->
                    <div class="dropdown position-absolute top-0 end-0 m-2">
                        <button class="custom-toggle" onclick="toggleMenu(this)">
                            <span class="material-icons">more_vert</span>
                        </button>
                        <div class="dropdown-menu">
                            <button onclick="renameFolder({{ $folder->id }})">Rename</button>
                            <button onclick="shareFolder({{ $folder->id }})">Share</button>
                            <button onclick="deleteFolder({{ $folder->id }})">Delete</button>
                        </div>
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
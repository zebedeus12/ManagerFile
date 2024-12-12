@extends('layouts.media')

@section('title', 'Folder Details')

@section('content')
@include('layouts.navbar')

<div class="main-layout">
    @include('layouts.sidebar')

    <div class="employee-content">
        <div class="header">
            <h2>Folder: {{ $folder->name }}</h2>
            <div>
                <!-- Button to add subfolder -->
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubfolderModal">Add
                    Folder</button>
                <!-- Button to add media -->
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMediaModal">Create
                    Media</button>
            </div>
        </div>

        <!-- Add Subfolder Modal -->
        <div class="modal fade" id="addSubfolderModal" tabindex="-1" aria-labelledby="addSubfolderModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSubfolderModalLabel">Add Folder</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('media.folder.store', ['parentId' => $folder->id]) }}" method="POST">
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

        <!-- Add Media Modal -->
        <div class="modal fade" id="addMediaModal" tabindex="-1" aria-labelledby="addMediaModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addMediaModalLabel">Add Media</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="mediaName">Media Name</label>
                                <input type="text" class="form-control" name="name" id="mediaName" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="mediaFile">Media File</label>
                                <input type="file" class="form-control" name="file" id="mediaFile"
                                    accept="image/*,audio/*,video/*" required>
                            </div>
                            <input type="hidden" name="folder_id" value="{{ $folder->id }}">
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary">Create Media</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subfolder List -->
        <div class="subfolder-list mb-4">
            <h5>Folders</h5>
            <div class="file-grid">
                @foreach($folder->subfolders as $subfolder)
                    <div class="file-card folder-card">
                        <a href="{{ route('media.folder.show', $subfolder->id) }}" class="folder-link">
                            <div class="file-info">
                                <p>{{ $subfolder->name }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Media List -->
        <div class="media-list">
            <h5>Media Files</h5>
            <div class="file-grid">
                @foreach($folder->mediaItems as $media)
                    <div class="file-card">
                        <div class="image-container">
                            @if(Str::startsWith($media->type, 'image/'))
                                <img src="{{ Storage::url($media->path) }}" alt="{{ $media->name }}" class="media-preview">
                            @else
                                <div class="file-icon text-center">
                                    <span class="material-icons" style="font-size: 48px;">insert_drive_file</span>
                                </div>
                            @endif
                        </div>
                        <div class="file-info">
                            <p>{{ $media->name }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
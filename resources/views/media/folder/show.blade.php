@extends('layouts.media')

@section('title', 'Folder Details')

@section('content')
<h2>Folder: {{ $folder->name }}</h2>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4>Manage Folder</h4>
    </div>
    <div>
        <!-- Tombol untuk Add Subfolder -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubfolderModal">Add Folder</button>
        <!-- Tombol untuk Add Media -->
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMediaModal">Create Media</button>
    </div>
</div>

<!-- Modal untuk Add Subfolder -->
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

<!-- Modal untuk Add Media -->
<div class="modal fade" id="addMediaModal" tabindex="-1" aria-labelledby="addMediaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMediaModalLabel">Add Media</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="mediaName">Media Name</label>
                        <input type="text" class="form-control" name="name" id="mediaName" required>
                    </div>
                    <div class="form-group">
                        <label for="mediaFile">Media File</label>
                        <input type="file" class="form-control" name="file" id="mediaFile" required>
                    </div>
                    <input type="hidden" name="folder_id" value="{{ $folder->id }}">
                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-success">Upload Media</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tampilkan subfolder jika ada -->
@if($folder->subfolders->isNotEmpty())
    <div class="subfolder-list mt-4">
        <h4>Subfolders:</h4>
        @foreach($folder->subfolders as $subfolder)
            <div>
                <a href="{{ route('media.folder.show', $subfolder->id) }}">{{ $subfolder->name }}</a>
            </div>
        @endforeach
    </div>
@endif

<!-- Tampilkan media di dalam folder -->
<div class="file-list mt-4">
    @if($folder->mediaItems->isEmpty())
        <p>No media available in this folder.</p>
    @else
        @foreach($folder->mediaItems as $media)
            <div>{{ $media->name }}</div>
        @endforeach
    @endif
</div>
@endsection
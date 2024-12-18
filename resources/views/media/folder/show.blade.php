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

        <!-- Subfolder List -->
        <div class="file-grid mt-4" id="media-container">
            @foreach($folder->subfolders as $subfolder)
                <div class="folder-card position-relative">
                    <a href="{{ route('media.folder.show', $subfolder->id) }}" class="folder-link">
                        <div class="folder-icon">
                            <span class="material-icons">folder</span>
                        </div>
                        <div class="folder-name text-center">{{ $subfolder->name }}</div>
                    </a>

                    <!-- Tombol Titik Tiga -->
                    <div class="dropdown position-absolute top-0 end-0 m-2">
                        <button class="custom-toggle" onclick="toggleMenu(this)">
                            <span class="material-icons">more_vert</span>
                        </button>
                        <div class="dropdown-menu">
                            <button onclick="renameFolder({{ $subfolder->id }})">Rename</button>
                            <button onclick="shareFolder({{ $subfolder->id }})">Share</button>
                            <button onclick="deleteFolder({{ $subfolder->id }})">Delete</button>
                            <button onclick="copyFolder({{ $subfolder->id }})">Copy</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Rename Modal -->
        <div class="modal fade" id="renameFolderModal" tabindex="-1" aria-labelledby="renameFolderModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Rename Folder</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="renameFolderForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="folderNewName">Nama Baru</label>
                                <input type="text" id="folderNewName" name="name" class="form-control" required>
                            </div>
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Share Modal -->
        <div class="modal fade" id="shareFolderModal" tabindex="-1" aria-labelledby="shareFolderModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Share Folder</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="shareFolderLink" class="form-control" readonly>
                        <div class="text-center mt-3">
                            <button onclick="copyToClipboard()" class="btn btn-primary">Copy Link</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal untuk Konfirmasi Delete Folder -->
        <div class="modal fade" id="deleteFolderModal" tabindex="-1" aria-labelledby="deleteFolderModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteFolderModalLabel">Delete?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Anda yakin ingin menghapus folder tersebut?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form id="deleteFolderForm" action="" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
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

        <div class="media-list">
            <h5>Media Files</h5>
            <div class="file-grid">
                @foreach($folder->mediaItems as $media)
                    <div class="file-card position-relative">
                        <div class="media-container"
                            onclick="handleMediaClick('{{ $media->id }}', '{{ Storage::url($media->path) }}', '{{ $media->type }}')">
                            @if(Str::startsWith($media->type, 'image/'))
                                <!-- Tampilkan Gambar -->
                                <img src="{{ Storage::url($media->path) }}" alt="{{ $media->name }}" class="media-preview"
                                    id="media-{{ $media->id }}">
                            @elseif(Str::startsWith($media->type, 'audio/'))
                                <!-- Tampilkan Ikon Musik -->
                                <div class="audio-icon text-center">
                                    <span class="material-icons" style="font-size: 48px;">music_note</span>
                                </div>
                                <audio id="audio-{{ $media->id }}" preload="none">
                                    <source src="{{ Storage::url($media->path) }}" type="{{ $media->type }}">
                                </audio>
                            @elseif(Str::startsWith($media->type, 'video/'))
                                <!-- Tampilkan Video -->
                                <video class="media-preview" id="video-{{ $media->id }}" preload="none">
                                    <source src="{{ Storage::url($media->path) }}" type="{{ $media->type }}">
                                    Your browser does not support the video tag.
                                </video>
                            @endif
                        </div>
                        <div class="file-info text-center">
                            <p>{{ $media->name }}</p>
                        </div>

                        <div class="dropdown position-absolute top-0 end-0 m-2">
                            <button class="custom-toggle" onclick="toggleMenu(this)">
                                <span class="material-icons">more_vert</span>
                            </button>
                            <div class="dropdown-menu">
                                <a href="{{ route('media.edit', $media->id) }}" class="dropdown-item">Edit</a>
                                <button onclick="deleteMedia({{ $media->id }})"
                                    class="dropdown-item text-danger">Delete</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<script>
    function toggleMenu(button) {
        // Get the dropdown menu associated with the button
        const menu = button.nextElementSibling;

        // Close all other dropdowns
        document.querySelectorAll('.dropdown-menu').forEach(otherMenu => {
            if (otherMenu !== menu) {
                otherMenu.classList.remove('show');
            }
        });

        // Toggle the current dropdown menu
        menu.classList.toggle('show');
    }

    document.addEventListener('click', function (event) {
        // Close all dropdowns if the click is outside of any dropdown
        if (!event.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });
</script>
@endsection
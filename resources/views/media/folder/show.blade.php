@extends('layouts.media')

@section('title', 'Folder Details')

@section('content')
    @include('layouts.navbar')

    <div class="main-layout">
        @include('layouts.sidebar')

        <div class="employee-content p-4">
            <div class="header d-flex align-items-center justify-content-between mb-4">
                <h2>Folder: {{ $folder->name }}</h2>
                <div class="d-flex align-items-center gap-2 justify-content-end">
                    <form method="GET" action="{{ route('media.folder.show', $folder->id) }}" class="d-flex mb-3">
                        <div class="search-container">
                            <input type="text" name="search" value="{{ request('search') }}" class="search-input"
                                placeholder="Search...">
                        </div>
                        <button type="submit" class="search-btn">
                            <i class="material-icons">search</i>
                        </button>
                    </form>
                    @if(auth()->user()->role === 'super_admin')
                        <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#addSubfolderModal">
                            <i class="material-icons">create_new_folder</i>
                        </button>
                        <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#addMediaModal">
                            <i class="material-icons">add_photo_alternate</i>
                        </button>
                    @endif
                    <button class="btn btn-custom" onclick="toggleView()">
                        <i class="material-icons">grid_view</i>
                    </button>
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
                                    <label for="folderAccessibility">Accessibility</label>
                                    <select class="form-control" name="accessibility" id="folderAccessibility" required>
                                        <option value="public">Public</option>
                                        <option value="private">Private</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="hak-akses" class="form-label">Hak Akses</label>
                                    <select class="form-select" id="hak-akses" name="hak-akses" required>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id_user }}">{{ $employee->nama_user }} -
                                                {{ ucfirst($employee->role) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="folderDescription">Keterangan</label>
                                    <textarea class="form-control" name="description" id="folderDescription"
                                        rows="3"></textarea>
                                </div>
                                <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-primary">Create Folder</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Media Modal -->
            <div class="modal fade" id="editMediaModal" tabindex="-1" aria-labelledby="editMediaModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editMediaModalLabel">Edit Media</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editMediaForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="mediaName">Media Name</label>
                                    <input type="text" class="form-control" id="mediaName" name="name" required>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="mediaType">Media Type</label>
                                    <input type="text" class="form-control" id="mediaType" name="type" required>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="mediaFile">Upload New Media File (Optional)</label>
                                    <input type="file" class="form-control" name="file" id="mediaFile" accept="image/*,audio/*,video/*">
                                </div>
                                <input type="hidden" name="folder_id" id="editFolderId">
                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Media List -->
            <div class="media-list">
                <h5>Media Files</h5>
                <div id="gridViewFiles" class="file-grid">
                    @if($mediaItems->isEmpty())
                        <p>No media found.</p>
                    @else
                        @foreach($folder->mediaItems as $media)
                            <div class="file-card position-relative">
                                <div class="file-info text-center">
                                    @if(Str::startsWith($media->type, 'image/'))
                                        <span class="material-icons media-icon">image</span>
                                    @elseif(Str::startsWith($media->type, 'audio/'))
                                        <span class="material-icons media-icon">music_note</span>
                                    @elseif(Str::startsWith($media->type, 'video/'))
                                        <span class="material-icons media-icon">videocam</span>
                                    @endif
                                    <p>{{ $media->name }}</p>
                                </div>
                                <div class="media-container"
                                    onclick="handleMediaClick('{{ $media->id }}', '{{ Storage::url($media->path) }}', '{{ $media->type }}')">
                                    @if(Str::startsWith($media->type, 'image/'))
                                        <img src="{{ Storage::url($media->path) }}" alt="{{ $media->name }}" class="media-preview"
                                            id="media-{{ $media->id }}">
                                    @elseif(Str::startsWith($media->type, 'audio/'))
                                        <div class="audio-container">
                                            <span class="material-icons audio-icon">play_arrow</span>
                                            <audio id="audio-{{ $media->id }}" preload="none" class="audio-player">
                                                <source src="{{ Storage::url($media->path) }}" type="{{ $media->type }}">
                                            </audio>
                                        </div>
                                    @elseif(Str::startsWith($media->type, 'video/'))
                                        <video class="media-preview video-player" id="video-{{ $media->id }}" preload="none" controls>
                                            <source src="{{ Storage::url($media->path) }}" type="{{ $media->type }}">
                                            Your browser does not support the video tag.
                                        </video>
                                    @endif
                                </div>

                                @if(auth()->user()->role === 'super_admin')
                                    <div class="dropdown position-absolute top-0 end-0 m-2">
                                        <button class="custom-toggle" onclick="toggleMenu(this)">
                                            <span class="material-icons">more_vert</span>
                                        </button>
                                        <div class="dropdown-menu">
                                            <button onclick="openEditMediaModal({{ $media->id }}, '{{ $media->name }}', '{{ $media->type }}', '{{ $media->folder_id }}')" class="dropdown-item">Edit</button>
                                            <button onclick="deleteMedia({{ $media->id }})"
                                                class="dropdown-item text-danger">Delete</button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditMediaModal(id, name, type, folderId) {
            document.getElementById('mediaName').value = name;
            document.getElementById('mediaType').value = type;
            document.getElementById('editFolderId').value = folderId;
            document.getElementById('editMediaForm').action = `/media/${id}`;
            new bootstrap.Modal(document.getElementById('editMediaModal')).show();
        }

        function toggleMenu(button) {
            const menu = button.nextElementSibling;
            document.querySelectorAll('.dropdown-menu').forEach(otherMenu => {
                if (otherMenu !== menu) {
                    otherMenu.classList.remove('show');
                }
            });
            menu.classList.toggle('show');
        }

        document.addEventListener('click', function (event) {
            if (!event.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });

        function toggleView() {
            const gridViewFolders = document.getElementById('gridViewFolders');
            const listViewFolders = document.getElementById('listViewFolders');
            const gridViewFiles = document.getElementById('gridViewFiles');
            const listViewFiles = document.getElementById('listViewFiles');

            if (gridViewFolders.style.display === 'none') {
                gridViewFolders.style.display = 'flex';
                listViewFolders.style.display = 'none';
                gridViewFiles.style.display = 'flex';
                listViewFiles.style.display = 'none';
            } else {
                gridViewFolders.style.display = 'none';
                listViewFolders.style.display = 'block';
                gridViewFiles.style.display = 'none';
                listViewFiles.style.display = 'block';
            }
        }
    </script>
@endsection
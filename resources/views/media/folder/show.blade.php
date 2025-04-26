@extends('layouts.media')

@section('title', 'Folder Details')

@section('content')
    @include('layouts.navbar')

    <div class="main-layout">
        @include('layouts.sidebar')

        <div class="employee-content p-4">
            <div class="header d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2>Folder: {{ $folder->name }}</h2>
                    <div class="breadcrumb mt-2">
                        <a href="{{ route('media.index') }}">Media Manager</a>
                    
                        @foreach ($breadcrumbs as $breadcrumb)
                            <span> / </span>
                            <a href="{{ route('media.folder.show', $breadcrumb->id) }}">{{ $breadcrumb->name }}</a>
                        @endforeach
                    
                        <span> / {{ $folder->name }}</span>
                    </div>
                </div>
                
                
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
                    @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                        @if(auth()->user()->id_user == $folder->owner_id || auth()->user()->role == 'super_admin')
                        <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#addSubfolderModal">
                            <i class="material-icons">create_new_folder</i>
                        </button>
                        <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#addMediaModal">
                            <i class="material-icons">add_photo_alternate</i>
                        </button>
                        @endif
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
                                    <select class="form-select" name="accessibility" id="folderAccessibility" required>
                                        <option value="public">Public</option>
                                        <option value="private">Private</option>
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

            @include('layouts.index')

            <!-- Subfolder List -->
            <div id="gridViewFolders" class="folder-grid mt-4">
                @if($subfolders->isEmpty())
                    <p>No subfolders found.</p>
                @else
                    @foreach($folder->subfolders as $subfolder)
                    
                        <div class="folder-card">
                            <form id="downloadForm-{{ $subfolder->id }}" action="{{ route('mediaFolder.download', $subfolder->id) }}" method="POST">
                                @csrf
                            </form>
                            @if($subfolder->accessibility === 'private')
                                <span title="Private" class="ms-1 align-middle text-muted">
                                    <i class="material-icons" style="font-size: 18px;">lock</i> 
                                </span>
                            @endif
                            <a href="{{ route('media.folder.show', $subfolder->id) }}" class="folder-link">
                                <div class="folder-header">
                                    <div class="folder-icon">
                                        <span class="material-icons">folder</span>
                                    </div>
                                    <span class="folder-name">{{ $subfolder->name }}</span>
                                </div>
                                <p class="folder-meta">
                                    Anda membuatnya · {{ $subfolder->created_at->format('d M Y') }}<br>
                                    <span class="folder-description">{{ $subfolder->description ?? 'Tidak ada keterangan' }}</span>
                                </p>
                            </a>
                            <!-- Tombol Titik Tiga -->
                            <div class="dropdown position-absolute top-0 end-0 m-2">
                                <button class="custom-toggle" onclick="toggleMenu(this)">
                                    <span class="material-icons">more_vert</span>
                                </button>
                                <div class="dropdown-menu">
                                    <button onclick="shareFolder({{ $subfolder->id }})">Share</button>
                                    <button onclick="submitDownloadForm(event, {{ $subfolder->id }})">Download</button>
                                    @if(auth()->user()->id_user == $subfolder->owner_id OR auth()->user()->role == 'super_admin')
                                        <button onclick="renameFolder({{ $subfolder->id }})">Rename</button>    
                                        <form action="{{ route('media.toggle-accessibility', $subfolder->id) }}" method="POST" onsubmit="return confirm('Ubah akses folder ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit">
                                                {{ $subfolder->accessibility === 'private' ? 'Ubah ke Public' : 'Ubah ke Private' }}
                                            </button>
                                        </form>
                                        <button onclick="deleteFolder({{ $subfolder->id }})">Delete</button>
                                        <!--<button onclick="copyFolder({{ $subfolder->id }})">Copy</button>-->
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            
            <!-- List View for Folders -->
            <div id="listViewFolders" class="folder-list mt-4" style="display: none;">
                @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                <button id="bulkDeleteBtn" class="btn btn-danger" onclick="bulkDelete()" >
                    <i class="fas fa-trash-alt"></i> &nbsp; Hapus yang Dipilih
                </button>
                @endif

                <table class="table table-striped">
                    <thead>
                        <tr>
                            @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                            <th><input class="form-check-input" type="checkbox" id="selectAll"></th>
                            @endif
                            <th>Name</th>
                            <th>Created At</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($folder->subfolders as $subfolder) 
                            <tr>
                                @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                                    @if(auth()->user()->id_user == $subfolder->owner_id OR auth()->user()->role == 'super_admin')
                                    <td><input type="checkbox" class="folder-checkbox form-check-input" value="{{ $subfolder->id }}"></td>
                                    @else
                                    <td></td>
                                    @endif
                                @endif
                                <td>{{ $subfolder->name }}</td>
                                <td>{{ $subfolder->created_at->format('d M Y') }}</td>
                                <td>{{ $subfolder->keterangan ?? 'Tidak ada keterangan' }}</td>
                                <td>
                                    @if(auth()->user()->id_user == $subfolder->owner_id OR auth()->user()->role == 'super_admin')
                                        <button class="button" onclick="renameFolder({{ $subfolder->id }})"><span
                                                class="material-icons">edit</span></button>
                                    @endif
                                    <button class="button" onclick="shareFolder({{ $subfolder->id }})"><span
                                            class="material-icons">share</span></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <form id="bulkDeleteForm" action="{{ route('media.folder.bulkDelete') }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="ids" id="bulkDeleteIds">
                </form>
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

            <div class="modal fade" id="warningModal" tabindex="-1" aria-labelledby="warningModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="warningModalLabel">Peringatan!</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p id="warningMessage"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
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
                                <div class="form-group mb-3">
                                    <label for="mediaDescription">Keterangan</label>
                                    <textarea class="form-control" name="description" id="mediaDescription"
                                        rows="3"></textarea>
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
                                    <input type="file" class="form-control" name="file" id="mediaFile"
                                        accept="image/*,audio/*,video/*">
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
                                <form id="downloadFormFile-{{ $media->id }}" action="{{ route('media.download', $media->id) }}" method="POST">
                                    @csrf
                                </form>
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
                                            Your browser does not support the vi deo tag.
                                        </video>
                                    @endif
                                </div>

                                <div class="dropdown position-absolute top-0 end-0 m-2">
                                    <button class="custom-toggle" onclick="toggleMenu(this)">
                                        <span class="material-icons">more_vert</span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <button onclick="submitDownloadForm(event, {{ $media->id }}, 'file')">Download</button>
                                        @if(auth()->user()->id_user == $subfolder->owner_id OR auth()->user()->role == 'super_admin')
                                            <button
                                                onclick="openEditMediaModal({{ $media->id }}, '{{ $media->name }}', '{{ $media->type }}', '{{ $media->folder_id }}')"
                                                class="dropdown-item">Edit</button>
                                            <button onclick="deleteMedia({{ $media->id }})"
                                                class="dropdown-item text-danger">Delete</button>
                                        @endif
                                        
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- List View for Files -->
            <div id="listViewFiles" class="file-list mt-4" style="display: none;">
                @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                @if(auth()->user()->id_user == $folder->owner_id || auth()->user()->role == 'super_admin')
                <button id="bulkDeleteBtnFile" class="btn btn-danger" onclick="bulkDeleteFile()">
                    <i class="fas fa-trash-alt"></i> &nbsp; Hapus yang Dipilih
                </button>
                @endif
                @endif
                <table class="table table-striped">
                    <thead>
                        <tr>
                            @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                            @if(auth()->user()->id_user == $folder->owner_id || auth()->user()->role == 'super_admin')
                            <th><input class="form-check-input" type="checkbox" id="selectAllFile"></th>
                            @endif
                            @endif
                            <th>Name</th>
                            <th>Created At</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($folder->mediaItems as $media) 
                            <tr>
                                @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                                    @if(auth()->user()->id_user == $folder->owner_id || auth()->user()->role == 'super_admin')
                                    <td><input type="checkbox" class="file-checkbox form-check-input" value="{{ $media->id }}"></td>
                                    @endif
                                @endif
                                <td>{{ $media->name }}</td>
                                <td>{{ $media->created_at->format('d M Y') }}</td>
                                <td>{{ $media->type }}</td>
                                @if(auth()->user()->id_user == $folder->owner_id || auth()->user()->role == 'super_admin')
                                    <td>
                                        <button class="button"
                                            onclick="openEditMediaModal({{ $media->id }}, '{{ $media->name }}', '{{ $media->type }}', '{{ $media->folder_id }}')"
                                            class="dropdown-item"><span class="material-icons">edit</span></button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <form id="bulkDeleteFormFile" action="{{ route('media.bulkDelete') }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="ids" id="bulkDeleteFileIds">
                </form>
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

<script>
    function submitDownloadForm(event, elementId, type) {
        event.preventDefault();

        let formId = (type == 'file') ? 'downloadFormFile-' + elementId : 'downloadForm-' + elementId;
        let form = document.getElementById(formId);

        if (form) {
            form.submit();
        } else {
            console.error('Form with ID ' + formId + ' not found.');
        }
    }
</script>

<script>
    document.getElementById('selectAll').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('.folder-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    function bulkDelete() {
        const selected = Array.from(document.querySelectorAll('.folder-checkbox:checked'))
                            .map(cb => cb.value);

        if (selected.length === 0) {
            alert('Pilih minimal satu folder untuk dihapus.');
            return;
        }

        if (confirm('Apakah Anda yakin ingin menghapus folder yang dipilih?')) {
            const form = document.getElementById('bulkDeleteForm');
            document.getElementById('bulkDeleteIds').value = JSON.stringify(selected);
            form.submit(); // ini akan trigger controller Laravel seperti biasa
        }
    }
</script>

<script>
    document.getElementById('selectAllFile').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('.file-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    function bulkDeleteFile() {
        const selected = Array.from(document.querySelectorAll('.file-checkbox:checked'))
                            .map(cb => cb.value);

        if (selected.length === 0) {
            alert('Pilih minimal satu media untuk dihapus.');
            return;
        }

        if (confirm('Apakah Anda yakin ingin menghapus media yang dipilih?')) {
            const form = document.getElementById('bulkDeleteFormFile');
            document.getElementById('bulkDeleteFileIds').value = JSON.stringify(selected);
            form.submit(); 
        }
    }
</script>
@endsection
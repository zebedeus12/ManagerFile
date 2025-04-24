@extends('layouts.manager')

@section('title', 'Files')

@section('content')
<!-- Navbar -->
@include('layouts.navbar')

<div class="main-layout">
    @include('layouts.sidebar')
    <div class="container content-container">
        <div class="header d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="mb-0">{{ $folder->name }}</h3>
                <!-- Breadcrumb untuk jalur folder -->
                <div class="breadcrumb mt-2">
                    <a href="{{ route('file.index') }}">File Manager</a>
                    @php
                        $current = $folder;
                        while ($current->parent) {
                            echo ' / <a href="' . route('folder.show', $current->parent->id) . '">' . $current->parent->name . '</a>';
                            $current = $current->parent;
                        }
                    @endphp
                    <span> / {{ $folder->name }}</span>
                </div>
            </div>
            <div class="buttons">
                <form action="{{ route('folder.show', $folder->id) }}" method="GET" class="d-flex me-3 align-items-center">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control rounded-pill"
                            placeholder="Search subfolders and files..." value="{{ request('search') }}"
                            style="background-color: #d8f5d5; color: #6b8e62; border: none;">
                        <button type="submit" class="btn btn-success rounded-circle" style="background-color: #b3e6b1; border: none;">
                            <span class="material-icons">search</span>
                        </button>
                    </div>
                </form>
                @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                    <button class="btn rounded-circle ms-2" data-bs-toggle="modal" data-bs-target="#addSubfolderModal"
                        style="background-color: #b3e6b1; border: none;">
                        <span class="material-icons">create_new_folder</span>
                    </button>
                    <button class="btn rounded-circle ms-2" data-bs-toggle="modal" data-bs-target="#uploadFileModal"
                        style="background-color: #b3e6b1; border: none;">
                        <span class="material-icons">upload_file</span>
                    </button>
                @endif
                <button class="btn rounded-circle ms-2" onclick="toggleView()" style="background-color: #b3e6b1; border: none;">
                    <span class="material-icons">grid_view</span>
                </button>
            </div>
        </div>

        <!-- Tampilkan notifikasi jika ada -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <h6>Folders</h6>
        <div id="gridViewFolders" class="folder-grid mt-4">
            @if($subFolders->isEmpty())
                <p>No subfolders found.</p>
            @else
                @foreach ($subFolders as $subFolder)
                    <div class="folder-card">
                        <a href="{{ route('folder.show', $subFolder->id) }}" class="folder-link">
                            <div class="folder-header">
                                <div class="folder-icon">
                                    <span class="material-icons">folder</span>
                                </div>
                                <div class="folder-name">{{ $subFolder->name }}</div>
                                <div class="dropdown">
                                    <button class="custom-toggle" onclick="toggleMenu(this, event)">
                                        <span class="material-icons">more_vert</span>
                                    </button>
                                    <div class="dropdown-menu">
                                        @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                                            <button onclick="openRenameModal({{ $subFolder->id }}, '{{ $subFolder->name }}')">Rename</button>
                                        @endif
                                        <button onclick="openShareModal({{ $subFolder->id }}, '{{ url('/folder/' . $subFolder->id . '/share') }}')">Share</button>
                                        @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                                            <button onclick="openDeleteModal({{ $subFolder->id }})">Delete</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <p class="folder-meta">
                                Anda membuatnya · {{ $subFolder->created_at->format('d M Y') }}<br>
                                <span class="folder-description">{{ $subFolder->keterangan ?? 'Tidak ada keterangan' }}</span>
                            </p>
                        </a>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- List View for Folders -->
        <div id="listViewFolders" class="folder-list mt-4" style="display: none;">
            @if($subFolders->isEmpty())
                <p>No subfolders found.</p>
            @else
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Created At</th>
                            <th>Description</th>
                            @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                                <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subFolders as $subFolder)
                            <tr>
                                <td>{{ $subFolder->name }}</td>
                                <td>{{ $subFolder->created_at->format('d M Y') }}</td>
                                <td>{{ $subFolder->keterangan ?? 'Tidak ada keterangan' }}</td>
                                @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                                    <td>
                                        <button class="button" onclick="openRenameModal({{ $subFolder->id }}, '{{ $subFolder->name }}')" title="Rename">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="button" onclick="openShareModal({{ $subFolder->id }}, '{{ url('/folder/' . $subFolder->id . '/share') }}')" title="Share">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Modal add folder-->
        <div class="modal fade" id="addSubfolderModal" tabindex="-1" aria-labelledby="addSubfolderModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSubfolderModalLabel">Create Subfolder</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('folder.store', ['parentId' => $folder->id]) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="folder_name" class="form-label">Folder Name</label>
                                <input type="text" class="form-control" id="folder_name" name="folder_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="accessibility" class="form-label">Accessibility</label>
                                <select class="form-select" id="accessibility" name="accessibility" required>
                                    <option value="public">Public</option>
                                    <option value="private">Private</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Create Folder</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rename Folder -->
        <div class="modal" id="renameFolderModal" tabindex="-1" aria-labelledby="renameFolderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="renameFolderModalLabel">Rename Folder</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="renameFolderForm" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="newFolderName" class="form-label">New Folder Name</label>
                                <input type="text" id="newFolderName" name="name" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Delete Folder -->
        <div class="modal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Delete Folder</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this folder?</p>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Share Folder -->
        <div class="modal" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="shareModalLabel">Share Folder Link</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="shareUrlInput" class="form-control" readonly>
                        <button id="copyLinkButton" class="btn btn-primary mt-2">Copy Link</button>
                    </div>
                </div>
            </div>
        </div>   

        <!-- Bagian untuk File -->
        <h6>Files</h6>
        <div id="gridViewFiles" class="file-grid">
            @if($files->isEmpty())
                <p>No files found.</p>
            @else
                @foreach ($files as $file)
                    <div class="file-card">
                        <div class="file-header">
                            <span class="file-icon">
                                @switch($file->type)
                                    @case('pdf')
                                        <i class="fas fa-file-pdf" style="color: #E74C3C;"></i>
                                        @break
                                    @case('xls')
                                    @case('xlsx')
                                        <i class="fas fa-file-excel" style="color: #28A745;"></i>
                                        @break
                                    @case('doc')
                                    @case('docx')
                                        <i class="fas fa-file-word" style="color: #3498DB;"></i>
                                        @break
                                    @case('ppt')
                                    @case('pptx')
                                        <i class="fas fa-file-powerpoint" style="color: #FF5733;"></i>
                                        @break
                                    @default
                                        <i class="fas fa-file" style="color: #BDC3C7;"></i>
                                @endswitch
                            </span>
                            <span class="file-name">{{ $file->name }}</span>
                            <div class="dropdown">
                                <button class="custom-toggle" onclick="toggleMenu(this, event)">
                                    <span class="material-icons">more_vert</span>
                                </button>
                                <div class="dropdown-menu">
                                    @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                                        <button onclick="openRenameFileModal({{ $file->id }}, '{{ $file->name }}')">Rename</button>
                                    @endif
                                    <button onclick="openShareFileModal('{{ route('file.share', $file->id) }}')">Share</button>
                                    @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                                        <button class="delete-file-btn" data-file-id="{{ $file->id }}" onclick="openDeleteFileModal({{ $file->id }})">Delete</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="file-footer">
                            <span class="file-info">
                                Anda membuatnya • {{ $file->created_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- List View -->
        <div id="listView" class="folder-list mt-4" style="display: none;">
            @if($files->isEmpty())
                <p>No files found.</p>
            @else 
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Created At</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($files as $file)
                            <tr>
                                <td>{{ $file->name }}</td>
                                <td>{{ $file->created_at->format('d M Y') }}</td>
                                <td>{{ $file->keterangan ?? 'No description' }}</td>
                                <td>
                                    @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#renameFileModal" onclick="document.getElementById('newFileName').value='{{ $file->name }}'; document.getElementById('renameFileForm').action='/file/rename/{{ $file->id }}';"><i class="fas fa-edit"></i></button>
                                    @endif
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#shareFileModal" onclick="document.getElementById('shareFileUrlInput').value='{{ route('file.share', $file->id) }}';"><i class="fas fa-share-alt"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Modal for Upload File -->
        <div class="modal fade" id="uploadFileModal" tabindex="-1" aria-labelledby="uploadFileModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadFileModalLabel">Upload File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('files.store', $folder->id ?? null) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if ($folder)
                                <input type="hidden" name="folder_id" value="{{ $folder->id }}">
                                <p class="text-muted">Mengunggah file ke folder: <strong>{{ $folder->name }}</strong></p>
                            @else
                                <p class="text-muted">Mengunggah file ke root directory.</p>
                            @endif

                            <div class="mb-3">
                                <label for="file" class="form-label">Pilih File</label>
                                <input type="file" class="form-control" id="file" name="file[]" multiple required
                                    accept=".pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx">
                            </div>

                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

       <!-- Modal Delete File -->
<div class="modal" id="deleteFileModal" tabindex="-1" aria-labelledby="deleteFileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteFileModalLabel">Delete File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this file?</p>
                <form id="deleteFileForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

        <!-- Modal Rename File -->
        <div class="modal" id="renameFileModal" tabindex="-1" aria-labelledby="renameFileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="renameFileModalLabel">Rename File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="renameFileForm" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="newFileName">Nama File Baru</label>
                                <input type="text" id="newFileName" name="name" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Share File -->
        <div class="modal" id="shareFileModal" tabindex="-1" aria-labelledby="shareFileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="shareFileModalLabel">Share File Link</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="shareFileUrlInput" class="form-control" readonly>
                        <button id="copyFileLinkButton" class="btn btn-primary mt-2">Copy Link</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to toggle between grid view and list view
    function toggleView() {
        const gridViewFolders = document.getElementById('gridViewFolders');
        const listViewFolders = document.getElementById('listViewFolders');
        const gridViewFiles = document.getElementById('gridViewFiles');
        const listViewFiles = document.getElementById('listView');

        // Check if grid view is currently active
        if (gridViewFiles.style.display === 'none') {
            gridViewFolders.style.display = 'grid';
            gridViewFolders.style.gridTemplateColumns = 'repeat(auto-fill, minmax(200px, 1fr))';
            gridViewFolders.style.gap = '20px';
            listViewFolders.style.display = 'none';
            gridViewFiles.style.display = 'grid';
            gridViewFiles.style.gridTemplateColumns = 'repeat(auto-fill, minmax(220px, 1fr));';
            gridViewFiles.style.gap = '20px';
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

@extends('layouts.manager')

@section('title', 'File Manager')

@section('content')
<!-- Navbar -->
@include('layouts.navbar')

<div class="main-layout">
    @include('layouts.sidebar')
    <div class="container content-container">
        <div class="header d-flex align-items-center justify-content-between mb-4">
            <h3 class="mb-0">File Manager</h3>
            <div class="d-flex align-items-center ms-auto">
                <form action="{{ route('file.index') }}" method="GET" class="d-flex me-3 align-items-center">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control rounded-pill"
                            placeholder="Search folders..." value="{{ request('search') }}"
                            style="background-color: #d8f5d5; color: #6b8e62; border: none;">
                        <button type="submit" class="btn btn-success rounded-circle"
                            style="background-color: #b3e6b1; border: none;">
                            <span class="material-icons">search</span>
                        </button>
                    </div>
                </form>
                <button class="btn rounded-circle ms-2" onclick="location.href='{{ route('folder.create') }}'"
                    style="background-color: #b3e6b1; border: none;">
                    <span class="material-icons">create_new_folder</span>
                </button>
                <button class="btn rounded-circle ms-2" onclick="toggleView()"
                    style="background-color: #b3e6b1; border: none;">
                    <span class="material-icons">grid_view</span>
                </button>
            </div>
        </div>
        <p class="text-muted">Terdapat {{ $folders->count() }} Folders.</p>

        <!-- Grid View -->
        <div id="gridView" class="folder-grid mt-6">
            @if($folders->isEmpty())
                <p>No folders found.</p>
            @else
                @foreach ($folders as $folder)
                    <div class="folder-card">
                        <a href="{{ route('folder.show', $folder->id) }}" class="folder-link">
                            <div class="folder-header">
                                <div class="folder-icon">
                                    <span class="material-icons">folder</span>
                                </div>
                                <div class="folder-name">{{ $folder->name }}</div>
                            </div>
                            <div class="dropdown">
                                <button class="custom-toggle" onclick="toggleMenu(this, event)">
                                    <span class="material-icons">more_vert</span>
                                </button>
                                <div class="dropdown-menu">
                                    <button onclick="openRenameModal({{ $folder->id }}, '{{ $folder->name }}')">Rename</button>
                                    <button
                                        onclick="openShareModal({{ $folder->id }}, '{{ url('/folder/' . $folder->id . '/share') }}')">Share</button>
                                    <button onclick="openDeleteModal({{ $folder->id }})">Delete</button>
                                </div>
                            </div>
                            <p class="folder-meta">
                                Anda membuatnya · {{ $folder->created_at->format('d M Y') }}<br>
                                <span class="folder-description">{{ $folder->keterangan ?? 'Tidak ada keterangan' }}</span>
                            </p>
                        </a>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- List View -->
        <div id="listView" class="folder-list mt-4" style="display: none;">
            @if($folders->isEmpty())
                <p>No folders found.</p>
            @else
                <form id="deleteMultipleForm" action="{{ route('folders.deleteMultiple') }}" method="POST">
                    @csrf
                    <table class="table table-striped">
                        <button type="button" class="button" onclick="confirmDeleteMultiple()">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="button"
                            onclick="openShareModal({{ $folder->id }}, '{{ url('/folder/' . $folder->id . '/share') }}')"
                            title="Share">
                            <i class="fas fa-share-alt"></i>
                        </button>
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll" onclick="toggleSelectAll()"> Select All</th>
                                <th>Name</th>
                                <th>Created At</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($folders as $folder)
                                <tr>
                                    <td><input type="checkbox" name="folders[]" value="{{ $folder->id }}"></td>
                                    <td>{{ $folder->name }}</td>
                                    <td>{{ $folder->created_at->format('d M Y') }}</td>
                                    <td>{{ $folder->keterangan ?? 'Tidak ada keterangan' }}</td>
                                    <td>
                                        <button class="button"
                                            onclick="openRenameModal({{ $folder->id }}, '{{ $folder->name }}')" title="Rename">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
            @endif
        </div>

        <!-- Rename Folder-->
        <div id="renameFolderModal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close" onclick="closeRenameModal()">&times;</span>
                <h2>Rename Folder</h2>
                <form id="renameFolderForm" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="newFolderName">Nama Folder Baru</label>
                        <input type="text" id="newFolderName" name="name" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>

        <!-- Delete -->
        <div id="deleteModal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close" onclick="closeDeleteModal()">&times;</span>
                <h2>Delete?</h2>
                <p>Anda yakin ingin menghapus folder tersebut?</p>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>

        <div id="warningModal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close" onclick="closeWarningModal()">&times;</span>
                <h2>Peringatan!!</h2>
                <p id="warningMessage"></p>
                <button class="btn btn-primary" onclick="closeWarningModal()">OK</button>
            </div>
        </div>

        <!-- Share -->
        <div id="shareModal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close" onclick="closeShareModal()">&times;</span>
                <h2>Share Folder Link</h2>
                <input type="text" id="shareUrlInput" class="form-control" readonly>
                <button id="copyLinkButton" class="btn btn-primary mt-2">Copy Link</button>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleView() {
        const gridView = document.getElementById('gridView');
        const listView = document.getElementById('listView');
        if (gridView.style.display === 'none') {
            gridView.style.display = 'grid'; // Pastikan menggunakan grid layout
            gridView.style.gridTemplateColumns = 'repeat(auto-fill, minmax(200px, 1fr))';
            gridView.style.gap = '20px';
            listView.style.display = 'none';
        } else {
            gridView.style.display = 'none';
            listView.style.display = 'block';
        }
    }

    // Toggle select all checkboxes
    function toggleSelectAll() {
        const selectAllCheckbox = document.getElementById("selectAll");
        const checkboxes = document.querySelectorAll("input[name='folders[]']");
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
    }

    // Confirm delete multiple folders
    function confirmDeleteMultiple() {
        const form = document.getElementById("deleteMultipleForm");
        const selectedFolders = document.querySelectorAll("input[name='folders[]']:checked");

        if (selectedFolders.length > 0) {
            if (confirm('Are you sure you want to delete the selected folders?')) {
                form.submit();
            }
        } else {
            alert("Please select at least one folder to delete.");
        }
    }

</script>
@endsection
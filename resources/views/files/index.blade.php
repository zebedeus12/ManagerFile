@extends('layouts.manager')

@section('title', 'File Manager')

@section('content')
<!-- Navbar -->
@include('layouts.navbar')

<div class="main-layout">
    @include('layouts.sidebar')
    <div class="container content-container">
        <div class="header d-flex align-items-center justify-content-between mb-4">
            <h1 class="mb-0">File Manager</h1>
            <button class="add-folder ms-auto" onclick="location.href='{{ route('folder.create') }}'">Add
                Folder</button>
        </div>
        <p class="text-muted">Terdapat {{ $folders->count() }} Folders.</p>

        <div class="folder-grid mt-4">
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
@endsection
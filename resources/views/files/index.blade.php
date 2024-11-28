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
        <p class="text-muted">Terdapat {{ $folders->count() }} Folders.
        </p>
        <div class="file-grid">
            {{-- Grid Folders --}}
            @foreach ($folders as $folder)
                <div class="file-card">
                    <!-- Dropdown Tombol -->
                    <div class="dropdown">
                        <button class="dropdown-toggle custom-toggle" onclick="toggleDropdown(this)">⋮</button>
                        <!-- Menu Dropdown -->
                        <div class="dropdown-menu">
                            <button onclick="openRenameModal({{ $folder->id }}, '{{ $folder->name }}')">Rename</button>
                            <button
                                onclick="openShareModal({{ $folder->id }}, '{{ url('/folder/' . $folder->id . '/share') }}')">Share</button>
                            <button onclick="openDeleteModal({{ $folder->id }})">Delete</button>
                        </div>
                    </div>

                    <!-- Ikon dan Info Folder -->
                    <a href="{{ route('folder.show', $folder->id) }}" class="folder-link">
                        <div class="icon-container">
                            <span class="material-icons folder-icon">folder</span>
                        </div>
                        <div class="file-info">
                            <span class="fw-bold">{{ $folder->name }}</span>
                            <span class="text-muted">Updated at: {{ $folder->updated_at->format('d/m/Y') }}</span>
                        </div>
                    </a>
                </div>
            @endforeach
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
<script>
    function toggleDropdown(button) {
        const dropdownMenu = button.nextElementSibling;

        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (menu !== dropdownMenu) {
                menu.style.display = 'none';
            }
        });

        if (dropdownMenu.style.display === 'block') {
            dropdownMenu.style.display = 'none';
        } else {
            dropdownMenu.style.display = 'block';
        }

        event.stopPropagation();
    }

    // Tutup dropdown saat klik di luar area
    window.addEventListener('click', function () {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.style.display = 'none';
        });
    });

    //SHARE
    function closeShareModal() {
        const modal = document.getElementById("shareModal");
        modal.style.display = "none";
    }
</script>

@endsection
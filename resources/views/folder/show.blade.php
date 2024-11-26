@extends('layouts.manager')

@section('title', 'Folder: {{ $folder->name }}')

@section('content')
<!-- Navbar -->
@include('layouts.navbar')

<div class="main-layout">
    @include('layouts.sidebar')
    <div class="container content-container">
        <div class="header d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="mb-0">{{ $folder->name }}</h1>

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
                <button class="add-folder ms-auto"
                    onclick="location.href='{{ route('folder.create', $folder->id) }}'">Add Folder</button>
                <button class="add-file ms-2"
                    onclick="location.href='{{ route('files.create', ['folder' => $folder->id]) }}'">
                    Add File
                </button>

            </div>
        </div>
        <p class="text-muted">
            Terdapat {{ $subFolders ? $subFolders->count() : 0 }} Folders,
            {{ $files ? $files->count() : 0 }} File.
        </p>

        <div class="file-grid">
            {{-- Tampilkan subfolder --}}
            @foreach ($subFolders as $subFolder)
                <div class="sub-folder-card">
                    <!-- Tombol titik tiga -->
                    <div class="dropdown">
                        <button class="dropdown-toggle custom-toggle" onclick="toggleDropdown(this)">⋮</button>
                        <div class="dropdown-menu">
                            <button onclick="openRenameModal({{ $folder->id }}, '{{ $folder->name }}')">Rename</button>
                            <button
                                onclick="openShareModal({{ $folder->id }}, '{{ url('/folder/' . $folder->id . '/share') }}')">Share</button>
                            <button onclick="deleteFolder({{ $folder->id }})">Delete</button>
                            <button onclick="copyFolder({{ $folder->id }})">Copy</button>
                        </div>
                    </div>
                    <a href="{{ route('folder.show', $subFolder->id) }}">
                        <div class="icon-container">
                            <span class="material-icons folder-icon">folder</span>
                        </div>
                        <div class="file-info">
                            <span class="fw-bold">{{ $subFolder->name }}</span>
                            <span class="text-muted">Updated at: {{ $subFolder->updated_at->format('d/m/Y') }}</span>
                        </div>
                    </a>
                </div>
            @endforeach

            {{-- Tampilkan file --}}
            @foreach ($files as $file)
                <div class="file-card">
                    <div class="icon-container">
                        <img src="{{ asset('icons/' . $file->type . '.png') }}" alt="{{ $file->type }} icon"
                            class="file-icon">
                    </div>
                    <div class="file-info">
                        <span class="fw-bold">{{ $file->name }}</span>
                        <span class="text-muted">{{ $file->size }} KB</span>
                    </div>
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
    </div>
</div>
<script>
    function toggleDropdown(button) {
        const dropdownMenu = button.nextElementSibling;
        // Tampilkan atau sembunyikan dropdown
        if (dropdownMenu.style.display === 'block') {
            dropdownMenu.style.display = 'none';
        } else {
            dropdownMenu.style.display = 'block';
        }
    }

    function renameFolder(folderId) {
        // Logika rename folder
        alert(`Rename folder dengan ID: ${folderId}`);
        // Tambahkan modal atau redirect ke halaman rename
    }

    function shareFolder(folderId) {
        // Logika share folder
        alert(`Share folder dengan ID: ${folderId}`);
        // Implementasikan logika share
    }

    function deleteFolder(folderId) {
        // Konfirmasi sebelum menghapus
        if (confirm('Apakah Anda yakin ingin menghapus folder ini?')) {
            alert(`Folder dengan ID ${folderId} akan dihapus.`);
            // Implementasikan logika penghapusan (AJAX atau redirect ke controller)
        }
    }

    function copyFolder(folderId) {
        // Logika copy folder
        alert(`Copy folder dengan ID: ${folderId}`);
        // Implementasikan logika copy
    }

    // Tutup dropdown saat klik di luar area
    window.addEventListener('click', function (e) {
        const dropdowns = document.querySelectorAll('.dropdown-menu');
        dropdowns.forEach(menu => {
            if (!menu.parentElement.contains(e.target)) {
                menu.style.display = 'none';
            }
        });
    });
</script>
@endsection
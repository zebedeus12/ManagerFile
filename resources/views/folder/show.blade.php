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

        <!-- Tampilkan notifikasi jika ada -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <p class="text-muted">
            Terdapat {{ $subFolders ? $subFolders->count() : 0 }} Folders,
            {{ $files ? $files->count() : 0 }} File.
        </p>

        <h3>Folders</h3>
        <div class="file-grid mt-4" id="media-container">
            {{-- Tampilkan subfolder --}}
            @foreach ($subFolders as $subFolder)
            <div class="folder-card">
            <a href="{{ route('folder.show', $subFolder->id) }}" class="file-link">
                <div class="d-flex align-items-center">
                    <div class="icon-container">
                        <span class="material-icons folder-icon">folder</span>
                    </div>
                    <div class="file-info ms-2">
                        <span class="fw-bold">{{ $subFolder->name }}</span>
                    </div>
                </div>
            </a>
            <!-- Dropdown Tombol -->
            <div class="dropdown">
                <button class="dropdown-toggle custom-toggle" onclick="toggleDropdown(this)">⋮</button>
                <div class="dropdown-menu">
                    <button onclick="openRenameModal({{ $subFolder->id }}, '{{ $subFolder->name }}')">Rename</button>
                    <button onclick="openShareModal({{ $subFolder->id }}, '{{ url('/folder/' . $subFolder->id . '/share') }}')">Share</button>
                    <button onclick="openDeleteModal({{ $subFolder->id }})">Delete</button>
                </div>
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

        <!-- Copy -->
        <div id="copyModal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close" onclick="closeCopyModal()">&times;</span>
                <h2>Copy Folder</h2>
                <p>Apakah Anda yakin ingin menyalin folder ini?</p>
                <form id="copyForm" method="POST" action="{{ route('folder.copy', $folder->id) }}">
                    @csrf
                        <div class="form-group">
                            <label for="destination_folder_id">Pilih Folder Tujuan</label>
                            <select id="destination_folder_id" name="destination_folder_id" class="form-control">
                                <option value="">Pilih Folder Tujuan</option>
                                    @foreach($allFolders as $folderOption)
                                        <option value="{{ $folderOption->id }}" @if($folderOption->id == $folder->id) selected @endif>
                                            {{ $folderOption->name }}
                                        </option>
                                    @endforeach
                                <option value="new">Buat Folder Baru</option>
                            </select>
                        </div>
                    <button type="button" class="btn btn-secondary" onclick="closeCopyModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Copy</button>
                </form>
            </div>
        </div>

        <div class="file-list">
            <h3>Files</h3>
            {{-- Tampilkan file --}}
            @foreach ($files as $file)
            <div class="file-card">
                <div class="dropdown">
                    <button class="dropdown-toggle custom-toggle" onclick="toggleDropdown(this)">⋮</button>
                <div class="dropdown-menu">
                    <button type="button" onclick="openRenameFileModal({{ $file->id }}, '{{ $file->name }}')">Rename</button>
                    <button type="button" onclick="openShareFileModal('{{ route('file.share', $file->id) }}')">Share</button>
                    <form action="{{ route('file.destroy', $file->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus file ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </div>
            </div>
            <a href="{{ Storage::url($file->path) }}" target="_blank" class="file-link">
                <div class="icon-container">
                    @switch($file->type)
                        @case('pdf')
                            <i class="fas fa-file-pdf file-icon" style="color: #E74C3C;"></i>
                            @break
                        @case('doc')
                        @case('docx')
                            <i class="fas fa-file-word file-icon" style="color: #3498DB;"></i>
                            @break
                        @case('xls')
                        @case('xlsx')
                            <i class="fas fa-file-excel file-icon" style="color: #28A745;"></i>
                            @break
                        @default
                            <i class="fas fa-file file-icon" style="color: #BDC3C7;"></i>
                    @endswitch
                </div>
                <div class="file-info">
                    <span class="fw-bold">{{ $file->name }}</span>
                    <span class="text-muted">{{ number_format($file->size, 2) }} KB</span>
                </div>
            </a>
        </div>
            @endforeach
        </div>
        </div>

{{-- rename file --}}
<div id="renameFileModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeRenameFileModal()">&times;</span>
        <h2>Rename File</h2>
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

{{-- share file --}}
<div id="shareFileModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeShareFileModal()">&times;</span>
        <h2>Share File Link</h2>
        <input type="text" id="shareFileUrlInput" class="form-control" readonly>
        <button id="copyFileLinkButton" class="btn btn-primary mt-2">Copy Link</button>
    </div>
</div>

{{-- delete file --}}
<div id="shareFileModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeShareFileModal()">&times;</span>
        <h2>Share File Link</h2>
        <input type="text" id="shareFileUrlInput" class="form-control" readonly>
        <button id="copyFileLinkButton" class="btn btn-primary mt-2">Copy Link</button>
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

    // Tutup dropdown saat klik di luar area
    window.addEventListener('click', function (e) {
        const dropdowns = document.querySelectorAll('.dropdown-menu');
        dropdowns.forEach(menu => {
            if (!menu.parentElement.contains(e.target)) {
                menu.style.display = 'none';
            }
        });
    });

    //SHARE
    function closeShareModal() {
        const modal = document.getElementById("shareModal");
        modal.style.display = "none";
    }

    // fungsi dropdown titik tiga file
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

function downloadFile(fileUrl) {
    window.open(fileUrl, '_blank');
}

function openRenameFileModal(fileId, currentName) {
    const modal = document.getElementById('renameFileModal');
    modal.style.display = 'block';
    const form = document.getElementById('renameFileForm');
    form.action = `/file/rename/${fileId}`;
    document.getElementById('newFileName').value = currentName;
}

function closeRenameFileModal() {
    const modal = document.getElementById('renameFileModal');
    modal.style.display = 'none';
}

function openShareFileModal(fileUrl) {
    const modal = document.getElementById('shareFileModal');
    modal.style.display = 'block';
    const shareUrlInput = document.getElementById('shareFileUrlInput');
    shareUrlInput.value = fileUrl;
    const copyButton = document.getElementById('copyFileLinkButton');
    copyButton.addEventListener('click', function () {
        navigator.clipboard.writeText(shareUrlInput.value).then(() => {
            alert('Link copied to clipboard!');
        });
    });
}

function closeShareFileModal() {
    const modal = document.getElementById('shareFileModal');
    modal.style.display = 'none';
}

function openDeleteFileModal(fileId) {
    const modal = document.getElementById('deleteFileModal');
    modal.style.display = 'block';
    const form = document.getElementById('deleteFileForm');
    form.action = `/file/delete/${fileId}`;
}

function closeDeleteFileModal() {
    const modal = document.getElementById('deleteFileModal');
    modal.style.display = 'none';
}

</script>
@endsection
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

        <div class="folder-grid ">
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
                                    <button onclick="openRenameModal({{ $folder->id }}, '{{ $folder->name }}')">Rename</button>
                                    <button onclick="openShareModal({{ $folder->id }}, '{{ url('/folder/' . $folder->id . '/share') }}')">Share</button>
                                    <button onclick="openDeleteModal({{ $subFolder->id }})">Delete</button>
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

        <!-- Bagian untuk File -->
        <h6>Files</h6>
        <div class="file-grid">
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
                            <button class="dropdown-toggle custom-toggle">⋮</button>
                            <div class="dropdown-menu">
                                <button onclick="openRenameFileModal({{ $file->id }}, '{{ $file->name }}')">Rename</button>
                                <button onclick="openShareFileModal('{{ route('file.share', $file->id) }}')">Share</button>
                                <form action="{{ route('file.destroy', $file->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus file ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="file-preview">
                        <img src="{{ $file->thumbnail ?? 'default-thumbnail.jpg' }}" alt="File Preview">
                    </div>
                    <div class="file-footer">
                        <span class="file-info">
                            Anda membuatnya • {{ $file->created_at->format('d M Y') }}
                        </span>
                    </div>
                </div>
            @endforeach
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
    </div>
</div>

<script>
   function toggleMenu(button) {
        // Tutup semua dropdown lainnya
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (menu !== button.nextElementSibling) {
                menu.classList.remove('show');
            }
        });

        // Toggle dropdown saat ini
        const menu = button.nextElementSibling;
        menu.classList.toggle('show');
    }

    // Tutup dropdown saat klik di luar
    document.addEventListener('click', function (event) {
        if (!event.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        }
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
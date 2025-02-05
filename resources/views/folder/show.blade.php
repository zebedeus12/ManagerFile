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
                <button class="btn rounded-circle ms-2" onclick="location.href='{{ route('folder.create', $folder->id) }}'"
                    style="background-color: #b3e6b1; border: none;">
                    <span class="material-icons">create_new_folder</span>
                </button>
                <button class="btn rounded-circle ms-2"
                    onclick="location.href='{{ route('files.create', ['folder' => $folder->id]) }}'"
                    style="background-color: #b3e6b1; border: none;">
                    <span class="material-icons">upload_file</span>
                </button>
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
                                    <button onclick="openRenameModal({{ $folder->id }}, '{{ $folder->name }}')">Rename</button>
                                    <button onclick="openShareModal({{ $folder->id }}, '{{ url('/folder/' . $folder->id . '/share') }}')">Share</button>
                                    <button onclick="openDeleteModal({{ $subFolder->id }})">Delete</button>
                                    <button onclick="openCopyModal({{ $folder->id }})">Copy</button>
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
            <table class="table table-striped">
            <button class="button" onclick="openDeleteModal({{ $folder->id }})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="button" onclick="openShareModal({{ $folder->id }}, '{{ url('/folder/' . $folder->id . '/share') }}')" title="Share">
                                <i class="fas fa-share-alt"></i>
                            </button>
                            <button class="button" onclick="openCopyModal({{ $folder->id }})">
                            <i class="fas fa-copy"></i>
                            </button>
                <thead> 
                    <tr> 
                        <th>Name</th>
                        <th>Created At</th> 
                        <th>Description</th> 
                        <th>Actions</th> 
                    </tr> 
                </thead> 
                <tbody> 
                    @foreach ($subFolders as $subFolder) 
                        <tr> 
                            <td>{{ $subFolder->name }}</td> 
                            <td>{{ $subFolder->created_at->format('d M Y') }}</td> 
                            <td>{{ $subFolder->keterangan ?? 'Tidak ada keterangan' }}</td> 
                            <td> 
                            <button class="button" onclick="openRenameModal({{ $folder->id }}, '{{ $folder->name }}')"
                                        title="Rename">
                                        <i class="fas fa-edit"></i>
                                    </button>
                            </td> 
                        </tr> 
                    @endforeach 
                </tbody>           
            </table>
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
        <form id="deleteMultipleForm" action="{{ route('files.deleteMultiple') }}" method="POST">
            @csrf
            <table class="table table-striped">
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
                    @foreach ($files as $file)
                        <tr>
                            <td><input type="checkbox" name="files[]" value="{{ $file->id }}"></td>
                            <td>{{ $file->name }}</td>
                            <td>{{ $file->created_at->format('d M Y') }}</td>
                            <td>{{ $file->keterangan ?? 'No description' }}</td>
                            <td>
                                <button class="button" onclick="openRenameModal({{ $file->id }}, '{{ $file->name }}')" title="Rename">
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

    function toggleView() { 
        const gridViewFolders = document.getElementById('gridViewFolders'); 
        const listViewFolders = document.getElementById('listViewFolders'); 
        const gridViewFiles = document.getElementById('gridViewFiles'); 
        const listViewFiles = document.getElementById('listViewFiles'); 
        
        if (gridViewFolders.style.display === 'none') { 
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
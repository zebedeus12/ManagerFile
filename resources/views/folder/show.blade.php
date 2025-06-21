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
                        $breadcrumbs = [];
                        $current = $folder;
                
                        while ($current->parent) {
                            $breadcrumbs[] = '<a href="' . route('folder.show', $current->parent->id) . '">' . $current->parent->name . '</a>';
                            $current = $current->parent;
                        }
                
                        $breadcrumbs = array_reverse($breadcrumbs);
                    @endphp
                
                    @foreach ($breadcrumbs as $breadcrumb)
                        <span> / </span>{!! $breadcrumb !!}
                    @endforeach
                
                    <span> /{{ $folder->name }}</span>
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
                    @if(auth()->user()->id_user == $folder->owner_id || auth()->user()->role == 'super_admin')
                        {{-- PEMILIK atau SUPER ADMIN - tombol selalu tampil --}}
                        <button class="btn rounded-circle ms-2" data-bs-toggle="modal" data-bs-target="#addSubfolderModal"
                            style="background-color: #b3e6b1; border: none;">
                            <span class="material-icons">create_new_folder</span>
                        </button>
                        <button class="btn rounded-circle ms-2" data-bs-toggle="modal" data-bs-target="#uploadFileModal"
                            style="background-color: #b3e6b1; border: none;">
                            <span class="material-icons">upload_file</span>
                        </button>
                    @elseif($folder->accessibility_subfolder == 1)
                        {{-- ADMIN tapi bukan pemilik dan folder diakses ALL --}}
                        <button class="btn rounded-circle ms-2" data-bs-toggle="modal" data-bs-target="#uploadFileModal"
                            style="background-color: #b3e6b1; border: none;">
                            <span class="material-icons">upload_file</span>
                        </button>
                    @endif
                @endif
                <button class="btn rounded-circle ms-2" onclick="toggleView()" style="background-color: #b3e6b1; border: none;">
                    <span class="material-icons">grid_view</span>
                </button>
            </div>
        </div>

        <h6>Folders</h6>
        <div id="gridViewFolders" class="folder-grid mt-4">
            @if($subFolders->isEmpty())
                <p>No subfolders found.</p>
            @else
                @foreach ($subFolders as $subFolder)
                    
                    <div class="folder-card">
                        <form id="downloadForm-{{ $subFolder->id }}" action="{{ route('folders.download', $subFolder->id) }}" method="POST">
                            @csrf
                        </form>
                        
                        <a href="{{ route('folder.show', $subFolder->id) }}" class="folder-link">
                            @if($subFolder->accessibility === 'private')
                            <span title="Private" class="ms-1 align-middle text-muted">
                                <i class="material-icons" style="font-size: 18px;">lock</i> 
                            </span>
                            @endif
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
                                        <button onclick="openShareModal({{ $subFolder->id }}, '{{ url('/folder/' . $subFolder->id . '/share') }}')">Share</button>
                                        <button onclick="submitDownloadForm(event, {{ $subFolder->id }}, 'folder')">Download</button>
                                        @if(auth()->user()->id_user == $subFolder->owner_id || auth()->user()->role == 'super_admin')
                                        <button onclick="openRenameModal({{ $subFolder->id }}, '{{ $subFolder->name }}')">Rename</button>
                                            <form action="{{ route('folders.toggle-accessibility', $subFolder->id) }}" method="POST" onsubmit="return confirm('Ubah akses folder ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit">
                                                    {{ $subFolder->accessibility === 'private' ? 'Ubah ke Public' : 'Ubah ke Private' }}
                                                </button>
                                            </form>   
                                           <form action="{{ route('folders.set-toall', $subFolder->id) }}" method="POST" onsubmit="return confirm('Ubah akses folder ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit">
                                                    {{ $subFolder->accessibility_subfolder == 1 ? 'Ubah ke Only Me' : 'Ubah ke All' }}
                                                </button>
                                            </form>
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

        @include('layouts.index')

        <!-- List View for Folders -->
        <div id="listViewFolders" class="folder-list mt-4" style="display: none;">
            @if($subFolders->isEmpty())
                <p>No subfolders found.</p>
            @else
                @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                @if(auth()->user()->id_user == $folder->owner_id || auth()->user()->role == 'super_admin')
                <button id="bulkDeleteBtn" class="btn btn-danger" onclick="bulkDelete()" >
                    <i class="fas fa-trash-alt"></i> &nbsp; Hapus yang Dipilih
                </button>
                @endif
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
                        @foreach ($subFolders as $subFolder)
                            <tr>
                                @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                                    @if(auth()->user()->id_user == $subFolder->owner_id OR auth()->user()->role == 'super_admin')
                                    <td><input type="checkbox" class="folder-checkbox form-check-input" value="{{ $subFolder->id }}"></td>
                                    @else
                                    <td></td>
                                    @endif
                                @endif
                                <td>{{ $subFolder->name }}</td>
                                <td>{{ $subFolder->created_at->format('d M Y') }}</td>
                                <td>{{ $subFolder->keterangan ?? 'Tidak ada keterangan' }}</td>
                                    <td>
                                        @if(auth()->user()->id_user == $subFolder->owner_id OR auth()->user()->role == 'super_admin')
                                        <button class="button" onclick="openRenameModal({{ $subFolder->id }}, '{{ $subFolder->name }}')" title="Rename">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @endif
                                        <button class="button" onclick="openShareModal({{ $subFolder->id }}, '{{ url('/folder/' . $subFolder->id . '/share') }}')" title="Share">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            <form id="bulkDeleteForm" action="{{ route('folders.bulkDelete') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="ids" id="bulkDeleteIds">
            </form>
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
                    <div class="modal-body text-center">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h5 class="mb-3">Konfirmasi Penghapusan</h5>
                        <p class="text-muted">Apakah kamu yakin ingin menghapus folder ini? Tindakan ini tidak bisa dibatalkan.</p>
                    
                        <form id="deleteForm" method="POST" class="d-flex justify-content-center gap-2 mt-4">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt me-1"></i> Hapus
                            </button>
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
                        <form id="downloadFormFile-{{ $file->id }}" action="{{ route('files.download', $file->id) }}" method="POST">
                            @csrf
                        </form>
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
                                    <button onclick="openShareFileModal('{{ route('file.share', $file->id) }}')">Share</button>
                                    <button onclick="submitDownloadForm(event, {{ $file->id }}, 'file')">Download</button>
                                    @if(auth()->user()->id_user == $folder->owner_id || auth()->user()->role == 'super_admin')
                                    <button onclick="openRenameFileModal({{ $file->id }}, '{{ $file->name }}')">Rename</button>
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
                            <th><input class="form-check-input" type="checkbox" id="selectAllFile"></th>
                            @endif
                            <th>Name</th>
                            <th>Created At</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($files as $file)
                            <tr>
                                @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                                    @if(auth()->user()->id_user == $folder->owner_id || auth()->user()->role == 'super_admin')
                                    <td><input type="checkbox" class="file-checkbox form-check-input" value="{{ $file->id }}"></td>
                                    @else
                                    <td></td>
                                    @endif
                                @endif
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

            <form id="bulkDeleteFormFile" action="{{ route('files.bulkDelete') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="ids" id="bulkDeleteFileIds">
            </form>
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
                    <div class="modal-body text-center">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h5 class="mb-3">Konfirmasi Penghapusan</h5>
                        <p class="text-muted">Apakah kamu yakin ingin menghapus file ini? Tindakan ini tidak bisa dibatalkan.</p>
                    
                        <form id="deleteFileForm" method="POST" class="d-flex justify-content-center gap-2 mt-4">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt me-1"></i> Hapus
                            </button>
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
            alert('Pilih minimal satu file untuk dihapus.');
            return;
        }

        if (confirm('Apakah Anda yakin ingin menghapus file yang dipilih?')) {
            const form = document.getElementById('bulkDeleteFormFile');
            document.getElementById('bulkDeleteFileIds').value = JSON.stringify(selected);
            form.submit(); // ini akan trigger controller Laravel seperti biasa
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
@endsection

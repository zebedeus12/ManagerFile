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
                    @if(auth()->user()->role === 'super_admin')
                        <button class="btn rounded-circle ms-2" data-bs-toggle="modal" id="openAddFolderModal"
                            style="background-color: #b3e6b1; border: none;">
                            <span class="material-icons">create_new_folder</span>
                        </button>
                    @endif
                    <button type="button" class="btn rounded-circle ms-2" id="toggleViewBtn"
                        style="background-color: #b3e6b1; border: none;">
                        <span class="material-icons" id="toggleIcon">grid_view</span>
                    </button>
                </div>
            </div>
            <p class="text-muted">Terdapat {{ $folders->count() }} Folders.</p>

            @include('layouts.index')

            <!-- Grid View -->
            <div id="gridView" class="folder-grid mt-6">
                @if($folders->isEmpty())
                    <p>No folders found.</p>
                @else
                    @foreach ($folders as $folder)
                        <div class="folder-card">
                            <form id="downloadForm-{{ $folder->id }}" action="{{ route('folders.download', $folder->id) }}" method="POST">
                                @csrf
                            </form>
                            <a href="{{ route('folder.show', $folder->id) }}" class="folder-link">
                                @if($folder->accessibility === 'private')
                                <span title="Private" class="ms-1 align-middle text-muted">
                                    <i class="material-icons" style="font-size: 18px;">lock</i> 
                                </span>
                                @endif
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
                                        <button onclick="submitDownloadForm(event, {{ $folder->id }})">Download</button>
                                        <button onclick=" openShareModal({{ $folder->id }}, '{{ url('/folder/' . $folder->id . '/share') }}')">Share</button>
                                        @if(auth()->user()->id_user == $folder->owner_id || auth()->user()->role == 'super_admin')

                                            <button onclick="openRenameModal({{ $folder->id }}, '{{ $folder->name }}')">Rename</button>
                                            <form action="{{ route('folders.toggle-accessibility', $folder->id) }}" method="POST" onsubmit="return confirm('Ubah akses folder ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit">
                                                    {{ $folder->accessibility === 'private' ? 'Ubah ke Public' : 'Ubah ke Private' }}
                                                </button>
                                            </form>
                                            
                                            <button onclick="openDeleteModal({{ $folder->id }})">Delete</button>
                                        @endif
                                    </div>
                                </div>
                                <p class="folder-meta">
                                    Anda membuatnya · {{ $folder->created_at->format('d M Y') }}<br>
                                    <span class="folder-description">{{ $folder->keterangan ?? 'Tidak ada keterangan' }}</span>
                                </p>
                            </a>
                        </div>
                    @endforeach

                    <form id="bulkDeleteForm" action="{{ route('folders.bulkDelete') }}" method="POST" style="display: none;">
                        @csrf
                        <input type="hidden" name="ids" id="bulkDeleteIds">
                    </form>
                @endif
            </div>

            <!-- List View -->
            <div id="listView" class="folder-list mt-4" style="display: none;">
                @if($folders->isEmpty())
                    <p>No folders found.</p>
                @else
                    <table class="table table-striped">
                        <!-- Trash Icon (Drag Area) -->
                        @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                        <button id="bulkDeleteBtn" class="btn btn-danger" onclick="bulkDelete()" style="margin: 10px;">
                            <i class="fas fa-trash-alt"></i> &nbsp; Hapus yang Dipilih
                        </button>
                        @endif
                        <thead>
                            <tr>
                                @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                                <th><input class="form-check-input" type="checkbox" id="selectAll"></th>
                                @endif
                                <th>Name</th>
                                <th>Created At</th>
                                <th>Description</th>
                                @if(auth()->user()->role === 'super_admin')
                                    <th>Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($folders as $folder)
                                <tr>
                                    @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                                    @if(auth()->user()->id_user == $folder->owner_id || auth()->user()->role == 'super_admin')
                                    <td><input type="checkbox" class="folder-checkbox form-check-input" value="{{ $folder->id }}"></td>
                                    @else
                                    <td></td>
                                    @endif
                                    @endif
                                    <td>{{ $folder->name }}</td>
                                    <td>{{ $folder->created_at->format('d M Y') }}</td>
                                    <td>{{ $folder->keterangan ?? 'Tidak ada keterangan' }}</td>
                                    @if(auth()->user()->role === 'super_admin')
                                        <td>
                                            <button class="button" onclick="openRenameModal({{ $folder->id }}, '{{ $folder->name }}')" title="Rename">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="button" onclick="openShareModal({{ $folder->id }}, '{{ url('/folder/' . $folder->id . '/share') }}')" title="Share">
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

            <!-- Modal Tambah Folder -->
            <div class="modal" id="addFolderModal" tabindex="-1" aria-labelledby="addFolderModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Folder Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('folder.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="folder_name" class="form-label">Nama Folder</label>
                                    <input type="text" class="form-control" id="folder_name" name="folder_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="accessibility" class="form-label">Aksesibilitas</label>
                                    <select class="form-select" id="accessibility" name="accessibility" required>
                                        <option value="public">Publik</option>
                                        <option value="private">Rahasia</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="owner_id" class="form-label">Hak Akses</label>
                                    <select class="form-select" id="hak-akses" name="owner_id" required>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id_user }}">{{ $employee->nama_user }} -
                                                {{ ucfirst($employee->role) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Folder</button>
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
                                    <label for="newFolderName" class="form -label">New Folder Name</label>
                                    <input type="text" id="newFolderName" name="name" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Folder -->
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
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const gridView = document.getElementById('gridView');
            const listView = document.getElementById('listView');
            const toggleButton = document.getElementById('toggleViewBtn');
            const toggleIcon = document.getElementById('toggleIcon');

            let isGridView = localStorage.getItem('viewMode') !== 'list';

            updateView();

            toggleButton.addEventListener("click", function (event) {
                event.preventDefault();
                isGridView = !isGridView;
                updateView();
                localStorage.setItem('viewMode', isGridView ? 'grid' : 'list');
            });

            function updateView() {
                if (isGridView) {
                    gridView.style.display = 'grid';
                    listView.style.display = 'none';
                    toggleIcon.textContent = 'grid_view';
                } else {
                    gridView.style.display = 'none';
                    listView.style.display = 'block';
                    toggleIcon.textContent = 'view_list';
                }
            }
        });

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
    function submitDownloadForm(event, folderId) {
        event.preventDefault();
        document.getElementById('downloadForm-'+ folderId).submit();
    }
</script>
@endsection
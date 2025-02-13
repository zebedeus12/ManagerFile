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
                    <!-- BUTTON GRID VIEW -->
                    <button type="button" class="btn rounded-circle ms-2" id="toggleViewBtn"
                        style="background-color: #b3e6b1; border: none;">
                        <span class="material-icons" id="toggleIcon">grid_view</span>
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
                                        @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                                            <button onclick="openRenameModal({{ $folder->id }}, '{{ $folder->name }}')">Rename</button>
                                        @endif

                                        <button
                                            onclick="openShareModal({{ $folder->id }}, '{{ url('/folder/' . $folder->id . '/share') }}')">Share</button>

                                        @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
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
                            @if(auth()->user()->role === 'super_admin')
                                <button class="button" onclick="openDeleteModal({{ $folder->id }})" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endif
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAll" onclick="toggleSelectAll()"></th>
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
                                        <td><input type="checkbox" name="folders[]" value="{{ $folder->id }}"></td>
                                        <td>{{ $folder->name }}</td>
                                        <td>{{ $folder->created_at->format('d M Y') }}</td>
                                        <td>{{ $folder->keterangan ?? 'Tidak ada keterangan' }}</td>
                                        @if(auth()->user()->role === 'super_admin')
                                            <td>
                                                <button class="button"
                                                    onclick="openRenameModal({{ $folder->id }}, '{{ $folder->name }}')" title="Rename">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="button"
                                                    onclick="openShareModal({{ $folder->id }}, '{{ url('/folder/' . $folder->id . '/share') }}')"
                                                    title="Share">
                                                    <i class="fas fa-share-alt"></i>
                                                </button>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
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
                                    <label for="hak-akses" class="form-label">Hak Akses</label>
                                    <select class="form-select" id="hak-akses" name="hak-akses" required>
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
            <div class="modal" id="renameFolderModal" tabindex="-1" aria-labelledby="renameFolderModalLabel"
                aria-hidden="true">
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

            <!-- Delete Folder -->
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

            <!-- Warning Modal -->
            <div class="modal" id="warningModal" tabindex="-1" aria-labelledby="warningModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="warningModalLabel">Warning</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p id="warningMessage"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
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

            // Cek apakah ada preferensi tampilan sebelumnya di localStorage
            let isGridView = localStorage.getItem('viewMode') !== 'list'; // Default ke Grid

            // Atur tampilan sesuai preferensi yang tersimpan
            updateView();

            // Event listener untuk tombol toggle tampilan
            toggleButton.addEventListener("click", function (event) {
                event.preventDefault();

                // Toggle tampilan
                isGridView = !isGridView;
                updateView();

                // Simpan preferensi ke localStorage agar tetap setelah reload
                localStorage.setItem('viewMode', isGridView ? 'grid' : 'list');
            });

            function updateView() {
                if (isGridView) {
                    gridView.style.display = 'grid';
                    gridView.style.gridTemplateColumns = 'repeat(auto-fill, minmax(200px, 1fr))';
                    gridView.style.gap = '20px';  // Ensure consistent gap in grid view
                    listView.style.display = 'none';
                    toggleIcon.textContent = 'grid_view'; // Change icon to List View
                } else {
                    gridView.style.display = 'none';
                    listView.style.display = 'block';
                    toggleIcon.textContent = 'view_list'; // Change icon to Grid View
                }
            }
        });
    </script>
@endsection
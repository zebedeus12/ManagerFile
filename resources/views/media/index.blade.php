@extends('layouts.media')

@section('title', 'Media Manager')

@section('content')
    <!-- Navbar -->
    @include('layouts.navbar')

    <div class="main-layout">
        @include('layouts.sidebar')
        <div class="employee-content p-4">
            <div class="header d-flex align-items-center justify-content-between mb-4">
                <h2>Media Manager</h2>
                <!-- Search Form -->
                <form method="GET" action="{{ route('media.index') }}" class="d-flex mb-3">
                    <div class="d-flex align-items-center gap-2 justify-content-end">
                        <!-- SEARCH INPUT -->
                        <div class="search-container">
                            <input type="text" name="search" value="{{ request('search') }}" class="search-input"
                                placeholder="Search folders...">
                        </div>
                        <!-- SEARCH BUTTON -->
                        <button type="submit" class="search-btn">
                            <i class="material-icons">search</i>
                        </button>
                        @if(in_array(auth()->user()->role, ['super_admin']))
                            <!-- BUTTON ADD FOLDER -->
                            <button type="button" class="btn btn-custom" data-bs-toggle="modal"
                                data-bs-target="#addFolderModal">
                                <i class="material-icons">create_new_folder</i>
                            </button>
                        @endif
                        <!-- BUTTON GRID VIEW -->
                        <button type="button" class="btn btn-custom" id="toggleViewBtn">
                            <i class="material-icons">grid_view</i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Modal untuk Add Folder -->
            <div class="modal fade" id="addFolderModal" tabindex="-1" aria-labelledby="addFolderModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addFolderModalLabel">Add Folder</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('media.folder.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="folderName">Folder Name</label>
                                    <input type="text" class="form-control" name="name" id="folderName" required>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="folderAccessibility">Accessibility</label>
                                    <select class="form-select" name="accessibility" id="folderAccessibility" required>
                                        <option value="public">Public</option>
                                        <option value="private">Private</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="owner_id" class="form-label">Hak Akses</label>
                                    <select class="form-select" id="owner_id" name="owner_id" required>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id_user }}">{{ $employee->nama_user }} -
                                                {{ ucfirst($employee->role) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="folderDescription">Keterangan</label>
                                    <textarea class="form-control" name="description" id="folderDescription"
                                        rows="3"></textarea>
                                </div>
                                <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-primary">Create Folder</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @include('layouts.index')

            <!-- Media Grid Display -->
            <div id="gridView" class="folder-grid mt-4">
                @if($folders->isEmpty())
                    <p>No folders found.</p>
                @else
                    @foreach ($folders as $folder)
                        <div class="folder-card">
                            @if($folder->accessibility === 'private')
                                <span title="Private" class="ms-1 align-middle text-muted">
                                    <i class="material-icons" style="font-size: 18px;">lock</i> 
                                </span>
                            @endif
                            <form id="downloadForm-{{ $folder->id }}" action="{{ route('mediaFolder.download', $folder->id) }}" method="POST">
                                @csrf
                            </form>
                            <a href="{{ route('media.folder.show', $folder->id) }}" class="folder-link d-flex align-items-center">
                                <div class="folder-icon">
                                    <span class="material-icons">folder</span>
                                </div>
                                <span class="folder-name text-center">{{ $folder->name }}</span>
                                <p class="folder-meta">
                                    Anda membuatnya · {{ $folder->created_at->format('d M Y') }}<br>
                                    <span class="folder-description">{{ $folder->description ?? 'Tidak ada keterangan' }}</span>
                                </p>
                            </a>
                            <div class="dropdown">
                                <button class="custom-toggle" onclick="toggleMenu(this)">
                                    <span class="material-icons">more_vert</span>
                                </button>
                                <div class="dropdown-menu">
                                    <button onclick="submitDownloadForm(event, {{ $folder->id }})">Download</button>
                                    <button onclick="shareFolder({{ $folder->id }})">Share</button>
                                    @if(auth()->user()->id_user == $folder->owner_id || auth()->user()->role == 'super_admin')
                                    <button onclick="renameFolder({{ $folder->id }})">Rename</button>
                                    <form action="{{ route('media.toggle-accessibility', $folder->id) }}" method="POST" onsubmit="return confirm('Ubah akses folder ini?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit">
                                            {{ $folder->accessibility === 'private' ? 'Ubah ke Public' : 'Ubah ke Private' }}
                                        </button>
                                    </form>
                                        <button onclick="deleteFolder({{ $folder->id }})">Delete</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- List View -->
            <div id="listView" class="folder-list mt-4" style="display: none;">
                @if($folders->isEmpty())
                    <p>No folders found.</p>
                @else
                
                    @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                    <button id="bulkDeleteBtn" class="btn btn-danger" onclick="bulkDelete()" style="margin: 10px;">
                        <i class="fas fa-trash-alt"></i> &nbsp; Hapus yang Dipilih
                    </button>
                    @endif
                        <!-- Table -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                                <th><input class="form-check-input" type="checkbox" id="selectAll"></th>
                                @endif
                                <th>Name</th>
                                <th>Created At</th>
                                <th>Description</th>
                                <th>Action</th>
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
                                    <td>
                                        @if(auth()->user()->id_user == $folder->owner_id || auth()->user()->role == 'super_admin')
                                        <button type="button" class="button" onclick="renameFolder({{ $folder->id }})"
                                            title="Rename">
                                            <span class="material-icons">edit</span>
                                        </button>
                                        @endif
                                        <button type="button" class="button" onclick="shareFolder({{ $folder->id }})"
                                            title="Share">
                                            <span class="material-icons">share</span>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                
                    <form id="bulkDeleteForm" action="{{ route('media.folder.bulkDelete') }}" method="POST" style="display: none;">
                        @csrf
                        <input type="hidden" name="ids" id="bulkDeleteIds">
                    </form>
                @endif
            </div>

            <!-- Modal untuk Rename Folder -->
            <div class="modal fade" id="renameFolderModal" tabindex="-1" aria-labelledby="renameFolderModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="renameFolderModalLabel">Rename Folder</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="renameFolderForm" action="" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="folderNewName">Nama Folder Baru</label>
                                    <input type="text" class="form-control" id="folderNewName" name="name"
                                        placeholder="Nama Folder Baru" required>
                                </div>
                                <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal untuk Share Folder -->
            <div class="modal fade" id="shareFolderModal" tabindex="-1" aria-labelledby="shareFolderModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="shareFolderModalLabel">Share Folder Link</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" id="shareFolderLink" class="form-control" readonly>
                            </div>
                            <div class="form-group mt-3 text-center">
                                <button class="btn btn-primary" onclick="copyToClipboard()">Copy Link</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Folder Modal -->
            <div class="modal" id="deleteFolderModal" tabindex="-1" aria-labelledby="deleteFolderModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteFolderModalLabel">Delete Folder</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                            <h5 class="mb-3">Konfirmasi Penghapusan</h5>
                            <p class="text-muted">Apakah kamu yakin ingin menghapus folder ini? Tindakan ini tidak bisa dibatalkan.</p>
                            <form id="deleteFolderForm" method="POST" class="d-flex justify-content-center gap-2 mt-4" action="">
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

            <div class="modal fade" id="warningModal" tabindex="-1" aria-labelledby="warningModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="warningModalLabel">Peringatan!</h5>
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


            @include('layouts.index')

        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const gridView = document.getElementById('gridView');
            const listView = document.getElementById('listView');
            const toggleButton = document.getElementById('toggleViewBtn');

            // Set default mode ke Grid
            let isGridView = true;

            // Event listener untuk tombol Grid/List
            toggleButton.addEventListener("click", function (event) {
                event.preventDefault(); // Mencegah halaman reload

                if (isGridView) {
                    // Jika dalam mode Grid, ubah ke List
                    gridView.style.display = 'none';
                    listView.style.display = 'block';
                    toggleButton.innerHTML = '<i class="material-icons">view_list</i>'; // Ubah ikon ke List View
                } else {
                    // Jika dalam mode List, ubah ke Grid
                    gridView.style.display = 'grid';
                    listView.style.display = 'none';
                    toggleButton.innerHTML = '<i class="material-icons">grid_view</i>'; // Ubah ikon ke Grid View
                }

                isGridView = !isGridView; // Toggle state
            });
        });

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

        function toggleView() {
            const gridView = document.getElementById('gridView');
            const listView = document.getElementById('listView');
            if (gridView.style.display === 'none') {
                gridView.style.display = 'flex';
                listView.style.display = 'none';
            } else {
                gridView.style.display = 'none';
                listView.style.display = 'block';
            }
        }
    </script>

<script>
    function submitDownloadForm(event, folderId) {
        event.preventDefault();
        document.getElementById('downloadForm-'+ folderId).submit();
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
@endsection
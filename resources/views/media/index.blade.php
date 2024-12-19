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
            <form method="GET" action="{{ route('media.folders.search') }}" class="d-flex mb-3">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2"
                    placeholder="Search folders...">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
            <!-- Button untuk Add Folder -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFolderModal">Add Folder</button>
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
                                <button type="submit" class="btn btn-primary">Create Folder</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Media Grid Display -->
        <div class="file-grid mt-4" id="media-container">
            @if($folders->isEmpty())
                <p>No folders found.</p>
            @else
                @foreach($folders as $folder)
                    <div class="folder-card position-relative">
                        <a href="{{ route('media.folder.show', $folder->id) }}" class="folder-link">
                            <div class="folder-icon">
                                <span class="material-icons">folder</span>
                            </div>
                            <div class="folder-name text-center">{{ $folder->name }}</div>
                        </a>

                        <!-- Tombol Titik Tiga -->
                        <div class="dropdown position-absolute top-0 end-0 m-2">
                            <button class="custom-toggle" onclick="toggleMenu(this)">
                                <span class="material-icons">more_vert</span>
                            </button>
                            <div class="dropdown-menu">
                                <button onclick="renameFolder({{ $folder->id }})">Rename</button>
                                <button onclick="shareFolder({{ $folder->id }})">Share</button>
                                <button onclick="deleteFolder({{ $folder->id }})">Delete</button>
                            </div>
                        </div>
                    </div>
                @endforeach
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

        <!-- Modal untuk Konfirmasi Delete Folder -->
        <div class="modal fade" id="deleteFolderModal" tabindex="-1" aria-labelledby="deleteFolderModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteFolderModalLabel">Delete?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Anda yakin ingin menghapus folder tersebut?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form id="deleteFolderForm" action="" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

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
</script>

@endsection
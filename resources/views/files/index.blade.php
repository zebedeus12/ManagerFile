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
        <p class="text-muted">Terdapat {{ $folders->count() }} Folders, {{ $files->count() }} File.
        </p>
        <div class="file-grid">
            {{-- Grid Folders --}}
            @foreach ($folders as $folder)
                <div class="file-card">
                    <a href="{{ route('folder.show', $folder->id) }}" class="folder-link">
                        <div class="icon-container">
                            <span class="material-icons folder-icon">folder</span>
                        </div>
                        <div class="file-info">
                            <span class="fw-bold">{{ $folder->name }}</span>
                            <span class="text-muted">Updated at: {{ $folder->updated_at->format('d/m/Y') }}</span>
                        </div>
                    </a>
                    <!-- Tombol titik tiga -->
                    <div class="dropdown">
                        <button class="dropdown-toggle" onclick="toggleDropdown(this)">
                            <span class="material-icons">more_vert</span>
                        </button>
                        <!-- Menu Pop-Up -->
                        <div class="dropdown-menu">
                            <button onclick="renameFolder({{ $folder->id }})">Rename</button>
                            <button onclick="shareFolder({{ $folder->id }})">Share</button>
                            <button onclick="deleteFolder({{ $folder->id }})">Delete</button>
                            <button onclick="copyFolder({{ $folder->id }})">Copy</button>
                        </div>
                    </div>
                </div>
            @endforeach
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
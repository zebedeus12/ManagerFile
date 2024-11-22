@extends('layouts.dashboard')

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

    </div>
</div>
<style>
    .logo {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 15px;
    }

    .navbar {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    .notification-link {
        position: relative;
        color: #333;
        font-size: 24px;
        text-decoration: none;
        transition: color 0.3s;
    }

    .notification-link:hover {
        color: #188A98;
    }

    .notification-count {
        position: absolute;
        top: -5px;
        right: -10px;
        background-color: #ff5e5e;
        color: white;
        font-size: 12px;
        border-radius: 50%;
        padding: 2px 6px;
        font-weight: bold;
    }

    .main-layout {
        display: flex;
        height: 100vh;
    }

    /* Sidebar */
    .sidebar {
        width: 80px;
        height: 100vh;
        background: linear-gradient(180deg, #188A98, #5CCED1);
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 20px;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        border-right: 1px solid #e0e0e0;
    }

    .icon-link {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 60px;
        text-decoration: none;
        color: white;
        font-size: 28px;
    }

    .icon-link:hover {
        background: none;
        /* Tidak ada efek hover */
    }

    .material-icons {
        font-size: 28px;
    }

    .menu ul {
        width: 100%;
        padding: 0;
        list-style: none;
    }

    .menu ul li {
        margin: 20px 0;
    }

    .icon-link:hover {
        background-color: #145d65;
    }

    /* File Manager Grid */
    .content-container {
        flex-grow: 1;
        padding: 20px;
        background-color: #ffffff;
        margin-left: 50px;
        min-height: 100vh;
    }

    .file-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .file-card {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        padding: 15px;
        text-align: center;
        transition: transform 0.2s;
    }

    .file-card:hover {
        transform: translateY(-5px);
    }

    .icon-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 10px;
    }

    .folder-icon {
        font-size: 48px;
        color: #FFB400;
    }

    .file-icon {
        width: 40px;
        height: 40px;
    }

    .file-info {
        font-size: 14px;
        color: #6c757d;
    }

    .add-folder,
    .add-file {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        transition: background-color 0.3s;
    }

    .add-folder:hover,
    .add-file:hover {
        background-color: #0056b3;
    }

    .header .buttons {
        margin-left: auto;
        /* Menarik tombol Add Folder ke kanan */
    }
</style>
@endsection
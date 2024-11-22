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

@endsection
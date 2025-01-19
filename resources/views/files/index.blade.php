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
            <button class="ms-2 btn btn-secondary" onclick="toggleView()">Toggle View</button>
        </div>
        <p class="text-muted">Terdapat {{ $folders->count() }} Folders.</p>

        <!-- Grid View -->
        <div id="gridView" class="folder-grid mt-4">
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
                                    <button onclick="openRenameModal({{ $folder->id }}, '{{ $folder->name }}')">Rename</button>
                                    <button
                                        onclick="openShareModal({{ $folder->id }}, '{{ url('/folder/' . $folder->id . '/share') }}')">Share</button>
                                    <button onclick="openDeleteModal({{ $folder->id }})">Delete</button>
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
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Created At</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($folders as $folder)
                            <tr>
                                <td>{{ $folder->name }}</td>
                                <td>{{ $folder->created_at->format('d M Y') }}</td>
                                <td>{{ $folder->keterangan ?? 'Tidak ada keterangan' }}</td>
                                <td>
                                    <button onclick="openRenameModal({{ $folder->id }}, '{{ $folder->name }}')">Rename</button>
                                    <button
                                        onclick="openShareModal({{ $folder->id }}, '{{ url('/folder/' . $folder->id . '/share') }}')">Share</button>
                                    <button onclick="openDeleteModal({{ $folder->id }})">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

<script>
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
@endsection
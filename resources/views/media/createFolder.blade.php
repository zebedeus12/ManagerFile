@extends('layouts.media')

@section('title', 'Add Folder')

@section('content')
<!-- Navbar -->
@include('layouts.navbar')

<div class="main-layout">
    @include('layouts.sidebar')

    <div class="container mt-4">
        <h2 class="mb-4">Add New Folder</h2>

        <form action="{{ route('media.folder.store', ['parentId' => $parentId ?? null]) }}" method="POST">
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
@endsection
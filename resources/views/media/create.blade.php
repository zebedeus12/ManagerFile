@extends('layouts.media')

@section('title', 'Add Media')

@section('content')
<!-- Navbar -->
@include('layouts.navbar')

<!-- Layout Sidebar dan Konten -->
<div class="main-layout">
    @include('layouts.sidebar')

    <!-- Konten Dashboard -->
    <div class="container mt-4">
        <h2 class="mb-4">Add New Media</h2>

        <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Media Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">Media File</label>
                <input type="file" name="file" class="form-control" accept="image/*,audio/*,video/*" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Media Type</label>
                <select name="type" class="form-select" required>
                    <option value="image">Image</option>
                    <option value="audio">Audio</option>
                    <option value="video">Video</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Media</button>
        </form>
    </div>

    <!-- Notifikasi Sukses -->
    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif
</div>

@endsection
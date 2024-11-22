@extends('layouts.dashboard')

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

<!-- Style Khusus -->
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html,
    body {
        height: 100%;
        overflow: hidden;
    }

    .main-layout {
        display: flex;
        width: 100vw;
        height: 100vh;
    }

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
        background-color: #145d65;
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
</style>


@endsection
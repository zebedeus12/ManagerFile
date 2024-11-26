@extends('layouts.media')

@section('title', 'Edit Media')

@section('content')
<!-- Navbar -->
@include('layouts.navbar')
<!-- Layout Sidebar dan Konten -->
<div class="main-layout">
    <!-- Sidebar -->
    @include('layouts.sidebar')
    <!-- Konten Utama -->
    <div class="container mt-4">
        <h2 class="mb-4">Edit Media</h2>

        <!-- Form untuk Mengedit Media -->
        <form action="{{ route('media.update', ['media' => $media->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Input Nama Media -->
            <div class="mb-3">
                <label for="name" class="form-label">Media Name</label>
                <input type="text" name="name" value="{{ $media->name }}" required>
                <input type="hidden" value="{{ $media->type }}" name="type">
            </div>
            <button type="submit" class="btn btn-primary">Update Media</button>
        </form>

        <!-- Form untuk Menghapus Media -->
        <form action="{{ route('media.destroy', ['media' => $media->id]) }}" method="POST" class="mt-2">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger"
                onclick="return confirm('Are you sure you want to delete this media?')">Delete Media</button>
        </form>

        <!-- Notifikasi Sukses -->
        @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <!-- Style Khusus -->
    <script>
        document.querySelector('.grid-layout').addEventListener('click', function () {
            document.querySelector('.file-grid').style.display = 'grid';
            this.classList.add('active');
            document.querySelector('.list-layout').classList.remove('active');
        });

        document.querySelector('.list-layout').addEventListener('click', function () {
            document.querySelector('.file-grid').style.display = 'block';
            this.classList.add('active');
            document.querySelector('.grid-layout').classList.remove('active');
        });
    </script>
    @endsection
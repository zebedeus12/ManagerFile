@extends('layouts.dashboard')

@section('title', 'Media')

@section('content')
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid align-items-center">
        <div class="d-flex align-items-center">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">BBSPJIS File Manager</a>
        </div>
        <div>
            <span class="fw-bold">Nama User</span>
            <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false"></button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="main-layout">
    <div class="sidebar">
        <nav class="menu">
            <ul class="list-unstyled">
                <li><a href="{{ route('employees.index') }}" class="icon-link"><span
                            class="material-icons">admin_panel_settings</span></a></li>
                <li><a href="{{ route('file.index') }}" class="icon-link"><span class="material-icons">folder</span></a>
                </li>
                <li><a href="{{ route('media.index') }}" class="icon-link"><span
                            class="material-icons">perm_media</span></a></li>
            </ul>
        </nav>
    </div>

    <div class="employee-content p-4">
        <div class="header d-flex align-items-center justify-content-between mb-4">
            <h2>Media Manager</h2>
            <a href="{{ route('media.create') }}" class="btn btn-primary">Add Media</a>
        </div>

        <div class="file-grid mt-4">
            @foreach($mediaItems as $media)
                <div class="file-card">
                    <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $media->name }}" class="media-preview mb-2" />
                    <div class="file-info">
                        <p class="fw-bold">{{ $media->name }}</p>
                        <span class="text-muted">{{ ucfirst($media->type) }}</span>
                    </div>
                    <div class="file-actions">
                        <!-- Tombol Edit -->
                        <a href="{{ route('media.edit', $media->id) }}" class="btn btn-warning btn-sm me-2">Edit</a>

                        <!-- Tombol Delete dengan AJAX -->
                        <button type="button" class="delete-button btn btn-danger btn-sm"
                            data-url="{{ route('media.destroy', ['media' => $media->id]) }}">Delete</button>
                    </div>
                </div>
            @endforeach
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>
</div>

<!-- Tambahkan Script jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('click', '.delete-button', function (e) {
        e.preventDefault();

        var url = $(this).data('url');
        var card = $(this).closest('.file-card'); // Mengambil elemen kartu media

        if (confirm('Are you sure you want to delete this media?')) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function (response) {
                    card.remove(); // Menghapus elemen dari tampilan setelah berhasil dihapus
                    alert('Media berhasil dihapus');
                },
                error: function (xhr) {
                    console.error('Error:', xhr);
                    alert('Gagal menghapus media');
                }
            });
        }
    });
</script>

<style>
    .logo {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 15px;
    }

    .main-layout {
        display: flex;
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
        color: white;
        font-size: 28px;
        text-decoration: none;
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

    .employee-content {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
    }

    .file-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
    }

    .file-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        background-color: #fff;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
    }

    .file-card:hover {
        transform: scale(1.05);
    }

    .media-preview {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 4px;
    }

    .file-info {
        text-align: center;
        margin-top: 10px;
    }

    .file-actions {
        margin-top: 10px;
        display: flex;
        justify-content: center;
        gap: 10px;
    }
</style>
@endsection
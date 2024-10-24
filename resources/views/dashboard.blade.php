@extends('layouts.dashboard') <!-- Menggunakan layout khusus dashboard -->

@section('title', 'Dashboard')

@section('content')
<div class="header">
    <h2>Dashboard</h2>
    <button class="add-folder-btn btn btn-outline-secondary">Add Folder</button>
</div>
<div class="file-grid mt-4">
    <div class="file-card">
        <img src="{{ asset('images/foto-icon.png') }}" alt="Foto" class="mb-2" />
        <p class="fw-bold">Foto</p>
        <span class="text-muted">20Mb</span><br>
        <span class="text-muted">20/10/2024</span>
    </div>
    <div class="file-card">
        <img src="{{ asset('images/pdf-icon.png') }}" alt="File" class="mb-2" />
        <p class="fw-bold">File</p>
        <span class="text-muted">20Mb</span><br>
        <span class="text-muted">20/10/2024</span>
    </div>
</div>

<!-- Styling khusus untuk dashboard -->
<style>
    .file-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-top: 20px;
    }

    .file-card {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .file-card img {
        width: 50px;
        height: auto;
        margin-bottom: 10px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
</style>
@endsection
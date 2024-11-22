@extends('layouts.dashboard') <!-- Menggunakan layout khusus dashboard -->

@section('title', 'Dashboard')

@section('content')
<!-- Navbar -->
@include('layouts.navbar')

<!-- Layout Sidebar dan Konten -->
<div class="main-layout">
    @include('layouts.sidebar')
    <!-- Konten Dashboard -->
    <div class="dashboard-content">
        <div class="header d-flex align-items-center justify-content-between">
            <h2 class="mb-0">Dashboard</h2>
            <div class="tools d-flex align-items-center">
                <div class="layout-tools d-flex align-items-center">
                    <button class="btn btn-outline-secondary grid-layout active me-2" title="Grid Layout">
                        <span class="material-icons">grid_view</span>
                    </button>
                    <button class="btn btn-outline-secondary list-layout" title="List Layout">
                        <span class="material-icons">view_list</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- File Grid -->
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
    </div>
</div>

<!-- Styling Khusus -->
<style>
    .main-layout {
        display: flex;
        width: 100vw;
        height: 100vh;
    }

    /* Konten Dashboard */
    .dashboard-content {
        flex: 1;
        background-color: #f1f1f1;
        padding: 20px;
        overflow-y: auto;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .file-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
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
</style>
<script>
    // Semua script toggle layout
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
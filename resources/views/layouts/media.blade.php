<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - File Manager</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=grid_view" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=view_list" />

    <!-- Tambahkan di dalam <head> jika belum ada -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        /* Global reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body,
        html {
            height: 100%;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            display: flex;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .main-layout {
            display: flex;
            width: 100vw;
            height: 100vh;
        }

        /* Sidebar styling */
        .sidebar {
            width: 70px;
            background-color: white;
        }

        /* Main content area */
        .employee-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        /* Filter buttons styling */
        .btn {
            margin-right: 10px;
        }

        .btn-primary,
        .btn-success {
            font-size: 16px;
        }

        .filter-buttons .btn-filter {
            margin-right: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            color: #495057;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .filter-buttons .btn-filter.active {
            background-color: #007bff;
            color: #fff;
        }

        /* File grid layout */
        .file-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        /* File card container */
        .file-card {
            width: 150px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .file-card:hover {
            transform: scale(1.05);
        }

        .file-info {
            padding: 10px;
        }

        .media-preview {
            width: 100%;
            height: auto;
            max-height: 120px;
            object-fit: cover;
        }

        /* Image container for media preview */
        .image-container {
            width: 100%;
            aspect-ratio: 4 / 3;
            /* Set aspect ratio for consistent display */
            overflow: hidden;
        }

        .media-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Dropdown Container */
        .custom-toggle {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 20px;
            color: #888;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            padding: 10px 0;
            z-index: 20;
            min-width: 150px;
        }

        .dropdown-menu button {
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            padding: 8px 15px;
            font-size: 14px;
            cursor: pointer;
            color: #333;
        }

        .dropdown-menu button:hover {
            background-color: #f5f5f5;
        }

        .dropdown-menu.show {
            display: block;
        }

        .folder-card .dropdown {
            position: absolute;
            top: 5px;
            /* Sesuaikan dengan kebutuhan */
            right: 5px;
            z-index: 10;
        }


        /* File info section */
        .file-info {
            padding: 10px;
            text-align: center;
        }

        .file-info p {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Zoom slider container */
        .zoom-slider-container {
            display: flex;
            align-items: center;
        }

        .zoom-slider-button {
            background: none;
            border: none;
            margin: 0 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .zoom-slider {
            width: 100px;
            margin: 0 10px;
        }

        /* Menambahkan gaya untuk folder */
        .folder-card {
            width: 150px;
            height: 150px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            margin: 10px;
            position: relative;
            /* Menjadikan posisi dropdown mengikuti card */
        }

        .folder-card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .folder-icon {
            font-size: 50px;
            color: #ffc107;
            /* Warna kuning untuk folder */
            margin-bottom: 10px;
        }

        .folder-link {
            text-decoration: none;
            color: inherit;
        }

        .folder-name {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            word-wrap: break-word;
        }

        /* Modal Styling */
        .modal-content {
            border-radius: 8px;
        }

        .modal-header {
            background-color: #f8f9fa;
        }

        .modal-body {
            padding: 20px;
        }

        /* Subfolder List */
        .subfolder-list {
            margin-top: 20px;
        }

        .subfolder-list a {
            font-size: 16px;
            text-decoration: none;
            color: #007bff;
        }

        .subfolder-list a:hover {
            text-decoration: underline;
        }

        /* File List */
        .file-list {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .file-list div {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            width: 200px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .file-list div:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .file-icon {
            font-size: 48px;
            color: #888;
        }

        /* Form Styling */
        .form-group label {
            font-size: 14px;
            font-weight: bold;
        }

        .form-control {
            font-size: 14px;
            padding: 10px;
            margin-top: 5px;
        }

        .media-preview {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .media-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .media-item img {
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>


    @stack('styles')
</head>

<body>
    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
<script>
    function renameFolder(folderId) {
        // Dapatkan elemen form
        const form = document.getElementById('renameFolderForm');

        // Atur action URL form sesuai folder yang akan di-rename
        form.action = `/media/folder/${folderId}/rename`; // Endpoint untuk rename

        // Tampilkan modal rename folder
        const renameModal = new bootstrap.Modal(document.getElementById('renameFolderModal'));
        renameModal.show();
    }

    function shareFolder(folderId) {
        // Set URL share folder (sesuaikan endpoint Anda)
        const link = `${window.location.origin}/folder/${folderId}/share`;

        // Set link ke input di modal
        document.getElementById('shareFolderLink').value = link;

        // Tampilkan modal
        const shareModal = new bootstrap.Modal(document.getElementById('shareFolderModal'));
        shareModal.show();
    }

    function copyToClipboard() {
        // Salin teks dari input ke clipboard
        const linkInput = document.getElementById('shareFolderLink');
        linkInput.select();
        linkInput.setSelectionRange(0, 99999); // Untuk perangkat seluler
        navigator.clipboard.writeText(linkInput.value);

        // Beri notifikasi
        alert('Link copied to clipboard!');
    }

    function deleteFolder(folderId) {
        // Dapatkan elemen form
        const form = document.getElementById('deleteFolderForm');

        // Atur action URL form sesuai folder yang akan dihapus
        form.action = `/media/folder/${folderId}/delete`; // Endpoint untuk delete

        // Tampilkan modal delete folder
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteFolderModal'));
        deleteModal.show();
    }
</script>

</html>
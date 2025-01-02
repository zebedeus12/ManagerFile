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

        h5 {
            padding-left: 20px;
            padding-top: 10px;
        }

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
            margin-left: 20px;
            /* Jarak tambahan dari kiri agar terlihat lebih rapi */
            margin-right: 20px;
        }

        .main-layout {
            display: flex;
            width: 100vw;
            height: 100vh;
            position: relative;
            overflow: hidden;
        }

        /* Sidebar styling */
        .sidebar {
            width: 240px;
            /* Lebar default sidebar */
            background-color: #ffffff;
            transition: width 0.3s ease;
            /* Animasi transisi */
            z-index: 1000;
        }

        .sidebar.collapsed {
            width: 70px;
            /* Sidebar dalam mode collapse */
        }

        .sidebar.collapsed~.employee-content {
            margin-left: 70px;
        }

        /* Main content area */
        .employee-content {
            flex: 1;
            margin-left: 240px;
            /* Jarak default konten dari sidebar */
            padding: 20px;
            transition: margin-left 0.3s ease;
            /* Transisi saat sidebar berubah */
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
            margin-left: 20px;
            /* Jarak dari kiri */
            margin-right: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 20px;
            transition: all 0.3s ease;
            /* Efek transisi halus */
        }

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

        .content-container {
            background-color: #ffffff;
            min-height: 100vh;
            padding: 25px;
            overflow-y: auto;
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
        .dropdown {
            z-index: 10;
            position: absolute;
        }

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
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 15px;
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .folder-card:hover {
            transform: scale(1.05);
            /* box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); */
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
            cursor: pointer;
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

        .media-container {
            position: relative;
            overflow: hidden;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
        }

        .audio-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .audio-icon {
            cursor: pointer;
            color: #4CAF50;
            transition: transform 0.3s;
        }

        .audio-icon:hover {
            transform: scale(1.2);
        }

        .audio-player {
            width: 100%;
            height: 40px;
            display: none;
        }

        .audio-container .audio-player {
            display: block;
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

    function deleteMedia(mediaId) {
        if (confirm('Are you sure you want to delete this media?')) {
            const form = document.createElement('form');
            form.action = `/media/${mediaId}`;
            form.method = 'POST';
            form.style.display = 'none';

            // Create CSRF token input
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Create method field for DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            document.body.appendChild(form);
            form.submit();
        }
    }

    function handleMediaClick(mediaId, mediaUrl, mediaType) {
        // Hentikan semua audio dan video yang sedang diputar
        document.querySelectorAll('audio, video').forEach(media => {
            media.pause();
            media.currentTime = 0;
        });

        // Periksa jenis media yang diklik
        if (mediaType.startsWith('audio/')) {
            const audioElement = document.getElementById(`audio-${mediaId}`);
            audioElement.play();
        } else if (mediaType.startsWith('video/')) {
            const videoElement = document.getElementById(`video-${mediaId}`);
            videoElement.play();
        } else if (mediaType.startsWith('image/')) {
            // Buka gambar dalam modal
            openImageModal(mediaUrl);
        }
    }

    function openImageModal(imageUrl) {
        const modal = document.createElement('div');
        modal.innerHTML = `
            <div style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); display:flex; justify-content:center; align-items:center; z-index:1000;">
                <img src="${imageUrl}" style="max-width:90%; max-height:90%;">
                <button onclick="this.parentNode.remove()" style="position:absolute; top:20px; right:20px; background:red; color:white; border:none; font-size:20px; cursor:pointer;">&times;</button>
            </div>
        `;
        document.body.appendChild(modal);
    }
</script>

</html>
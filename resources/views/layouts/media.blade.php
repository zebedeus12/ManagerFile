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
            background-image: url('{{ asset('img/dashboard.jpeg') }}');
            box-sizing: border-box;
            background-size: cover;
            /* Menyesuaikan gambar dengan ukuran layar */
            background-position: center;
            /* Menjaga gambar tetap di tengah */
            background-repeat: no-repeat;
            /* Transisi saat sidebar berubah */
            max-height: calc(100vh - 80px);
            overflow-y: auto;
        }

        .content-container {
            background-color: #ffffff;
            min-height: 100vh;
            padding: 25px;
            overflow-y: auto;
        }

        /* ========== SEARCH BAR STYLING ========== */
        .search-container {
            display: flex;
            align-items: center;
            background-color: #b3e6b1;
            border-radius: 300px;
            padding: 14px 25px;
            width: 200px;
        }

        .search-input {
            border: none;
            outline: none;
            background: transparent;
            flex: 1;
            font-size: 14px;
            color: #388e3c;
        }

        /* ========== SEARCH BUTTON ========== */
        .search-btn {
            background-color: #b3e6b1;
            border: none;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.3s ease;
        }

        .search-btn i {
            color: #4caf50;
            font-size: 30px;
        }

        .search-btn:hover {
            background-color: #c8e6c9;
        }

        /* ========== CUSTOM BUTTONS (Add Folder & Grid View) ========== */
        .btn-custom {
            background-color: #b3e6b1;
            border: none;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.3s ease;
        }

        .btn-custom i {
            color: #66bb6a;
            font-size: 30px;
        }

        .btn-custom:hover {
            background-color: #c8e6c9;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .search-container {
                width: 200px;
            }
        }

        @media (max-width: 576px) {
            .search-container {
                width: 150px;
            }
        }

        /* ========== MEDIA ========== */
        .media-container {
            width: 100%;
            aspect-ratio: 4 / 3;
            overflow: hidden;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .media-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
        }

        .media-icon {
            font-size: 32px;
            color: #555;
            display: block;
            margin: 0 auto 5px;
        }

        .audio-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .audio-icon {
            font-size: 48px;
            color: #4CAF50;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .audio-icon:hover {
            transform: scale(1.2);
        }

        .audio-player {
            display: none;
            width: 100%;
            margin-top: 10px;
        }

        .audio-container:hover .audio-player {
            display: block;
            width: 100%;
        }

        .video-player {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            border: 1px solid #ddd;
        }


        /* Dropdown Container */
        .dropdown {
            z-index: 10;
            position: relative;
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
            right: 5px;
            z-index: 10;
        }


        /* File */
        .file-grid {
            margin-left: 20px;
            margin-right: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 20px;
            transition: all 0.3s ease;
        }

        .file-card {
            width: 150px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
            transition: transform 0.3s ease;
            padding: 10px;
        }

        .file-card:hover {
            transform: scale(1.05);
        }

        .file-info {
            text-align: center;
        }

        .file-info p {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin: 5px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

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
            font-size: 32px;
            color: #555;
            margin-bottom: 5px;
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

        /* folder dan subfolder */
        .folder-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
            padding: 0 20px;
        }

        .folder-card {
            background-color: #e8f5e9;
            border-radius: 12px;
            padding: 15px;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .folder-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            width: 100%;
        }

        .folder-card:hover {
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
            transform: translateY(-3px);
        }

        .folder-icon {
            font-size: 48px;
            color: #43a047;
            margin-bottom: -20px;
        }

        .folder-link {
            color: #333;
            font-weight: bold;
            font-size: 14px;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .folder-name {
            font-size: 14px;
            /* Nama folder */
            font-weight: bold;
            color: #1b5e20;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            max-width: 100%;
            /* Agar teks tidak melebar */
        }

        .folder-name:hover {
            color: #2e7d32;
        }

        .folder-meta {
            font-size: 12px;
            color: #616161;
            margin-top: 5px;
        }

        .folder-description {
            font-style: italic;
            color: #757575;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
            max-width: 200px;
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

        /* Modal pop up*/
        .modal-content {
            border-radius: 8px;
        }

        .modal-header {
            background-color: #f8f9fa;
        }

        .modal-body {
            padding: 20px;
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

        /* Table Styles */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .table th,
        .table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #4CAF50;
            color: white;
            text-align: left;
        }

        .table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table tr:hover {
            background-color: #d1e7dd;
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
        // Panggil endpoint untuk mengecek isi folder
        fetch(`/media/folder/check/${folderId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'error') {
                    // Jika folder tidak kosong, tampilkan modal peringatan
                    showWarningModal(data.message);
                } else {
                    // Jika folder kosong, tampilkan modal konfirmasi penghapusan
                    const deleteForm = document.getElementById('deleteFolderForm');
                    deleteForm.action = `/media/folder/${folderId}`;
                    const deleteModal = new bootstrap.Modal(document.getElementById('deleteFolderModal'));
                    deleteModal.show();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function showWarningModal(message) {
        const warningModal = document.getElementById('warningModal');
        const warningMessage = document.getElementById('warningMessage');
        warningMessage.textContent = message;
        const modalInstance = new bootstrap.Modal(warningModal);
        modalInstance.show();
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
            // Buka video dalam modal dan tampilkan preview
            openVideoModal(mediaUrl, mediaId);
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

    function openAudioModal(audioUrl) {
        const modal = document.createElement('div');
        modal.innerHTML = `
        <div style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); display:flex; justify-content:center; align-items:center; z-index:1000;">
            <audio controls autoplay style="max-width:90%; max-height:90%;">
                <source src="${audioUrl}" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
            <button onclick="this.parentNode.remove()" style="position:absolute; top:20px; right:20px; background:red; color:white; border:none; font-size:20px; cursor:pointer;">&times;</button>
        </div>
    `;
        document.body.appendChild(modal);
    }

    function openVideoModal(videoUrl, mediaId) {
        const modal = document.createElement('div');
        modal.innerHTML = `
        <div style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); display:flex; justify-content:center; align-items:center; z-index:1000;">
            <div style="position:relative; max-width:90%; max-height:90%; display:flex; flex-direction:column; justify-content:center; align-items:center;">
                <video id="video-${mediaId}" controls poster="${videoUrl}" style="max-width:100%; max-height:100%; cursor: pointer;">
                    <source src="${videoUrl}" type="video/mp4">
                    Your browser does not support the video element.
                </video>
                <button onclick="this.parentNode.parentNode.remove()" style="position:absolute; top:20px; right:20px; background:red; color:white; border:none; font-size:20px; cursor:pointer;">&times;</button>
                            </div>
        </div>
    `;
        document.body.appendChild(modal);
    }

    function togglePlayPause(mediaId) {
        const video = document.getElementById(`video-${mediaId}`);
        const button = document.getElementById(`playPauseBtn-${mediaId}`);

        if (video.paused) {
            video.play();
            button.textContent = 'Pause'; // Change button text to "Pause"
        } else {
            video.pause();
            button.textContent = 'Play'; // Change button text to "Play"
        }
    }
</script>

</html>
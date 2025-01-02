<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - File Manager</title>

    {{-- tampilan icon file --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

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

    <!-- Custom CSS -->
    <style>
        /* Mengatur Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body,
        html {
            height: 100%;
            width: 100%;
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
            /* Mencegah scroll di body */
            background-color: #f5f5f5;
        }

        /* Wrapper Utama */
        .main-layout {
            display: flex;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 250px; /* Lebar sidebar tetap */
            position: fixed;
            height: 100vh;
            background-color: white;
            box-shadow: 2px 0px 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }


        /* Kontainer Konten Utama */
        .container {
            transition: none; /* Hilangkan efek transisi */
        }

        /* Content Container Styling */
        .content-container {
            margin-left: 250px; /* Sesuai dengan lebar sidebar */
            padding: 25px;
            width: calc(100% - 250px); /* Sisa lebar layar */
            background-color: #ffffff;
            min-height: 100vh;
            overflow-y: auto;
        }

        /* Tombol Tambah Folder/File */
        .add-folder,
        .add-file {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .add-folder:hover,
        .add-file:hover {
            background-color: #0056b3;
        }


        .header .buttons {
            margin-left: auto;
            /* Menarik tombol Add Folder ke kanan */
        }

        /* Dropdown Container */
        .dropdown {
            z-index: 10;
            position: absolute;
            display: inline-block;
            top: 10px;
            right: 10px;
        }

        .dropdown-toggle {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 20px;
            color: #000;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .dropdown-toggle::before {
            display: none;
            content: none;
        }

        .dropdown-toggle::after {
            display: none !important;
            content: none !important;
        }

        .custom-toggle {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 20px;
            color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            width: 40px;
            height: 40px;
        }

        .dropdown-menu {
            z-index: 20;
            position: absolute;
            display: none;
            top: 40px;
            right: 0;
            background-color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            padding: 10px 0;
            width: 150px;
            pointer-events: auto;
        }

        .dropdown-menu button {
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            padding: 8px 15px;
            font-size: 14px;
            cursor: pointer;
        }

        .dropdown-menu button:hover {
            background-color: #f5f5f5;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        /* style  */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            width: 400px;
            position: relative;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-secondary {
            background-color: #ccc;
            margin-right: 10px;
        }

        .btn-danger {
            background-color: #d9534f;
            color: white;
        }

        .icon-container {
            text-align: center;
            flex-shrink: 0;
        }

        /* Folder */
        .folder-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            /* Card responsif */
            gap: 15px;
            /* Jarak antar card */
            margin-top: 20px;
            /* Jarak atas */
        }

        .folder-card {
            background-color: #f0fdf4;
            /* Hijau pias, hampir putih */
            border-radius: 8px;
            /* Sudut melengkung */
            padding: 10px 15px;
            /* Padding dalam card */
            display: flex;
            justify-content: space-between;
            /* Jarak antara ikon + teks dengan dropdown */
            align-items: center;
            position: relative;
            transition: all 0.3s ease;
            /* Animasi hover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Bayangan lembut */
        }

        .folder-card:hover {
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
            /* Bayangan lebih besar saat hover */
            transform: translateY(-3px);
            /* Efek naik saat hover */
        }

        .folder-icon {
            font-size: 40px;
            /* Ukuran ikon */
            color: #4caf50;
            /* Warna hijau untuk ikon */
            margin-right: 10px;
            /* Jarak dengan teks */
        }

        .folder-link {

            /* Hilangkan garis bawah */
            color: #333;
            /* Warna teks menjadi abu-abu gelap */
            font-weight: bold;
            /* Teks lebih tebal */
            font-size: 14px;
            /* Ukuran font teks */
        }

        .folder-link:hover {
            color: #2d6a4f;
            /* Warna teks saat hover */
        }

        .folder-card .dropdown {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }

        .file-info {
            text-decoration: none;
        }

        /* File Card */
        .file-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            /* Responsif */
            gap: 15px;
            /* Jarak antar card */
            margin-top: 20px;
        }

        .file-card {
            background-color: #f0fdf4;
            /* Hijau pias, hampir putih */
            border-radius: 8px;
            /* Sudut melengkung */
            width: 300px;
            /* Lebar card */
            color: #333;
            /* Warna teks */
            font-family: 'Poppins', sans-serif;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Bayangan lembut */
            display: flex;
            flex-direction: column;
            /* Vertikal */
            gap: 10px;
            /* Jarak antar elemen dalam card */
            position: relative;
            margin: 10px;
            /* Jarak antar card */
        }

        .file-header {
            display: flex;
            align-items: center;
            /* Posisikan ikon dan nama file sejajar secara vertikal */
            gap: 8px;
            /* Sebar antara nama file dan dropdown */
        }

        .file-icon i {
            font-size: 24px;
            /* Ukuran ikon */
        }

        .file-name {
            font-size: 14px;
            /* Ukuran teks nama file */
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            /* Potong teks yang terlalu panjang */
            max-width: 200px;
            /* Batasi lebar teks */
            margin: 0;
        }

        .file-preview img {
            width: 100%;
            /* Lebar penuh */
            height: 150px;
            /* Tinggi tetap */
            object-fit: cover;
            /* Crop gambar agar pas */
            border-radius: 4px;
            /* Sedikit lengkung di preview */
        }

        .file-footer {
            font-size: 12px;
            /* Ukuran teks lebih kecil */
            color: #ccc;
            /* Warna teks lebih redup */
            display: flex;
            align-items: center;
            gap: 5px;
            /* Jarak antar elemen */
        }

        .file-footer i {
            color: #999;
            /* Warna ikon lebih redup */
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
    //RENAME
    function openRenameModal(folderId, currentName) {
        const modal = document.getElementById("renameFolderModal");
        modal.style.display = "block";

        // Set action pada form
        const form = document.getElementById("renameFolderForm");
        form.action = `/folder/rename/${folderId}`;

        // Isi input dengan nama folder saat ini
        document.getElementById("newFolderName").value = currentName;
    }

    function closeRenameModal() {
        const modal = document.getElementById("renameFolderModal");
        modal.style.display = "none";
    }

    //DELETE
    function openDeleteModal(folderId) {
        const modal = document.getElementById("deleteModal");
        modal.style.display = "block";

        // Set form action untuk menghapus folder
        const deleteForm = document.getElementById("deleteForm");
        deleteForm.action = `/folder/delete/${folderId}`;
    }

    function closeDeleteModal() {
        const modal = document.getElementById("deleteModal");
        modal.style.display = "none";
    }

    //COPY
    function openCopyModal(folderId) {
        const modal = document.getElementById("copyModal");
        modal.style.display = "block";

        // Set form action untuk copy folder
        const copyForm = document.getElementById("copyForm");
        copyForm.action = `/folder/copy/${folderId}`;
    }

    function closeCopyModal() {
        const modal = document.getElementById("copyModal");
        modal.style.display = "none";
    }

    //SHARE
    function openShareModal(folderId, shareUrl) {
        // Show the modal (if you have a modal to show the share URL)
        const modal = document.getElementById("shareModal");
        modal.style.display = "block";

        // Show the URL in the modal (optional)
        const shareUrlInput = document.getElementById("shareUrlInput");
        shareUrlInput.value = shareUrl;

        // Add an event listener for the "Copy Link" button
        const copyButton = document.getElementById("copyLinkButton");
        copyButton.addEventListener('click', function () {
            copyToClipboard(shareUrlInput.value);
        });
    }

    // Function to copy text to clipboard
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function () {
            alert('Link copied to clipboard!');
        }, function (err) {
            alert('Failed to copy the link: ' + err);
        });
    }

</script>

</html>
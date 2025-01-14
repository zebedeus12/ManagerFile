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
            width: 250px;
            position: fixed;
            height: 100vh;
            background-color: white;
            box-shadow: 2px 0px 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }


        /* Kontainer Konten Utama */
        .container {
            transition: none;
            /* Hilangkan efek transisi */
            margin-left: 250px;
            /* Sesuai dengan lebar sidebar */
            width: calc(100% - 250px);
            /* Sisa lebar layar */
            padding: 25px;
        }

        /* Content Container Styling */
        .content-container {
            margin-left: 250px;
            padding: 25px;
            width: calc(100% - 250px);
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

        /* folder dan subfolder */
        .folder-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
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

        .folder-card:hover {
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
            transform: translateY(-3px);
        }

        .folder-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            width: 100%;
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

        /* File Card */
        .file-info {
            text-decoration: none;
        }

        .file-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .file-card {
            background-color: #f0fdf4;
            border-radius: 8px;
            width: 300px;
            color: #333;
            font-family: 'Poppins', sans-serif;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 10px;
            position: relative;
            margin: 10px;
        }

        .file-header {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .file-icon i {
            font-size: 24px;

        }

        .file-name {
            font-size: 14px;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
            margin: 0;
        }

        .file-preview img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
        }

        .file-footer {
            font-size: 12px;
            color: #ccc;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .file-footer i {
            color: #999;
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
    function toggleMenu(button, event) {
        // Mencegah aksi default (navigasi ke folder) dan propagasi event
        event.preventDefault();
        event.stopPropagation();

        // Tutup semua dropdown lainnya
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (menu !== button.nextElementSibling) {
                menu.classList.remove('show');
            }
        });

        // Toggle dropdown saat ini
        const menu = button.nextElementSibling;
        menu.classList.toggle('show');
    }

    // Tutup dropdown saat klik di luar
    document.addEventListener('click', function () {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
    });

    //RENAME
    function openRenameModal(folderId, currentName) {
        event.preventDefault();
        event.stopPropagation();

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
        event.preventDefault();
        event.stopPropagation();
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
        event.preventDefault();
        event.stopPropagation();
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
        event.preventDefault();
        event.stopPropagation();
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
        event.preventDefault();
        event.stopPropagation();
        navigator.clipboard.writeText(text).then(function () {
            alert('Link copied to clipboard!');
        }, function (err) {
            alert('Failed to copy the link: ' + err);
        });
    }

</script>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - File Manager</title>

    {{-- tampilan icon file --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

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
            margin-left: 250px;
            width: calc(100% - 250px);
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
            overflow-x: hidden;
            background-image: url('{{ asset('img/dashboard.jpeg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            max-height: calc(100vh - 80px);
            padding-bottom: 80px;
        }

        /* Tombol Tambah Folder/File */
        .buttons {
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .buttons .btn {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: #b3e6b1;
            color: #4caf50;
            border: none;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .buttons .btn:hover {
            background-color: #a3d6a1;
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        /* Rounded Buttons */
        .btn.rounded-circle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            padding: 0;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #b3e6b1;
            color: #4caf50;
        }

        .btn.rounded-circle:hover {
            background-color: #a3d6a1;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* Search Input Styling */
        .input-group .form-control {
            height: 45px;
            border: none;
            background-color: #d8f5d5;
            color: #6b8e62;
            border-radius: 50px;
            padding-left: 20px;
        }

        .input-group .form-control:focus {
            box-shadow: none;
            outline: none;
        }

        /* Search Button Styling */
        .input-group .btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: #b3e6b1;
            color: #4caf50;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .input-group .btn:hover {
            background-color: #a3d6a1;
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

        /* folder dan subfolder */
        .folder-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
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
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .file-card {
            background-color: #f0fdf4;
            border-radius: 8px;
            width: 100%;
            color: #333;
            font-family: 'Poppins', sans-serif;
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 5px;
            position: relative;
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
            font-size: 13px;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
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

        /* Table List */
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

        .button {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #d4f8d4;
            color: #4caf50;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #b2e8b2;
        }

        .button i {
            font-size: 16px;
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
    //DROPDOWN
    function toggleMenu(button, event) {
        event.preventDefault();
        event.stopPropagation();
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (menu !== button.nextElementSibling) {
                menu.classList.remove('show');
            }
        });
        const menu = button.nextElementSibling;
        menu.classList.toggle('show');
    }

    document.addEventListener('click', function () {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
    });

    //MODAL ADD FOLDER
    document.addEventListener("DOMContentLoaded", function () {
        var addFolderModal = new bootstrap.Modal(document.getElementById("addFolderModal"));

        document.getElementById("openAddFolderModal").addEventListener("click", function () {
            addFolderModal.show();
        });

        document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function (btn) {
            btn.addEventListener("click", function () {
                addFolderModal.hide();
            });
        });
    });

    // RENAME
    function openRenameModal(folderId, currentName) {
        event.preventDefault();
        event.stopPropagation();

        const modal =
            new bootstrap.Modal(document.getElementById("renameFolderModal"));
        modal.show();

        // Set action on the form
        const form = document.getElementById("renameFolderForm");
        form.action = `{{ url('folder/rename') }}/${folderId}`;

        // Set the current folder name in the input field
        document.getElementById("newFolderName").value = currentName;
    }

    // Handle dragging files/folders
    let draggedItemId = null;

    function startDrag(event, itemId) {
        draggedItemId = itemId;
        event.dataTransfer.setData('text', itemId);
    }

    const trashArea = document.getElementById('trashArea');

    trashArea.addEventListener('dragover', function (event) {
        event.preventDefault();
    });

    trashArea.addEventListener('drop', function (event) {
        event.preventDefault();

        // Tampilkan modal konfirmasi penghapusan
        openDeleteModal(draggedItemId);
    });

    function deleteItem(itemId) {
        const url = `/folder/${itemId}/delete`; // Modify according to your folder deletion route

        fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Item deleted successfully');
                    location.reload();
                } else {
                    alert('Failed to delete item');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
    }

    // DELETE
    function openDeleteModal(folderId) {
        event.preventDefault();
        event.stopPropagation();

        const modal = new bootstrap.Modal(document.getElementById("deleteModal"));
        modal.show();

        // Set the action for the delete form
        const deleteForm = document.getElementById("deleteForm");
        deleteForm.action = `{{ url('folder/delete') }}/${folderId}`;
    }

    // SHARE
    function openShareModal(folderId, shareUrl) {
        event.preventDefault();
        event.stopPropagation();

        const modal = new bootstrap.Modal(document.getElementById("shareModal"));
        modal.show();

        const shareUrlInput = document.getElementById("shareUrlInput");
        shareUrlInput.value = shareUrl;

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

    // Function to open the rename modal for file
    function openRenameFileModal(fileId, fileName) {
        event.preventDefault();
        event.stopPropagation();

        // Get the modal element
        const modal = new bootstrap.Modal(document.getElementById('renameFileModal'));
        modal.show();

        // Set the action URL for the form
        const form = document.getElementById('renameFileForm');
        form.action = "{{ url('file/rename') }}/" + fileId;

        // Set the current file name in the input field
        document.getElementById('newFileName').value = fileName;
    }

    // Function to open the share modal for file
    function openShareFileModal(shareUrl) {
        event.preventDefault();
        event.stopPropagation();

        // Get the modal element
        const modal = new bootstrap.Modal(document.getElementById('shareFileModal'));
        modal.show();

        // Set the share URL in the input field
        const shareUrlInput = document.getElementById('shareFileUrlInput');
        shareUrlInput.value = shareUrl;

        // Copy URL button functionality
        const copyButton = document.getElementById('copyFileLinkButton');
        copyButton.addEventListener('click', function () {
            copyToClipboard(shareUrlInput.value);
        });
    }

    // Function to open the delete modal for file
    function openDeleteFileModal(fileId) {
        const form = document.getElementById('deleteFileForm');
        form.action = "{{ url('files/delete') }}/" + fileId; // Set action ke URL delete file
        const modal = new bootstrap.Modal(document.getElementById('deleteFileModal'));
        modal.show(); // Tampilkan modal
    }
</script>

</html>
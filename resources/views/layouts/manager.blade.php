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

    <!-- Custom CSS -->
    <style>
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
            margin: 0;
            padding: 0;
            display: flex;
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

        /*container*/
        .container {
            flex: 1;
            background-color: #f1f1f1;
            padding: 20px;
            overflow-y: auto;
        }

        /* File Manager Grid */
        .content-container {
            flex-grow: 1;
            padding: 20px;
            background-color: #ffffff;
            margin-left: 50px;
            min-height: 100vh;
        }

        .file-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .file-card,
        .sub-folder-card {
            position: relative;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            text-align: center;
            transition: transform 0.2s;
        }

        .file-card:hover,
        .sub-folder-card:hover {
            transform: translateY(-5px);
        }

        .icon-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 10px;
            text-decoration: none;
        }

        .folder-icon {
            font-size: 48px;
            color: #FFB400;
        }

        .file-icon {
            width: 40px;
            height: 40px;
        }

        .file-info {
            font-size: 14px;
            color: #6c757d;
        }

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
            position: absolute;
            display: inline-block;
            top: 10px;
            right: 10px;
        }

        /* Tombol Titik Tiga */
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

        /* Gaya Menu Dropdown */
        .dropdown-menu {
            z-index: 1050;
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

</script>

</html>
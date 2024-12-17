<div class="sidebar" id="sidebar">
    <!-- Header Sidebar -->
    <div class="sidebar-header">
        <h3 class="logo">BBSPJIS</h3>
    </div>

    <!-- Menu Navigasi -->
    <nav class="menu">
        <ul class="list-unstyled">
            <li>
                <a href="{{ route('employees.index') }}" class="menu-item">
                    <span class="material-icons">admin_panel_settings</span>
                    <span class="menu-text">Employees</span>
                </a>
            </li>
            <li>
                <a href="{{ route('file.index') }}" class="menu-item">
                    <span class="material-icons">folder</span>
                    <span class="menu-text">Files</span>
                </a>
            </li>
            <li>
                <a href="{{ route('media.index') }}" class="menu-item">
                    <span class="material-icons">perm_media</span>
                    <span class="menu-text">Media</span>
                </a>
            </li>
                <!-- Tombol Logout -->
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="menu-item logout-btn">
                        <span class="material-icons">logout</span>
                        <span class="menu-text">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</div>

<!-- CSS untuk Sidebar -->
<style>
    /* Sidebar Default (Collapsed) */
    .sidebar {
        width: 100px;
        height: 100vh;
        background-color: #188A98;
        color: white;
        transition: width 0.3s ease;
        overflow: hidden;
        position: fixed;
    }

    /* Sidebar saat dihover */
    .sidebar:hover {
        width: 260px;
    }

    /* Logo Styling */
    .logo {
        font-size: 20px;
        text-align: center;
        padding: 20px 0;
        display: none; /* Sembunyikan logo saat sidebar collapsed */
    }

    .sidebar:hover .logo {
        display: block; /* Tampilkan logo saat sidebar dihover */
    }

    /* Menu Item Styling */
    .menu ul {
        padding: 0;
        list-style: none;
    }

    .menu-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px 30px;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        transition: background 0.3s;
    }

    .menu-item:hover {
        background-color: white;
        color: #188A98;
    }

    /* Menu Text (Judul Menu) */
    .menu-text {
        display: none;
        transition: opacity 0.3s ease;
    }

    /* Tampilkan judul menu saat sidebar dihover */
    .sidebar:hover .menu-text {
        display: inline;
        opacity: 1;
    }

    /* Icon Styling */
    .material-icons {
        font-size: 26px;
        text-align: center;
    }

    /* Logout Button */
    .logout-btn {
        background: none;
        border: none;
        color: white;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 15px;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }

    .logout-btn:hover {
        background-color: white;
        color: #188A98;
    }
</style>

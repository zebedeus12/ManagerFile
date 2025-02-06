<div class="sidebar" id="sidebar">
    <!-- Header Sidebar -->
    <div class="sidebar-header">
        <img src="{{ asset('img/logo.png') }}" alt="Logo">
    </div>

    <!-- Menu Navigasi -->
    <nav class="menu">
        <ul class="list-unstyled">
            @if(auth()->user()->role === 'super_admin')
                <li>
                    <a href="{{ route('employees.index') }}" class="menu-item">
                        <span class="material-icons">admin_panel_settings</span>
                        <span class="menu-text">Employees</span>
                    </a>
                </li>
            @endif
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
        width: 260px;
        /* Lebar sidebar tetap penuh */
        height: 100vh;
        background: linear-gradient(180deg, #4CAF50, #2E7D32);
        /* Gradasi hijau */
        /* Warna sidebar */
        color: white;
        position: fixed;
        transition: none;
        align-items: center;
        /* Tidak ada animasi hover */
    }

    /* Logo Styling */
    .logo {
        font-size: 20px;
        text-align: center;
        padding: 20px 0;
        display: block;
        /* Selalu tampilkan logo */
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
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.05);
        color: #fff;
    }

    /* Menu Text (Judul Menu) */
    .menu-text {
        display: inline;
        /* Selalu tampilkan judul menu */
        opacity: 1;
        margin-left: 10px;
        /* Tambahkan jarak agar lebih rapi */
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
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.05);
        color: white;
    }

    .sidebar-header {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        padding: 20px 0;
    }

    .sidebar-header img {
        width: 80px;
        /* Perbesar ukuran logo */
        height: 80px;
        border-radius: 50%;
        display: block;
        background-color: whitesmoke;
    }
</style>
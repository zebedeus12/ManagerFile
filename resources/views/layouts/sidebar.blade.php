<div class="sidebar">
    <nav class="menu">
        <ul class="list-unstyled">
            <li>
            <li>
                <a href="{{ route('employees.index') }}" class="icon-link">
                    <span class="material-icons">admin_panel_settings</span>
                </a>
            </li>

            </li>
            <li>
                <a href="{{ route('file.index') }}" class=" icon-link">
                    <span class="material-icons">folder</span>
                </a>
            </li>
            <li>
                <a href="{{ route('media.index') }}" class="icon-link">
                    <span class="material-icons">perm_media</span>
                </a>
            </li>
            <li>
                <!-- Tambahkan Logout Button di Sidebar -->
                <form action="{{ route('logout') }}" method="POST" class="d-flex justify-content-center mt-3">
                    @csrf
                    <button type="submit" class="btn btn-link icon-link" title="Logout">
                        <span class="material-icons">logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</div>

<style>
    .sidebar {
        width: 80px;
        height: 100vh;
        background: linear-gradient(180deg, #188A98, #5CCED1);
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 20px;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        border-right: 1px solid #e0e0e0;
    }

    .icon-link {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 60px;
        text-decoration: none;
        color: white;
        font-size: 28px;
        /* Ukuran ikon */
    }

    .icon-link:hover {
        background: none;
        /* Tidak ada efek hover */
    }


    .material-icons {
        font-size: 28px;
    }


    .menu ul {
        width: 100%;
        padding: 0;
        list-style: none;
    }

    .menu ul li {
        margin: 20px 0;
    }

    .icon-link:hover {
        background-color: #145d65;
    }

    .menu ul li a {
        color: #adb5bd;
        display: flex;
        justify-content: center;
        padding: 15px 15px;
        font-size: 24px;
        /* Adjust icon size */
    }

    .menu ul li a:hover {
        background-color: #1CAAB8;
        color: white;
    }
</style>
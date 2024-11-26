<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid align-items-center">
        <div class="d-flex align-items-center">
            <img src="{{ asset('img/logo.png') }}" alt="Logo"
                style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px;">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">BBSPJIS File Manager</a>
        </div>
        <div class="d-flex align-items-center user-info-notification">
            @if(Auth::check())
                <span class="fw-bold">{{ Auth::user()->nama_user }}</span>
            @else
                <span class="fw-bold">Guest</span>
            @endif
            <a href="#" class="notification-link me-3" title="Notifications" onclick="fetchNotifications()">
                <span class="material-icons">notifications</span>
                <span class="notification-count" id="notificationCount">0</span>
            </a>
            <div id="notificationDropdown" class="dropdown-menu" style="display: none; position: absolute; right: 0;">
                <div id="notificationList"></div>
            </div>
        </div>
    </div>
</nav>

<style>
    /*navbar*/
    .navbar {
        background-color: white;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        padding: 15px 20px;
        /* Perbesar padding untuk ukuran navbar */
        position: relative;
    }

    .navbar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.1);
        pointer-events: none;
        filter: blur(8px);
        opacity: 0.4;
        z-index: 0;
        /* Pastikan ini di belakang elemen navbar */
    }

    .navbar-brand {
        color: white;
        font-size: 1.5rem;
        /* Besarkan ukuran font logo */
    }

    .navbar a {
        color: #31511E;
        font-size: 1.1rem;
        /* Besarkan ukuran font link */
    }

    .navbar a:hover {
        color: #4CAF50;
    }

    .navbar .dropdown-item {
        color: #31511E;
    }

    .navbar .dropdown-item:hover {
        background-color: rgba(49, 81, 30, 0.1);
        /* Tambahkan efek hover untuk item dropdown */
    }

    /* notifikasi */
    .user-info-notification {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        /* Mengatur jarak antara nama pengguna dan notifikasi */
    }

    .user-info {
        text-align: right;
    }


    .notification-link {
        position: relative;
        color: #333;
        font-size: 24px;
        text-decoration: none;
        transition: color 0.3s;
    }

    .notification-link:hover {
        color: #188A98;
    }

    .notification-count {
        position: absolute;
        top: -5px;
        right: -10px;
        background-color: #ff5e5e;
        color: white;
        font-size: 12px;
        border-radius: 50%;
        padding: 2px 6px;
        font-weight: bold;
    }

    #notificationDropdown {
        background: white;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        padding: 10px;
        max-height: 200px;
        overflow-y: auto;
        width: 300px;
        z-index: 1000;
        position: absolute;
    }

    #notificationDropdown .dropdown-item {
        padding: 10px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
        color: #333;
    }

    #notificationDropdown .dropdown-item:last-child {
        border-bottom: none;
    }

    #notificationDropdown .dropdown-item:hover {
        background: #f5f5f5;
    }
</style>
<script>
    function fetchNotifications() {
        const dropdown = document.getElementById('notificationDropdown');
        const notificationList = document.getElementById('notificationList');

        // Tampilkan dropdown
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';

        // Ambil notifikasi dari server
        fetch('{{ route('notifications.get') }}')
            .then(response => response.json())
            .then(notifications => {
                notificationList.innerHTML = ''; // Hapus notifikasi lama
                if (notifications.length > 0) {
                    notifications.forEach(notification => {
                        const item = document.createElement('div');
                        item.classList.add('dropdown-item');
                        item.textContent = notification.message;
                        notificationList.appendChild(item);
                    });
                } else {
                    notificationList.innerHTML = '<div class="dropdown-item">Tidak ada notifikasi</div>';
                }

                // Update jumlah notifikasi
                document.getElementById('notificationCount').textContent = notifications.length;
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }

    // Tutup dropdown jika klik di luar
    window.addEventListener('click', function (e) {
        const dropdown = document.getElementById('notificationDropdown');
        if (!dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });
</script>
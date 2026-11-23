<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'SIM-BMN KEMENAG MINAHASA')</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <style>
        :root {
            /* Colors */
            --navy-dark: #0A1128;
            --navy-card: #0F172A;
            --blue-primary: #1E3A8A;
            --blue-light: #EFF6FF;
            --blue-accent: #3B82F6;
            --bg-gray: #F8FAFC;
            --border-light: #E2E8F0;
            
            --text-dark: #0F172A;
            --text-muted: #64748B;
            
            /* Status Colors */
            --success-bg: #D1FAE5;
            --success-text: #059669;
            --warning-bg: #FEF3C7;
            --warning-text: #D97706;
            --danger-bg: #FEE2E2;
            --danger-text: #DC2626;
            
            /* Dimensions */
            --bottom-nav-height: 70px;
            --sidebar-width: 260px;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-gray);
            color: var(--text-dark);
            -webkit-font-smoothing: antialiased;
            padding-bottom: var(--bottom-nav-height); /* Space for bottom nav on mobile */
        }

        /* --- MOBILE FIRST DESIGN --- */
        
        /* Top Header (Mobile) */
        .mobile-header {
            background-color: #FFFFFF;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-light);
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .header-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo-icon {
            width: 32px;
            height: 32px;
            background-color: var(--navy-dark);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        .header-title {
            line-height: 1.2;
        }
        .header-title-main {
            font-weight: 700;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .badge-ri {
            background-color: var(--border-light);
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 4px;
            color: var(--text-muted);
            font-weight: 600;
        }
        .header-subtitle {
            font-size: 11px;
            color: var(--text-muted);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--blue-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--navy-dark);
            position: relative;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .badge-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background-color: var(--danger-text);
            border-radius: 50%;
            border: 2px solid white;
        }

        .avatar-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--navy-dark);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Bottom Navigation Bar (Mobile) */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: var(--bottom-nav-height);
            background-color: #FFFFFF;
            border-top: 1px solid var(--border-light);
            display: flex;
            justify-content: space-around;
            align-items: center;
            z-index: 50;
            padding-bottom: env(safe-area-inset-bottom);
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 11px;
            font-weight: 500;
            flex: 1;
        }

        .nav-item.active {
            color: var(--navy-dark);
            font-weight: 600;
        }

        .nav-item i {
            font-size: 24px;
        }

        /* Elevated Center Button (Scan QR) */
        .nav-item-center {
            position: relative;
            top: -15px;
            background-color: var(--navy-dark);
            color: white;
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(10, 17, 40, 0.2);
            text-decoration: none;
        }

        .nav-item-center i {
            font-size: 28px;
            color: #38BDF8;
        }

        /* Desktop Sidebar (Hidden on Mobile) */
        .desktop-sidebar {
            display: none;
        }

        /* Mobile Menu Overlay */
        .mobile-menu-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0,0,0,0.5);
            z-index: 100;
            display: none;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .mobile-menu-overlay.active {
            display: block;
            opacity: 1;
        }
        .mobile-menu-content {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            background-color: white;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            padding: 24px;
            transform: translateY(100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            max-height: 80vh;
            overflow-y: auto;
        }
        .mobile-menu-overlay.active .mobile-menu-content {
            transform: translateY(0);
        }
        .mobile-menu-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 0;
            border-bottom: 1px solid var(--border-light);
            color: var(--text-dark);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }
        .mobile-menu-item:last-child {
            border-bottom: none;
        }
        .mobile-menu-item i {
            font-size: 20px;
            color: var(--blue-accent);
        }
        .mobile-menu-item.danger i {
            color: var(--danger-text);
        }

        /* Notification Dropdown */
        .notif-dropdown {
            position: absolute;
            top: 50px;
            right: 0;
            width: 280px;
            background: white;
            border: 1px solid var(--border-light);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            display: none;
            z-index: 60;
            flex-direction: column;
            overflow: hidden;
        }
        .notif-dropdown.active {
            display: flex;
        }
        .notif-header {
            padding: 16px;
            background: var(--bg-gray);
            border-bottom: 1px solid var(--border-light);
            font-weight: 700;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .notif-body {
            padding: 20px;
            text-align: center;
            color: var(--text-muted);
            font-size: 13px;
        }
        .notif-body i {
            font-size: 32px;
            color: #CBD5E1;
            margin-bottom: 8px;
            display: block;
        }

        /* Main Container */
        .main-container {
            padding: 16px;
            max-width: 600px; /* Constrain width on tablets */
            margin: 0 auto;
        }

        /* --- DESKTOP DESIGN OVERRIDES --- */
        @media (min-width: 1024px) {
            body {
                padding-bottom: 0;
                display: flex;
                height: 100vh;
                overflow: hidden;
            }

            .mobile-header {
                display: none; /* Hide mobile top header */
            }

            .bottom-nav {
                display: none; /* Hide bottom nav on desktop */
            }

            /* Show Sidebar */
            .desktop-sidebar {
                display: flex;
                width: var(--sidebar-width);
                background-color: var(--navy-dark);
                color: white;
                flex-direction: column;
                height: 100%;
            }

            .sidebar-header {
                padding: 24px;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }
            .sidebar-header h2 {
                margin: 0;
                font-size: 20px;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .sidebar-menu {
                padding: 20px 0;
                flex: 1;
            }

            .sidebar-link {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 14px 24px;
                color: #94A3B8;
                text-decoration: none;
                font-weight: 500;
                font-size: 15px;
                transition: 0.2s;
            }

            .sidebar-link:hover, .sidebar-link.active {
                background-color: rgba(255,255,255,0.05);
                color: white;
                border-left: 4px solid var(--blue-accent);
            }

            .sidebar-link i {
                font-size: 20px;
            }

            /* Adjust Main Container for Desktop */
            .desktop-main {
                flex: 1;
                display: flex;
                flex-direction: column;
                overflow: hidden;
            }

            .desktop-topbar {
                height: 70px;
                background: white;
                border-bottom: 1px solid var(--border-light);
                display: flex;
                justify-content: flex-end;
                align-items: center;
                padding: 0 30px;
                gap: 20px;
            }

            .main-container {
                max-width: 1200px;
                margin: 0;
                padding: 30px;
                overflow-y: auto;
                height: calc(100vh - 70px);
            }
        }
    </style>
</head>
<body>

    <!-- Desktop Sidebar -->
    <div class="desktop-sidebar">
        <div class="sidebar-header">
            <h2>
                <div class="logo-icon"><i class="ph ph-cube"></i></div>
                SIM-BMN KEMENAG MINAHASA
            </h2>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="ph ph-squares-four"></i> Dasbor
            </a>
            <a href="{{ route('bmn.index') }}" class="sidebar-link {{ request()->routeIs('bmn.*') ? 'active' : '' }}">
                <i class="ph ph-archive-box"></i> Data BMN
            </a>
            <a href="{{ route('sensus.index') }}" class="sidebar-link {{ request()->routeIs('sensus.*') ? 'active' : '' }}">
                <i class="ph ph-check-square-offset"></i> Sensus Fisik
            </a>
            <a href="{{ route('riwayat-kerusakan.index') }}" class="sidebar-link {{ request()->routeIs('riwayat-kerusakan.*') ? 'active' : '' }}">
                <i class="ph ph-wrench"></i> Riwayat Kerusakan
            </a>
            <a href="{{ route('klasifikasi.index') }}" class="sidebar-link {{ request()->routeIs('klasifikasi.*') ? 'active' : '' }}">
                <i class="ph ph-tree-structure"></i> Klasifikasi ML
            </a>
            <a href="{{ route('report.index') }}" class="sidebar-link {{ request()->routeIs('report.*') ? 'active' : '' }}">
                <i class="ph ph-file-text"></i> Laporan BMN
            </a>
            @if(Auth::user()->role === 'ADMIN')
            <a href="{{ route('units.index') }}" class="sidebar-link {{ request()->routeIs('units.*') ? 'active' : '' }}">
                <i class="ph ph-buildings"></i> Cabang
            </a>
            <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="ph ph-users"></i> Pengguna
            </a>
            @endif
        </div>
    </div>

    <div class="desktop-main">
        <!-- Mobile Header (Hidden on Desktop) -->
        <header class="mobile-header">
            <div class="header-logo">
                <div class="logo-icon">
                    <i class="ph ph-cube"></i>
                </div>
                <div class="header-title">
                    <div class="header-title-main">
                        SIM-BMN KEMENAG MINAHASA
                    </div>
                    <div class="header-subtitle">Kementerian Agama Kabupaten Minahasa</div>
                </div>
            </div>
            
            <div class="header-actions">
                <div style="position: relative;">
                    <button class="btn-icon" onclick="toggleNotif('mobileNotif')">
                        <i class="ph ph-bell"></i>
                        <span class="badge-dot"></span>
                    </button>
                    <!-- Notification Dropdown Mobile -->
                    <div class="notif-dropdown" id="mobileNotif">
                        <div class="notif-header">Notifikasi Baru <span style="background:var(--danger-bg);color:var(--danger-text);padding:2px 6px;border-radius:10px;font-size:10px;">0</span></div>
                        <div class="notif-body">
                            <i class="ph ph-bell-slash"></i>
                            Belum ada pesan atau pemberitahuan baru saat ini.
                        </div>
                    </div>
                </div>
                <div class="avatar-circle">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <!-- Desktop Topbar (Hidden on Mobile) -->
        <div class="desktop-topbar" style="display: none;">
            <div style="position: relative;">
                <button class="btn-icon" onclick="toggleNotif('desktopNotif')">
                    <i class="ph ph-bell"></i>
                    <span class="badge-dot"></span>
                </button>
                <!-- Notification Dropdown Desktop -->
                <div class="notif-dropdown" id="desktopNotif" style="right: -100px;">
                    <div class="notif-header">Notifikasi Baru <span style="background:var(--danger-bg);color:var(--danger-text);padding:2px 6px;border-radius:10px;font-size:10px;">0</span></div>
                    <div class="notif-body">
                        <i class="ph ph-bell-slash"></i>
                        Belum ada pesan atau pemberitahuan baru saat ini.
                    </div>
                </div>
            </div>
            <div class="avatar-circle">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" style="background:none; border:none; color:var(--danger-text); font-weight:600; cursor:pointer;">Logout</button>
            </form>
        </div>
        <!-- Style override to show topbar only on desktop -->
        <style>
            @media (min-width: 1024px) {
                .desktop-topbar { display: flex !important; }
            }
        </style>

        <!-- Main Content Area -->
        <main class="main-container">
            @yield('content')
        </main>
    </div>

    <!-- Mobile Bottom Navigation (Hidden on Desktop) -->
    <nav class="bottom-nav">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="ph ph-squares-four"></i>
            Beranda
        </a>
        <a href="{{ route('bmn.index') }}" class="nav-item {{ request()->routeIs('bmn.*') ? 'active' : '' }}">
            <i class="ph ph-archive-box"></i>
            Data BMN
        </a>
        
        <a href="{{ route('klasifikasi.create') }}" class="nav-item-center">
            <i class="ph ph-tree-structure"></i>
            <span style="font-size: 10px; font-weight:600; margin-top:2px;">Uji ML</span>
        </a>
        
        <a href="{{ route('riwayat-kerusakan.index') }}" class="nav-item {{ request()->routeIs('riwayat-kerusakan.*') ? 'active' : '' }}">
            <i class="ph ph-wrench"></i>
            Kerusakan
        </a>
        <a href="javascript:void(0)" class="nav-item" onclick="toggleMobileMenu()">
            <i class="ph ph-list"></i>
            Menu
        </a>
    </nav>

    <!-- Mobile Menu Modal -->
    <div class="mobile-menu-overlay" id="mobileMenu" onclick="if(event.target === this) toggleMobileMenu()">
        <div class="mobile-menu-content">
            <h3 style="margin-top: 0; margin-bottom: 20px; text-align: center;">Menu Sistem</h3>
            
            <a href="{{ route('sensus.index') }}" class="mobile-menu-item">
                <i class="ph ph-check-square-offset"></i> Sensus Fisik Lapangan
            </a>
            <a href="{{ route('klasifikasi.index') }}" class="mobile-menu-item">
                <i class="ph ph-tree-structure"></i> Riwayat Klasifikasi Decision Tree
            </a>
            <a href="{{ route('report.index') }}" class="mobile-menu-item">
                <i class="ph ph-file-text"></i> Pusat Laporan & Cetak
            </a>

            @if(Auth::user()->role === 'ADMIN')
            <a href="{{ route('units.index') }}" class="mobile-menu-item">
                <i class="ph ph-buildings"></i> Manajemen Cabang (Unit)
            </a>
            <a href="{{ route('users.index') }}" class="mobile-menu-item">
                <i class="ph ph-users"></i> Manajemen Pengguna
            </a>
            @endif
            
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="mobile-menu-item danger" style="width: 100%; border: none; background: none; text-align: left; cursor: pointer; color: var(--danger-text);">
                    <i class="ph ph-sign-out"></i> Keluar (Logout)
                </button>
            </form>
            
            <button onclick="toggleMobileMenu()" style="width: 100%; padding: 14px; margin-top: 20px; border-radius: 12px; border: none; background-color: var(--bg-gray); color: var(--text-dark); font-weight: 600; font-size: 14px;">Tutup</button>
        </div>
    </div>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            if (menu.classList.contains('active')) {
                menu.querySelector('.mobile-menu-content').style.transform = 'translateY(100%)';
                setTimeout(() => menu.classList.remove('active'), 300);
            } else {
                menu.classList.add('active');
                setTimeout(() => menu.querySelector('.mobile-menu-content').style.transform = 'translateY(0)', 10);
            }
        }

        function toggleNotif(id) {
            const el = document.getElementById(id);
            if(el.classList.contains('active')) {
                el.classList.remove('active');
            } else {
                // close others first
                document.querySelectorAll('.notif-dropdown').forEach(n => n.classList.remove('active'));
                el.classList.add('active');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const notifs = document.querySelectorAll('.notif-dropdown');
            const bells = document.querySelectorAll('.btn-icon');
            let clickedInside = false;
            
            bells.forEach(b => { if(b.contains(event.target)) clickedInside = true; });
            notifs.forEach(n => { if(n.contains(event.target)) clickedInside = true; });
            
            if (!clickedInside) {
                notifs.forEach(n => n.classList.remove('active'));
            }
        });
    </script>

</body>
</html>

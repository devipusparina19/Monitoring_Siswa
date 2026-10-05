<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Monitoring Perkembangan Siswa')
    </title>

    {{-- Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f7fb;
            color: #1e293b;
        }

        /* =====================================================
           LAYOUT UTAMA
        ===================================================== */

        .app-wrapper {
            min-height: 100vh;
            display: flex;
        }

        /* =====================================================
           SIDEBAR
        ===================================================== */

        .sidebar {
            width: 270px;
            min-width: 270px;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1050;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            height: 82px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #edf0f4;
        }

        .sidebar-logo {
            width: 48px;
            height: 48px;
            flex-shrink: 0;
            border-radius: 12px;
            background-image:
                linear-gradient(
                    rgba(15, 82, 186, 0.15),
                    rgba(15, 82, 186, 0.15)
                ),
                url("{{ asset('images/sekolah.jpg') }}");
            background-size: cover;
            background-position: center;
            border: 2px solid #e7efff;
        }

        .sidebar-brand-text {
            min-width: 0;
        }

        .sidebar-brand-text strong {
            display: block;
            font-size: 14px;
            color: #0f3d91;
            line-height: 1.3;
        }

        .sidebar-brand-text span {
            display: block;
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }

        .sidebar-menu {
            padding: 22px 14px;
            flex: 1;
            overflow-y: auto;
        }

        .menu-title {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .7px;
            margin: 4px 12px 10px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 46px;
            padding: 11px 14px;
            margin-bottom: 5px;
            border-radius: 10px;
            text-decoration: none;
            color: #475569;
            font-size: 13px;
            font-weight: 500;
            transition: .2s ease;
        }

        .sidebar-menu a i {
            font-size: 18px;
            width: 22px;
            text-align: center;
        }

        .sidebar-menu a:hover {
            background: #eff6ff;
            color: #155eef;
        }

        .sidebar-menu a.active {
            background: #eaf2ff;
            color: #155eef;
            font-weight: 600;
        }

        /* =====================================================
           SIDEBAR USER
        ===================================================== */

        .sidebar-user {
            padding: 14px;
            border-top: 1px solid #edf0f4;
        }

        .sidebar-user-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 12px;
        }

        .sidebar-user-name {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
        }

        .sidebar-user-role {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }

        .logout-btn {
            width: 100%;
            margin-top: 10px;
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #dc2626;
            border-radius: 9px;
            padding: 9px 12px;
            font-family: inherit;
            font-size: 12px;
            font-weight: 500;
        }

        .logout-btn:hover {
            background: #fef2f2;
        }

        /* =====================================================
           AREA KONTEN
        ===================================================== */

        .main-area {
            width: calc(100% - 270px);
            margin-left: 270px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* =====================================================
           HEADER
        ===================================================== */

        .top-header {
            height: 76px;
            min-height: 76px;
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .mobile-menu-btn {
            display: none;
            border: none;
            background: #eff6ff;
            color: #155eef;
            width: 42px;
            height: 42px;
            border-radius: 10px;
            font-size: 20px;
        }

        .header-title h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
        }

        .header-title p {
            margin: 2px 0 0;
            font-size: 11px;
            color: #64748b;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #eaf2ff;
            color: #155eef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 15px;
        }

        .header-user-info {
            text-align: right;
        }

        .header-user-name {
            font-size: 12px;
            font-weight: 600;
            color: #1e293b;
        }

        .header-user-role {
            font-size: 10px;
            color: #64748b;
        }

        /* =====================================================
           CONTENT
        ===================================================== */

        .content-area {
            flex: 1;
            padding: 32px;
            width: 100%;
        }

        .content-wrapper {
            width: 100%;
            max-width: 1500px;
            margin: 0 auto;
        }

        /* =====================================================
           ALERT
        ===================================================== */

        .alert {
            border: none;
            border-radius: 12px;
            font-size: 13px;
        }

        /* =====================================================
           FOOTER
        ===================================================== */

        .app-footer {
            background: #ffffff;
            border-top: 1px solid #e5e7eb;
            padding: 18px 32px;
            color: #64748b;
            font-size: 11px;
        }

        /* =====================================================
           OVERLAY MOBILE
        ===================================================== */

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .45);
            z-index: 1040;
        }

        /* =====================================================
           TABLE
        ===================================================== */

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* =====================================================
           MOBILE
        ===================================================== */

        @media (max-width: 991px) {

            .sidebar {
                transform: translateX(-100%);
                transition: transform .25s ease;
                box-shadow: 8px 0 30px rgba(15, 23, 42, .08);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-overlay.show {
                display: block;
            }

            .main-area {
                width: 100%;
                margin-left: 0;
            }

            .mobile-menu-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .top-header {
                padding: 0 20px;
            }

            .content-area {
                padding: 24px 20px;
            }

        }

        @media (max-width: 576px) {

            .top-header {
                height: 68px;
                min-height: 68px;
                padding: 0 14px;
            }

            .header-title h5 {
                font-size: 14px;
            }

            .header-title p {
                display: none;
            }

            .header-user-info {
                display: none;
            }

            .header-avatar {
                width: 38px;
                height: 38px;
            }

            .content-area {
                padding: 18px 14px;
            }

            .app-footer {
                padding: 16px 14px;
                text-align: center;
            }
        }

    </style>

    @stack('styles')

</head>

<body>

<div class="app-wrapper">

    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-brand">

            <div class="sidebar-logo"></div>

            <div class="sidebar-brand-text">
                <strong>UPTD SDN Kandangan Baru</strong>
                <span>Monitoring Perkembangan Siswa</span>
            </div>

        </div>

        <div class="sidebar-menu">

            <div class="menu-title">
                Menu Utama
            </div>

            {{-- Dashboard --}}
            <a
                href="{{ route('dashboard') }}"
                class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
            >
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>

            {{-- =================================================
                 ADMIN
            ================================================== --}}
            @if(auth()->check() && auth()->user()->role === 'admin')

                <a
                    href="{{ route('users.index') }}"
                    class="{{ request()->routeIs('users.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-people-fill"></i>
                    <span>Data User</span>
                </a>

                <a
                    href="{{ route('students.index') }}"
                    class="{{ request()->routeIs('students.*') ? 'active' : '' }}"
                >
                    <i class="bi bi-mortarboard-fill"></i>
                    <span>Data Siswa</span>
                </a>

                @if(Route::has('reports.index'))
                    <a href="{{ route('reports.index') }}">
                        <i class="bi bi-file-earmark-text-fill"></i>
                        <span>Laporan</span>
                    </a>
                @endif

            @endif

            {{-- =================================================
                 GURU
            ================================================== --}}
            @if(auth()->check() && auth()->user()->role === 'guru')

                @if(Route::has('developments.index'))
                    <a
                        href="{{ route('developments.index') }}"
                        class="{{ request()->routeIs('developments.*') ? 'active' : '' }}"
                    >
                        <i class="bi bi-clipboard2-check-fill"></i>
                        <span>Monitoring Siswa</span>
                    </a>
                @endif

                @if(Route::has('videos.index'))
                    <a
                        href="{{ route('videos.index') }}"
                        class="{{ request()->routeIs('videos.*') ? 'active' : '' }}"
                    >
                        <i class="bi bi-play-circle-fill"></i>
                        <span>Video Pembelajaran</span>
                    </a>
                @endif

                @if(Route::has('reports.index'))
                    <a href="{{ route('reports.index') }}">
                        <i class="bi bi-file-earmark-text-fill"></i>
                        <span>Laporan</span>
                    </a>
                @endif

            @endif

            {{-- =================================================
                 ORANG TUA
            ================================================== --}}
            @if(auth()->check() && auth()->user()->role === 'orang_tua')

                @if(Route::has('parent.child'))
                    @foreach(auth()->user()->children as $child)

                        <a
                            href="{{ route('parent.child', $child) }}"
                            class="{{ request()->routeIs('parent.child') && request()->route('student')?->id === $child->id ? 'active' : '' }}"
                        >
                            <i class="bi bi-person-vcard-fill"></i>
                            <span>{{ $child->name }}</span>
                        </a>

                    @endforeach
                @endif

                @if(Route::has('reports.index'))
                    <a href="{{ route('reports.index') }}">
                        <i class="bi bi-file-earmark-text-fill"></i>
                        <span>Laporan</span>
                    </a>
                @endif

            @endif

        </div>

        {{-- USER --}}
        <div class="sidebar-user">

            <div class="sidebar-user-box">

                <div class="sidebar-user-name">
                    {{ auth()->user()->name }}
                </div>

                <div class="sidebar-user-role">
                    @if(auth()->user()->role === 'admin')
                        Administrator
                    @elseif(auth()->user()->role === 'guru')
                        Guru
                    @else
                        Orang Tua
                    @endif
                </div>

                <form
                    action="{{ route('logout') }}"
                    method="POST"
                >
                    @csrf

                    <button
                        type="submit"
                        class="logout-btn"
                    >
                        <i class="bi bi-box-arrow-right me-1"></i>
                        Keluar dari Sistem
                    </button>

                </form>

            </div>

        </div>

    </aside>


    {{-- OVERLAY MOBILE --}}
    <div
        class="sidebar-overlay"
        id="sidebarOverlay"
        onclick="closeSidebar()"
    ></div>


    {{-- =====================================================
         MAIN AREA
    ====================================================== --}}
    <div class="main-area">

        {{-- HEADER --}}
        <header class="top-header">

            <div class="header-left">

                <button
                    type="button"
                    class="mobile-menu-btn"
                    onclick="toggleSidebar()"
                    aria-label="Buka menu"
                >
                    <i class="bi bi-list"></i>
                </button>

                <div class="header-title">

                    <h5>
                        @yield('title', 'Dashboard')
                    </h5>

                    <p>
                        Sistem Informasi Monitoring Perkembangan Belajar Siswa
                    </p>

                </div>

            </div>


            {{-- USER --}}
            <div class="header-user">

                <div class="header-user-info">

                    <div class="header-user-name">
                        {{ auth()->user()->name }}
                    </div>

                    <div class="header-user-role">

                        @if(auth()->user()->role === 'admin')
                            Administrator
                        @elseif(auth()->user()->role === 'guru')
                            Guru
                        @else
                            Orang Tua
                        @endif

                    </div>

                </div>

                <div class="header-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

            </div>

        </header>


        {{-- CONTENT --}}
        <main class="content-area">

            <div class="content-wrapper">

                {{-- SUCCESS --}}
                @if(session('success'))

                    <div class="alert alert-success alert-dismissible fade show mb-4">

                        <i class="bi bi-check-circle-fill me-2"></i>

                        {{ session('success') }}

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                        ></button>

                    </div>

                @endif


                {{-- ERROR --}}
                @if(session('error'))

                    <div class="alert alert-danger alert-dismissible fade show mb-4">

                        <i class="bi bi-exclamation-circle-fill me-2"></i>

                        {{ session('error') }}

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                        ></button>

                    </div>

                @endif


                {{-- VALIDATION --}}
                @if($errors->any())

                    <div class="alert alert-danger mb-4">

                        <strong>
                            Terdapat kesalahan:
                        </strong>

                        <ul class="mb-0 mt-2">

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                @yield('content')

            </div>

        </main>


        {{-- FOOTER --}}
        <footer class="app-footer">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <span>
                    © {{ date('Y') }} UPTD SDN Kandangan Baru
                </span>

                <span>
                    Sistem Informasi Monitoring Perkembangan Belajar Siswa
                </span>

            </div>

        </footer>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

    function toggleSidebar() {

        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');

    }

    function closeSidebar() {

        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        sidebar.classList.remove('show');
        overlay.classList.remove('show');

    }

    // Tutup sidebar setelah klik menu di HP
    document.querySelectorAll('.sidebar a').forEach(function(link) {

        link.addEventListener('click', function() {

            if (window.innerWidth <= 991) {
                closeSidebar();
            }

        });

    });

</script>

@stack('scripts')

</body>
</html>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#17365D">

    <title>@yield('title', 'Monitoring Perkembangan Siswa') | UPTD SDN Kandangan Baru</title>

    {{-- Font: satu keluarga untuk seluruh sistem (sama dengan halaman login) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap + Bootstrap Icons (satu keluarga ikon) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* =========================================================
           1. DESIGN TOKENS
           Semua halaman memakai nilai yang sama dari sini.
           Halaman anak (dashboard dll.) TIDAK boleh mendefinisikan ulang.
        ========================================================= */
        :root {
            /* Warna */
            --navy: #17365D;
            --midnight: #142C46;
            --blue: #426789;
            --steel: #6F8FAA;
            --sky: #BFD9EE;
            --soft: #EAF1F7;
            --paper: #FAFAF7;
            --white: #FFFFFF;
            --text: #263445;
            --slate: #64748B;
            --line: #E3E9EF;

            /* Status */
            --ok: #1F7A55;       --ok-soft: #E5F4EC;
            --warn: #8A5A06;     --warn-soft: #FBF0D9;
            --bad: #B3261E;      --bad-soft: #FBEAE8;
            --info: #2D5A87;     --info-soft: #E6EFF8;

            /* Tipografi */
            --font: "Plus Jakarta Sans", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;

            /* Radius: kecil untuk kontrol, sedang untuk kartu, besar untuk modal */
            --r-sm: 10px;
            --r-md: 14px;
            --r-lg: 20px;

            /* Bayangan: hanya dua tingkat */
            --sh-1: 0 1px 2px rgba(20, 44, 70, .05), 0 4px 16px rgba(20, 44, 70, .05);
            --sh-2: 0 10px 30px rgba(20, 44, 70, .14);

            /* Gerak */
            --ease: cubic-bezier(.22, .8, .25, 1);
            --t-fast: .18s;
            --t-base: .3s;

            /* Layout */
            --sidebar-w: 300px;
            --header-h: 84px;

            /* Pemetaan ke Bootstrap */
            --bs-body-font-family: var(--font);
            --bs-body-color: var(--text);
            --bs-body-bg: var(--paper);
            --bs-primary: #17365D;
            --bs-primary-rgb: 23, 54, 93;
            --bs-link-color: #25507F;
            --bs-link-hover-color: #17365D;
            --bs-border-color: var(--line);
            --bs-secondary-color: var(--slate);
            --bs-border-radius: var(--r-sm);
        }

        *, *::before, *::after { box-sizing: border-box; }

        html { -webkit-text-size-adjust: 100%; scroll-behavior: smooth; font-size: 17px; }

        body {
            margin: 0;
            font-family: var(--font);
            font-size: 1rem;
            line-height: 1.7;
            background: var(--paper);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
        }

        body.no-scroll { overflow: hidden; }

        :focus-visible { outline: 3px solid rgba(66, 103, 137, .55); outline-offset: 2px; }

        ::-webkit-scrollbar { width: 10px; height: 10px; }
        ::-webkit-scrollbar-thumb { background: #CBD6E0; border-radius: 10px; border: 2px solid var(--paper); }

        /* =========================================================
           2. TIPOGRAFI
        ========================================================= */
        h1, h2, h3, h4, h5, h6 { color: var(--navy); font-weight: 800; letter-spacing: -.02em; line-height: 1.25; }
        h1, .h1 { font-size: 2.1rem; }
        h2, .h2 { font-size: 1.7rem; }
        h3, .h3 { font-size: 1.4rem; }
        h4, .h4 { font-size: 1.25rem; }
        h5, .h5 { font-size: 1.1rem; }
        h6, .h6 { font-size: 1rem; }
        .text-muted { color: var(--slate) !important; }
        a { text-underline-offset: 3px; }

        /* =========================================================
           3. LAYOUT APLIKASI
        ========================================================= */
        .app-wrapper { min-height: 100vh; display: flex; }

        /* Dekorasi latar yang bergerak sangat pelan */
        .bg-deco { position: fixed; z-index: 0; border-radius: 50%; pointer-events: none; }
        .bg-deco.d1 { width: 460px; height: 460px; right: -140px; top: 90px; background: radial-gradient(circle, rgba(111, 143, 170, .22), rgba(111, 143, 170, 0) 70%); animation: drift 24s ease-in-out infinite; }
        .bg-deco.d2 { width: 440px; height: 440px; left: calc(var(--sidebar-w) - 60px); bottom: -170px; background: radial-gradient(circle, rgba(191, 217, 238, .5), rgba(191, 217, 238, 0) 70%); animation: drift 30s ease-in-out -9s infinite; }

        /* Garis tipis saat berpindah halaman */
        .nav-progress {
            position: fixed; top: 0; left: 0; z-index: 2000;
            height: 3px; width: 0; opacity: 0; pointer-events: none;
            background: linear-gradient(90deg, var(--blue), var(--sky));
            transition: width .4s var(--ease), opacity .3s ease;
        }
        .nav-progress.run { opacity: 1; width: 82%; transition: width 6s cubic-bezier(.1, .6, .3, 1), opacity .1s; }

        /* ---------- Sidebar ---------- */
        .sidebar {
            position: fixed; inset: 0 auto 0 0; z-index: 1050;
            width: var(--sidebar-w); min-width: var(--sidebar-w);
            display: flex; flex-direction: column;
            background: linear-gradient(185deg, var(--navy) 0%, var(--midnight) 100%);
            color: var(--white);
            overflow: hidden;
            transition: transform var(--t-base) var(--ease), visibility var(--t-base);
        }

        /* Dua lingkaran dekoratif (selaras dengan halaman login) */
        .sidebar::before,
        .sidebar::after {
            content: ""; position: absolute; border-radius: 50%; pointer-events: none;
            border: 1.5px solid rgba(255, 255, 255, .08);
        }
        .sidebar::before { right: -170px; top: -150px; width: 340px; height: 340px; animation: drift 26s ease-in-out infinite; }
        .sidebar::after  { left: -130px; bottom: -150px; width: 320px; height: 320px; border-color: rgba(255, 255, 255, .06); animation: drift 32s ease-in-out -12s infinite reverse; }

        .sidebar-brand {
            position: relative; z-index: 1;
            padding: 26px 22px 22px;
            display: flex; flex-direction: column; align-items: flex-start; gap: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, .09);
        }

        .sidebar-logo {
            width: 78px; height: 78px; padding: 5px; border-radius: 20px;
            background: var(--white); box-shadow: 0 10px 26px rgba(0, 0, 0, .25);
            animation: float 7s ease-in-out infinite;
        }
        .sidebar-logo img { width: 100%; height: 100%; display: block; object-fit: contain; border-radius: 13px; }

        .sidebar-brand-text strong { display: block; font-size: 20px; font-weight: 800; line-height: 1.3; letter-spacing: -.01em; color: var(--white); }
        .sidebar-brand-text span { display: block; margin-top: 3px; font-size: 15px; font-weight: 500; line-height: 1.45; color: rgba(255, 255, 255, .68); }

        .sidebar-menu { position: relative; z-index: 1; flex: 1; overflow-y: auto; padding: 18px 16px 12px; }
        .sidebar-menu::-webkit-scrollbar { width: 6px; }
        .sidebar-menu::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, .16); border: 0; }

        .menu-group + .menu-group { margin-top: 16px; }
        .menu-group:nth-child(1) { --g: .10s; }
        .menu-group:nth-child(2) { --g: .22s; }
        .menu-group:nth-child(3) { --g: .34s; }
        .menu-group:nth-child(4) { --g: .46s; }
        .menu-title { margin: 0 14px 10px; font-size: 14px; font-weight: 600; letter-spacing: .01em; color: rgba(255, 255, 255, .62); }

        .menu-link {
            position: relative;
            display: flex; align-items: center; gap: 14px;
            min-height: 54px; padding: 11px 16px; margin-bottom: 5px;
            border-radius: 12px; text-decoration: none;
            color: rgba(255, 255, 255, .80);
            font-size: 17px; font-weight: 500; line-height: 1.35;
            transition: background-color var(--t-fast) ease, color var(--t-fast) ease, transform var(--t-fast) ease;
            animation: slide-in .6s var(--ease) backwards;
            animation-delay: var(--g, 0s);
        }
        .menu-link:nth-of-type(2)  { animation-delay: calc(var(--g, 0s) + .07s); }
        .menu-link:nth-of-type(3)  { animation-delay: calc(var(--g, 0s) + .14s); }
        .menu-link:nth-of-type(4)  { animation-delay: calc(var(--g, 0s) + .21s); }
        .menu-link:nth-of-type(n+5) { animation-delay: calc(var(--g, 0s) + .28s); }

        .menu-link i { width: 26px; flex: 0 0 26px; text-align: center; font-size: 22px; transition: color var(--t-fast) ease, transform var(--t-base) var(--ease); }
        .menu-link span { min-width: 0; overflow-wrap: anywhere; }

        .menu-link.active { background: rgba(255, 255, 255, .13); color: var(--white); font-weight: 700; }
        .menu-link.active i { color: var(--sky); }
        .menu-link.active::before {
            content: ""; position: absolute; left: -16px; top: 11px; bottom: 11px; width: 4px;
            border-radius: 0 4px 4px 0; background: var(--sky);
        }

        .menu-link:focus-visible, .logout-btn:focus-visible, .mobile-menu-btn:focus-visible { outline: 3px solid rgba(191, 217, 238, .85); outline-offset: 2px; }

        .sidebar-user { position: relative; z-index: 1; padding: 14px 16px 18px; border-top: 1px solid rgba(255, 255, 255, .09); }
        .sidebar-user-box { padding: 14px; border-radius: var(--r-md); background: rgba(255, 255, 255, .07); }
        .sidebar-user-info { display: flex; align-items: center; gap: 12px; }

        .sidebar-avatar {
            width: 50px; height: 50px; flex: 0 0 50px; display: grid; place-items: center;
            border-radius: 50%; background: var(--soft); color: var(--navy); font-size: 19px; font-weight: 800;
        }
        .sidebar-user-name { font-size: 16px; font-weight: 700; line-height: 1.3; color: var(--white); overflow-wrap: anywhere; }
        .sidebar-user-role { margin-top: 1px; font-size: 14.5px; color: rgba(255, 255, 255, .68); }

        .logout-btn {
            width: 100%; min-height: 52px; margin-top: 12px;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            border: 1px solid rgba(255, 255, 255, .22); border-radius: 12px;
            background: transparent; color: var(--white);
            font-family: inherit; font-size: 15.5px; font-weight: 600; cursor: pointer;
            transition: background-color var(--t-fast) ease, border-color var(--t-fast) ease;
        }
        .logout-btn:hover { background: rgba(179, 38, 30, .9); border-color: transparent; }

        .sidebar-overlay {
            position: fixed; inset: 0; z-index: 1040;
            background: rgba(10, 24, 46, .55);
            opacity: 0; visibility: hidden;
            transition: opacity var(--t-base) ease, visibility var(--t-base) ease;
        }
        .sidebar-overlay.show { opacity: 1; visibility: visible; }

        /* ---------- Area konten ---------- */
        .main-area { width: calc(100% - var(--sidebar-w)); margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; min-width: 0; }

        .top-header {
            position: sticky; top: 0; z-index: 1000;
            height: var(--header-h); min-height: var(--header-h);
            padding: 0 36px;
            display: flex; align-items: center; justify-content: space-between; gap: 16px;
            background: rgba(250, 250, 247, .92);
            -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--line);
        }

        /* Garis warna bergerak di bawah topbar */
        .top-header::after {
            content: ""; position: absolute; left: 0; right: 0; bottom: -1px; height: 3px;
            background: linear-gradient(90deg, var(--navy), var(--blue), var(--sky), var(--blue), var(--navy));
            background-size: 200% 100%; animation: slide-bar 10s linear infinite;
        }

        .header-left { display: flex; align-items: center; gap: 14px; min-width: 0; }

        .mobile-menu-btn {
            display: none; flex: 0 0 46px; width: 46px; height: 46px;
            align-items: center; justify-content: center;
            border: 0; border-radius: 13px; background: var(--soft); color: var(--navy); font-size: 24px;
            transition: background-color var(--t-fast) ease, transform var(--t-fast) ease;
        }
        .mobile-menu-btn:active { transform: scale(.94); }

        .header-title { min-width: 0; }
        .header-title h1 { margin: 0; font-size: 25px; font-weight: 800; letter-spacing: -.015em; color: var(--navy); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .header-title p { margin: 1px 0 0; font-size: 15px; color: var(--slate); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .crumbs { margin: 0 0 6px; font-size: 13px; color: var(--slate); }
        .crumbs a { color: var(--blue); text-decoration: none; font-weight: 600; }
        .crumbs a:hover { text-decoration: underline; }

        .header-right { display: flex; align-items: center; gap: 16px; }

        .header-date {
            display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px;
            border-radius: 999px; background: var(--soft); color: var(--navy);
            font-size: 14.5px; font-weight: 600; white-space: nowrap;
        }

        /* Avatar topbar hanya muncul di HP/tablet, karena di desktop
           nama & peran sudah tampil di sidebar (tidak ditampilkan dua kali). */
        .header-avatar {
            display: none; width: 44px; height: 44px; place-items: center;
            border-radius: 50%; background: linear-gradient(135deg, var(--navy), var(--blue));
            color: var(--white); font-size: 17px; font-weight: 800;
        }

        .content-area { position: relative; z-index: 1; flex: 1; width: 100%; padding: 38px 44px 48px; }
        .content-wrapper { width: 100%; max-width: 1560px; margin: 0 auto; }

        /* Komponen muncul bertahap, satu kali saat halaman dibuka.
           Halaman anak tidak perlu (dan tidak boleh) menambah animasi muncul sendiri. */
        .content-wrapper > *:not(.modal):not(.modal-backdrop):not(script):not(style):not(.row) { animation: rise .6s var(--ease) backwards; }
        .content-wrapper > :nth-child(1) { animation-delay: .02s; }
        .content-wrapper > :nth-child(2) { animation-delay: .08s; }
        .content-wrapper > :nth-child(3) { animation-delay: .14s; }
        .content-wrapper > :nth-child(4) { animation-delay: .20s; }
        .content-wrapper > :nth-child(5) { animation-delay: .26s; }
        .content-wrapper > :nth-child(6) { animation-delay: .32s; }
        .content-wrapper > :nth-child(n+7) { animation-delay: .38s; }

        .content-wrapper .row > [class*="col"] { animation: rise .65s var(--ease) backwards; }
        .content-wrapper .row > :nth-child(1) { animation-delay: .10s; }
        .content-wrapper .row > :nth-child(2) { animation-delay: .18s; }
        .content-wrapper .row > :nth-child(3) { animation-delay: .26s; }
        .content-wrapper .row > :nth-child(4) { animation-delay: .34s; }
        .content-wrapper .row > :nth-child(5) { animation-delay: .42s; }
        .content-wrapper .row > :nth-child(n+6) { animation-delay: .50s; }

        .app-footer {
            position: relative; z-index: 1;
            padding: 22px 44px; background: var(--white); border-top: 1px solid var(--line);
            color: var(--slate); font-size: 14.5px;
        }
        .app-footer strong { color: var(--navy); font-weight: 700; }
        .footer-feature { display: inline-flex; align-items: center; gap: 8px; }
        .footer-feature i { color: var(--ok); font-size: 16px; }

        /* =========================================================
           4. KOMPONEN UMUM (dipakai semua halaman)
        ========================================================= */

        /* Kepala halaman: judul di kiri, tombol aksi di kanan */
        .page-head { display: flex; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; gap: 14px 20px; margin-bottom: 24px; }
        .page-head h1, .page-head h2 { margin: 0; }
        .page-head p { margin: 4px 0 0; color: var(--slate); }
        .page-actions { display: flex; flex-wrap: wrap; gap: 10px; }

        /* Kartu */
        .card {
            background: var(--white); border: 1px solid var(--line); border-radius: var(--r-md); box-shadow: var(--sh-1);
            transition: transform var(--t-base) var(--ease), box-shadow var(--t-base) ease, border-color var(--t-base) ease;
        }
        .card-header { background: transparent; border-bottom: 1px solid var(--line); padding: 18px 26px; font-size: 1.1rem; font-weight: 800; color: var(--navy); }
        .card-body { padding: 26px; }
        .card-footer { background: transparent; border-top: 1px solid var(--line); padding: 14px 22px; }

        /* Kartu yang berisi form/tabel tidak ikut terangkat saat hover,
           supaya tidak bergeser ketika pengguna sedang mengisi atau membaca. */
        @media (hover: hover) {
            .card:not(:has(form, table, input, select, textarea)):hover { transform: translateY(-4px); box-shadow: var(--sh-2); border-color: #CFDBE6; }
        }

        /* Kartu ringkasan angka */
        .stat-card { display: flex; align-items: center; gap: 18px; padding: 24px 26px; }
        .stat-icon { width: 62px; height: 62px; flex: 0 0 62px; display: grid; place-items: center; border-radius: 18px; background: var(--soft); color: var(--navy); font-size: 30px; }
        .stat-value { font-size: 2.5rem; font-weight: 800; line-height: 1.1; color: var(--navy); font-variant-numeric: tabular-nums; }
        .stat-label { margin-top: 2px; font-size: 1rem; font-weight: 600; color: var(--slate); }

        /* Tombol */
        .btn {
            --bs-btn-border-radius: 12px;
            min-height: 50px; padding: 10px 24px;
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            font-weight: 700; font-size: 1rem; line-height: 1.3;
            transition: transform var(--t-fast) ease, box-shadow var(--t-fast) ease, background-color var(--t-fast) ease, border-color var(--t-fast) ease, color var(--t-fast) ease;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0) scale(.98); }
        .btn-sm { min-height: 42px; padding: 6px 16px; font-size: .92rem; --bs-btn-border-radius: 10px; }
        .btn-lg { min-height: 58px; padding: 12px 30px; font-size: 1.1rem; }

        .btn-primary {
            --bs-btn-bg: #17365D; --bs-btn-border-color: #17365D; --bs-btn-color: #fff;
            --bs-btn-hover-bg: #1F4676; --bs-btn-hover-border-color: #1F4676; --bs-btn-hover-color: #fff;
            --bs-btn-active-bg: #142C46; --bs-btn-active-border-color: #142C46;
            --bs-btn-disabled-bg: #17365D; --bs-btn-disabled-border-color: #17365D;
            --bs-btn-focus-shadow-rgb: 66, 103, 137;
        }
        .btn-primary:hover { box-shadow: 0 8px 18px rgba(23, 54, 93, .28); }

        .btn-outline-primary {
            --bs-btn-color: #17365D; --bs-btn-border-color: #17365D;
            --bs-btn-hover-bg: #17365D; --bs-btn-hover-border-color: #17365D; --bs-btn-hover-color: #fff;
            --bs-btn-active-bg: #142C46; --bs-btn-active-border-color: #142C46;
        }

        .btn-secondary {
            --bs-btn-bg: #EAF1F7; --bs-btn-border-color: #EAF1F7; --bs-btn-color: #17365D;
            --bs-btn-hover-bg: #DCE7F1; --bs-btn-hover-border-color: #DCE7F1; --bs-btn-hover-color: #17365D;
            --bs-btn-active-bg: #CFDDEA; --bs-btn-active-border-color: #CFDDEA; --bs-btn-active-color: #17365D;
        }
        .btn-outline-secondary {
            --bs-btn-color: #426789; --bs-btn-border-color: #C9D5E0;
            --bs-btn-hover-bg: #EAF1F7; --bs-btn-hover-border-color: #C9D5E0; --bs-btn-hover-color: #17365D;
            --bs-btn-active-bg: #DCE7F1; --bs-btn-active-border-color: #C9D5E0; --bs-btn-active-color: #17365D;
        }
        .btn-success {
            --bs-btn-bg: #1F7A55; --bs-btn-border-color: #1F7A55; --bs-btn-color: #fff;
            --bs-btn-hover-bg: #19664A; --bs-btn-hover-border-color: #19664A; --bs-btn-hover-color: #fff;
            --bs-btn-active-bg: #145540; --bs-btn-active-border-color: #145540;
        }
        .btn-danger {
            --bs-btn-bg: #B3261E; --bs-btn-border-color: #B3261E; --bs-btn-color: #fff;
            --bs-btn-hover-bg: #98201A; --bs-btn-hover-border-color: #98201A; --bs-btn-hover-color: #fff;
            --bs-btn-active-bg: #801B16; --bs-btn-active-border-color: #801B16;
        }
        .btn-outline-danger {
            --bs-btn-color: #B3261E; --bs-btn-border-color: #E6B8B4;
            --bs-btn-hover-bg: #B3261E; --bs-btn-hover-border-color: #B3261E; --bs-btn-hover-color: #fff;
        }
        .btn-warning {
            --bs-btn-bg: #E8B53C; --bs-btn-border-color: #E8B53C; --bs-btn-color: #3A2A00;
            --bs-btn-hover-bg: #D9A52A; --bs-btn-hover-border-color: #D9A52A; --bs-btn-hover-color: #3A2A00;
        }
        .btn-light { --bs-btn-bg: #fff; --bs-btn-border-color: var(--line); --bs-btn-color: #17365D; --bs-btn-hover-bg: #EAF1F7; --bs-btn-hover-border-color: var(--line); }

        /* Tombol utama: kilau saat disentuh */
        .btn-primary, .btn-success, .btn-danger { position: relative; overflow: hidden; }
        .btn-primary::after, .btn-success::after, .btn-danger::after {
            content: ""; position: absolute; top: 0; left: -70%; width: 40%; height: 100%;
            background: linear-gradient(100deg, transparent, rgba(255, 255, 255, .28), transparent);
            transform: skewX(-20deg); pointer-events: none;
        }
        .btn-primary:hover::after, .btn-success:hover::after, .btn-danger:hover::after { animation: sheen .8s ease; }

        /* Keadaan memuat (aktifkan dengan atribut data-loading pada <form>) */
        .btn.is-loading { pointer-events: none; opacity: .85; }
        .btn.is-loading::before {
            content: ""; width: 16px; height: 16px; border-radius: 50%;
            border: 2.5px solid rgba(255, 255, 255, .4); border-top-color: currentColor;
            animation: spin .8s linear infinite;
        }

        /* Formulir */
        .form-label { margin-bottom: 8px; font-weight: 700; font-size: 1rem; color: var(--navy); }
        .form-label.required::after { content: " *"; color: var(--bad); }
        .form-label .optional { font-weight: 500; color: var(--slate); font-size: .9rem; }
        .form-text { margin-top: 6px; font-size: .9rem; color: var(--slate); }

        .form-control, .form-select {
            min-height: 54px; padding: 12px 16px;
            border: 1.5px solid #D5DEE7; border-radius: 12px;
            background-color: var(--white); color: var(--text); font-size: 1.05rem;
            transition: border-color var(--t-fast) ease, box-shadow var(--t-fast) ease, background-color var(--t-fast) ease;
        }
        .form-control::placeholder { color: #9AA8B6; }
        .form-control:hover:not(:disabled), .form-select:hover:not(:disabled) { border-color: #B9C8D6; }
        .form-control:focus, .form-select:focus { border-color: var(--blue); box-shadow: 0 0 0 4px rgba(66, 103, 137, .15); background-color: #fff; }
        .form-control:disabled, .form-select:disabled { background: #F1F4F7; color: var(--slate); }
        textarea.form-control { min-height: 110px; }
        .form-control.is-invalid, .form-select.is-invalid { border-color: var(--bad); background-color: #FFF9F8; }
        .form-control.is-invalid:focus, .form-select.is-invalid:focus { box-shadow: 0 0 0 4px rgba(179, 38, 30, .13); }
        .invalid-feedback { font-size: .92rem; font-weight: 600; color: var(--bad); }
        .form-check-input { width: 1.2em; height: 1.2em; border-color: #B9C8D6; }
        .form-check-input:checked { background-color: var(--navy); border-color: var(--navy); }
        .form-check-input:focus { box-shadow: 0 0 0 4px rgba(66, 103, 137, .15); border-color: var(--blue); }
        .input-group-text { border: 1.5px solid #D5DEE7; border-radius: 12px; background: var(--soft); color: var(--navy); }

        /* Tabel */
        .table { --bs-table-border-color: var(--line); --bs-table-hover-bg: #F4F8FB; --bs-table-color: var(--text); margin-bottom: 0; }
        .table > :not(caption) > * > * { padding: 17px 20px; vertical-align: middle; }
        .table thead th { background: var(--soft); color: var(--navy); font-size: 15px; font-weight: 700; border-bottom: 0; white-space: nowrap; }
        .table thead th:first-child { border-radius: 10px 0 0 10px; }
        .table thead th:last-child { border-radius: 0 10px 10px 0; }
        .table tbody tr { transition: background-color var(--t-fast) ease; animation: row-in .5s var(--ease) backwards; }
        .table tbody tr:nth-child(1) { animation-delay: .05s; }
        .table tbody tr:nth-child(2) { animation-delay: .10s; }
        .table tbody tr:nth-child(3) { animation-delay: .15s; }
        .table tbody tr:nth-child(4) { animation-delay: .20s; }
        .table tbody tr:nth-child(5) { animation-delay: .25s; }
        .table tbody tr:nth-child(6) { animation-delay: .30s; }
        .table tbody tr:nth-child(7) { animation-delay: .35s; }
        .table tbody tr:nth-child(8) { animation-delay: .40s; }
        .table tbody tr:nth-child(n+9) { animation-delay: .45s; }
        .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }

        /* Badge status: satu gaya untuk semua warna */
        .badge { padding: .5em .95em; border-radius: 999px; font-size: 14px; font-weight: 700; letter-spacing: 0; }
        .badge.bg-success, .badge.text-bg-success { background: var(--ok-soft) !important; color: var(--ok) !important; }
        .badge.bg-danger,  .badge.text-bg-danger  { background: var(--bad-soft) !important; color: var(--bad) !important; }
        .badge.bg-warning, .badge.text-bg-warning { background: var(--warn-soft) !important; color: var(--warn) !important; }
        .badge.bg-info,    .badge.text-bg-info,
        .badge.bg-primary, .badge.text-bg-primary { background: var(--info-soft) !important; color: var(--info) !important; }
        .badge.bg-secondary, .badge.text-bg-secondary,
        .badge.bg-light, .badge.text-bg-light { background: #EEF1F4 !important; color: var(--slate) !important; }

        /* Notifikasi */
        .alert { border: 0; border-radius: var(--r-md); padding: 16px 20px; font-size: 1rem; }
        .alert-success { background: var(--ok-soft); color: var(--ok); }
        .alert-danger  { background: var(--bad-soft); color: var(--bad); }
        .alert-warning { background: var(--warn-soft); color: var(--warn); }
        .alert-info    { background: var(--info-soft); color: var(--info); }
        .alert ul { padding-left: 1.2rem; }

        /* Progress */
        .progress { height: 10px; border-radius: 999px; background: var(--soft); }
        .progress-bar { background: linear-gradient(90deg, var(--steel), var(--navy)); border-radius: 999px; transition: width 1.1s var(--ease); }

        /* Pagination */
        .pagination { --bs-pagination-border-radius: 10px; gap: 6px; flex-wrap: wrap; }
        .page-link { min-width: 46px; min-height: 46px; display: grid; place-items: center; border: 1px solid var(--line); border-radius: 10px !important; color: var(--navy); font-weight: 600; transition: background-color var(--t-fast) ease; }
        .page-link:hover { background: var(--soft); color: var(--navy); }
        .page-item.active .page-link { background: var(--navy); border-color: var(--navy); color: #fff; }
        .page-item.disabled .page-link { color: #A5B2BF; background: #fff; }

        /* Dropdown */
        .dropdown-menu { padding: 8px; border: 1px solid var(--line); border-radius: var(--r-md); box-shadow: var(--sh-2); }
        .dropdown-menu.show { animation: drop .18s var(--ease); }
        .dropdown-item { min-height: 46px; display: flex; align-items: center; gap: 10px; border-radius: 10px; font-weight: 500; }
        .dropdown-item:hover, .dropdown-item:focus { background: var(--soft); color: var(--navy); }

        /* Modal */
        .modal.fade .modal-dialog { transform: translateY(22px) scale(.98); transition: transform var(--t-base) var(--ease); }
        .modal.show .modal-dialog { transform: none; }
        .modal-content { border: 0; border-radius: var(--r-lg); box-shadow: var(--sh-2); }
        .modal-header { padding: 20px 24px; border-bottom: 1px solid var(--line); }
        .modal-title { font-weight: 800; color: var(--navy); font-size: 1.3rem; }
        .modal-body { padding: 24px; }
        .modal-footer { padding: 16px 24px; border-top: 1px solid var(--line); gap: 10px; }
        .modal-backdrop { --bs-backdrop-bg: #0F2238; --bs-backdrop-opacity: .55; }

        .list-group { --bs-list-group-border-color: var(--line); --bs-list-group-border-radius: var(--r-md); }
        .list-group-item { padding: 14px 18px; }

        /* Keadaan kosong */
        .empty-state { padding: 48px 24px; text-align: center; }
        .empty-state .empty-icon { width: 72px; height: 72px; margin: 0 auto 16px; display: grid; place-items: center; border-radius: 50%; background: var(--soft); color: var(--blue); font-size: 32px; }
        .empty-state h3 { margin-bottom: 6px; font-size: 1.35rem; }
        .empty-state p { max-width: 420px; margin: 0 auto 18px; color: var(--slate); }

        /* Kerangka memuat */
        .skeleton { border-radius: 10px; background: linear-gradient(90deg, #EEF2F6 25%, #F7F9FB 37%, #EEF2F6 63%); background-size: 400% 100%; animation: shimmer 1.4s ease infinite; }

        /* Tabel menjadi kartu di HP (aktifkan dengan class .table-stack dan atribut data-label pada <td>) */
        @media (max-width: 767.98px) {
            .table-stack thead { display: none; }
            .table-stack, .table-stack tbody, .table-stack tr, .table-stack td { display: block; width: 100%; }
            .table-stack tr { margin-bottom: 12px; padding: 6px 0; border: 1px solid var(--line); border-radius: var(--r-md); background: var(--white); }
            .table-stack td { display: flex; justify-content: space-between; gap: 16px; padding: 10px 16px !important; border: 0 !important; text-align: right; }
            .table-stack td::before { content: attr(data-label); flex: 0 0 40%; text-align: left; font-weight: 700; color: var(--navy); }
        }

        /* Banner sambutan standar (hanya dashboard yang tidak punya banner sendiri) */
        .welcome {
            position: relative; overflow: hidden;
            display: flex; align-items: center; justify-content: space-between; gap: 32px;
            margin-bottom: 32px; padding: 40px 48px;
            border-radius: var(--r-lg); color: var(--white);
            background: linear-gradient(120deg, var(--navy) 0%, var(--midnight) 100%);
            box-shadow: var(--sh-2);
        }
        .welcome::before {
            content: ""; position: absolute; right: -90px; top: -130px; width: 400px; height: 400px; border-radius: 50%;
            background: radial-gradient(circle, rgba(111, 143, 170, .5), rgba(111, 143, 170, 0) 70%);
            animation: drift 20s ease-in-out infinite;
        }
        .welcome-text { position: relative; z-index: 1; max-width: 640px; }
        .welcome-hello { margin: 0 0 4px; font-size: 1.2rem; font-weight: 600; color: var(--sky); }
        .welcome h2 { margin: 0; color: var(--white); font-size: clamp(2rem, 3.2vw, 2.75rem); line-height: 1.15; overflow-wrap: anywhere; }
        .welcome-sub { margin: 12px 0 0; max-width: 540px; font-size: 1.15rem; color: rgba(255, 255, 255, .86); }
        .welcome-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 28px; }

        .welcome-art { position: relative; z-index: 1; flex: 0 0 270px; height: 210px; }
        .welcome-art .ring { position: absolute; inset: 0; margin: auto; width: 210px; height: 210px; border-radius: 50%; border: 2px dashed rgba(191, 217, 238, .42); animation: spin 50s linear infinite; }
        .tile {
            position: absolute; width: 72px; height: 72px; display: grid; place-items: center;
            border-radius: 22px; font-size: 34px; color: var(--white);
            background: rgba(255, 255, 255, .14); border: 1px solid rgba(255, 255, 255, .24);
        }
        .tile.t1 { left: 24px; top: 8px; animation: float 7s ease-in-out infinite; }
        .tile.t2 { right: 8px; top: 56px; color: #F0C45A; animation: float-2 9s ease-in-out infinite; }
        .tile.t3 { left: 92px; bottom: 0; animation: float-3 8s ease-in-out infinite; }

        /* =========================================================
           5. HOVER (hanya perangkat dengan mouse; tidak menempel di layar sentuh)
        ========================================================= */
        @media (hover: hover) {
            .menu-link:hover { background: rgba(255, 255, 255, .08); color: var(--white); transform: translateX(3px); }
            .menu-link:hover i { transform: scale(1.18) rotate(-8deg); }
            .menu-link.active:hover { transform: none; background: rgba(255, 255, 255, .13); }
        }

        /* =========================================================
           6. ANIMASI
        ========================================================= */
        @keyframes rise   { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
        @keyframes drop   { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: none; } }
        @keyframes drift  { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(-22px, 18px); } }
        @keyframes spin   { to { transform: rotate(360deg); } }
        @keyframes shimmer{ to { background-position: -135% 0; } }
        @keyframes float  { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-12px); } }
        @keyframes float-2{ 0%, 100% { transform: translateY(0); } 50% { transform: translateY(14px); } }
        @keyframes float-3{ 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(8px, -12px); } }
        @keyframes slide-in { from { opacity: 0; transform: translateX(-16px); } to { opacity: 1; transform: none; } }
        @keyframes row-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }
        @keyframes sheen  { to { left: 130%; } }
        @keyframes slide-bar { to { background-position: 200% 0; } }

        /* =========================================================
           7. RESPONSIF
        ========================================================= */
        @media (max-width: 1199.98px) {
            .header-date { display: none; }
        }

        @media (max-width: 991.98px) {
            /* Sidebar menjadi drawer. visibility mencegah menu tersembunyi ikut terfokus lewat keyboard. */
            .sidebar { transform: translateX(-100%); visibility: hidden; box-shadow: 10px 0 40px rgba(10, 24, 46, .28); width: min(300px, 86vw); min-width: 0; }
            .sidebar.show { transform: translateX(0); visibility: visible; }
            .main-area { width: 100%; margin-left: 0; }
            .mobile-menu-btn { display: inline-flex; }
            .header-avatar { display: grid; }
            .top-header { padding: 0 20px; }
            .content-area { padding: 26px 20px 34px; }
            .app-footer { padding: 18px 20px; }
            .bg-deco.d2 { display: none; }
            .welcome { padding: 32px 28px; }
        }

        @media (max-width: 767.98px) {
            .welcome { flex-direction: column; align-items: flex-start; }
            .welcome-art { display: none; }
        }

        @media (max-width: 575.98px) {
            html { font-size: 16px; }
            :root { --header-h: 72px; }
            .welcome { padding: 26px 22px; }
            .top-header { padding: 0 14px; }
            .header-title h1 { font-size: 19px; }
            .header-title p { display: none; }
            .header-avatar { width: 40px; height: 40px; }
            .content-area { padding: 20px 14px 28px; }
            .card-body { padding: 18px; }
            .page-head { margin-bottom: 18px; }
            .page-actions { width: 100%; }
            .page-actions .btn { flex: 1 1 auto; }
            .modal-body, .modal-header, .modal-footer { padding-left: 18px; padding-right: 18px; }
            .app-footer { text-align: center; }
            .app-footer .footer-row { justify-content: center !important; }
        }

        /* Menghormati pengguna yang mengurangi gerakan (berlaku untuk seluruh halaman) */
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; animation-delay: 0ms !important; transition-duration: .01ms !important; }
        }
    </style>

    @stack('styles')

</head>

<body>

@php
    use Illuminate\Support\Facades\Route;

    $authUser  = auth()->user();
    $role      = $authUser->role;
    $roleLabel = match ($role) {
        'admin'          => 'Administrator',
        'guru'           => 'Guru',
        'kepala_sekolah' => 'Kepala Sekolah',
        default          => 'Orang Tua',
    };
    $initial = strtoupper(mb_substr($authUser->name, 0, 1));

    $welcomeText = match ($role) {
        'admin'          => 'Kelola data pengguna, siswa, dan laporan sekolah dari satu tempat.',
        'guru'           => 'Catat perkembangan belajar siswa dan siapkan video pembelajaran.',
        'kepala_sekolah' => 'Lihat ringkasan perkembangan belajar siswa dengan mudah.',
        default          => 'Pantau perkembangan belajar anak Anda dengan mudah.',
    };

    /*
     * Banner sambutan standar hanya tampil di dashboard.
     * Halaman yang sudah punya banner sendiri (mis. dashboard admin) cukup
     * menulis @section('no_welcome', '1') supaya banner tidak tampil dua kali.
     */
    $showWelcome = request()->routeIs('dashboard') && ! $__env->hasSection('no_welcome');

    /*
     * Struktur menu. Route, nama route, dan hak akses per role
     * sama seperti sebelumnya, hanya dikelompokkan agar lebih jelas.
     * Format item: [route, pola aktif, ikon, label]
     */
    $groups = [];

    $groups[] = ['label' => 'Utama', 'items' => [
        ['dashboard', 'dashboard', 'bi-grid-1x2-fill', 'Dashboard'],
    ]];

        if ($role === 'admin') {
        $groups[] = ['label' => 'Pengelolaan', 'items' => [
            ['users.index', 'users.*', 'bi-people-fill', 'Data User'],
            ['students.index', 'students.*', 'bi-mortarboard-fill', 'Data Siswa'],
            ['classrooms.index', 'classrooms.*', 'bi-building-fill', 'Data Kelas'],
            ['subjects.index', 'subjects.*', 'bi-journal-bookmark-fill', 'Mata Pelajaran'],
            ['assignments.index', 'assignments.*', 'bi-diagram-3-fill', 'Penugasan Guru'],
        ]];
    }

    if ($role === 'guru') {
        $groups[] = ['label' => 'Pembelajaran', 'items' => [
            ['teacher.students.index', 'teacher.students.*', 'bi-people-fill', 'Siswa Saya'],
            ['developments.index', 'developments.*', 'bi-clipboard2-check-fill', 'Monitoring Siswa'],
            ['videos.index', 'videos.*', 'bi-play-circle-fill', 'Video Pembelajaran'],
        ]];
    }

    if (in_array($role, ['admin', 'guru', 'kepala_sekolah', 'orang_tua'], true)) {
        $groups[] = ['label' => 'Laporan', 'items' => [
            ['reports.index', 'reports.*', 'bi-file-earmark-text-fill', 'Laporan'],
        ]];
    }

    // Menu menjadi daftar siap tampil (href + status aktif)
    $menu = [];
    foreach ($groups as $group) {
        $items = [];
        foreach ($group['items'] as [$routeName, $pattern, $icon, $label]) {
            if (Route::has($routeName)) {
                $items[] = [
                    'href'   => route($routeName),
                    'active' => request()->routeIs($pattern),
                    'icon'   => $icon,
                    'label'  => $label,
                ];
            }
        }
        $menu[] = ['label' => $group['label'], 'items' => $items];
    }

    // Orang tua: satu menu untuk setiap anak
    if ($role === 'orang_tua' && Route::has('parent.child')) {
        $childItems = [];
        foreach ($authUser->children as $child) {
            $childItems[] = [
                'href'   => route('parent.child', $child),
                'active' => request()->routeIs('parent.child') && request()->route('student')?->id === $child->id,
                'icon'   => 'bi-person-vcard-fill',
                'label'  => $child->name,
            ];
        }
        array_splice($menu, 1, 0, [['label' => 'Anak Saya', 'items' => $childItems]]);
    }

    // Menu cepat di banner: maksimal tiga tujuan pertama selain Dashboard
    $quick = [];
    foreach (array_slice($menu, 1) as $quickGroup) {
        foreach ($quickGroup['items'] as $quickItem) {
            $quick[] = $quickItem;
        }
    }
    $quick = array_slice($quick, 0, 3);
@endphp

<div class="nav-progress" id="navProgress" aria-hidden="true"></div>
<span class="bg-deco d1" aria-hidden="true"></span>
<span class="bg-deco d2" aria-hidden="true"></span>

<div class="app-wrapper">

    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-brand">

            <div class="sidebar-logo">
                <img src="{{ asset('images/logosekolah.jpg') }}" alt="Logo UPTD SDN Kandangan Baru">
            </div>

            <div class="sidebar-brand-text">
                <strong>UPTD SDN Kandangan Baru</strong>
                <span>Monitoring Perkembangan Siswa</span>
            </div>

        </div>

        <nav class="sidebar-menu" aria-label="Menu utama">

            @foreach($menu as $group)
                @if(count($group['items']))

                    <div class="menu-group">

                        <div class="menu-title">{{ $group['label'] }}</div>

                        @foreach($group['items'] as $item)
                            <a
                                href="{{ $item['href'] }}"
                                class="menu-link {{ $item['active'] ? 'active' : '' }}"
                                @if($item['active']) aria-current="page" @endif
                            >
                                <i class="bi {{ $item['icon'] }}" aria-hidden="true"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endforeach

                    </div>

                @endif
            @endforeach

        </nav>

        {{-- USER --}}
        <div class="sidebar-user">

            <div class="sidebar-user-box">

                <div class="sidebar-user-info">

                    <div class="sidebar-avatar" aria-hidden="true">{{ $initial }}</div>

                    <div>
                        <div class="sidebar-user-name">{{ $authUser->name }}</div>
                        <div class="sidebar-user-role">{{ $roleLabel }}</div>
                    </div>

                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button type="submit" class="logout-btn">
                        <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                        Keluar dari Sistem
                    </button>
                </form>

            </div>

        </div>

    </aside>


    {{-- OVERLAY MOBILE --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>


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
                    id="menuButton"
                    onclick="toggleSidebar()"
                    aria-label="Buka menu"
                    aria-controls="sidebar"
                    aria-expanded="false"
                >
                    <i class="bi bi-list" aria-hidden="true"></i>
                </button>

                <div class="header-title">

                    @hasSection('breadcrumb')
                        <nav class="crumbs d-none d-md-block" aria-label="Breadcrumb">@yield('breadcrumb')</nav>
                    @endif

                    <h1>@yield('title', 'Dashboard')</h1>

                    <p>
                        @hasSection('subtitle')
                            @yield('subtitle')
                        @else
                            Sistem Informasi Monitoring Perkembangan Belajar Siswa
                        @endif
                    </p>

                </div>

            </div>


            <div class="header-right">

                <span class="header-date">
                    <i class="bi bi-calendar3" aria-hidden="true"></i>
                    <span id="headerDate">{{ date('d-m-Y') }}</span>
                </span>

                {{-- Hanya tampil di HP/tablet (di desktop identitas pengguna ada di sidebar) --}}
                <div class="header-avatar" aria-hidden="true">{{ $initial }}</div>

            </div>

        </header>


        {{-- CONTENT --}}
        <main class="content-area" id="content">

            <div class="content-wrapper">

                {{-- SUCCESS --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-start gap-2" role="status" data-autodismiss="7000">
                        <i class="bi bi-check-circle-fill mt-1" aria-hidden="true"></i>
                        <div class="flex-grow-1">{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif


                {{-- ERROR --}}
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start gap-2" role="alert">
                        <i class="bi bi-exclamation-circle-fill mt-1" aria-hidden="true"></i>
                        <div class="flex-grow-1">{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif


                {{-- VALIDATION --}}
                @if($errors->any())
                    <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
                        <i class="bi bi-exclamation-circle-fill mt-1" aria-hidden="true"></i>
                        <div>
                            <strong>Terdapat kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif


                {{-- BANNER SAMBUTAN (hanya dashboard tanpa banner sendiri) --}}
                @if($showWelcome)
                    <section class="welcome" aria-label="Selamat datang">

                        <div class="welcome-text">
                            <p class="welcome-hello" id="greeting">Selamat datang,</p>
                            <h2>{{ $authUser->name }}</h2>
                            <p class="welcome-sub">{{ $welcomeText }}</p>

                            @if(count($quick))
                                <div class="welcome-actions">
                                    @foreach($quick as $quickItem)
                                        <a href="{{ $quickItem['href'] }}" class="btn btn-light btn-lg">
                                            <i class="bi {{ $quickItem['icon'] }}" aria-hidden="true"></i>
                                            {{ $quickItem['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="welcome-art" aria-hidden="true">
                            <span class="ring"></span>
                            <span class="tile t1"><i class="bi bi-book-fill"></i></span>
                            <span class="tile t2"><i class="bi bi-star-fill"></i></span>
                            <span class="tile t3"><i class="bi bi-pencil-fill"></i></span>
                        </div>

                    </section>
                @endif


                @yield('content')

            </div>

        </main>


        {{-- FOOTER --}}
        <footer class="app-footer">

            <div class="footer-row d-flex justify-content-between align-items-center flex-wrap gap-2">

                <span>&copy; {{ date('Y') }} <strong>UPTD SDN Kandangan Baru</strong></span>

                <span class="footer-feature">
                    <i class="bi bi-whatsapp" aria-hidden="true"></i>
                    Terintegrasi dengan WhatsApp
                </span>

            </div>

        </footer>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

    var sidebarEl   = document.getElementById('sidebar');
    var overlayEl   = document.getElementById('sidebarOverlay');
    var menuButton  = document.getElementById('menuButton');
    var calmMotion  = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------- Sidebar (drawer di HP) ---------- */
    function openSidebar() {
        sidebarEl.classList.add('show');
        overlayEl.classList.add('show');
        document.body.classList.add('no-scroll');
        menuButton.setAttribute('aria-expanded', 'true');
        var first = sidebarEl.querySelector('.menu-link');
        if (first) { setTimeout(function () { first.focus({ preventScroll: true }); }, 320); }
    }

    function closeSidebar() {
        var wasOpen = sidebarEl.classList.contains('show');
        sidebarEl.classList.remove('show');
        overlayEl.classList.remove('show');
        document.body.classList.remove('no-scroll');
        menuButton.setAttribute('aria-expanded', 'false');
        if (wasOpen && window.innerWidth <= 991) { menuButton.focus({ preventScroll: true }); }
    }

    function toggleSidebar() {
        sidebarEl.classList.contains('show') ? closeSidebar() : openSidebar();
    }

    // Tutup drawer setelah memilih menu di HP
    document.querySelectorAll('.sidebar a').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth <= 991) { closeSidebar(); }
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { closeSidebar(); }
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth > 991) {
            sidebarEl.classList.remove('show');
            overlayEl.classList.remove('show');
            document.body.classList.remove('no-scroll');
            menuButton.setAttribute('aria-expanded', 'false');
        }
    });

    /* ---------- Tanggal dalam bahasa Indonesia ---------- */
    (function () {
        var el = document.getElementById('headerDate');
        if (el) {
            el.textContent = new Date().toLocaleDateString('id-ID', {
                weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
            });
        }
    })();

    /* ---------- Sapaan sesuai waktu (banner standar) ---------- */
    (function () {
        var el = document.getElementById('greeting');
        if (!el) { return; }
        var h = new Date().getHours();
        var s = h < 11 ? 'Selamat pagi' : (h < 15 ? 'Selamat siang' : (h < 18 ? 'Selamat sore' : 'Selamat malam'));
        el.textContent = s + ',';
    })();

    /* ---------- Notifikasi sukses menutup sendiri ---------- */
    document.querySelectorAll('[data-autodismiss]').forEach(function (el) {
        setTimeout(function () {
            if (document.body.contains(el)) { bootstrap.Alert.getOrCreateInstance(el).close(); }
        }, parseInt(el.dataset.autodismiss, 10) || 7000);
    });

    /* ---------- Tooltip (opsional: data-bs-toggle="tooltip") ---------- */
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
        new bootstrap.Tooltip(el);
    });

    /* ---------- Progress bar terisi bertahap ---------- */
    (function () {
        var bars = document.querySelectorAll('.progress-bar');
        if (calmMotion || !bars.length) { return; }

        bars.forEach(function (bar) {
            var target = bar.style.width;
            if (!target) { return; }
            bar.style.transition = 'none';
            bar.style.width = '0';
            bar.getBoundingClientRect();
            setTimeout(function () {
                bar.style.transition = '';
                bar.style.width = target;
            }, 250);
        });
    })();

    /* ---------- Angka bertambah (SATU-SATUNYA penghitung di seluruh sistem) ----------
       Halaman anak cukup memberi atribut data-count berisi angka pada elemen,
       tanpa menulis script hitung sendiri.
       Opsional: data-decimals="1" data-suffix="%"
    ------------------------------------------------------------------ */
    (function () {
        // Angka ringkasan di dalam .card / .stat-value otomatis ikut beranimasi
        document.querySelectorAll('.card h1, .card h2, .card h3, .card h4, .card h5, .card .fw-bold, .card [class*="fs-"], .card [class*="display-"], .stat-value').forEach(function (el) {
            if (el.hasAttribute('data-count') || el.children.length || el.closest('table, form')) { return; }
            var t = el.textContent.trim();
            var n = null;
            if (/^\d{1,3}(\.\d{3})+$/.test(t)) { n = t.replace(/\./g, ''); }
            else if (/^(0|[1-9]\d{0,5})$/.test(t)) { n = t; }
            if (n !== null) { el.setAttribute('data-count', n); }
        });

        var nodes = document.querySelectorAll('[data-count]');
        if (!nodes.length || calmMotion) { return; }   // gerak dikurangi: angka tetap tampil apa adanya

        function format(value, decimals, suffix) {
            return value.toLocaleString('id-ID', {
                minimumFractionDigits: decimals, maximumFractionDigits: decimals
            }) + suffix;
        }

        function run(el) {
            var end = parseFloat(el.dataset.count);
            if (isNaN(end) || end <= 0) { return; }
            var decimals = parseInt(el.dataset.decimals || '0', 10);
            var suffix   = el.dataset.suffix || '';
            var duration = 1100, start = null;

            function step(ts) {
                if (start === null) { start = ts; }
                var p = Math.min((ts - start) / duration, 1);
                var eased = 1 - Math.pow(1 - p, 3);
                el.textContent = format(end * eased, decimals, suffix);
                if (p < 1) { requestAnimationFrame(step); }
            }
            requestAnimationFrame(step);
        }

        if ('IntersectionObserver' in window) {
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) { run(entry.target); io.unobserve(entry.target); }
                });
            }, { threshold: .3 });
            nodes.forEach(function (n) { io.observe(n); });
        } else {
            nodes.forEach(run);
        }
    })();

    /* ---------- Tombol memuat (hanya form dengan atribut data-loading) ---------- */
    document.querySelectorAll('form[data-loading]').forEach(function (form) {
        form.addEventListener('submit', function () {
            var btn = form.querySelector('button[type="submit"]');
            if (btn) {
                setTimeout(function () {
                    btn.classList.add('is-loading');
                    btn.setAttribute('aria-busy', 'true');
                }, 0);
            }
        });
    });

    /* ---------- Garis progres saat berpindah halaman ---------- */
    (function () {
        var bar = document.getElementById('navProgress');

        document.addEventListener('click', function (e) {
            var a = e.target.closest('a[href]');
            if (!a || e.defaultPrevented || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) { return; }
            if (a.target === '_blank' || a.hasAttribute('download') || a.hasAttribute('data-bs-toggle')) { return; }
            var url = new URL(a.href, location.href);
            if (url.origin !== location.origin) { return; }
            if (url.pathname === location.pathname && url.search === location.search) { return; }
            bar.classList.add('run');
        });

        window.addEventListener('pageshow', function () {
            bar.classList.remove('run');
            document.querySelectorAll('.btn.is-loading').forEach(function (b) {
                b.classList.remove('is-loading');
                b.removeAttribute('aria-busy');
            });
        });
    })();

</script>

@stack('scripts')

</body>
</html>
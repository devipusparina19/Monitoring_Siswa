<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#142C46">
    <meta name="description" content="Sistem Informasi Monitoring Perkembangan Belajar Siswa UPTD SDN Kandangan Baru">
    <title>Masuk | Monitoring Perkembangan Belajar Siswa</title>

    {{-- Foto sekolah dimuat lebih awal agar tampil langsung tajam --}}
    <link rel="preload" as="image" href="{{ asset('images/sekolah.jpg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* =========================================================
           TOKEN: nilainya sama dengan layouts.app supaya halaman
           login dan aplikasi terasa satu keluarga.
        ========================================================= */
        :root {
            --navy: #17365D;
            --midnight: #142C46;
            --blue: #426789;
            --steel: #6F8FAA;
            --sky: #BFD9EE;
            --soft: #EAF1F7;
            --paper: #FAFAF7;
            --text: #263445;
            --slate: #64748B;
            --line: #DDE4EB;
            --gold: #E8B53C;
            --bad: #B3261E;
            --bad-soft: #FBEAE8;
            --font: "Plus Jakarta Sans", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            --ease: cubic-bezier(.22, .8, .25, 1);
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { -webkit-text-size-adjust: 100%; }

        body {
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            font-family: var(--font);
            background: var(--midnight);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        :focus-visible { outline: 3px solid rgba(191, 217, 238, .8); outline-offset: 2px; }

        /* =========================================================
           LATAR: FOTO SEKOLAH ASLI (tajam, tanpa blur, tanpa zoom)
        ========================================================= */
        .bg { position: fixed; inset: 0; z-index: -3; background: var(--midnight); }
        .bg img {
            width: 100%; height: 100%; display: block;
            object-fit: cover; object-position: center;
            filter: contrast(1.05) saturate(1.06);
        }

        .shade {
            position: fixed; inset: 0; z-index: -2;
            background: linear-gradient(90deg,
                rgba(10, 24, 42, .88) 0%,
                rgba(20, 44, 70, .64) 40%,
                rgba(20, 44, 70, .12) 72%,
                rgba(20, 44, 70, .22) 100%);
        }

        .arc { position: fixed; z-index: -1; border-radius: 50%; border: 1.5px solid rgba(255, 255, 255, .2); pointer-events: none; }
        .arc.a1 { width: 760px; height: 760px; right: -240px; top: -180px; animation: drift 22s ease-in-out infinite; }
        .arc.a2 { width: 460px; height: 460px; right: 60px; bottom: -260px; border-color: rgba(191, 217, 238, .26); animation: drift 18s ease-in-out -7s infinite; }

        /* =========================================================
           HEADER
        ========================================================= */
        .header {
            position: relative; z-index: 10;
            display: flex; align-items: center; justify-content: space-between; gap: 16px;
            padding: 20px 48px;
            background: linear-gradient(180deg, rgba(10, 24, 42, .72), rgba(10, 24, 42, 0));
        }
        .brand { display: flex; align-items: center; gap: 13px; min-width: 0; }
        .brand-logo {
            width: 52px; height: 52px; flex: 0 0 52px; padding: 3px;
            border-radius: 14px; background: #fff;
            box-shadow: 0 8px 22px rgba(0, 0, 0, .28);
        }
        .brand-logo img { width: 100%; height: 100%; display: block; object-fit: contain; border-radius: 11px; }
        .brand-text { display: flex; flex-direction: column; line-height: 1.3; min-width: 0; color: #fff; }
        .brand-text strong { font-size: 16px; font-weight: 800; letter-spacing: -.01em; }
        .brand-text span { font-size: 13px; font-weight: 500; color: rgba(255, 255, 255, .78); }

        /* =========================================================
           AREA UTAMA
        ========================================================= */
        .main { flex: 1; display: flex; align-items: center; padding: 12px 48px 36px; }

        .wrap {
            width: 100%; max-width: 1300px; margin: 0 auto;
            display: grid; grid-template-columns: minmax(0, 1fr) 450px;
            align-items: center; gap: 64px;
        }

        /* ---------- Kiri ---------- */
        .hero { color: #fff; min-width: 0; }

        .hero h1 {
            max-width: 620px;
            font-size: clamp(36px, 4.2vw, 60px);
            line-height: 1.08; font-weight: 800; letter-spacing: -.035em;
            text-shadow: 0 2px 24px rgba(5, 15, 32, .5);
        }
        .hero h1 .line { display: block; overflow: hidden; padding-bottom: .06em; }
        .hero h1 .line > span { display: block; transform: translateY(105%); animation: reveal .95s var(--ease) forwards; }
        .hero h1 .line:nth-child(1) > span { animation-delay: .25s; }
        .hero h1 .line:nth-child(2) > span { animation-delay: .4s; }

        .stroke { display: block; width: 190px; height: 16px; margin-top: 8px; }
        .stroke path {
            fill: none; stroke: var(--sky); stroke-width: 4; stroke-linecap: round;
            stroke-dasharray: 1; stroke-dashoffset: 1;
            animation: draw 1.4s cubic-bezier(.5, 0, .2, 1) 1s forwards;
        }

        .hero p {
            max-width: 470px; margin-top: 16px;
            font-size: 18px; line-height: 1.65; color: rgba(255, 255, 255, .92);
            text-shadow: 0 1px 14px rgba(5, 15, 32, .55);
            animation: rise .9s var(--ease) .7s both;
        }

        /* ---------- Ilustrasi: buku terbuka, grafik tumbuh dari halamannya ----------
           Murni ilustrasi. Tidak ada angka atau nilai yang ditampilkan. */
        .visual { position: relative; width: min(560px, 100%); height: 340px; margin-top: 26px; }

        .book {
            position: absolute; left: 50%; bottom: 0; width: min(470px, 88%); transform: translateX(-50%);
            animation: rise 1.1s var(--ease) .5s both;
            filter: drop-shadow(0 22px 30px rgba(4, 12, 26, .4));
        }
        .book svg { width: 100%; height: auto; display: block; overflow: visible; }

        .page-l, .page-r { transform-box: fill-box; opacity: 0; animation: open 1.8s var(--ease) .9s forwards; }
        .page-l { transform-origin: 100% 50%; }
        .page-r { transform-origin: 0% 50%; }

        .bar { transform-box: fill-box; transform-origin: 50% 100%; transform: scaleY(0); animation: grow 1.2s var(--ease) forwards; }
        .bar:nth-of-type(1) { animation-delay: 2.0s; }
        .bar:nth-of-type(2) { animation-delay: 2.15s; }
        .bar:nth-of-type(3) { animation-delay: 2.3s; }
        .bar:nth-of-type(4) { animation-delay: 2.45s; }
        .bar:nth-of-type(5) { animation-delay: 2.6s; }

        .trend { stroke-dasharray: 1; stroke-dashoffset: 1; animation: draw 2s ease 2.5s forwards; }
        .trend-dot { opacity: 0; animation: fade .6s ease 4.2s forwards; }

        /* Tiga kartu kecil yang menjelaskan alur sistem (sama dengan dashboard admin) */
        .fx { position: absolute; will-change: transform; transition: transform .35s ease-out; }
        .fx.f1 { left: 0;  top: 2%; --d: 1.5s; --float: float-a; --fd: 7s; }
        .fx.f2 { right: 0; top: 24%; --d: 1.8s; --float: float-b; --fd: 9s; }
        .fx.f3 { left: 4%; bottom: 14%; --d: 2.1s; --float: float-c; --fd: 8s; }

        .info-card {
            display: flex; align-items: center; gap: 12px;
            width: 230px; padding: 13px 16px; border-radius: 16px;
            background: #fff; color: var(--navy);
            font-size: 14px; font-weight: 700; line-height: 1.35;
            box-shadow: 0 20px 44px rgba(4, 12, 26, .36);
            animation: pop .9s var(--ease) var(--d) both, var(--float) var(--fd) ease-in-out calc(var(--d) + .9s) infinite;
        }
        .info-ico {
            width: 38px; height: 38px; flex: 0 0 38px; display: grid; place-items: center;
            border-radius: 12px; background: var(--soft); color: var(--navy);
        }
        .info-ico svg { width: 19px; height: 19px; }
        .f2 .info-ico { background: #E5F4EC; color: #1F7A55; }

        /* =========================================================
           KARTU LOGIN
        ========================================================= */
        .card {
            position: relative; width: 100%; padding: 38px 38px 32px;
            border-radius: 24px; background: var(--paper);
            box-shadow: 0 34px 80px rgba(4, 12, 26, .45);
            animation: rise .9s var(--ease) .35s both;
        }
        .card::before {
            content: ""; position: absolute; left: 38px; top: 0; width: 84px; height: 5px;
            border-radius: 0 0 6px 6px; background: linear-gradient(90deg, var(--navy), var(--steel));
        }

        .card h2 { font-size: 30px; line-height: 1.2; font-weight: 800; letter-spacing: -.025em; color: var(--navy); }
        .card .sub { margin-top: 8px; font-size: 16px; line-height: 1.6; color: var(--slate); }

        .alert {
            display: flex; gap: 10px; align-items: flex-start;
            margin-top: 20px; padding: 12px 14px; border-radius: 13px;
            background: var(--bad-soft); color: var(--bad);
            font-size: 14.5px; font-weight: 600; line-height: 1.55;
            animation: nudge .5s ease .1s both;
        }
        .alert svg { width: 18px; height: 18px; flex: 0 0 18px; margin-top: 3px; }

        form { margin-top: 24px; }
        .field { margin-bottom: 18px; }
        .field label { display: block; margin-bottom: 8px; font-size: 15px; font-weight: 700; color: var(--navy); }

        .input-wrap { position: relative; }
        .lead-icon {
            position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
            width: 20px; height: 20px; color: #8A98A6; pointer-events: none;
            transition: color .2s ease;
        }
        .input-wrap:focus-within .lead-icon { color: var(--blue); }

        .input {
            width: 100%; height: 56px; padding: 0 16px 0 48px;
            border: 1.5px solid var(--line); border-radius: 12px; background: #fff;
            color: var(--text); font-family: var(--font); font-size: 16px; outline: none;
            transition: border-color .2s ease, box-shadow .25s ease;
        }
        .input::placeholder { color: #9AA8B6; }
        .input:hover { border-color: #C3CFDA; }
        .input:focus { border-color: var(--blue); box-shadow: 0 0 0 4px rgba(66, 103, 137, .16); }
        .input.is-invalid { border-color: var(--bad); background: #FFF9F8; }
        .input.has-toggle { padding-right: 58px; }

        .toggle {
            position: absolute; top: 50%; right: 6px; transform: translateY(-50%);
            width: 46px; height: 46px; display: grid; place-items: center;
            border: 0; border-radius: 10px; background: transparent; color: var(--slate); cursor: pointer;
            transition: background-color .2s ease, color .2s ease;
        }
        .toggle:hover { background: var(--soft); color: var(--navy); }
        .toggle svg { width: 22px; height: 22px; }
        .toggle .icon-hide { display: none; }
        .toggle.is-visible .icon-show { display: none; }
        .toggle.is-visible .icon-hide { display: block; }

        .toggle:focus-visible, .button:focus-visible { outline: 3px solid rgba(66, 103, 137, .5); outline-offset: 2px; }

        .button {
            position: relative; overflow: hidden; width: 100%; height: 58px; margin-top: 8px;
            border: 0; border-radius: 12px;
            background: var(--navy);
            color: #fff; font-family: var(--font); font-size: 16.5px; font-weight: 800; cursor: pointer;
            box-shadow: 0 12px 26px rgba(20, 44, 70, .3);
            transition: transform .25s ease, box-shadow .25s ease, background-color .25s ease;
        }
        .button::before {
            content: ""; position: absolute; top: 0; left: -60%; width: 40%; height: 100%;
            background: linear-gradient(100deg, transparent, rgba(255, 255, 255, .28), transparent);
            transform: skewX(-20deg); pointer-events: none;
        }
        .button:hover { background: #1F4676; transform: translateY(-2px); box-shadow: 0 18px 34px rgba(20, 44, 70, .38); }
        .button:hover::before { animation: sheen .9s ease; }
        .button:active { transform: translateY(0) scale(.99); }
        .button .label { position: relative; display: inline-flex; align-items: center; justify-content: center; gap: 10px; }
        .button .arrow { width: 20px; height: 20px; transition: transform .25s ease; }
        .button:hover .arrow { transform: translateX(5px); }

        .spinner {
            display: none; width: 20px; height: 20px;
            border: 3px solid rgba(255, 255, 255, .35); border-top-color: #fff; border-radius: 50%;
            animation: spin .8s linear infinite;
        }
        .button.is-loading { cursor: progress; pointer-events: none; filter: saturate(.8); }
        .button.is-loading .spinner { display: inline-block; }
        .button.is-loading .arrow { display: none; }

        .help {
            display: flex; gap: 12px; align-items: flex-start;
            margin-top: 22px; padding: 14px 16px; border-radius: 12px;
            background: var(--soft); font-size: 14.5px; line-height: 1.6; color: var(--blue);
        }
        .help svg { width: 20px; height: 20px; flex: 0 0 20px; margin-top: 2px; }
        .help strong { display: block; color: var(--navy); font-weight: 800; }

        /* =========================================================
           FOOTER
        ========================================================= */
        .footer {
            position: relative; z-index: 10;
            padding: 26px 48px 22px;
            background: linear-gradient(0deg, rgba(10, 24, 42, .78), rgba(10, 24, 42, 0));
            color: rgba(255, 255, 255, .84); font-size: 14px;
        }
        .footer strong { color: #fff; font-weight: 700; }

        /* =========================================================
           ANIMASI
        ========================================================= */
        .rise { animation: rise .9s var(--ease) both; animation-delay: calc(var(--i, 0) * .13s + .1s); }

        @keyframes rise   { from { opacity: 0; transform: translateY(22px); } to { opacity: 1; transform: none; } }
        @keyframes reveal { to { transform: translateY(0); } }
        @keyframes fade   { to { opacity: 1; } }
        @keyframes pop    { from { opacity: 0; transform: translateY(18px) scale(.94); } to { opacity: 1; transform: none; } }
        @keyframes open   { from { opacity: 0; transform: scaleX(.04); } to { opacity: 1; transform: scaleX(1); } }
        @keyframes grow   { to { transform: scaleY(1); } }
        @keyframes draw   { to { stroke-dashoffset: 0; } }
        @keyframes float-a{ 0%, 100% { transform: translateY(0); }  50% { transform: translateY(-12px); } }
        @keyframes float-b{ 0%, 100% { transform: translateY(0); }  50% { transform: translateY(14px); } }
        @keyframes float-c{ 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(6px, -10px); } }
        @keyframes drift  { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(-24px, 20px); } }
        @keyframes sheen  { to { left: 130%; } }
        @keyframes spin   { to { transform: rotate(360deg); } }
        @keyframes nudge  { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-6px); } 75% { transform: translateX(6px); } }

        /* =========================================================
           LAPTOP KECIL
        ========================================================= */
        @media (max-width: 1220px) {
            .header { padding: 18px 32px; }
            .main { padding: 12px 32px 32px; }
            .wrap { grid-template-columns: minmax(0, 1fr) 420px; gap: 36px; }
            .info-card { width: 208px; }
            .footer { padding: 24px 32px 20px; }
        }

        /* =========================================================
           TABLET & HP: disusun ulang, form tetap prioritas
        ========================================================= */
        @media (max-width: 980px) {
            .shade {
                background: linear-gradient(180deg,
                    rgba(10, 24, 42, .80) 0%, rgba(20, 44, 70, .58) 46%, rgba(10, 24, 42, .78) 100%);
            }
            .arc.a1 { width: 480px; height: 480px; right: -220px; top: -120px; }
            .arc.a2 { display: none; }

            .header { padding: 16px 20px; }
            .main { align-items: flex-start; padding: 8px 20px 28px; }
            .wrap { grid-template-columns: 1fr; gap: 22px; max-width: 520px; }

            .hero h1 { font-size: 33px; }
            .hero p { margin-top: 12px; font-size: 16px; }
            .stroke { width: 150px; }

            .visual { height: 210px; margin-top: 16px; }
            .book { width: min(330px, 80%); }
            .f2 { display: none; }
            .fx.f3 { left: auto; right: 0; bottom: 20%; }
            .info-card { width: 190px; padding: 11px 13px; font-size: 13.5px; }

            .card { padding: 32px 24px 26px; border-radius: 20px; }
            .card::before { left: 24px; }
            .card h2 { font-size: 27px; }
            .footer { padding: 18px 20px 20px; }
        }

        @media (max-width: 480px) {
            .header { padding: 14px; }
            .brand-logo { width: 46px; height: 46px; flex-basis: 46px; }
            .brand-text strong { font-size: 15px; }
            .main { padding: 6px 14px 24px; }
            .hero h1 { font-size: 29px; }
            .visual { height: 180px; }
            .f3 { display: none; }
            .card { padding: 28px 20px 24px; }
            .card::before { left: 20px; }
            .card h2 { font-size: 25px; }
            .arc { animation: none; }
        }

        @media (hover: none) { .fx { transition: none; } }

        /* Pengguna yang mengurangi gerakan: semua animasi selesai seketika */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-delay: 0ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
            .spinner { animation-duration: 1.5s !important; animation-iteration-count: infinite !important; }
        }
    </style>
</head>

<body>

{{-- LATAR: foto sekolah asli --}}
<div class="bg" aria-hidden="true">
    <img src="{{ asset('images/sekolah.jpg') }}" alt="" fetchpriority="high" decoding="async">
</div>
<div class="shade" aria-hidden="true"></div>
<span class="arc a1" aria-hidden="true"></span>
<span class="arc a2" aria-hidden="true"></span>


{{-- HEADER --}}
<header class="header">
    <div class="brand rise" style="--i:0">
        <div class="brand-logo">
            <img src="{{ asset('images/logosekolah.jpg') }}" alt="Logo UPTD SDN Kandangan Baru">
        </div>
        <div class="brand-text">
            <strong>UPTD SDN Kandangan Baru</strong>
            <span>Monitoring Perkembangan Siswa</span>
        </div>
    </div>
</header>


{{-- UTAMA --}}
<main class="main" id="main">
    <div class="wrap">

        {{-- ================= KIRI ================= --}}
        <section class="hero">

            <h1>
                <span class="line"><span>Setiap Perkembangan</span></span>
                <span class="line"><span>Anak, Berarti.</span></span>
            </h1>

            <svg class="stroke" viewBox="0 0 190 16" aria-hidden="true"><path pathLength="1" d="M3 11 C 40 2, 80 4, 118 8 S 170 12, 187 5"/></svg>

            <p>Kenali proses belajar anak, dampingi setiap perkembangannya.</p>

            <div class="visual" aria-hidden="true">

                {{-- Buku terbuka, grafik tumbuh dari halamannya (ilustrasi, bukan data) --}}
                <div class="book">
                    <svg viewBox="0 0 500 300" fill="none">
                        <ellipse cx="250" cy="278" rx="210" ry="14" fill="rgba(0,0,0,.22)"/>

                        <g class="page-l">
                            <path d="M250 70 C200 48 110 48 40 66 L40 250 C110 232 200 232 250 254 Z" fill="#FAF7F2"/>
                            <path d="M250 70 C200 48 110 48 40 66 L40 76 C110 58 200 58 250 80 Z" fill="#E4EBF2"/>
                            <g stroke="#CFE3F3" stroke-width="3" stroke-linecap="round">
                                <path d="M70 110 H170"/><path d="M70 130 H200"/><path d="M70 150 H150"/><path d="M70 170 H190"/>
                            </g>
                            <circle cx="86" cy="205" r="9" fill="#E8B53C"/>
                            <path d="M106 205 H190" stroke="#CFE3F3" stroke-width="3" stroke-linecap="round"/>
                        </g>

                        <g class="page-r">
                            <path d="M250 70 C300 48 390 48 460 66 L460 250 C390 232 300 232 250 254 Z" fill="#FFFFFF"/>
                            <path d="M250 70 C300 48 390 48 460 66 L460 76 C390 58 300 58 250 80 Z" fill="#E4EBF2"/>
                            <rect class="bar" x="285" y="190" width="24" height="42"  rx="6" fill="#CFE3F3"/>
                            <rect class="bar" x="319" y="168" width="24" height="64"  rx="6" fill="#8FB3D1"/>
                            <rect class="bar" x="353" y="146" width="24" height="86"  rx="6" fill="#4F7396"/>
                            <rect class="bar" x="387" y="120" width="24" height="112" rx="6" fill="#315B82"/>
                            <rect class="bar" x="421" y="96"  width="24" height="136" rx="6" fill="#17365D"/>
                        </g>

                        <path class="trend" pathLength="1" d="M296 176 C320 160 338 150 365 128 S 420 98 433 78"
                              stroke="#E8B53C" stroke-width="4" stroke-linecap="round"/>
                        <circle class="trend-dot" cx="433" cy="78" r="7" fill="#E8B53C"/>
                        <circle class="trend-dot" cx="433" cy="78" r="13" fill="none" stroke="#E8B53C" stroke-opacity=".45"/>

                        <path d="M250 70 V254" stroke="rgba(16,40,74,.18)" stroke-width="2"/>
                    </svg>
                </div>

                {{-- Tiga langkah yang dijalankan sistem --}}
                <div class="fx f1" data-depth="14">
                    <div class="info-card">
                        <span class="info-ico">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                        </span>
                        Guru mencatat perkembangan
                    </div>
                </div>

                <div class="fx f2" data-depth="-20">
                    <div class="info-card">
                        <span class="info-ico">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                        </span>
                        Orang tua menerima kabar
                    </div>
                </div>

                <div class="fx f3" data-depth="10">
                    <div class="info-card">
                        <span class="info-ico">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17l6-6 4 4 8-8"/><path d="M15 7h6v6"/></svg>
                        </span>
                        Anak terus berkembang
                    </div>
                </div>

            </div>

        </section>


        {{-- ================= KANAN: LOGIN ================= --}}
        <section class="card" aria-label="Form masuk">

            <h2>Selamat Datang</h2>
            <p class="sub">Masukkan email dan kata sandi Anda untuk masuk ke sistem.</p>

            @if($errors->any())
                <div class="alert" role="alert" id="loginError">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v5"/><path d="M12 16.5v.01"/></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- Route, method, CSRF, dan name input sama dengan sebelumnya --}}
            <form id="loginForm" action="{{ route('login.process') }}" method="POST" novalidate>
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <svg class="lead-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                        <input
                            type="email" id="email" name="email"
                            class="input @if($errors->has('email')) is-invalid @endif"
                            value="{{ old('email') }}"
                            placeholder="Contoh: nama@email.com"
                            autocomplete="email" inputmode="email"
                            autocapitalize="none" spellcheck="false"
                            @if($errors->has('email')) aria-invalid="true" aria-describedby="loginError" @endif
                            required
                        >
                    </div>
                </div>

                <div class="field">
                    <label for="password">Kata sandi</label>
                    <div class="input-wrap">
                        <svg class="lead-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>
                        <input
                            type="password" id="password" name="password"
                            class="input has-toggle @if($errors->has('password')) is-invalid @endif"
                            placeholder="Masukkan kata sandi"
                            autocomplete="current-password"
                            @if($errors->has('password')) aria-invalid="true" aria-describedby="loginError" @endif
                            required
                        >
                        <button type="button" class="toggle" id="togglePassword" aria-label="Tampilkan kata sandi" aria-controls="password">
                            <svg class="icon-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="icon-hide" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17.9 17.9A10.7 10.7 0 0 1 12 19c-6.4 0-10-7-10-7a18 18 0 0 1 4.1-5"/><path d="M9.9 5.2A10 10 0 0 1 12 5c6.4 0 10 7 10 7a18 18 0 0 1-2.2 3.2"/><path d="M14.1 14.1a3 3 0 1 1-4.2-4.2"/><path d="m2 2 20 20"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="button" id="loginButton">
                    <span class="label">
                        <span class="spinner" aria-hidden="true"></span>
                        <span id="loginText">Masuk</span>
                        <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                    </span>
                </button>
            </form>

            <div class="help">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M9.1 9a3 3 0 0 1 5.8 1c0 2-3 2.5-3 4.5"/><path d="M12 17.5v.01"/></svg>
                <span>
                    <strong>Mengalami kendala masuk?</strong>
                    Hubungi guru kelas atau admin sekolah untuk dibantu.
                </span>
            </div>

        </section>

    </div>
</main>


{{-- FOOTER --}}
<footer class="footer">
    <span>&copy; {{ date('Y') }} <strong>UPTD SDN Kandangan Baru</strong></span>
</footer>


<script>
(function () {

    var calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var fine = window.matchMedia('(hover: hover) and (pointer: fine) and (min-width: 981px)').matches;

    /* Parallax halus mengikuti mouse (hanya desktop) */
    if (fine && !calm) {
        var main  = document.getElementById('main');
        var items = document.querySelectorAll('.fx');
        var tx = 0, ty = 0, raf = null;

        main.addEventListener('mousemove', function (e) {
            tx = e.clientX / window.innerWidth  - 0.5;
            ty = e.clientY / window.innerHeight - 0.5;
            if (raf) { return; }
            raf = requestAnimationFrame(function () {
                items.forEach(function (el) {
                    var d = parseFloat(el.dataset.depth || 10);
                    el.style.transform = 'translate3d(' + (tx * d).toFixed(1) + 'px,' + (ty * d).toFixed(1) + 'px,0)';
                });
                raf = null;
            });
        });
    }

    /* Fokus otomatis hanya di desktop. Di HP, keyboard yang langsung muncul
       akan menutupi tampilan, jadi dibiarkan pengguna menekan kolom sendiri. */
    if (fine) {
        var firstInvalid = document.querySelector('.input.is-invalid');
        (firstInvalid || document.getElementById('email')).focus({ preventScroll: true });
    }

    /* Tampilkan / sembunyikan kata sandi */
    var password = document.getElementById('password');
    var toggle   = document.getElementById('togglePassword');

    toggle.addEventListener('click', function () {
        var show = password.type === 'password';
        password.type = show ? 'text' : 'password';
        toggle.classList.toggle('is-visible', show);
        toggle.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
        password.focus();
    });

    /* Animasi memuat saat login (form tetap dikirim ke Laravel) */
    var form   = document.getElementById('loginForm');
    var button = document.getElementById('loginButton');
    var text   = document.getElementById('loginText');

    form.addEventListener('submit', function () {
        setTimeout(function () {
            button.classList.add('is-loading');
            button.setAttribute('aria-busy', 'true');
            text.textContent = 'Memproses...';
        }, 0);
    });

    window.addEventListener('pageshow', function (e) {
        if (e.persisted) {
            button.classList.remove('is-loading');
            button.removeAttribute('aria-busy');
            text.textContent = 'Masuk';
        }
    });

})();
</script>

</body>
</html>
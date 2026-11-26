@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('no_welcome', '1')

@push('styles')
<style>

    /* =====================================================
       Semua token (warna, radius, bayangan, easing) memakai
       variabel dari layouts.app. Tidak didefinisikan ulang di sini.
       Semua class diberi awalan "dx-" supaya tidak bentrok.
    ===================================================== */
    .dx { color: var(--text); }

    /* ---------- Judul bagian ---------- */
    .dx-section { margin-bottom: 40px; }

    .dx-section-head { margin-bottom: 16px; }

    .dx-section-title {
        margin: 0 0 4px;
        font-size: 21px;
        font-weight: 800;
        letter-spacing: -.015em;
        color: var(--navy);
    }

    .dx-section-hint {
        margin: 0;
        font-size: 14.5px;
        line-height: 1.55;
        color: var(--slate);
    }

    /* =====================================================
       BANNER UTAMA (foto sekolah asli tetap dipakai)
    ===================================================== */
    .dx-hero {
        position: relative;
        overflow: hidden;
        isolation: isolate;
        margin-bottom: 36px;
        padding: 44px 48px;
        min-height: 300px;
        display: grid;
        grid-template-columns: minmax(0, 1fr) 420px;
        align-items: center;
        gap: 32px;
        border-radius: var(--r-lg);
        color: #ffffff;
        background-color: var(--midnight);
        box-shadow: 0 20px 44px rgba(20, 44, 70, .25);
        --mx: 0;
        --my: 0;
    }

    .dx-hero-photo {
        position: absolute;
        inset: 0;
        z-index: -3;
        background-image: url("{{ asset('images/sekolah.jpg') }}");
        background-size: cover;
        background-position: center;
        filter: contrast(1.05) saturate(1.06);
    }

    .dx-hero-shade {
        position: absolute;
        inset: 0;
        z-index: -2;
        background: linear-gradient(90deg,
            rgba(10, 24, 42, .92) 0%,
            rgba(20, 44, 70, .74) 45%,
            rgba(20, 44, 70, .28) 100%);
    }

    .dx-hero-ring {
        position: absolute;
        z-index: -1;
        border-radius: 50%;
        border: 1.5px solid rgba(255, 255, 255, .18);
        pointer-events: none;
    }

    .dx-hero-ring.r1 { width: 420px; height: 420px; right: -120px; top: -190px; animation: dx-drift 22s ease-in-out infinite; }
    .dx-hero-ring.r2 { width: 200px; height: 200px; left: 38%; bottom: -110px; border-color: rgba(207, 227, 243, .24); animation: dx-drift 16s ease-in-out -6s infinite; }

    .dx-hero-text { min-width: 0; }

    .dx-hero h1 {
        margin: 0 0 12px;
        max-width: 560px;
        font-size: clamp(28px, 3vw, 40px);
        line-height: 1.15;
        font-weight: 800;
        letter-spacing: -.03em;
        color: #ffffff;
        text-shadow: 0 2px 20px rgba(5, 15, 32, .4);
    }

    .dx-hero-text p {
        margin: 0;
        max-width: 480px;
        font-size: 16.5px;
        line-height: 1.7;
        color: rgba(255, 255, 255, .92);
    }

    .dx-hero-line {
        display: block;
        width: 150px;
        height: 14px;
        margin-top: 14px;
    }

    .dx-hero-line path {
        fill: none;
        stroke: #CFE3F3;
        stroke-width: 4;
        stroke-linecap: round;
        stroke-dasharray: 180;
        stroke-dashoffset: 180;
        animation: dx-draw 1.4s cubic-bezier(.5, 0, .2, 1) .8s forwards;
    }

    /* ---------- Panggung ilustrasi (dekoratif) ---------- */
    .dx-scene-wrap { width: 420px; height: 260px; }
    .dx-scene { position: relative; width: 420px; height: 260px; transform-origin: left top; }

    .dx-layer { position: absolute; inset: 0; transition: transform .35s ease-out; pointer-events: none; }
    .dx-far  { transform: translate3d(calc(var(--mx) * 22px),  calc(var(--my) * 14px), 0); }
    .dx-mid  { transform: translate3d(calc(var(--mx) * -8px),  calc(var(--my) * -5px), 0); }
    .dx-near { transform: translate3d(calc(var(--mx) * -16px), calc(var(--my) * -10px), 0); }

    .dx-blob {
        position: absolute;
        left: 210px;
        top: 50px;
        width: 190px;
        height: 190px;
        background: radial-gradient(circle at 30% 30%, rgba(207, 227, 243, .45), rgba(66, 103, 137, .18));
        animation: dx-morph 16s ease-in-out infinite;
    }

    .dx-star { position: absolute; color: #CFE3F3; animation: dx-twinkle 5s ease-in-out infinite; }
    .dx-star svg { width: 100%; height: 100%; display: block; }
    .dx-star.s1 { width: 22px; height: 22px; left: 196px; top: 118px; }
    .dx-star.s2 { width: 14px; height: 14px; left: 392px; top: 96px; animation-delay: -2s; }

    /* Buku yang membalik halaman */
    .dx-book { position: absolute; right: 18px; bottom: 8px; width: 232px; height: 138px; perspective: 900px; }

    .dx-book::before {
        content: "";
        position: absolute;
        inset: -7px -9px -4px;
        border-radius: 13px;
        background: linear-gradient(180deg, #25507F, #17365D);
        box-shadow: 0 20px 32px rgba(4, 12, 26, .45);
    }

    .dx-pg {
        position: absolute;
        top: 0;
        width: 50%;
        height: 100%;
        background:
            repeating-linear-gradient(180deg, transparent 0 16px, rgba(66, 103, 137, .22) 16px 18px),
            #FAF7F2;
    }

    .dx-pg.l { left: 0; border-radius: 8px 0 0 8px; box-shadow: inset -12px 0 14px -10px rgba(20, 38, 62, .28); }
    .dx-pg.r { left: 50%; border-radius: 0 8px 8px 0; box-shadow: inset 12px 0 14px -10px rgba(20, 38, 62, .28); }

    .dx-flip {
        position: absolute;
        left: 50%;
        top: 0;
        width: 50%;
        height: 100%;
        border-radius: 0 8px 8px 0;
        transform-origin: left center;
        background:
            repeating-linear-gradient(180deg, transparent 0 16px, rgba(66, 103, 137, .22) 16px 18px),
            #F3EEE5;
        box-shadow: -5px 0 14px rgba(20, 38, 62, .18);
        animation: dx-flip 9s ease-in-out infinite;
    }

    /* Kartu melayang */
    .dx-slot { position: absolute; animation: dx-rise .9s var(--ease) backwards; animation-delay: var(--d, .8s); }
    .dx-slot.a { left: 0; top: 4px; }
    .dx-slot.b { left: 214px; top: 26px; }
    .dx-slot.c { left: 22px; top: 116px; }

    .dx-chip {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 196px;
        padding: 13px 16px;
        border-radius: var(--r-md);
        background: #FFFDF9;
        color: var(--navy);
        font-size: 13.5px;
        font-weight: 700;
        line-height: 1.35;
        box-shadow: 0 18px 40px rgba(4, 12, 26, .34);
    }

    .dx-chip i {
        width: 38px;
        height: 38px;
        flex: 0 0 38px;
        display: grid;
        place-items: center;
        border-radius: var(--r-sm);
        background: var(--tint);
        color: var(--tone);
        font-size: 18px;
    }

    .dx-slot.a .dx-chip { animation: dx-float 7s ease-in-out infinite; }
    .dx-slot.b .dx-chip { animation: dx-float 8.5s ease-in-out -3s infinite; }
    .dx-slot.c .dx-chip { animation: dx-float 6.5s ease-in-out -1.5s infinite; }

    /* =====================================================
       KARTU STATISTIK
       Satu keluarga warna biru. Hijau hanya untuk Data Monitoring
       karena itu penanda "sudah tercatat".
    ===================================================== */
    .dx-stat {
        position: relative;
        height: 100%;
        min-height: 172px;
        padding: 24px 24px 24px 28px;
        border-radius: var(--r-lg);
        background: #ffffff;
        border: 1px solid var(--line);
        box-shadow: var(--sh-1);
        overflow: hidden;
    }

    .dx-stat::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 5px;
        background: var(--tone);
    }

    .dx-stat::after {
        content: "";
        position: absolute;
        right: -40px;
        bottom: -50px;
        width: 140px;
        height: 140px;
        border-radius: 50%;
        background: var(--tint);
        opacity: .7;
        transition: transform .4s ease;
    }

    .dx-stat-top {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 18px;
    }

    .dx-stat-label {
        margin-bottom: 8px;
        font-size: 15px;
        font-weight: 600;
        color: var(--slate);
    }

    .dx-stat-number {
        font-size: 40px;
        line-height: 1;
        font-weight: 800;
        letter-spacing: -.03em;
        color: var(--navy);
        font-variant-numeric: tabular-nums;
    }

    .dx-stat-icon {
        width: 56px;
        height: 56px;
        flex: 0 0 56px;
        display: grid;
        place-items: center;
        border-radius: var(--r-md);
        background: var(--tint);
        color: var(--tone);
        font-size: 25px;
    }

    .dx-stat-desc {
        position: relative;
        z-index: 1;
        max-width: 85%;
        font-size: 14px;
        line-height: 1.55;
        color: var(--slate);
    }

    /* Keadaan kosong (angka masih 0) */
    .dx-stat-empty {
        position: relative;
        z-index: 1;
        margin-top: 10px;
        font-size: 13.5px;
        line-height: 1.5;
        font-weight: 600;
        color: var(--blue);
    }

    /* =====================================================
       MENU PENGELOLAAN
    ===================================================== */
    .dx-quick {
        position: relative;
        height: 100%;
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 22px 24px;
        border-radius: var(--r-lg);
        background: #ffffff;
        border: 1px solid var(--line);
        color: inherit;
        text-decoration: none;
        box-shadow: var(--sh-1);
        overflow: hidden;
        -webkit-tap-highlight-color: transparent;
        transition: transform var(--t-base) var(--ease), box-shadow var(--t-base) var(--ease), border-color var(--t-base) var(--ease);
    }

    .dx-quick::before {
        content: "";
        position: absolute;
        inset: 0;
        z-index: 0;
        background: linear-gradient(120deg, var(--navy), #25507F);
        transform: translateX(-101%);
        transition: transform .4s var(--ease);
    }

    .dx-quick > * { position: relative; z-index: 1; }

    .dx-quick:focus-visible {
        outline: 3px solid rgba(66, 103, 137, .5);
        outline-offset: 3px;
    }

    .dx-quick:active { transform: scale(.985); }

    .dx-quick-icon {
        width: 62px;
        height: 62px;
        flex: 0 0 62px;
        display: grid;
        place-items: center;
        border-radius: 18px;
        background: var(--soft);
        color: var(--navy);
        font-size: 27px;
        transition: background-color var(--t-base) ease, color var(--t-base) ease;
    }

    .dx-quick-text { flex: 1; min-width: 0; }

    .dx-quick h3 {
        margin: 0 0 5px;
        font-size: 17.5px;
        font-weight: 800;
        color: var(--navy);
        transition: color var(--t-base) ease;
    }

    .dx-quick p {
        margin: 0;
        font-size: 14.5px;
        line-height: 1.55;
        color: var(--slate);
        transition: color var(--t-base) ease;
    }

    .dx-quick-arrow {
        font-size: 22px;
        color: #8696A6;
        transition: transform var(--t-base) ease, color var(--t-base) ease;
    }

    /* =====================================================
       ALUR SISTEM (memang berurutan, jadi diberi nomor)
    ===================================================== */
    .dx-flow {
        position: relative;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 22px;
    }

    .dx-flow::before {
        content: "";
        position: absolute;
        left: 16%;
        right: 16%;
        top: 59px;
        height: 2px;
        background: repeating-linear-gradient(90deg, #B9CAD9 0 8px, transparent 8px 16px);
    }

    .dx-step {
        position: relative;
        padding: 26px 24px 24px;
        border-radius: var(--r-lg);
        background: #ffffff;
        border: 1px solid var(--line);
        box-shadow: var(--sh-1);
        text-align: center;
    }

    .dx-step-icon {
        position: relative;
        z-index: 1;
        width: 66px;
        height: 66px;
        margin: 0 auto 16px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: var(--tint);
        color: var(--tone);
        font-size: 28px;
        box-shadow: 0 0 0 7px #ffffff;
    }

    .dx-step-no {
        position: absolute;
        right: -4px;
        top: -4px;
        width: 26px;
        height: 26px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: var(--navy);
        color: #ffffff;
        font-size: 13px;
        font-weight: 800;
    }

    .dx-step strong {
        display: block;
        margin-bottom: 6px;
        font-size: 17px;
        font-weight: 800;
        color: var(--navy);
    }

    .dx-step .dx-desc {
        display: block;
        font-size: 14.5px;
        line-height: 1.65;
        color: var(--slate);
    }

    /* =====================================================
       HOVER: hanya untuk perangkat yang punya mouse,
       supaya tidak "menempel" di layar sentuh (HP orang tua/guru)
    ===================================================== */
    @media (hover: hover) {
        .dx-stat { transition: transform var(--t-base) ease, box-shadow var(--t-base) ease; }
        .dx-stat:hover { transform: translateY(-4px); box-shadow: var(--sh-2); }
        .dx-stat:hover::after { transform: scale(1.25); }

        .dx-step { transition: transform var(--t-base) ease, box-shadow var(--t-base) ease; }
        .dx-step:hover { transform: translateY(-4px); box-shadow: var(--sh-2); }

        .dx-quick:hover {
            transform: translateY(-4px);
            border-color: var(--navy);
            box-shadow: 0 18px 36px rgba(20, 44, 70, .2);
            color: inherit;
        }
        .dx-quick:hover::before { transform: translateX(0); }
        .dx-quick:hover h3,
        .dx-quick:hover p,
        .dx-quick:hover .dx-quick-arrow { color: #ffffff; }
        .dx-quick:hover .dx-quick-icon { background: rgba(255, 255, 255, .16); color: #ffffff; }
        .dx-quick:hover .dx-quick-arrow { transform: translateX(6px); }
    }


    /* =====================================================
       ANIMASI
    ===================================================== */
    @keyframes dx-rise    { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes dx-float   { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
    @keyframes dx-drift   { 0%, 100% { transform: translate(0, 0); } 50% { transform: translate(-22px, 18px); } }
    @keyframes dx-twinkle { 0%, 100% { opacity: .35; transform: scale(.85); } 50% { opacity: 1; transform: scale(1.1); } }
    @keyframes dx-morph   { 0%, 100% { border-radius: 58% 42% 55% 45% / 48% 56% 44% 52%; transform: rotate(0); }
                            50%      { border-radius: 44% 56% 40% 60% / 58% 42% 58% 42%; transform: rotate(24deg); } }
    @keyframes dx-flip    { 0%, 12% { transform: rotateY(0); } 40%, 58% { transform: rotateY(-176deg); } 88%, 100% { transform: rotateY(0); } }
    @keyframes dx-draw    { to { stroke-dashoffset: 0; } }

    /* =====================================================
       RESPONSIF
    ===================================================== */
    @media (max-width: 1199px) {
        .dx-hero { grid-template-columns: minmax(0, 1fr) 336px; padding: 38px 36px; }
        .dx-scene-wrap { width: 336px; height: 208px; }
        .dx-scene { transform: scale(.8); }
    }

    @media (max-width: 991px) {
        .dx-hero { grid-template-columns: 1fr; padding: 34px 28px; min-height: 0; }
        .dx-scene-wrap { display: none; }
        .dx-hero-shade { background: linear-gradient(180deg, rgba(10, 24, 42, .88), rgba(20, 44, 70, .68)); }

        .dx-flow { grid-template-columns: 1fr; }
        .dx-flow::before { display: none; }
        .dx-step { display: flex; align-items: flex-start; gap: 18px; text-align: left; }
        .dx-step-icon { margin: 0; flex: 0 0 62px; width: 62px; height: 62px; box-shadow: none; }
    }

    @media (max-width: 576px) {
        .dx-hero { padding: 28px 22px; border-radius: 20px; margin-bottom: 28px; }
        .dx-hero h1 { font-size: 26px; }
        .dx-hero-text p { font-size: 15.5px; }
        .dx-section { margin-bottom: 32px; }
        .dx-section-title { font-size: 19px; }
        .dx-stat { min-height: 152px; }
        .dx-stat-number { font-size: 36px; }
        .dx-quick { padding: 18px; gap: 14px; }
        .dx-quick-icon { width: 54px; height: 54px; flex-basis: 54px; font-size: 24px; }
        .dx-step { padding: 20px 18px; }
    }

</style>
@endpush


@section('content')

<div class="dx">

    {{-- =====================================================
         BANNER UTAMA
         Menjawab: "saya sedang di halaman apa, dan siapa saya?"
    ====================================================== --}}
    <header class="dx-hero" id="dxHero">

        <div class="dx-hero-photo" aria-hidden="true"></div>
        <div class="dx-hero-shade" aria-hidden="true"></div>
        <span class="dx-hero-ring r1" aria-hidden="true"></span>
        <span class="dx-hero-ring r2" aria-hidden="true"></span>

        <div class="dx-hero-text">

            <h1>
                <span id="dxGreeting">Selamat datang</span>, {{ auth()->user()->name }}
            </h1>

            <p>
                Kelola data pengguna, siswa, guru, dan monitoring perkembangan belajar.
            </p>

            <svg class="dx-hero-line" viewBox="0 0 150 14" aria-hidden="true"><path d="M3 9 C 30 2, 62 3, 92 6 S 132 10, 147 4"/></svg>

        </div>

        {{-- Ilustrasi dekoratif --}}
        <div class="dx-scene-wrap" aria-hidden="true">
            <div class="dx-scene">

                <div class="dx-layer dx-far">
                    <span class="dx-blob"></span>
                    <span class="dx-star s1"><svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 2.5l2.9 6.1 6.6.8-4.9 4.6 1.3 6.6L12 17.3 6.1 20.6l1.3-6.6L2.5 9.4l6.6-.8z"/></svg></span>
                    <span class="dx-star s2"><svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 2.5l2.9 6.1 6.6.8-4.9 4.6 1.3 6.6L12 17.3 6.1 20.6l1.3-6.6L2.5 9.4l6.6-.8z"/></svg></span>
                </div>

                <div class="dx-layer dx-mid">
                    <div class="dx-book">
                        <div class="dx-pg l"></div>
                        <div class="dx-pg r"></div>
                        <div class="dx-flip"></div>
                    </div>
                </div>

                <div class="dx-layer dx-near">

                    <div class="dx-slot a" style="--d:.8s">
                        <div class="dx-chip" style="--tone:#426789; --tint:#EAF1F7">
                            <i class="bi bi-pencil-fill"></i>
                            Guru mencatat perkembangan
                        </div>
                    </div>

                    <div class="dx-slot b" style="--d:1s">
                        <div class="dx-chip" style="--tone:#2E9F6B; --tint:#E6F5EE">
                            <i class="bi bi-whatsapp"></i>
                            Orang tua menerima kabar
                        </div>
                    </div>

                    <div class="dx-slot c" style="--d:1.2s">
                        <div class="dx-chip" style="--tone:#17365D; --tint:#EAF1F7">
                            <i class="bi bi-star-fill"></i>
                            Anak terus berkembang
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </header>


    {{-- =====================================================
         RINGKASAN
         Menjawab: "informasi apa yang paling penting sekarang?"
         Semua angka berasal dari controller, tidak ada data dummy.
    ====================================================== --}}
    <section class="dx-section" aria-labelledby="dxRingkasan">

        <div class="dx-section-head">
            <h2 class="dx-section-title" id="dxRingkasan">Ringkasan Sistem</h2>
            <p class="dx-section-hint">Jumlah data yang saat ini tersimpan di sistem.</p>
        </div>

        <div class="row g-4">

            {{-- SISWA --}}
            <div class="col-12 col-sm-6 col-xl-3">
                <article class="dx-stat" style="--tone:#426789; --tint:#EAF1F7">

                    <div class="dx-stat-top">
                        <div>
                            <div class="dx-stat-label">Total Siswa</div>
                            <div class="dx-stat-number" data-count="{{ $studentCount }}">{{ $studentCount }}</div>
                        </div>
                        <div class="dx-stat-icon" aria-hidden="true">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                    </div>

                    <div class="dx-stat-desc">Data siswa yang terdaftar dalam sistem</div>

                    @if ((int) $studentCount === 0)
                        <div class="dx-stat-empty">Belum ada siswa. Tambahkan lewat menu Kelola Data Siswa.</div>
                    @endif

                </article>
            </div>

            {{-- GURU --}}
            <div class="col-12 col-sm-6 col-xl-3">
                <article class="dx-stat" style="--tone:#17365D; --tint:#E3EBF3">

                    <div class="dx-stat-top">
                        <div>
                            <div class="dx-stat-label">Total Guru</div>
                            <div class="dx-stat-number" data-count="{{ $teacherCount }}">{{ $teacherCount }}</div>
                        </div>
                        <div class="dx-stat-icon" aria-hidden="true">
                            <i class="bi bi-person-workspace"></i>
                        </div>
                    </div>

                    <div class="dx-stat-desc">Guru yang memiliki akses ke sistem</div>

                    @if ((int) $teacherCount === 0)
                        <div class="dx-stat-empty">Belum ada guru. Buat akun guru lewat menu Kelola Data User.</div>
                    @endif

                </article>
            </div>

            {{-- ORANG TUA --}}
            <div class="col-12 col-sm-6 col-xl-3">
                <article class="dx-stat" style="--tone:#6F8FAA; --tint:#EDF2F7">

                    <div class="dx-stat-top">
                        <div>
                            <div class="dx-stat-label">Total Orang Tua</div>
                            <div class="dx-stat-number" data-count="{{ $parentCount }}">{{ $parentCount }}</div>
                        </div>
                        <div class="dx-stat-icon" aria-hidden="true">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>

                    <div class="dx-stat-desc">Akun orang tua yang terhubung dengan siswa</div>

                    @if ((int) $parentCount === 0)
                        <div class="dx-stat-empty">Belum ada akun orang tua. Buat lewat menu Kelola Data User.</div>
                    @endif

                </article>
            </div>

            {{-- MONITORING --}}
            <div class="col-12 col-sm-6 col-xl-3">
                <article class="dx-stat" style="--tone:#2E9F6B; --tint:#E6F5EE">

                    <div class="dx-stat-top">
                        <div>
                            <div class="dx-stat-label">Data Monitoring</div>
                            <div class="dx-stat-number" data-count="{{ $developmentCount }}">{{ $developmentCount }}</div>
                        </div>
                        <div class="dx-stat-icon" aria-hidden="true">
                            <i class="bi bi-clipboard2-check-fill"></i>
                        </div>
                    </div>

                    <div class="dx-stat-desc">Catatan perkembangan yang telah diinput</div>

                    @if ((int) $developmentCount === 0)
                        <div class="dx-stat-empty">Belum ada catatan. Guru bisa mulai menginput perkembangan siswa.</div>
                    @endif

                </article>
            </div>

        </div>

    </section>


    {{-- =====================================================
         MENU PENGELOLAAN
         Menjawab: "apa yang bisa saya lakukan selanjutnya?"
    ====================================================== --}}
    <section class="dx-section" aria-labelledby="dxMenu">

        <div class="dx-section-head">
            <h2 class="dx-section-title" id="dxMenu">Menu Pengelolaan</h2>
            <p class="dx-section-hint">Pilih data yang ingin Anda kelola.</p>
        </div>

        <div class="row g-4">

            <div class="col-12 col-md-6">
                <a href="{{ route('users.index') }}" class="dx-quick">

                    <div class="dx-quick-icon" aria-hidden="true">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <div class="dx-quick-text">
                        <h3>Kelola Data User</h3>
                        <p>Kelola akun admin, guru, dan orang tua.</p>
                    </div>

                    <i class="bi bi-arrow-right dx-quick-arrow" aria-hidden="true"></i>

                </a>
            </div>

            <div class="col-12 col-md-6">
                <a href="{{ route('students.index') }}" class="dx-quick">

                    <div class="dx-quick-icon" aria-hidden="true">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>

                    <div class="dx-quick-text">
                        <h3>Kelola Data Siswa</h3>
                        <p>Kelola identitas siswa dan hubungan dengan orang tua.</p>
                    </div>

                    <i class="bi bi-arrow-right dx-quick-arrow" aria-hidden="true"></i>

                </a>
            </div>

        </div>

    </section>


    {{-- =====================================================
         ALUR SISTEM
    ====================================================== --}}
    <section class="dx-section" aria-labelledby="dxAlur">

        <div class="dx-section-head">
            <h2 class="dx-section-title" id="dxAlur">Informasi Sistem</h2>
            <p class="dx-section-hint">Begini cara data mengalir dari guru sampai ke orang tua.</p>
        </div>

        <div class="dx-flow">

            <div class="dx-step" style="--tone:#426789; --tint:#EAF1F7">
                <div class="dx-step-icon" aria-hidden="true">
                    <i class="bi bi-person-badge-fill"></i>
                    <span class="dx-step-no">1</span>
                </div>
                <div>
                    <strong>Pengelolaan Pengguna</strong>
                    <span class="dx-desc">
                        Admin dapat mengelola akun guru dan orang tua
                        yang menggunakan sistem.
                    </span>
                </div>
            </div>

            <div class="dx-step" style="--tone:#17365D; --tint:#E3EBF3">
                <div class="dx-step-icon" aria-hidden="true">
                    <i class="bi bi-clipboard2-data-fill"></i>
                    <span class="dx-step-no">2</span>
                </div>
                <div>
                    <strong>Monitoring Perkembangan</strong>
                    <span class="dx-desc">
                        Data perkembangan diinput oleh guru berdasarkan
                        kegiatan pembelajaran yang dilakukan.
                    </span>
                </div>
            </div>

            <div class="dx-step" style="--tone:#2E9F6B; --tint:#E6F5EE">
                <div class="dx-step-icon" aria-hidden="true">
                    <i class="bi bi-whatsapp"></i>
                    <span class="dx-step-no">3</span>
                </div>
                <div>
                    <strong>Integrasi WhatsApp</strong>
                    <span class="dx-desc">
                        Sistem dapat menyampaikan informasi perkembangan
                        kepada orang tua melalui WhatsApp.
                    </span>
                </div>
            </div>

        </div>

    </section>

</div>

@endsection


@push('scripts')
<script>

    (function () {

        /* Sapaan sesuai jam. Tanggal sudah ada di topbar, angka dihitung
           oleh layout (atribut data-count), jadi tidak diulang di sini. */
        var greetEl = document.getElementById('dxGreeting');

        if (greetEl) {
            var jam = new Date().getHours();
            greetEl.textContent = jam < 11 ? 'Selamat pagi'
                                : jam < 15 ? 'Selamat siang'
                                : jam < 18 ? 'Selamat sore'
                                : 'Selamat malam';
        }

        /* Ilustrasi bergeser halus mengikuti mouse (desktop saja) */
        var hero = document.getElementById('dxHero');
        var calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (!hero || calm || !window.matchMedia('(hover: hover) and (min-width: 992px)').matches) {
            return;
        }

        var raf = null, px = 0, py = 0;

        hero.addEventListener('mousemove', function (e) {

            var r = hero.getBoundingClientRect();

            px = (e.clientX - r.left) / r.width - 0.5;
            py = (e.clientY - r.top) / r.height - 0.5;

            if (raf) { return; }

            raf = requestAnimationFrame(function () {
                hero.style.setProperty('--mx', px.toFixed(3));
                hero.style.setProperty('--my', py.toFixed(3));
                raf = null;
            });

        });

        hero.addEventListener('mouseleave', function () {
            hero.style.setProperty('--mx', 0);
            hero.style.setProperty('--my', 0);
        });

    })();

</script>
@endpush
{{--
    Gaya tambahan bersama (di atas komponen layouts.app).
    Pemakaian di halaman:  @push('styles') @include('partials.ui-extras') @endpush
    Berisi: filter bar, form, chip, hero siswa, timeline, grafik aktivitas,
    pemilih anak, daftar centang, kotak info, video.
--}}
@include('partials.data-table-styles')

<style>

    .min-w-0 { min-width: 0; }
    .person-avatar.lg { width: 60px; height: 60px; flex-basis: 60px; font-size: 22px; }

    /* ---------- Chip ---------- */
    .chip-list { display: flex; flex-wrap: wrap; gap: 8px; }

    .chip {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 12px; border-radius: 999px;
        background: var(--soft); color: var(--navy);
        font-size: .88rem; font-weight: 700; line-height: 1.3;
    }

    .chip.is-primary { background: var(--navy); color: #fff; }
    .chip.is-gold { background: var(--warn-soft); color: var(--warn); }

    /* ---------- Filter bar ---------- */
    .filter-bar {
        display: flex; flex-wrap: wrap; gap: 14px; align-items: flex-end;
        padding: 18px 24px; border-bottom: 1px solid var(--line); background: var(--paper);
    }

    .filter-bar .field { flex: 1 1 170px; min-width: 0; }
    .filter-bar .field.grow { flex: 2 1 260px; }
    .filter-bar .form-label { margin-bottom: 6px; font-size: .88rem; font-weight: 700; color: var(--navy); }
    .filter-bar .btn-row { display: flex; gap: 8px; }

    @media (max-width: 767.98px) {
        .filter-bar { padding: 16px 18px; }
        .filter-bar .field, .filter-bar .field.grow { flex: 1 1 100%; }
        .filter-bar .btn-row { width: 100%; }
        .filter-bar .btn-row .btn { flex: 1; }
    }

    /* ---------- Form ---------- */
    .form-card { max-width: 800px; }

    .form-section + .form-section { margin-top: 30px; padding-top: 26px; border-top: 1px solid var(--line); }

    .form-section-title { display: flex; align-items: center; gap: 10px; margin: 0 0 4px; font-size: 1.05rem; font-weight: 800; color: var(--navy); }
    .form-section-title i { color: var(--blue); }
    .form-section-hint { margin: 0 0 18px; color: var(--slate); }

    .form-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 30px; padding-top: 22px; border-top: 1px solid var(--line); }

    @media (max-width: 575.98px) { .form-actions .btn { flex: 1 1 100%; } }

    .student-banner {
        display: flex; align-items: center; gap: 14px;
        margin-bottom: 20px; padding: 16px 18px;
        border-radius: var(--r-md); background: var(--soft);
    }

    .student-banner .name { font-weight: 800; color: var(--navy); line-height: 1.3; overflow-wrap: anywhere; }
    .student-banner .meta { color: var(--slate); font-size: .94rem; }

    .info-box {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 14px 16px; border-radius: var(--r-sm);
        background: var(--info-soft); color: var(--info); line-height: 1.55;
    }

    .info-box i { margin-top: 3px; font-size: 1.1rem; }
    .info-box strong { color: var(--navy); }

    /* ---------- Daftar centang (orang tua / wali) ---------- */
    .check-list { max-height: 280px; overflow: auto; border: 1.5px solid var(--line); border-radius: var(--r-sm); background: #fff; }
    .check-item { display: flex; align-items: center; gap: 12px; margin: 0; padding: 12px 14px; cursor: pointer; }
    .check-item + .check-item { border-top: 1px solid var(--line); }
    .check-item:hover { background: var(--soft); }
    .check-item input { width: 20px; height: 20px; flex: 0 0 20px; accent-color: var(--navy); }
    .check-item .meta { min-width: 0; }
    .check-item .meta strong { display: block; color: var(--navy); overflow-wrap: anywhere; }
    .check-item .meta small { color: var(--slate); }

    /* ---------- Jenis penugasan ---------- */
    .type-card { display: flex; gap: 14px; height: 100%; padding: 16px 18px; border-radius: var(--r-md); background: var(--soft); }
    .type-card .stat-icon { flex: 0 0 auto; }
    .type-card strong { display: block; margin-bottom: 2px; color: var(--navy); }
    .type-card span { color: var(--slate); font-size: .94rem; }

    /* ---------- Hero siswa ---------- */
    .student-hero {
        position: relative; overflow: hidden;
        display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 20px;
        padding: 28px; border-radius: var(--r-lg); color: #fff;
        background: linear-gradient(120deg, var(--midnight), var(--navy) 55%, var(--blue));
        box-shadow: var(--sh-2);
    }

    .student-hero::after {
        content: ""; position: absolute; right: -90px; top: -110px; width: 320px; height: 320px;
        border-radius: 50%; border: 1.5px solid rgba(255, 255, 255, .18); pointer-events: none;
    }

    .hero-main { position: relative; z-index: 1; display: flex; align-items: center; gap: 18px; min-width: 0; }

    .hero-avatar {
        width: 76px; height: 76px; flex: 0 0 76px; display: grid; place-items: center;
        border-radius: 50%; background: rgba(255, 255, 255, .14); border: 2px solid rgba(255, 255, 255, .38);
        font-size: 30px; font-weight: 800;
    }

    .hero-name { margin: 0 0 10px; font-size: clamp(1.4rem, 2.6vw, 1.9rem); font-weight: 800; letter-spacing: -.02em; line-height: 1.2; overflow-wrap: anywhere; }

    .student-hero .chip { background: rgba(255, 255, 255, .15); color: #fff; }
    .student-hero .chip.is-gold { background: rgba(232, 181, 60, .25); color: #FFE3A0; }

    .hero-actions { position: relative; z-index: 1; display: flex; flex-wrap: wrap; gap: 10px; }

    @media (max-width: 767.98px) {
        .student-hero { padding: 22px 18px; }
        .hero-avatar { width: 60px; height: 60px; flex-basis: 60px; font-size: 24px; }
        .hero-actions { width: 100%; }
        .hero-actions .btn { flex: 1 1 100%; }
    }

    /* ---------- Ringkasan angka kecil ---------- */
    .mini-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 14px; }
    .mini-stat { padding: 16px 18px; border-radius: var(--r-md); background: #fff; border: 1px solid var(--line); box-shadow: var(--sh-1); }
    .mini-stat .v { display: block; font-size: 1.5rem; font-weight: 800; line-height: 1.2; color: var(--navy); }
    .mini-stat .l { color: var(--slate); font-size: .9rem; }

    /* ---------- Grafik aktivitas bulanan ---------- */
    .mini-bars { display: flex; align-items: stretch; gap: 10px; height: 150px; }
    .mini-bar { flex: 1; min-width: 0; display: flex; flex-direction: column; align-items: center; gap: 6px; }
    .mini-bar b { font-size: .85rem; color: var(--navy); line-height: 1; }
    .mini-bar small { color: var(--slate); font-size: .8rem; }
    .bar-track { flex: 1; width: 100%; display: flex; align-items: flex-end; justify-content: center; }

    .bar-fill {
        width: 100%; max-width: 36px; min-height: 4px; border-radius: 8px 8px 4px 4px;
        background: linear-gradient(180deg, var(--steel), var(--navy));
        transform-origin: bottom; animation: bar-grow .8s var(--ease) both;
    }

    .bar-fill.is-zero { background: var(--line); }

    @keyframes bar-grow { from { transform: scaleY(0); } to { transform: scaleY(1); } }

    /* ---------- Timeline ---------- */
    .timeline { position: relative; margin: 0; padding: 0; list-style: none; }
    .timeline::before { content: ""; position: absolute; left: 11px; top: 8px; bottom: 8px; width: 2px; background: var(--line); }
    .tl-item { position: relative; padding: 0 0 20px 40px; }
    .tl-item:last-child { padding-bottom: 0; }
    .tl-dot { position: absolute; left: 3px; top: 6px; width: 18px; height: 18px; border-radius: 50%; background: #fff; border: 4px solid var(--blue); }
    .tl-card { padding: 16px 18px; border-radius: var(--r-md); background: #fff; border: 1px solid var(--line); box-shadow: var(--sh-1); }
    .tl-head { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 12px; }
    .tl-date { font-weight: 800; color: var(--navy); }
    .tl-by { color: var(--slate); font-size: .9rem; }
    .tl-label { display: block; margin-bottom: 2px; font-size: .76rem; font-weight: 800; letter-spacing: .05em; text-transform: uppercase; color: var(--steel); }
    .tl-text { margin: 0 0 12px; white-space: pre-line; overflow-wrap: anywhere; color: var(--text); }
    .tl-text:last-child { margin-bottom: 0; }
    .tl-tip { margin-top: 4px; padding: 12px 14px; border-radius: var(--r-sm); background: var(--info-soft); }
    .tl-tip .tl-text { margin: 0; }
    .tl-note { margin: 0 0 12px; padding: 12px 14px; border-radius: var(--r-sm); background: var(--warn-soft); }
    .tl-note .tl-text { margin: 0; }

    /* ---------- Pemilih anak ---------- */
    .switcher { display: flex; gap: 10px; overflow-x: auto; padding: 2px 2px 8px; }

    .switch-item {
        flex: 0 0 auto; display: flex; align-items: center; gap: 10px;
        padding: 8px 18px 8px 8px; border-radius: 999px; background: #fff;
        border: 1.5px solid var(--line); color: var(--navy); font-weight: 700; text-decoration: none;
        transition: border-color var(--t-fast, .18s) ease, background-color var(--t-fast, .18s) ease;
    }

    .switch-item:hover { border-color: var(--steel); color: var(--navy); }
    .switch-item.is-active { background: var(--navy); border-color: var(--navy); color: #fff; }

    .switch-item .mini-avatar {
        width: 34px; height: 34px; display: grid; place-items: center; border-radius: 50%;
        background: var(--soft); color: var(--navy); font-size: .95rem; font-weight: 800;
    }

    .switch-item.is-active .mini-avatar { background: rgba(255, 255, 255, .18); color: #fff; }

    /* ---------- Data anak (dashboard orang tua) ---------- */
    .child-facts { margin: 18px 0 20px; padding: 12px 16px; border-radius: var(--r-sm); background: var(--soft); }
    .child-facts div { display: flex; justify-content: space-between; gap: 16px; padding: 5px 0; }
    .child-facts dt { font-weight: 500; color: var(--slate); }
    .child-facts dd { margin: 0; font-weight: 700; color: var(--navy); text-align: right; overflow-wrap: anywhere; }

    /* ---------- Video ---------- */
    .video-frame { aspect-ratio: 16 / 9; border-radius: var(--r-sm); overflow: hidden; background: var(--soft); }
    .video-frame iframe { width: 100%; height: 100%; border: 0; display: block; }
    .video-frame.is-fallback { display: grid; place-items: center; padding: 16px; text-align: center; color: var(--slate); }
    .video-frame.is-fallback i { display: block; margin-bottom: 6px; font-size: 30px; }
    .video-title { margin: 16px 0 8px; font-size: 1.05rem; font-weight: 800; line-height: 1.4; color: var(--navy); }

    .side-list-item { display: flex; justify-content: space-between; gap: 12px; padding: 12px 0; }
    .side-list-item + .side-list-item { border-top: 1px solid var(--line); }
    .side-list-item strong { display: block; color: var(--navy); overflow-wrap: anywhere; }
    .side-list-item small { color: var(--slate); }

    .panel-title { margin: 0 0 14px; font-size: 1.05rem; font-weight: 800; color: var(--navy); }

    @media (prefers-reduced-motion: reduce) {
        .bar-fill { animation: none; }
    }

</style>
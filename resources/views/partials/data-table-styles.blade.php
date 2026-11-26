{{--
    Gaya bersama untuk semua halaman daftar data (user, siswa, dst.).
    Dipakai dengan:  @push('styles') @include('partials.data-table-styles') @endpush
    Komponen dasar (tabel, tombol, badge, pagination, empty state) tetap berasal dari layouts.app.
--}}
<style>

    .data-card { overflow: hidden; }

    .data-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 18px 24px;
        border-bottom: 1px solid var(--line);
    }

    .data-card-top strong { font-size: 1.05rem; font-weight: 800; color: var(--navy); }

    .data-card-bottom { padding: 16px 24px; border-top: 1px solid var(--line); }
    .data-card-bottom:empty { display: none; }
    .data-card-bottom .pagination { margin: 0; }

    /* Nama + avatar inisial */
    .person { display: flex; align-items: center; gap: 14px; min-width: 0; }

    .person-avatar {
        width: 46px;
        height: 46px;
        flex: 0 0 46px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: var(--soft);
        color: var(--navy);
        font-size: 17px;
        font-weight: 800;
    }

    .person-name { font-weight: 700; color: var(--navy); line-height: 1.35; overflow-wrap: anywhere; }
    .cell-muted  { color: var(--slate); overflow-wrap: anywhere; }

    /* Tombol aksi di baris tabel */
    .actions { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .actions form { margin: 0; }

    /* Penyesuaian .table-stack di HP: kolom nama dan aksi tampil penuh */
    @media (max-width: 767.98px) {
        .table-stack td.cell-name,
        .table-stack td.cell-actions { display: block; text-align: left; }

        .table-stack td.cell-name::before,
        .table-stack td.cell-actions::before { display: none; }

        .table-stack td.cell-name { padding-bottom: 6px !important; }

        .table-stack .actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .table-stack .actions .btn { width: 100%; }

        .data-card-top, .data-card-bottom { padding-left: 18px; padding-right: 18px; }
    }

</style>
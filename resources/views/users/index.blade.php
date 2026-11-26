@extends('layouts.app')

@section('title', 'Kelola User')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / Kelola User
@endsection

@push('styles')
<style>

    /* =====================================================
       Halaman ini memakai komponen dari layouts.app:
       .page-head, .card, .table, .table-stack, .badge, .btn,
       .empty-state, .modal, .pagination dan animasi muncul.
       Di sini hanya gaya yang khusus untuk halaman user.
    ===================================================== */

    .users-card { overflow: hidden; }

    .users-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 18px 24px;
        border-bottom: 1px solid var(--line);
    }

    .users-card-top strong {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--navy);
    }

    .users-card-bottom { padding: 16px 24px; border-top: 1px solid var(--line); }
    .users-card-bottom:empty { display: none; }
    .users-card-bottom .pagination { margin: 0; }

    /* Nama + avatar */
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
    .cell-email  { color: var(--slate); overflow-wrap: anywhere; }

    /* Role: satu keluarga biru, dibedakan lewat ketebalan warna */
    .role {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        border-radius: 999px;
        background: var(--tint);
        color: var(--tone);
        font-size: 14px;
        font-weight: 700;
        white-space: nowrap;
    }

    .role.admin     { --tone: #17365D; --tint: #E1E9F2; }
    .role.guru      { --tone: #2D5A87; --tint: #E6EFF8; }
    .role.orang_tua { --tone: #526173; --tint: #EEF1F4; }

    /* WhatsApp */
    .wa { display: inline-flex; align-items: center; gap: 8px; white-space: nowrap; }
    .wa i { color: var(--ok); font-size: 17px; }
    .wa.empty, .wa.empty i { color: #8696A6; }

    /* Tombol aksi */
    .actions { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .actions form { margin: 0; }

    /* Penyesuaian .table-stack (HP): kolom nama dan aksi tampil penuh */
    @media (max-width: 767.98px) {
        .table-stack td.cell-name,
        .table-stack td.cell-actions { display: block; text-align: left; }

        .table-stack td.cell-name::before,
        .table-stack td.cell-actions::before { display: none; }

        .table-stack td.cell-name { padding-bottom: 6px !important; }

        .table-stack .actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .table-stack .actions .btn { width: 100%; }

        .users-card-top, .users-card-bottom { padding-left: 18px; padding-right: 18px; }
    }

</style>
@endpush


@section('content')

{{-- KEPALA HALAMAN (judul sudah ada di topbar, jadi cukup penjelasan + tombol utama) --}}
<div class="page-head">

    <p>Kelola akun admin, guru, dan orang tua yang menggunakan sistem.</p>

    <div class="page-actions">
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg" aria-hidden="true"></i>
            Tambah User
        </a>
    </div>

</div>


{{-- TABEL USER --}}
<div class="card users-card">

    <div class="users-card-top">

        <strong>Daftar Pengguna</strong>

        <span class="badge text-bg-primary">
            {{ method_exists($users, 'total') ? $users->total() : $users->count() }} user
        </span>

    </div>


    @if($users->isEmpty())

        <div class="empty-state">

            <div class="empty-icon" aria-hidden="true">
                <i class="bi bi-people"></i>
            </div>

            <h3>Belum ada user</h3>

            <p>Tambahkan akun pertama agar guru atau orang tua bisa masuk ke sistem.</p>

            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                Tambah User
            </a>

        </div>

    @else

        <div class="table-responsive">

            <table class="table table-hover table-stack align-middle">

                <thead>

                    <tr>
                        <th scope="col">Nama</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th scope="col">WhatsApp</th>
                        <th scope="col">Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($users as $user)

                        @php
                            $roleLabel = $user->role === 'admin'
                                ? 'Administrator'
                                : ($user->role === 'guru' ? 'Guru' : 'Orang Tua');
                        @endphp

                        <tr>

                            <td class="cell-name" data-label="Nama">

                                <div class="person">

                                    <div class="person-avatar" aria-hidden="true">
                                        {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                                    </div>

                                    <div class="person-name">{{ $user->name }}</div>

                                </div>

                            </td>

                            <td data-label="Email">
                                <span class="cell-email">{{ $user->email }}</span>
                            </td>

                            <td data-label="Role">
                                <span class="role {{ $user->role }}">{{ $roleLabel }}</span>
                            </td>

                            <td data-label="WhatsApp">

                                @if($user->whatsapp)
                                    <span class="wa">
                                        <i class="bi bi-whatsapp" aria-hidden="true"></i>
                                        {{ $user->whatsapp }}
                                    </span>
                                @else
                                    <span class="wa empty">
                                        <i class="bi bi-dash-circle" aria-hidden="true"></i>
                                        Belum diisi
                                    </span>
                                @endif

                            </td>

                            <td class="cell-actions" data-label="Aksi">

                                <div class="actions">

                                    <a
                                        href="{{ route('users.edit', $user) }}"
                                        class="btn btn-secondary btn-sm"
                                        aria-label="Edit {{ $user->name }}"
                                    >
                                        <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('users.destroy', $user) }}"
                                        method="POST"
                                        data-confirm-delete
                                        data-name="{{ $user->name }}"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-outline-danger btn-sm"
                                            aria-label="Hapus {{ $user->name }}"
                                        >
                                            <i class="bi bi-trash3" aria-hidden="true"></i>
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        <div class="users-card-bottom">
            {{ $users->links() }}
        </div>

    @endif

</div>


{{-- KONFIRMASI HAPUS (satu modal untuk semua baris) --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalTitle">Hapus user ini?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                Akun <strong id="deleteName"></strong> akan dihapus dari sistem.
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="deleteConfirm">
                    <i class="bi bi-trash3" aria-hidden="true"></i>
                    Ya, hapus
                </button>
            </div>

        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>

    (function () {

        var modalEl = document.getElementById('deleteModal');
        if (!modalEl) { return; }

        var modal   = bootstrap.Modal.getOrCreateInstance(modalEl);
        var nameEl  = document.getElementById('deleteName');
        var confirm = document.getElementById('deleteConfirm');
        var pending = null;

        // Form hapus per baris tetap berfungsi tanpa JavaScript.
        // Dengan JavaScript, pengguna diminta konfirmasi lewat modal.
        document.querySelectorAll('form[data-confirm-delete]').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                if (form.dataset.confirmed === '1') { return; }
                e.preventDefault();
                pending = form;
                nameEl.textContent = form.dataset.name || 'ini';
                modal.show();
            });
        });

        confirm.addEventListener('click', function () {
            if (!pending) { return; }
            pending.dataset.confirmed = '1';
            confirm.classList.add('is-loading');
            pending.submit();
        });

        modalEl.addEventListener('hidden.bs.modal', function () {
            pending = null;
            confirm.classList.remove('is-loading');
        });

    })();

</script>
@endpush
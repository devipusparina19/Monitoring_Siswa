{{--
    Modal konfirmasi yang dipakai bersama.
    Pemakaian:  @include('partials.confirm-delete', ['noun' => 'siswa'])
    Opsional: 'title', 'verb', 'confirmLabel', 'note'
    Pada form hapus tambahkan atribut:  data-confirm-delete data-name="{{ $item->name }}"
    Form tetap berfungsi tanpa JavaScript.
--}}
@php
    $noun = $noun ?? 'data';
    $title = $title ?? "Hapus {$noun} ini?";
    $verb = $verb ?? 'dihapus dari sistem';
    $confirmLabel = $confirmLabel ?? 'Ya, hapus';
    $note = $note ?? null;
@endphp

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalTitle">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                Data {{ $noun }} <strong id="deleteName"></strong> akan {{ $verb }}.
                @if($note)
                    <div class="text-muted small mt-2">{{ $note }}</div>
                @endif
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="deleteConfirm">
                    <i class="bi bi-trash3" aria-hidden="true"></i>
                    {{ $confirmLabel }}
                </button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>

    (function () {

        var modalEl = document.getElementById('deleteModal');
        if (!modalEl) { return; }

        var modal   = bootstrap.Modal.getOrCreateInstance(modalEl);
        var nameEl  = document.getElementById('deleteName');
        var confirm = document.getElementById('deleteConfirm');
        var pending = null;

        document.querySelectorAll('form[data-confirm-delete]').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                if (form.dataset.confirmed === '1') { return; }
                e.preventDefault();
                pending = form;
                nameEl.textContent = form.dataset.name || '';
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
{{-- Pakai di dalam @section('content') halaman form:  @include('partials.form-lock')
     Pada <form> tambahkan atribut: data-lock --}}
@push('scripts')
<script>
    (function () {
        document.querySelectorAll('form[data-lock]').forEach(function (form) {
            form.addEventListener('submit', function () {
                var btn = form.querySelector('button[type="submit"]');
                if (!btn) { return; }
                setTimeout(function () {
                    btn.disabled = true;
                    btn.classList.add('is-loading');
                }, 0);
            });
        });

        window.addEventListener('pageshow', function (e) {
            if (!e.persisted) { return; }
            document.querySelectorAll('form[data-lock] button[type="submit"]').forEach(function (btn) {
                btn.disabled = false;
                btn.classList.remove('is-loading');
            });
        });
    })();
</script>
@endpush
@extends('layouts.app')

@section('title', 'Penugasan Guru')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / Penugasan Guru
@endsection

@push('styles')
    @include('partials.ui-extras')
@endpush

@section('content')

<div class="page-head">
    <p>Tentukan wali kelas dan guru mata pelajaran. Hak akses guru mengikuti penugasan di halaman ini.</p>
</div>

{{-- PENJELASAN DUA JENIS PENUGASAN --}}
<div class="row g-3 mb-4">

    <div class="col-12 col-md-6">
        <div class="type-card">
            <div class="stat-icon" aria-hidden="true"><i class="bi bi-person-badge-fill"></i></div>
            <div>
                <strong>Wali Kelas</strong>
                <span>Bertanggung jawab atas satu kelas. Dapat memonitor seluruh siswa dan semua mata pelajaran di kelas itu.</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="type-card">
            <div class="stat-icon" aria-hidden="true"><i class="bi bi-journal-bookmark-fill"></i></div>
            <div>
                <strong>Guru Mata Pelajaran</strong>
                <span>Mengajar satu mata pelajaran di satu atau beberapa kelas. Tambahkan penugasan satu per satu untuk setiap kelas.</span>
            </div>
        </div>
    </div>

</div>


{{-- FORM TAMBAH PENUGASAN --}}
<div class="card mb-4">
    <div class="card-body">

        <h3 class="form-section-title"><i class="bi bi-plus-circle-fill" aria-hidden="true"></i> Tambah Penugasan</h3>
        <p class="form-section-hint">Pilih guru, jenis penugasan, kelas, dan (untuk guru mapel) mata pelajaran.</p>

        <form action="{{ route('assignments.store') }}" method="POST" data-lock>
            @csrf

            <div class="row g-3">

                <div class="col-12 col-md-6 col-xl-3">
                    <label class="form-label" for="teacher_id">Guru</label>
                    <select id="teacher_id" name="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" required>
                        <option value="">Pilih guru</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" @selected(old('teacher_id') == $teacher->id)>{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <label class="form-label" for="type">Jenis Penugasan</label>
                    <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="subject" @selected(old('type', 'subject') === 'subject')>Guru Mata Pelajaran</option>
                        <option value="homeroom" @selected(old('type') === 'homeroom')>Wali Kelas</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <label class="form-label" for="classroom_id">Kelas</label>
                    <select id="classroom_id" name="classroom_id" class="form-select @error('classroom_id') is-invalid @enderror" required>
                        <option value="">Pilih kelas</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" @selected(old('classroom_id') == $classroom->id)>Kelas {{ $classroom->name }}</option>
                        @endforeach
                    </select>
                    @error('classroom_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <label class="form-label" for="subject_id">Mata Pelajaran</label>
                    <select id="subject_id" name="subject_id" class="form-select @error('subject_id') is-invalid @enderror">
                        <option value="">Pilih mata pelajaran</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text" id="subjectHint">Tidak diperlukan untuk wali kelas.</div>
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg" aria-hidden="true"></i>
                    Simpan Penugasan
                </button>
            </div>

        </form>

    </div>
</div>


{{-- DAFTAR PENUGASAN --}}
<div class="card data-card">

    <div class="data-card-top">
        <strong>Daftar Penugasan</strong>
        <span class="badge text-bg-primary">{{ $assignments->total() }} penugasan</span>
    </div>

    <form method="GET" action="{{ route('assignments.index') }}" class="filter-bar">
        <div class="field grow">
            <label class="form-label" for="filter_teacher">Filter guru</label>
            <select id="filter_teacher" name="teacher_id" class="form-select">
                <option value="">Semua guru</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" @selected(request('teacher_id') == $teacher->id)>{{ $teacher->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="btn-row">
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-funnel" aria-hidden="true"></i>
                Terapkan
            </button>
            @if(request()->filled('teacher_id'))
                <a href="{{ route('assignments.index') }}" class="btn btn-secondary">Reset</a>
            @endif
        </div>
    </form>

    @if($assignments->isEmpty())

        <div class="empty-state">
            <div class="empty-icon" aria-hidden="true"><i class="bi bi-diagram-3"></i></div>
            <h3>Belum ada penugasan</h3>
            <p>Guru baru dapat melihat siswa setelah mendapat penugasan kelas.</p>
        </div>

    @else

        <div class="table-responsive">
            <table class="table table-hover table-stack align-middle">

                <thead>
                    <tr>
                        <th scope="col">Guru</th>
                        <th scope="col">Jenis</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">Mata Pelajaran</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($assignments as $item)

                        @php
                            $teacherName = $item->teacher?->name ?? 'Guru dihapus';
                            $label = $teacherName . ' — ' . ($item->isHomeroom() ? 'Wali Kelas ' : ($item->subject?->name . ' ')) . ($item->classroom?->name ?? '');
                        @endphp

                        <tr>

                            <td class="cell-name" data-label="Guru">
                                <div class="person">
                                    <div class="person-avatar" aria-hidden="true">{{ strtoupper(mb_substr($teacherName, 0, 1)) }}</div>
                                    <div class="person-name">{{ $teacherName }}</div>
                                </div>
                            </td>

                            <td data-label="Jenis">
                                @if($item->isHomeroom())
                                    <span class="badge text-bg-primary">Wali Kelas</span>
                                @else
                                    <span class="chip">Guru Mapel</span>
                                @endif
                            </td>

                            <td data-label="Kelas">Kelas {{ $item->classroom?->name ?? '-' }}</td>

                            <td data-label="Mata Pelajaran">
                                @if($item->isHomeroom())
                                    <span class="cell-muted">Semua mata pelajaran</span>
                                @else
                                    {{ $item->subject?->name ?? '-' }}
                                @endif
                            </td>

                            <td class="cell-actions" data-label="Aksi">
                                <div class="actions">
                                    <form action="{{ route('assignments.destroy', $item) }}" method="POST" data-confirm-delete data-name="{{ $label }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" aria-label="Hapus penugasan {{ $label }}">
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

        <div class="data-card-bottom">{{ $assignments->links() }}</div>

    @endif

</div>

@include('partials.confirm-delete', [
    'noun' => 'penugasan',
    'note' => 'Catatan monitoring yang sudah dibuat guru tetap tersimpan.',
])

@include('partials.form-lock')

@endsection


@push('scripts')
<script>
    (function () {
        var type    = document.getElementById('type');
        var subject = document.getElementById('subject_id');
        var hint    = document.getElementById('subjectHint');

        function sync() {
            var homeroom = type.value === 'homeroom';
            subject.disabled = homeroom;
            if (homeroom) { subject.value = ''; }
            hint.style.display = homeroom ? '' : 'none';
        }

        type.addEventListener('change', sync);
        sync();
    })();
</script>
@endpush
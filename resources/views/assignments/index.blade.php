@extends('layouts.app')

@section('title', 'Penugasan Guru')

@section('content')

<h2 class="mb-4">Penugasan Guru</h2>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="mb-3">Tambah Penugasan</h5>

        <form action="{{ route('assignments.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Guru</label>
                    <select name="teacher_id" class="form-select" required>
                        <option value="">-- Pilih Guru --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" @selected(old('teacher_id') == $teacher->id)>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Jenis Penugasan</label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="homeroom" @selected(old('type') === 'homeroom')>Wali Kelas</option>
                        <option value="subject" @selected(old('type', 'subject') === 'subject')>Guru Mata Pelajaran</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Kelas</label>
                    <select name="classroom_id" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" @selected(old('classroom_id') == $classroom->id)>
                                {{ $classroom->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Mata Pelajaran</label>
                    <select name="subject_id" id="subject_id" class="form-select">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <small class="text-muted d-block mt-2">
                Wali kelas otomatis dapat memonitor semua mata pelajaran di kelasnya.
                Guru mata pelajaran hanya untuk mapel dan kelas yang dipilih. Tambahkan beberapa kali
                jika guru mengajar beberapa kelas.
            </small>

            <button class="btn btn-primary mt-3">Simpan Penugasan</button>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">

        <form method="GET" action="{{ route('assignments.index') }}" class="row g-2 mb-3">
            <div class="col-md-4">
                <select name="teacher_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua guru</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" @selected(request('teacher_id') == $teacher->id)>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Guru</th>
                        <th>Jenis</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th style="width:100px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $item)
                        <tr>
                            <td>{{ $item->teacher?->name ?? '-' }}</td>
                            <td>
                                @if($item->isHomeroom())
                                    <span class="badge text-bg-primary">Wali Kelas</span>
                                @else
                                    <span class="badge text-bg-secondary">Guru Mapel</span>
                                @endif
                            </td>
                            <td>{{ $item->classroom?->name ?? '-' }}</td>
                            <td>{{ $item->isHomeroom() ? 'Semua mata pelajaran' : ($item->subject?->name ?? '-') }}</td>
                            <td>
                                <form action="{{ route('assignments.destroy', $item) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus penugasan ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">Belum ada penugasan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $assignments->links() }}
    </div>
</div>

<script>
    (function () {
        var type = document.getElementById('type');
        var subject = document.getElementById('subject_id');

        function sync() {
            var homeroom = type.value === 'homeroom';
            subject.disabled = homeroom;
            if (homeroom) { subject.value = ''; }
        }

        type.addEventListener('change', sync);
        sync();
    })();
</script>

@endsection
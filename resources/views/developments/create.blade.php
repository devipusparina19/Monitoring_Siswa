@extends('layouts.app')

@section('title', 'Input Monitoring')

@section('content')

<h2 class="mb-4">Input Perkembangan Belajar</h2>

<div class="alert alert-primary">
    <strong>{{ $student->name }}</strong>
    — NIS {{ $student->nis }} — Kelas {{ $student->classroom?->name ?? '-' }}
</div>

<div class="card shadow-sm">
    <div class="card-body">

        <form action="{{ route('developments.store') }}" method="POST" onsubmit="this.querySelector('button.btn-primary').disabled = true;">
            @csrf

            <input type="hidden" name="student_id" value="{{ $student->id }}">

            <div class="mb-3">
                <label class="form-label">Mata Pelajaran</label>
                <select name="subject_id" class="form-select" required>
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)>{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Monitoring</label>
                <input
                    type="date"
                    name="monitoring_date"
                    class="form-control"
                    max="{{ now()->format('Y-m-d') }}"
                    value="{{ old('monitoring_date', now()->format('Y-m-d')) }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Kemampuan yang Diamati</label>
                <textarea name="ability" class="form-control" rows="4" maxlength="2000" required
                    placeholder="Contoh: Mampu melakukan operasi penjumlahan pecahan.">{{ old('ability') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Perkembangan</label>
                <textarea name="development" class="form-control" rows="4" maxlength="2000" required
                    placeholder="Tuliskan perkembangan siswa berdasarkan hasil pengamatan.">{{ old('development') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Catatan</label>
                <textarea name="note" class="form-control" rows="3" maxlength="2000">{{ old('note') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Saran Belajar</label>
                <textarea name="suggestion" class="form-control" rows="3" maxlength="2000"
                    placeholder="Materi atau kemampuan yang perlu ditingkatkan.">{{ old('suggestion') }}</textarea>
            </div>

            <button class="btn btn-primary">Simpan Monitoring</button>
            <a href="{{ route('teacher.students.show', $student) }}" class="btn btn-secondary">Kembali</a>
        </form>

    </div>
</div>

@endsection
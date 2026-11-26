@extends('layouts.app')

@section('title', 'Edit Monitoring')

@section('content')

<h2 class="mb-4">Edit Perkembangan Belajar</h2>

<div class="alert alert-primary">
    <strong>{{ $development->student->name }}</strong>
    — NIS {{ $development->student->nis }} — Kelas {{ $development->student->classroom?->name ?? '-' }}
</div>

<div class="card shadow-sm">
    <div class="card-body">

        <form action="{{ route('developments.update', $development) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Mata Pelajaran</label>
                <select name="subject_id" class="form-select" required>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" @selected(old('subject_id', $development->subject_id) == $subject->id)>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input
                    type="date"
                    name="monitoring_date"
                    class="form-control"
                    max="{{ now()->format('Y-m-d') }}"
                    value="{{ old('monitoring_date', $development->monitoring_date->format('Y-m-d')) }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Kemampuan</label>
                <textarea name="ability" class="form-control" rows="4" maxlength="2000" required>{{ old('ability', $development->ability) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Perkembangan</label>
                <textarea name="development" class="form-control" rows="4" maxlength="2000" required>{{ old('development', $development->development) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Catatan</label>
                <textarea name="note" class="form-control" rows="3" maxlength="2000">{{ old('note', $development->note) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Saran</label>
                <textarea name="suggestion" class="form-control" rows="3" maxlength="2000">{{ old('suggestion', $development->suggestion) }}</textarea>
            </div>

            <button class="btn btn-primary">Update</button>
            <a href="{{ route('developments.index') }}" class="btn btn-secondary">Kembali</a>
        </form>

    </div>
</div>

@endsection
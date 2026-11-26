@extends('layouts.app')

@section('title', 'Rekomendasi Video')

@section('content')

<h2 class="mb-4">Rekomendasi Video Pembelajaran</h2>

<div class="alert alert-primary">
    <strong>{{ $student->name }}</strong>
    — NIS {{ $student->nis }} — Kelas {{ $student->classroom?->name ?? '-' }}
</div>

<div class="card shadow-sm">
    <div class="card-body">

        <form action="{{ route('videos.store') }}" method="POST" onsubmit="this.querySelector('button.btn-primary').disabled = true;">
            @csrf

            <input type="hidden" name="student_id" value="{{ $student->id }}">

            <div class="mb-3">
                <label class="form-label">Judul Video</label>
                <input name="title" class="form-control" value="{{ old('title') }}" maxlength="255"
                    placeholder="Contoh: Belajar Pecahan" required>
            </div>

            <div class="mb-3">
                <label class="form-label">URL YouTube</label>
                <input type="url" name="youtube_url" class="form-control" value="{{ old('youtube_url') }}" maxlength="500"
                    placeholder="https://www.youtube.com/watch?v=..." required>
                <small class="text-muted">Gunakan video dari channel resmi sekolah.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Materi</label>
                <input name="material" class="form-control" value="{{ old('material') }}" maxlength="255"
                    placeholder="Contoh: Pecahan" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi / Alasan Rekomendasi</label>
                <textarea name="description" class="form-control" rows="4" maxlength="2000">{{ old('description') }}</textarea>
            </div>

            <button class="btn btn-primary">Simpan Video</button>
            <a href="{{ route('teacher.students.show', $student) }}" class="btn btn-secondary">Kembali</a>
        </form>

    </div>
</div>

@endsection
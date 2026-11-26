@extends('layouts.app')

@section('title', 'Rekomendasi Video')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> /
    <a href="{{ route('teacher.students.index') }}">Siswa Saya</a> /
    <a href="{{ route('teacher.students.show', $student) }}">{{ $student->name }}</a> /
    Rekomendasi Video
@endsection

@push('styles')
    @include('partials.ui-extras')
@endpush

@section('content')

<div class="page-head">
    <p>Pilih video dari kanal YouTube resmi sekolah yang sesuai dengan kebutuhan belajar siswa.</p>
</div>

<div class="student-banner form-card">
    <div class="person-avatar lg" aria-hidden="true">{{ strtoupper(mb_substr($student->name, 0, 1)) }}</div>
    <div class="min-w-0">
        <div class="name">{{ $student->name }}</div>
        <div class="meta">NIS {{ $student->nis }} · Kelas {{ $student->classroom?->name ?? '-' }}</div>
    </div>
</div>

<div class="card form-card">
    <div class="card-body">

        <form action="{{ route('videos.store') }}" method="POST" data-lock>
            @csrf

            <input type="hidden" name="student_id" value="{{ $student->id }}">

            @error('student_id')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="form-section">

                <h3 class="form-section-title"><i class="bi bi-youtube" aria-hidden="true"></i> Video</h3>
                <p class="form-section-hint">Tempel tautan video YouTube. Video yang sama tidak bisa direkomendasikan dua kali untuk siswa yang sama.</p>

                <div class="row g-3">

                    <div class="col-12">
                        <label class="form-label" for="title">Judul Video</label>
                        <input id="title" name="title" maxlength="255" required
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}" placeholder="Contoh: Belajar Pecahan">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="youtube_url">Tautan YouTube</label>
                        <input id="youtube_url" type="url" name="youtube_url" maxlength="500" required
                            class="form-control @error('youtube_url') is-invalid @enderror"
                            value="{{ old('youtube_url') }}" placeholder="https://www.youtube.com/watch?v=...">
                        @error('youtube_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="material">Materi</label>
                        <input id="material" name="material" maxlength="255" required
                            class="form-control @error('material') is-invalid @enderror"
                            value="{{ old('material') }}" placeholder="Contoh: Pecahan">
                        @error('material')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

            </div>

            <div class="form-section">

                <h3 class="form-section-title"><i class="bi bi-chat-left-text-fill" aria-hidden="true"></i> Alasan Rekomendasi</h3>
                <p class="form-section-hint">Opsional. Penjelasan singkat membantu orang tua dan siswa memahami tujuan video.</p>

                <label class="form-label" for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="4" maxlength="2000"
                    class="form-control @error('description') is-invalid @enderror"
                    placeholder="Contoh: Video ini membantu memahami penjumlahan pecahan.">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            @if(config('services.fonnte.enabled'))
                <div class="info-box">
                    <i class="bi bi-whatsapp" aria-hidden="true"></i>
                    <span>Setelah disimpan, orang tua yang terhubung akan menerima pemberitahuan lewat WhatsApp.</span>
                </div>
            @endif

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg" aria-hidden="true"></i>
                    Simpan Video
                </button>
                <a href="{{ route('teacher.students.show', $student) }}" class="btn btn-secondary">Batal</a>
            </div>

        </form>

    </div>
</div>

@include('partials.form-lock')

@endsection
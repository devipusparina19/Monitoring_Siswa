@extends('layouts.app')

@section('title', 'Rekomendasi Video')

@section('content')

<h2 class="mb-4">
    Rekomendasi Video Pembelajaran
</h2>

<div class="card shadow-sm">

    <div class="card-body">

        <form
            action="{{ route('videos.store') }}"
            method="POST"
        >

            @csrf

            <div class="mb-3">

                <label class="form-label">
                    Siswa
                </label>

                <select
                    name="student_id"
                    class="form-select"
                    required
                >

                    <option value="">
                        -- Pilih Siswa --
                    </option>

                    @foreach($students as $student)

                        <option
                            value="{{ $student->id }}"
                        >
                            {{ $student->name }}
                            - {{ $student->class_name }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Judul Video
                </label>

                <input
                    name="title"
                    class="form-control"
                    placeholder="Contoh: Belajar Pecahan"
                    required
                >

            </div>

            <div class="mb-3">

                <label class="form-label">
                    URL YouTube
                </label>

                <input
                    type="url"
                    name="youtube_url"
                    class="form-control"
                    placeholder="https://www.youtube.com/watch?v=..."
                    required
                >

                <small class="text-muted">
                    Gunakan video dari channel resmi sekolah.
                </small>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Materi
                </label>

                <input
                    name="material"
                    class="form-control"
                    placeholder="Contoh: Pecahan"
                    required
                >

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Deskripsi
                </label>

                <textarea
                    name="description"
                    class="form-control"
                    rows="4"
                ></textarea>

            </div>

            <button class="btn btn-primary">
                Simpan Video
            </button>

        </form>

    </div>

</div>

@endsection
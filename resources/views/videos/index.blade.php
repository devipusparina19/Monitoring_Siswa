@extends('layouts.app')

@section('title', 'Video Pembelajaran')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / Video Pembelajaran
@endsection

@push('styles')
    @include('partials.ui-extras')
@endpush

@section('content')

<div class="page-head">

    <p>Video yang pernah Anda rekomendasikan kepada siswa. Untuk menambah video, mulai dari detail siswa.</p>

    <div class="page-actions">
        <a href="{{ route('teacher.students.index') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg" aria-hidden="true"></i>
            Rekomendasikan Video
        </a>
    </div>

</div>

@if($videos->isEmpty())

    <div class="card">
        <div class="empty-state">
            <div class="empty-icon" aria-hidden="true"><i class="bi bi-play-circle"></i></div>
            <h3>Belum ada video rekomendasi</h3>
            <p>Pilih siswa, lalu rekomendasikan video dari kanal YouTube sekolah.</p>
            <a href="{{ route('teacher.students.index') }}" class="btn btn-primary">
                <i class="bi bi-people-fill" aria-hidden="true"></i>
                Pilih Siswa
            </a>
        </div>
    </div>

@else

    <div class="row g-4">

        @foreach($videos as $video)

            <div class="col-12 col-md-6">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">

                        @if($video->youtube_id)
                            <div class="video-frame">
                                <iframe
                                    src="https://www.youtube.com/embed/{{ $video->youtube_id }}"
                                    title="{{ $video->title }}"
                                    loading="lazy"
                                    allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        @else
                            <div class="video-frame is-fallback">
                                <div>
                                    <i class="bi bi-exclamation-circle" aria-hidden="true"></i>
                                    Link video tidak dapat ditampilkan.
                                </div>
                            </div>
                        @endif

                        <div class="video-title">{{ $video->title }}</div>

                        <div class="chip-list mb-2">
                            <span class="chip is-primary"><i class="bi bi-bookmark-fill" aria-hidden="true"></i> {{ $video->material }}</span>
                            <span class="chip"><i class="bi bi-person" aria-hidden="true"></i> {{ $video->student?->name ?? '-' }} · Kelas {{ $video->student?->classroom?->name ?? '-' }}</span>
                        </div>

                        @if($video->description)
                            <p class="text-muted mb-3">{{ $video->description }}</p>
                        @endif

                        <div class="mt-auto pt-2">
                            <form action="{{ route('videos.destroy', $video) }}" method="POST" data-confirm-delete data-name="{{ $video->title }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" aria-label="Hapus video {{ $video->title }}">
                                    <i class="bi bi-trash3" aria-hidden="true"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        @endforeach

    </div>

    <div class="mt-4">{{ $videos->links() }}</div>

@endif

@include('partials.confirm-delete', ['noun' => 'video'])

@endsection
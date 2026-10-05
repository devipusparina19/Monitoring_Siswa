@extends('layouts.app')

@section('title', 'Video Pembelajaran')

@section('content')

<div class="d-flex justify-content-between mb-3">

    <h2>Video Pembelajaran</h2>

    <a
        href="{{ route('videos.create') }}"
        class="btn btn-primary"
    >
        + Rekomendasikan Video
    </a>

</div>

<div class="row g-4">

    @forelse($videos as $video)

        <div class="col-md-6">

            <div class="card shadow-sm">

                <div class="card-body">

                    <h5>
                        {{ $video->title }}
                    </h5>

                    <p>
                        Siswa:
                        <strong>
                            {{ $video->student->name }}
                        </strong>
                    </p>

                    <p>
                        Materi:
                        {{ $video->material }}
                    </p>

                    @if($video->youtube_id)

                        <div class="ratio ratio-16x9 mb-3">

                            <iframe
                                src="https://www.youtube.com/embed/{{ $video->youtube_id }}"
                                allowfullscreen
                            ></iframe>

                        </div>

                    @endif

                    <form
                        action="{{ route('videos.destroy', $video) }}"
                        method="POST"
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Hapus video?')"
                        >
                            Hapus
                        </button>

                    </form>

                </div>

            </div>

        </div>

    @empty

        <div class="col-12">

            <div class="alert alert-info">
                Belum ada video rekomendasi.
            </div>

        </div>

    @endforelse

</div>

{{ $videos->links() }}

@endsection
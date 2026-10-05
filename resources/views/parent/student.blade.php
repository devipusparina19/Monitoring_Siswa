@extends('layouts.app')

@section('title', 'Perkembangan Anak')

@section('content')

<h2>
    {{ $student->name }}
</h2>

<p class="text-muted">
    NIS: {{ $student->nis }}
    |
    Kelas: {{ $student->class_name }}
</p>

<hr>

<h4 class="mb-3">
    Riwayat Perkembangan
</h4>

@forelse($student->developments as $development)

    <div class="card shadow-sm mb-3">

        <div class="card-body">

            <div class="d-flex justify-content-between">

                <h5>
                    {{ $development->subject }}
                </h5>

                <span>
                    {{ $development->monitoring_date->format('d-m-Y') }}
                </span>

            </div>

            <p>
                <strong>Kemampuan:</strong><br>
                {{ $development->ability }}
            </p>

            <p>
                <strong>Perkembangan:</strong><br>
                {{ $development->development }}
            </p>

            <p>
                <strong>Catatan:</strong><br>
                {{ $development->note ?: '-' }}
            </p>

            <p>
                <strong>Saran:</strong><br>
                {{ $development->suggestion ?: '-' }}
            </p>

        </div>

    </div>

@empty

    <div class="alert alert-info">
        Belum ada data perkembangan.
    </div>

@endforelse

<hr class="my-5">

<h4 class="mb-3">
    Video Pembelajaran yang Direkomendasikan Guru
</h4>

<div class="row g-4">

    @forelse($student->videos as $video)

        <div class="col-md-6">

            <div class="card shadow-sm">

                <div class="card-body">

                    <h5>
                        {{ $video->title }}
                    </h5>

                    <p>
                        Materi:
                        {{ $video->material }}
                    </p>

                    @if($video->youtube_id)

                        <div class="ratio ratio-16x9">

                            <iframe
                                src="https://www.youtube.com/embed/{{ $video->youtube_id }}"
                                allowfullscreen
                            ></iframe>

                        </div>

                    @endif

                    @if($video->description)

                        <p class="mt-3">
                            {{ $video->description }}
                        </p>

                    @endif

                </div>

            </div>

        </div>

    @empty

        <div class="col-12">

            <div class="alert alert-info">
                Belum ada video rekomendasi dari guru.
            </div>

        </div>

    @endforelse

</div>

@endsection
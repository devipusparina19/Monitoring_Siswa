@extends('layouts.app')

@section('title', 'Perkembangan Anak')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / {{ $student->name }}
@endsection

@push('styles')
    @include('partials.ui-extras')
@endpush

@section('content')

@php
    $bySubject = $student->developments->groupBy('subject')->map->count()->sortDesc();
    $latest = $student->developments->first();
@endphp


{{-- PEMILIH ANAK (hanya jika punya lebih dari satu anak) --}}
@if($siblings->count() > 1)
    <nav class="switcher mb-3" aria-label="Pilih anak">
        @foreach($siblings as $sibling)
            <a href="{{ route('parent.child', $sibling) }}"
               class="switch-item {{ $sibling->id === $student->id ? 'is-active' : '' }}"
               @if($sibling->id === $student->id) aria-current="page" @endif>
                <span class="mini-avatar" aria-hidden="true">{{ strtoupper(mb_substr($sibling->name, 0, 1)) }}</span>
                {{ $sibling->name }}
            </a>
        @endforeach
    </nav>
@endif


{{-- HERO ANAK --}}
<section class="student-hero mb-4" aria-label="Identitas anak">

    <div class="hero-main">

        <div class="hero-avatar" aria-hidden="true">{{ strtoupper(mb_substr($student->name, 0, 1)) }}</div>

        <div class="min-w-0">
            <h2 class="hero-name">{{ $student->name }}</h2>

            <div class="chip-list">
                <span class="chip"><i class="bi bi-building" aria-hidden="true"></i> Kelas {{ $student->classroom?->name ?? '-' }}</span>
                <span class="chip"><i class="bi bi-hash" aria-hidden="true"></i> NIS {{ $student->nis }}</span>
                @if($student->classroom?->homeroomAssignment?->teacher)
                    <span class="chip"><i class="bi bi-person-badge" aria-hidden="true"></i> Wali kelas: {{ $student->classroom->homeroomAssignment->teacher->name }}</span>
                @endif
            </div>
        </div>

    </div>

</section>


{{-- RINGKASAN --}}
<div class="mini-stats mb-4">

    <div class="mini-stat">
        <span class="v">{{ $student->developments->count() }}</span>
        <span class="l">Catatan perkembangan</span>
    </div>

    <div class="mini-stat">
        <span class="v">{{ $student->videos->count() }}</span>
        <span class="l">Video rekomendasi</span>
    </div>

    <div class="mini-stat">
        <span class="v" style="font-size:1.05rem">{{ $latest ? $latest->monitoring_date->locale('id')->translatedFormat('d M Y') : '-' }}</span>
        <span class="l">Catatan terakhir</span>
    </div>

</div>


<div class="row g-4 mb-5">

    {{-- TIMELINE --}}
    <div class="col-12 col-lg-8">

        <h3 class="panel-title">Perjalanan Belajar</h3>

        @if($student->developments->isEmpty())

            <div class="card">
                <div class="empty-state">
                    <div class="empty-icon" aria-hidden="true"><i class="bi bi-clipboard-x"></i></div>
                    <h3>Belum ada catatan perkembangan</h3>
                    <p>Catatan akan muncul di sini setelah guru menuliskan perkembangan belajar {{ \Illuminate\Support\Str::before($student->name, ' ') }}.</p>
                </div>
            </div>

        @else

            <ol class="timeline">
                @foreach($student->developments as $development)
                    <li class="tl-item">

                        <span class="tl-dot" aria-hidden="true"></span>

                        <article class="tl-card">

                            <div class="tl-head">
                                <div>
                                    <div class="tl-date">{{ $development->monitoring_date->locale('id')->translatedFormat('d F Y') }}</div>
                                    <div class="tl-by">dari {{ $development->teacher?->name ?? 'Guru' }}</div>
                                </div>
                                <span class="badge text-bg-primary">{{ $development->subject }}</span>
                            </div>

                            <span class="tl-label">Yang sudah dapat dilakukan</span>
                            <p class="tl-text">{{ $development->ability }}</p>

                            <span class="tl-label">Perkembangan</span>
                            <p class="tl-text">{{ $development->development }}</p>

                            @if($development->note)
                                <div class="tl-note">
                                    <span class="tl-label">Catatan guru</span>
                                    <p class="tl-text">{{ $development->note }}</p>
                                </div>
                            @endif

                            @if($development->suggestion)
                                <div class="tl-tip">
                                    <span class="tl-label">Saran untuk belajar di rumah</span>
                                    <p class="tl-text">{{ $development->suggestion }}</p>
                                </div>
                            @endif

                        </article>

                    </li>
                @endforeach
            </ol>

        @endif

    </div>


    {{-- SAMPING --}}
    <div class="col-12 col-lg-4">

        <div class="card mb-4">
            <div class="card-body">
                <h3 class="panel-title">Aktivitas 6 Bulan Terakhir</h3>
                <div class="mini-bars" role="img" aria-label="Jumlah catatan perkembangan per bulan">
                    @foreach($activity as $row)
                        <div class="mini-bar">
                            <b>{{ $row['count'] }}</b>
                            <div class="bar-track">
                                <div class="bar-fill {{ $row['count'] === 0 ? 'is-zero' : '' }}" style="height: {{ $row['count'] === 0 ? 4 : $row['percent'] }}%"></div>
                            </div>
                            <small>{{ $row['label'] }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        @if($bySubject->isNotEmpty())
            <div class="card">
                <div class="card-body">
                    <h3 class="panel-title">Catatan per Mata Pelajaran</h3>
                    <div class="chip-list">
                        @foreach($bySubject as $subjectName => $total)
                            <span class="chip">{{ $subjectName }} · {{ $total }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </div>

</div>


{{-- VIDEO --}}
<section aria-labelledby="videoTitle">

    <h3 class="panel-title" id="videoTitle">Video Pembelajaran dari Guru</h3>

    @if($student->videos->isEmpty())

        <div class="card">
            <div class="empty-state">
                <div class="empty-icon" aria-hidden="true"><i class="bi bi-play-circle"></i></div>
                <h3>Belum ada video rekomendasi</h3>
                <p>Video akan muncul di sini setelah guru merekomendasikannya.</p>
            </div>
        </div>

    @else

        <div class="row g-4">

            @foreach($student->videos as $video)

                <div class="col-12 col-md-6">
                    <div class="card h-100">
                        <div class="card-body">

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

                            <div class="chip-list">
                                <span class="chip is-primary"><i class="bi bi-bookmark-fill" aria-hidden="true"></i> {{ $video->material }}</span>
                                <span class="chip"><i class="bi bi-person" aria-hidden="true"></i> {{ $video->teacher?->name ?? 'Guru' }}</span>
                            </div>

                            @if($video->description)
                                <p class="text-muted mt-3 mb-0">{{ $video->description }}</p>
                            @endif

                        </div>
                    </div>
                </div>

            @endforeach

        </div>

    @endif

</section>

@endsection
@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> /
    <a href="{{ route('teacher.students.index') }}">Siswa Saya</a> /
    {{ $student->name }}
@endsection

@push('styles')
    @include('partials.ui-extras')
@endpush

@section('content')

{{-- HERO SISWA --}}
<section class="student-hero mb-4" aria-label="Identitas siswa">

    <div class="hero-main">

        <div class="hero-avatar" aria-hidden="true">{{ strtoupper(mb_substr($student->name, 0, 1)) }}</div>

        <div class="min-w-0">
            <h2 class="hero-name">{{ $student->name }}</h2>

            <div class="chip-list">
                <span class="chip"><i class="bi bi-building" aria-hidden="true"></i> Kelas {{ $student->classroom?->name ?? '-' }}</span>
                <span class="chip"><i class="bi bi-hash" aria-hidden="true"></i> NIS {{ $student->nis }}</span>
                <span class="chip"><i class="bi bi-person" aria-hidden="true"></i> {{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                @if($student->classroom?->homeroomAssignment?->teacher)
                    <span class="chip"><i class="bi bi-person-badge" aria-hidden="true"></i> Wali: {{ $student->classroom->homeroomAssignment->teacher->name }}</span>
                @endif
            </div>
        </div>

    </div>

    <div class="hero-actions">
        <a href="{{ route('developments.create', ['student' => $student->id]) }}" class="btn btn-light">
            <i class="bi bi-plus-lg" aria-hidden="true"></i>
            Input Monitoring
        </a>
        <a href="{{ route('videos.create', ['student' => $student->id]) }}" class="btn btn-outline-light">
            <i class="bi bi-play-circle" aria-hidden="true"></i>
            Rekomendasi Video
        </a>
    </div>

</section>


<div class="row g-4">

    {{-- ============ KOLOM UTAMA ============ --}}
    <div class="col-12 col-lg-8">

        <h3 class="panel-title">Riwayat Monitoring</h3>

        @if($developments->isEmpty())

            <div class="card">
                <div class="empty-state">
                    <div class="empty-icon" aria-hidden="true"><i class="bi bi-clipboard-x"></i></div>
                    <h3>Belum ada catatan monitoring</h3>
                    <p>Catat perkembangan belajar pertama {{ $student->name }}.</p>
                    <a href="{{ route('developments.create', ['student' => $student->id]) }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg" aria-hidden="true"></i>
                        Input Monitoring
                    </a>
                </div>
            </div>

        @else

            <ol class="timeline">
                @foreach($developments as $item)
                    <li class="tl-item">

                        <span class="tl-dot" aria-hidden="true"></span>

                        <article class="tl-card">

                            <div class="tl-head">
                                <div>
                                    <div class="tl-date">{{ $item->monitoring_date->locale('id')->translatedFormat('d F Y') }}</div>
                                    <div class="tl-by">oleh {{ $item->teacher?->name ?? 'Guru' }}</div>
                                </div>
                                <span class="badge text-bg-primary">{{ $item->subject }}</span>
                            </div>

                            <span class="tl-label">Kemampuan yang diamati</span>
                            <p class="tl-text">{{ $item->ability }}</p>

                            <span class="tl-label">Perkembangan</span>
                            <p class="tl-text">{{ $item->development }}</p>

                            @if($item->note)
                                <div class="tl-note">
                                    <span class="tl-label">Catatan</span>
                                    <p class="tl-text">{{ $item->note }}</p>
                                </div>
                            @endif

                            @if($item->suggestion)
                                <div class="tl-tip">
                                    <span class="tl-label">Saran belajar</span>
                                    <p class="tl-text">{{ $item->suggestion }}</p>
                                </div>
                            @endif

                            @if($item->teacher_id === auth()->id())
                                <div class="mt-3">
                                    <a href="{{ route('developments.edit', $item) }}" class="btn btn-secondary btn-sm">
                                        <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                        Edit
                                    </a>
                                </div>
                            @endif

                        </article>

                    </li>
                @endforeach
            </ol>

            @if($developmentCount > $developments->count())
                <p class="text-muted small mt-3 mb-0">
                    Menampilkan {{ $developments->count() }} catatan terbaru.
                    <a href="{{ route('developments.index', ['q' => $student->nis]) }}">Lihat semua riwayat</a>
                </p>
            @endif

        @endif

    </div>


    {{-- ============ KOLOM SAMPING ============ --}}
    <div class="col-12 col-lg-4">

        <div class="mini-stats mb-4">
            <div class="mini-stat">
                <span class="v">{{ $developmentCount }}</span>
                <span class="l">Catatan monitoring</span>
            </div>
            <div class="mini-stat">
                <span class="v">{{ $videoCount }}</span>
                <span class="l">Video rekomendasi</span>
            </div>
            <div class="mini-stat">
                <span class="v" style="font-size:1.05rem">{{ $lastDate ? \Illuminate\Support\Carbon::parse($lastDate)->locale('id')->translatedFormat('d M Y') : '-' }}</span>
                <span class="l">Monitoring terakhir</span>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h3 class="panel-title">Aktivitas 6 Bulan Terakhir</h3>
                <div class="mini-bars" role="img" aria-label="Jumlah catatan monitoring per bulan">
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

        <div class="card mb-4">
            <div class="card-body">
                <h3 class="panel-title">Mata Pelajaran yang Dapat Anda Monitor</h3>

                @if($isHomeroom)
                    <div class="info-box">
                        <i class="bi bi-person-badge-fill" aria-hidden="true"></i>
                        <span>Anda <strong>wali kelas</strong> siswa ini, sehingga dapat memonitor semua mata pelajaran.</span>
                    </div>
                @elseif($subjects->isEmpty())
                    <span class="text-muted">Belum ada penugasan mata pelajaran.</span>
                @else
                    <div class="chip-list">
                        @foreach($subjects as $subject)
                            <span class="chip">{{ $subject->name }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 class="panel-title">Video Rekomendasi</h3>

                @forelse($videos as $video)
                    <div class="side-list-item">
                        <div class="min-w-0">
                            <strong>{{ $video->title }}</strong>
                            <small>{{ $video->material }} · {{ $video->teacher?->name ?? '-' }}</small>
                        </div>
                        <small class="text-nowrap">{{ $video->created_at->format('d-m-Y') }}</small>
                    </div>
                @empty
                    <span class="text-muted">Belum ada video rekomendasi.</span>
                @endforelse
            </div>
        </div>

    </div>

</div>

@endsection
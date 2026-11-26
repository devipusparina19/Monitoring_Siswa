@extends('layouts.app')

@section('title', 'Dashboard Orang Tua')

{{-- Halaman ini punya pembuka sendiri, jadi banner sambutan layout dimatikan agar tidak tampil dua kali --}}
@section('no_welcome', '1')

@push('styles')
    @include('partials.ui-extras')
@endpush


@section('content')

@php
    // Video terbaru dari semua anak (maksimal 4 agar halaman tetap ringan)
    $videoItems = [];

    foreach ($children as $child) {
        foreach ($child->videos as $video) {
            $videoItems[] = ['video' => $video, 'child' => $child];
        }
    }

    usort($videoItems, fn ($a, $b) => $b['video']->created_at <=> $a['video']->created_at);
    $videoItems = array_slice($videoItems, 0, 4);
@endphp


{{-- PEMBUKA --}}
<div class="page-head">

    <div>
        <h2><span id="greeting">Selamat datang,</span> {{ auth()->user()->name }}</h2>
        <p>Pantau perkembangan belajar anak Anda dengan mudah, kapan saja.</p>
    </div>

</div>


{{-- DATA ANAK --}}
<section class="mb-5" aria-labelledby="anakTitle">

    <h3 class="h4 mb-3" id="anakTitle">{{ $children->count() > 1 ? 'Pilih Anak' : 'Anak Anda' }}</h3>

    <div class="row g-4">

        @forelse($children as $child)

            @php($latest = $child->developments->first())

            <div class="col-12 col-md-6">

                <div class="card h-100">
                    <div class="card-body d-flex flex-column">

                        <div class="d-flex align-items-center gap-3">

                            <div class="person-avatar lg" aria-hidden="true">{{ strtoupper(mb_substr($child->name, 0, 1)) }}</div>

                            <div class="min-w-0">
                                <h4 class="mb-2">{{ $child->name }}</h4>
                                <div class="chip-list">
                                    <span class="chip is-primary">Kelas {{ $child->classroom?->name ?? '-' }}</span>
                                    <span class="chip">NIS {{ $child->nis }}</span>
                                </div>
                            </div>

                        </div>

                        <dl class="child-facts mb-0">
                            <div>
                                <dt>Catatan perkembangan</dt>
                                <dd>{{ $child->developments->count() }}</dd>
                            </div>
                            <div>
                                <dt>Monitoring terakhir</dt>
                                <dd>
                                    @if($latest)
                                        {{ $latest->monitoring_date->locale('id')->translatedFormat('d M Y') }}
                                    @else
                                        Belum ada
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt>Video rekomendasi</dt>
                                <dd>{{ $child->videos->count() }}</dd>
                            </div>
                        </dl>

                        <a href="{{ route('parent.child', $child) }}" class="btn btn-primary w-100 mt-auto">
                            <i class="bi bi-eye-fill" aria-hidden="true"></i>
                            Lihat Perkembangan {{ \Illuminate\Support\Str::before($child->name, ' ') }}
                        </a>

                    </div>
                </div>

            </div>

        @empty

            <div class="col-12">

                <div class="card">
                    <div class="empty-state">

                        <div class="empty-icon" aria-hidden="true"><i class="bi bi-person-x"></i></div>

                        <h3>Data anak belum tersedia</h3>

                        <p>Silakan hubungi admin sekolah untuk memastikan data anak sudah terhubung dengan akun Anda.</p>

                    </div>
                </div>

            </div>

        @endforelse

    </div>

</section>


{{-- VIDEO TERBARU --}}
<section class="mb-5" aria-labelledby="videoTitle">

    <h3 class="h4 mb-3" id="videoTitle">Video Pembelajaran Terbaru</h3>

    @if(count($videoItems))

        <div class="row g-4">

            @foreach($videoItems as $item)

                @php($video = $item['video'])

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
                                @if($children->count() > 1)
                                    <span class="chip"><i class="bi bi-person" aria-hidden="true"></i> {{ $item['child']->name }}</span>
                                @endif
                            </div>

                            @if($video->description)
                                <p class="text-muted mt-3 mb-0">{{ $video->description }}</p>
                            @endif

                        </div>
                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="card">
            <div class="empty-state">

                <div class="empty-icon" aria-hidden="true"><i class="bi bi-play-circle"></i></div>

                <h3>Belum ada video pembelajaran</h3>

                <p>Guru belum memberikan rekomendasi video pembelajaran untuk anak Anda.</p>

            </div>
        </div>

    @endif

</section>


{{-- PANDUAN SINGKAT --}}
<section aria-labelledby="panduanTitle">

    <div class="card">
        <div class="card-body">

            <h3 class="h4 mb-2" id="panduanTitle">Yang bisa Anda lakukan di sini</h3>

            <div class="side-list-item">
                <div class="d-flex align-items-start gap-3">
                    <div class="stat-icon" aria-hidden="true"><i class="bi bi-clipboard2-check-fill"></i></div>
                    <div>
                        <strong>Melihat perkembangan anak</strong>
                        <small>Buka catatan perkembangan belajar yang ditulis oleh guru, lengkap dengan saran belajar di rumah.</small>
                    </div>
                </div>
            </div>

            <div class="side-list-item">
                <div class="d-flex align-items-start gap-3">
                    <div class="stat-icon" aria-hidden="true"><i class="bi bi-play-circle-fill"></i></div>
                    <div>
                        <strong>Menonton video belajar</strong>
                        <small>Video pilihan guru dari YouTube sekolah untuk membantu anak belajar di rumah.</small>
                    </div>
                </div>
            </div>

            <div class="side-list-item">
                <div class="d-flex align-items-start gap-3">
                    <div class="stat-icon" aria-hidden="true"><i class="bi bi-whatsapp"></i></div>
                    <div>
                        <strong>Menerima kabar lewat WhatsApp</strong>
                        <small>Pemberitahuan perkembangan dapat dikirim ke WhatsApp Anda. Informasi lengkap tetap ada di sini.</small>
                    </div>
                </div>
            </div>

        </div>
    </div>

</section>

@endsection
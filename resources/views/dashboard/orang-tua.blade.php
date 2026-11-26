@extends('layouts.app')

@section('title', 'Dashboard Orang Tua')

{{-- Halaman ini punya pembuka sendiri, jadi banner sambutan layout dimatikan agar tidak tampil dua kali --}}
@section('no_welcome', '1')

@push('styles')
<style>

    /* Khusus dashboard orang tua. Kartu, tombol, badge, dan empty state memakai komponen layout. */

    .child-facts {
        margin: 18px 0 20px;
        padding: 12px 16px;
        border-radius: var(--r-sm);
        background: var(--soft);
    }

    .child-facts div { display: flex; justify-content: space-between; gap: 16px; padding: 5px 0; }
    .child-facts dt  { font-weight: 500; color: var(--slate); }
    .child-facts dd  { margin: 0; font-weight: 700; color: var(--navy); text-align: right; overflow-wrap: anywhere; }

    .video-frame { aspect-ratio: 16 / 9; border-radius: var(--r-sm); overflow: hidden; background: var(--soft); }
    .video-frame iframe { width: 100%; height: 100%; border: 0; display: block; }
    .video-frame.is-fallback { display: grid; place-items: center; padding: 16px; text-align: center; color: var(--slate); }
    .video-frame.is-fallback i { display: block; margin-bottom: 6px; font-size: 30px; }

    .video-title { margin: 16px 0 8px; font-size: 1.05rem; font-weight: 800; line-height: 1.4; color: var(--navy); }

    .help-item { display: flex; align-items: flex-start; gap: 16px; padding: 16px 0; }
    .help-item + .help-item { border-top: 1px solid var(--line); }
    .help-item strong { display: block; margin-bottom: 2px; color: var(--navy); }
    .help-item span { color: var(--slate); }

</style>
@endpush


@section('content')

@php
    $children = auth()->user()->children;

    // Kumpulkan video semua anak sekali saja, lengkap dengan ID YouTube-nya
    $videoItems = [];

    foreach ($children as $child) {
        foreach ($child->videos as $video) {

            preg_match(
                '/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([^&\n?#]+)/',
                (string) $video->youtube_url,
                $matches
            );

            $videoItems[] = [
                'video'     => $video,
                'child'     => $child,
                'youtubeId' => $matches[1] ?? null,
            ];
        }
    }
@endphp


{{-- PEMBUKA: sapaan. Judul halaman sudah ada di topbar; sapaan diisi otomatis oleh script layout (id="greeting"). --}}
<div class="page-head">

    <div>
        <h2><span id="greeting">Selamat datang,</span> {{ auth()->user()->name }}</h2>
        <p>Pantau perkembangan belajar anak Anda secara berkala melalui sistem informasi sekolah.</p>
    </div>

</div>


{{-- DATA ANAK --}}
<section class="mb-5" aria-labelledby="anakTitle">

    <h3 class="h4 mb-3" id="anakTitle">Data Anak</h3>

    <div class="row g-4">

        @forelse($children as $child)

            <div class="col-12 col-md-6">

                <div class="card h-100">
                    <div class="card-body">

                        <div class="d-flex align-items-center gap-3">

                            <div class="stat-icon" aria-hidden="true">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>

                            <h4 class="mb-0">{{ $child->name }}</h4>

                        </div>

                        <dl class="child-facts mb-0">
                            <div>
                                <dt>NIS</dt>
                                <dd>{{ $child->nis }}</dd>
                            </div>
                            <div>
                                <dt>Kelas</dt>
                                <dd>{{ $child->class_name }}</dd>
                            </div>
                        </dl>

                        @if(\Illuminate\Support\Facades\Route::has('parent.child'))
                            <a href="{{ route('parent.child', $child) }}" class="btn btn-primary w-100 mt-3">
                                <i class="bi bi-eye-fill" aria-hidden="true"></i>
                                Lihat Perkembangan Anak
                            </a>
                        @endif

                    </div>
                </div>

            </div>

        @empty

            <div class="col-12">

                <div class="card">
                    <div class="empty-state">

                        <div class="empty-icon" aria-hidden="true">
                            <i class="bi bi-person-x"></i>
                        </div>

                        <h3>Data anak belum tersedia</h3>

                        <p>Silakan hubungi admin sekolah untuk memastikan data anak sudah terhubung dengan akun Anda.</p>

                    </div>
                </div>

            </div>

        @endforelse

    </div>

</section>


{{-- VIDEO PEMBELAJARAN --}}
<section class="mb-5" aria-labelledby="videoTitle">

    <h3 class="h4 mb-3" id="videoTitle">Video Pembelajaran</h3>

    @if(count($videoItems))

        <div class="row g-4">

            @foreach($videoItems as $item)

                @php
                    $video = $item['video'];
                @endphp

                <div class="col-12 col-md-6">

                    <div class="card h-100">
                        <div class="card-body">

                            @if($item['youtubeId'])

                                <div class="video-frame">
                                    <iframe
                                        src="https://www.youtube.com/embed/{{ $item['youtubeId'] }}"
                                        title="{{ $video->title }}"
                                        loading="lazy"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
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

                            <span class="badge text-bg-primary">
                                <i class="bi bi-bookmark-fill" aria-hidden="true"></i>
                                {{ $video->material }}
                            </span>

                            @if($video->description)
                                <p class="text-muted mt-3 mb-0">{{ $video->description }}</p>
                            @endif

                            {{-- Nama anak hanya perlu ditampilkan kalau orang tua punya lebih dari satu anak --}}
                            @if($children->count() > 1)
                                <p class="text-muted small mt-3 mb-0">
                                    Video untuk: <strong>{{ $item['child']->name }}</strong>
                                </p>
                            @endif

                        </div>
                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="card">
            <div class="empty-state">

                <div class="empty-icon" aria-hidden="true">
                    <i class="bi bi-play-circle"></i>
                </div>

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

            <div class="help-item">
                <div class="stat-icon" aria-hidden="true"><i class="bi bi-clipboard2-check-fill"></i></div>
                <div>
                    <strong>Melihat perkembangan anak</strong>
                    <span>Buka catatan perkembangan belajar anak yang ditulis oleh guru.</span>
                </div>
            </div>

            <div class="help-item">
                <div class="stat-icon" aria-hidden="true"><i class="bi bi-play-circle-fill"></i></div>
                <div>
                    <strong>Menonton video belajar</strong>
                    <span>Video pilihan guru dari YouTube sekolah untuk membantu anak belajar di rumah.</span>
                </div>
            </div>

            <div class="help-item">
                <div class="stat-icon" aria-hidden="true"><i class="bi bi-whatsapp"></i></div>
                <div>
                    <strong>Menerima kabar lewat WhatsApp</strong>
                    <span>Informasi perkembangan belajar dapat disampaikan ke WhatsApp Anda.</span>
                </div>
            </div>

        </div>
    </div>

</section>

@endsection
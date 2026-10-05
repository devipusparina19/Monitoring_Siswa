@extends('layouts.app')

@section('title', 'Dashboard Orang Tua')

@push('styles')
<style>

    .parent-welcome {
        background: linear-gradient(
            135deg,
            #0d6efd 0%,
            #2563eb 100%
        );
        border-radius: 20px;
        padding: 30px;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 28px;
    }

    .parent-welcome::after {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        right: -70px;
        top: -80px;
        background: rgba(255,255,255,.08);
        border-radius: 50%;
    }

    .parent-welcome-content {
        position: relative;
        z-index: 2;
    }

    .parent-welcome h2 {
        font-size: 25px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .parent-welcome p {
        margin: 0;
        font-size: 14px;
        line-height: 1.7;
        max-width: 720px;
        opacity: .92;
    }

    .section-title {
        font-size: 19px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 16px;
    }

    .child-card {
        background: #ffffff;
        border: 1px solid #e7edf5;
        border-radius: 18px;
        padding: 22px;
        height: 100%;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .04);
        transition: .2s ease;
    }

    .child-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
    }

    .child-top {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .child-avatar {
        width: 58px;
        height: 58px;
        flex-shrink: 0;
        border-radius: 16px;
        background: #eaf2ff;
        color: #155eef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
    }

    .child-name {
        font-size: 17px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .child-class {
        font-size: 12px;
        color: #64748b;
    }

    .child-info {
        background: #f8fafc;
        border-radius: 12px;
        padding: 14px;
        margin-bottom: 18px;
    }

    .child-info-row {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        padding: 5px 0;
        font-size: 12px;
    }

    .child-info-label {
        color: #64748b;
    }

    .child-info-value {
        color: #1e293b;
        font-weight: 600;
        text-align: right;
    }

    .btn-child {
        width: 100%;
        min-height: 48px;
        border-radius: 11px;
        font-size: 13px;
        font-weight: 600;
    }

    .info-card {
        background: #ffffff;
        border: 1px solid #e7edf5;
        border-radius: 18px;
        padding: 24px;
        margin-top: 28px;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 12px 0;
    }

    .info-icon {
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        border-radius: 11px;
        background: #eff6ff;
        color: #155eef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .info-item strong {
        display: block;
        font-size: 13px;
        margin-bottom: 3px;
    }

    .info-item span {
        font-size: 12px;
        color: #64748b;
        line-height: 1.6;
    }


    /* =========================================
       TAMBAHAN: VIDEO PEMBELAJARAN
       ========================================= */

    .video-section {
        margin-top: 28px;
    }

    .video-card {
        background: #ffffff;
        border: 1px solid #e7edf5;
        border-radius: 18px;
        padding: 18px;
        height: 100%;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .04);
        transition: .2s ease;
    }

    .video-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
    }

    .video-thumbnail {
        position: relative;
        width: 100%;
        aspect-ratio: 16 / 9;
        overflow: hidden;
        border-radius: 14px;
        background: #eaf2ff;
    }

    .video-thumbnail iframe {
        width: 100%;
        height: 100%;
        border: 0;
        display: block;
    }

    .video-content {
        padding-top: 16px;
    }

    .video-title {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
        line-height: 1.5;
    }

    .video-material {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        border-radius: 20px;
        background: #eff6ff;
        color: #155eef;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .video-description {
        font-size: 12px;
        line-height: 1.7;
        color: #64748b;
        margin-bottom: 0;
    }

    .video-student {
        margin-top: 12px;
        padding-top: 10px;
        border-top: 1px solid #edf1f6;
        font-size: 11px;
        color: #64748b;
    }

    .video-student strong {
        color: #334155;
    }

    .empty-video {
        background: #ffffff;
        border: 1px dashed #d7e2ef;
        border-radius: 18px;
        padding: 35px 20px;
        text-align: center;
    }

    .empty-video-icon {
        width: 55px;
        height: 55px;
        margin: 0 auto 14px;
        border-radius: 15px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
    }

    .empty-video h5 {
        font-size: 14px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
    }

    .empty-video p {
        margin: 0;
        font-size: 12px;
        color: #94a3b8;
        line-height: 1.6;
    }


    @media (max-width: 576px) {

        .parent-welcome {
            padding: 23px 20px;
            border-radius: 16px;
            margin-bottom: 22px;
        }

        .parent-welcome h2 {
            font-size: 21px;
        }

        .parent-welcome p {
            font-size: 12px;
        }

        .section-title {
            font-size: 17px;
        }

        .child-card {
            padding: 18px;
            border-radius: 16px;
        }

        .child-name {
            font-size: 16px;
        }

        .info-card {
            padding: 18px;
            border-radius: 16px;
        }

        .video-card {
            padding: 15px;
            border-radius: 16px;
        }

        .video-title {
            font-size: 14px;
        }

    }

</style>
@endpush


@section('content')

    {{-- WELCOME --}}
    <div class="parent-welcome">

        <div class="parent-welcome-content">

            <h2>
                Selamat Datang, {{ auth()->user()->name }} 👋
            </h2>

            <p>
                Pantau perkembangan belajar anak secara berkala
                melalui sistem informasi UPTD SDN Kandangan Baru.
            </p>

        </div>

    </div>


    {{-- ANAK --}}
    <div class="section-title">
        Data Anak
    </div>

    <div class="row g-4">

        @forelse(auth()->user()->children as $child)

            <div class="col-12 col-md-6">

                <div class="child-card">

                    <div class="child-top">

                        <div class="child-avatar">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>

                        <div>

                            <div class="child-name">
                                {{ $child->name }}
                            </div>

                            <div class="child-class">
                                Siswa UPTD SDN Kandangan Baru
                            </div>

                        </div>

                    </div>


                    <div class="child-info">

                        <div class="child-info-row">

                            <span class="child-info-label">
                                NIS
                            </span>

                            <span class="child-info-value">
                                {{ $child->nis }}
                            </span>

                        </div>

                        <div class="child-info-row">

                            <span class="child-info-label">
                                Kelas
                            </span>

                            <span class="child-info-value">
                                {{ $child->class_name }}
                            </span>

                        </div>

                    </div>


                    @if(Route::has('parent.child'))

                        <a
                            href="{{ route('parent.child', $child) }}"
                            class="btn btn-primary btn-child"
                        >
                            <i class="bi bi-eye-fill me-2"></i>
                            Lihat Perkembangan Anak
                        </a>

                    @endif

                </div>

            </div>

        @empty

            <div class="col-12">

                <div class="child-card text-center py-5">

                    <div class="mb-3">

                        <i
                            class="bi bi-person-x"
                            style="font-size: 42px; color: #94a3b8;"
                        ></i>

                    </div>

                    <h5>
                        Data anak belum tersedia
                    </h5>

                    <p class="text-muted small mb-0">
                        Silakan hubungi admin sekolah untuk memastikan
                        data anak sudah terhubung dengan akun orang tua.
                    </p>

                </div>

            </div>

        @endforelse

    </div>


    {{-- =========================================
         TAMBAHAN: VIDEO PEMBELAJARAN
         ========================================= --}}

    <div class="video-section">

        <div class="section-title">
            Video Pembelajaran
        </div>


        @php

            $hasVideos = false;

            foreach (auth()->user()->children as $child) {

                if ($child->videos->count() > 0) {
                    $hasVideos = true;
                    break;
                }

            }

        @endphp


        @if($hasVideos)

            <div class="row g-4">

                @foreach(auth()->user()->children as $child)

                    @foreach($child->videos as $video)

                        @php

                            $youtubeId = null;

                            preg_match(
                                '/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([^&\n?#]+)/',
                                $video->youtube_url,
                                $matches
                            );

                            if (!empty($matches[1])) {
                                $youtubeId = $matches[1];
                            }

                        @endphp


                        <div class="col-12 col-md-6">

                            <div class="video-card">

                                @if($youtubeId)

                                    <div class="video-thumbnail">

                                        <iframe
                                            src="https://www.youtube.com/embed/{{ $youtubeId }}"
                                            title="{{ $video->title }}"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                        </iframe>

                                    </div>

                                @else

                                    <div
                                        class="video-thumbnail d-flex align-items-center justify-content-center"
                                    >

                                        <div class="text-center px-3">

                                            <i
                                                class="bi bi-exclamation-circle"
                                                style="font-size: 30px; color: #94a3b8;"
                                            ></i>

                                            <div
                                                class="small text-muted mt-2"
                                            >
                                                Link video tidak dapat ditampilkan.
                                            </div>

                                        </div>

                                    </div>

                                @endif


                                <div class="video-content">

                                    <div class="video-title">
                                        {{ $video->title }}
                                    </div>


                                    <div class="video-material">

                                        <i class="bi bi-bookmark-fill"></i>

                                        {{ $video->material }}

                                    </div>


                                    @if($video->description)

                                        <p class="video-description">
                                            {{ $video->description }}
                                        </p>

                                    @endif


                                    <div class="video-student">

                                        Video pembelajaran untuk:

                                        <strong>
                                            {{ $child->name }}
                                        </strong>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                @endforeach

            </div>

        @else

            <div class="empty-video">

                <div class="empty-video-icon">

                    <i class="bi bi-play-circle"></i>

                </div>

                <h5>
                    Belum Ada Video Pembelajaran
                </h5>

                <p>
                    Guru belum memberikan rekomendasi video pembelajaran
                    untuk siswa.
                </p>

            </div>

        @endif

    </div>


    {{-- INFORMASI --}}
    <div class="info-card">

        <div class="section-title mb-2">
            Informasi Sistem
        </div>

        <div class="info-item">

            <div class="info-icon">
                <i class="bi bi-clipboard2-check-fill"></i>
            </div>

            <div>

                <strong>
                    Monitoring Perkembangan
                </strong>

                <span>
                    Orang tua dapat melihat informasi perkembangan
                    belajar anak yang telah dicatat oleh guru.
                </span>

            </div>

        </div>


        <div class="info-item">

            <div class="info-icon">
                <i class="bi bi-play-circle-fill"></i>
            </div>

            <div>

                <strong>
                    Video Pembelajaran
                </strong>

                <span>
                    Guru dapat memberikan rekomendasi video pembelajaran
                    dari YouTube sekolah sebagai bahan belajar tambahan di rumah.
                </span>

            </div>

        </div>


        <div class="info-item">

            <div class="info-icon">
                <i class="bi bi-whatsapp"></i>
            </div>

            <div>

                <strong>
                    Informasi melalui WhatsApp
                </strong>

                <span>
                    Informasi perkembangan belajar dapat disampaikan
                    kepada orang tua melalui WhatsApp.
                </span>

            </div>

        </div>

    </div>

@endsection
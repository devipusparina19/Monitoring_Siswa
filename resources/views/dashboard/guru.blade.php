@extends('layouts.app')

@section('title', 'Dashboard Guru')

@push('styles')
<style>

    .dashboard-header {
        margin-bottom: 28px;
    }

    .dashboard-header h1 {
        font-size: 25px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .dashboard-header p {
        color: #64748b;
        font-size: 13px;
        margin: 0;
    }

    /* ===============================
       PROFILE GURU
    =============================== */

    .teacher-profile {
        background: linear-gradient(
            135deg,
            #0d6efd,
            #2563eb
        );
        border-radius: 20px;
        padding: 25px;
        color: white;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }

    .teacher-profile::after {
        content: "";
        position: absolute;
        width: 230px;
        height: 230px;
        right: -80px;
        top: -100px;
        background: rgba(255,255,255,.08);
        border-radius: 50%;
    }

    .teacher-profile-content {
        position: relative;
        z-index: 2;
    }

    .teacher-profile-label {
        font-size: 11px;
        opacity: .8;
        margin-bottom: 5px;
    }

    .teacher-profile h2 {
        font-size: 23px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .teacher-profile p {
        font-size: 12px;
        margin: 0;
        opacity: .9;
    }

    .teacher-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.2);
        padding: 7px 11px;
        border-radius: 30px;
        font-size: 11px;
        margin-top: 15px;
    }

    /* ===============================
       STAT CARD
    =============================== */

    .stat-card {
        background: #ffffff;
        border: 1px solid #e7edf5;
        border-radius: 18px;
        padding: 22px;
        min-height: 145px;
        height: 100%;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .04);
        transition: .2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
    }

    .stat-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .stat-label {
        color: #64748b;
        font-size: 12px;
        margin-bottom: 5px;
    }

    .stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 13px;
        background: #eaf2ff;
        color: #155eef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
    }

    .stat-description {
        font-size: 11px;
        color: #94a3b8;
    }

    /* ===============================
       QUICK ACTION
    =============================== */

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 16px;
    }

    .action-card {
        background: #ffffff;
        border: 1px solid #e7edf5;
        border-radius: 17px;
        padding: 19px;
        display: flex;
        align-items: center;
        gap: 15px;
        text-decoration: none;
        color: inherit;
        height: 100%;
        transition: .2s ease;
    }

    .action-card:hover {
        color: inherit;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(15, 23, 42, .06);
        border-color: #bfdbfe;
    }

    .action-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        border-radius: 13px;
        background: #eff6ff;
        color: #155eef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .action-card h6 {
        margin: 0 0 4px;
        font-size: 13px;
        font-weight: 600;
    }

    .action-card p {
        margin: 0;
        color: #64748b;
        font-size: 11px;
        line-height: 1.5;
    }

    /* ===============================
       MONITORING TERBARU
    =============================== */

    .monitoring-card {
        background: #ffffff;
        border: 1px solid #e7edf5;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .04);
    }

    .monitoring-header {
        padding: 21px 23px;
        border-bottom: 1px solid #edf1f5;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .monitoring-header h5 {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }

    .monitoring-header span {
        font-size: 11px;
        color: #64748b;
    }

    .monitoring-table {
        margin: 0;
    }

    .monitoring-table th {
        font-size: 11px;
        color: #64748b;
        font-weight: 600;
        white-space: nowrap;
        padding: 14px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #edf1f5;
    }

    .monitoring-table td {
        font-size: 12px;
        padding: 15px 20px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .monitoring-table tr:last-child td {
        border-bottom: none;
    }

    .student-name {
        font-weight: 600;
        color: #1e293b;
    }

    .subject-badge {
        background: #eff6ff;
        color: #155eef;
        border-radius: 7px;
        padding: 5px 8px;
        font-size: 10px;
        font-weight: 600;
        white-space: nowrap;
    }

    .empty-monitoring {
        padding: 45px 20px;
        text-align: center;
        color: #64748b;
    }

    .empty-monitoring i {
        display: block;
        font-size: 35px;
        color: #cbd5e1;
        margin-bottom: 10px;
    }

    .empty-monitoring p {
        margin: 0;
        font-size: 12px;
    }

    @media (max-width: 576px) {

        .dashboard-header h1 {
            font-size: 21px;
        }

        .dashboard-header p {
            font-size: 12px;
            line-height: 1.6;
        }

        .teacher-profile {
            padding: 21px;
            border-radius: 17px;
        }

        .teacher-profile h2 {
            font-size: 20px;
        }

        .stat-card {
            min-height: 135px;
            padding: 19px;
        }

        .stat-number {
            font-size: 25px;
        }

        .section-title {
            font-size: 17px;
        }

        .monitoring-header {
            padding: 18px;
        }

        .monitoring-table th,
        .monitoring-table td {
            padding: 13px 15px;
        }

    }

</style>
@endpush


@section('content')

    {{-- HEADER --}}
    <div class="dashboard-header">

        <h1>
            Dashboard Guru
        </h1>

        <p>
            Kelola monitoring perkembangan siswa sesuai tanggung jawab mengajar Anda.
        </p>

    </div>


    {{-- PROFIL GURU --}}
    <div class="teacher-profile">

        <div class="teacher-profile-content">

            <div class="teacher-profile-label">
                Guru
            </div>

            <h2>
                {{ auth()->user()->name }}
            </h2>

            <p>
                UPTD SDN Kandangan Baru
            </p>

            {{-- Nanti dinamis sesuai data guru --}}
            <div class="teacher-badge">

                <i class="bi bi-person-badge-fill"></i>

                <span>
                    Guru
                </span>

            </div>

        </div>

    </div>


    {{-- STATISTIK --}}
    <div class="row g-4 mb-5">

        <div class="col-12 col-sm-6 col-xl-4">

            <div class="stat-card">

                <div class="stat-top">

                    <div>

                        <div class="stat-label">
                            Siswa dalam Tanggung Jawab
                        </div>

                        <div class="stat-number">
                            {{ $studentCount }}
                        </div>

                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>

                </div>

                <div class="stat-description">
                    Siswa sesuai kelas atau mata pelajaran yang diampu
                </div>

            </div>

        </div>


        <div class="col-12 col-sm-6 col-xl-4">

            <div class="stat-card">

                <div class="stat-top">

                    <div>

                        <div class="stat-label">
                            Monitoring Saya
                        </div>

                        <div class="stat-number">
                            {{ $developmentCount }}
                        </div>

                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-clipboard2-check-fill"></i>
                    </div>

                </div>

                <div class="stat-description">
                    Data perkembangan yang telah dicatat
                </div>

            </div>

        </div>


        <div class="col-12 col-sm-6 col-xl-4">

            <div class="stat-card">

                <div class="stat-top">

                    <div>

                        <div class="stat-label">
                            Video Pembelajaran
                        </div>

                        <div class="stat-number">
                            {{ $videoCount }}
                        </div>

                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-play-circle-fill"></i>
                    </div>

                </div>

                <div class="stat-description">
                    Rekomendasi video pembelajaran yang dibuat
                </div>

            </div>

        </div>

    </div>


    {{-- AKSI CEPAT --}}
    <div class="section-title">
        Aksi Cepat
    </div>

    <div class="row g-4 mb-5">

        <div class="col-12 col-md-6">

            <a
                href="{{ route('developments.create') }}"
                class="action-card"
            >

                <div class="action-icon">
                    <i class="bi bi-plus-lg"></i>
                </div>

                <div>

                    <h6>
                        Input Monitoring
                    </h6>

                    <p>
                        Catat perkembangan belajar siswa.
                    </p>

                </div>

            </a>

        </div>


        <div class="col-12 col-md-6">

            <a
                href="{{ route('videos.create') }}"
                class="action-card"
            >

                <div class="action-icon">
                    <i class="bi bi-play-circle-fill"></i>
                </div>

                <div>

                    <h6>
                        Rekomendasi Video
                    </h6>

                    <p>
                        Tambahkan video pembelajaran sesuai kebutuhan siswa.
                    </p>

                </div>

            </a>

        </div>

    </div>


    {{-- MONITORING TERBARU --}}
    <div class="section-title">
        Monitoring Terbaru
    </div>

    <div class="monitoring-card">

        <div class="monitoring-header">

            <div>

                <h5>
                    Data Monitoring
                </h5>

                <span>
                    Catatan perkembangan terbaru
                </span>

            </div>

        </div>


        @if($developments->count())

            <div class="table-responsive">

                <table class="table monitoring-table">

                    <thead>

                        <tr>

                            <th>
                                Tanggal
                            </th>

                            <th>
                                Siswa
                            </th>

                            <th>
                                Mata Pelajaran
                            </th>

                            <th>
                                Perkembangan
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($developments as $item)

                            <tr>

                                <td>
                                    {{ $item->monitoring_date->format('d-m-Y') }}
                                </td>

                                <td>
                                    <span class="student-name">
                                        {{ $item->student->name }}
                                    </span>
                                </td>

                                <td>

                                    <span class="subject-badge">
                                        {{ $item->subject }}
                                    </span>

                                </td>

                                <td>
                                    {{ Str::limit($item->development, 80) }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="empty-monitoring">

                <i class="bi bi-clipboard-x"></i>

                <p>
                    Belum ada data monitoring.
                </p>

            </div>

        @endif

    </div>

@endsection
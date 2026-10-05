@extends('layouts.app')

@section('title', 'Dashboard Admin')

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
       STAT CARD
    =============================== */

    .stat-card {
        background: #ffffff;
        border: 1px solid #e7edf5;
        border-radius: 18px;
        padding: 22px;
        height: 100%;
        min-height: 145px;
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
        margin-bottom: 22px;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eaf2ff;
        color: #155eef;
        font-size: 21px;
    }

    .stat-label {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 5px;
    }

    .stat-number {
        font-size: 28px;
        line-height: 1;
        font-weight: 700;
        color: #0f172a;
    }

    .stat-description {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 8px;
    }

    /* ===============================
       QUICK MENU
    =============================== */

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 16px;
    }

    .quick-card {
        display: flex;
        align-items: center;
        gap: 16px;
        background: #ffffff;
        border: 1px solid #e7edf5;
        border-radius: 16px;
        padding: 18px;
        text-decoration: none;
        color: inherit;
        height: 100%;
        transition: .2s ease;
    }

    .quick-card:hover {
        transform: translateY(-2px);
        border-color: #bfdbfe;
        box-shadow: 0 8px 25px rgba(15, 23, 42, .06);
        color: inherit;
    }

    .quick-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: #155eef;
        font-size: 20px;
    }

    .quick-card h6 {
        margin: 0 0 4px;
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
    }

    .quick-card p {
        margin: 0;
        font-size: 11px;
        line-height: 1.5;
        color: #64748b;
    }

    /* ===============================
       SYSTEM INFO
    =============================== */

    .system-card {
        background: #ffffff;
        border: 1px solid #e7edf5;
        border-radius: 18px;
        padding: 24px;
        height: 100%;
    }

    .system-item {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        padding: 12px 0;
    }

    .system-item:not(:last-child) {
        border-bottom: 1px solid #f1f5f9;
    }

    .system-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 11px;
        background: #eff6ff;
        color: #155eef;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .system-item strong {
        display: block;
        font-size: 12px;
        margin-bottom: 3px;
    }

    .system-item span {
        font-size: 11px;
        color: #64748b;
        line-height: 1.5;
    }

    @media (max-width: 576px) {

        .dashboard-header h1 {
            font-size: 21px;
        }

        .dashboard-header p {
            font-size: 12px;
            line-height: 1.6;
        }

        .stat-card {
            padding: 19px;
            min-height: 135px;
        }

        .stat-number {
            font-size: 25px;
        }

        .section-title {
            font-size: 17px;
        }

        .quick-card {
            padding: 16px;
        }

        .system-card {
            padding: 18px;
        }

    }

</style>
@endpush


@section('content')

    {{-- HEADER --}}
    <div class="dashboard-header">

        <h1>
            Dashboard Admin
        </h1>

        <p>
            Kelola data pengguna, siswa, guru, dan monitoring perkembangan belajar.
        </p>

    </div>


    {{-- STATISTIK --}}
    <div class="row g-4 mb-5">

        {{-- SISWA --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card">

                <div class="stat-top">

                    <div>
                        <div class="stat-label">
                            Total Siswa
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
                    Data siswa yang terdaftar dalam sistem
                </div>

            </div>

        </div>


        {{-- GURU --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card">

                <div class="stat-top">

                    <div>
                        <div class="stat-label">
                            Total Guru
                        </div>

                        <div class="stat-number">
                            {{ $teacherCount }}
                        </div>
                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-person-workspace"></i>
                    </div>

                </div>

                <div class="stat-description">
                    Guru yang memiliki akses ke sistem
                </div>

            </div>

        </div>


        {{-- ORANG TUA --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card">

                <div class="stat-top">

                    <div>
                        <div class="stat-label">
                            Total Orang Tua
                        </div>

                        <div class="stat-number">
                            {{ $parentCount }}
                        </div>
                    </div>

                    <div class="stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                </div>

                <div class="stat-description">
                    Akun orang tua yang terhubung dengan siswa
                </div>

            </div>

        </div>


        {{-- MONITORING --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card">

                <div class="stat-top">

                    <div>
                        <div class="stat-label">
                            Data Monitoring
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
                    Catatan perkembangan yang telah diinput
                </div>

            </div>

        </div>

    </div>


    {{-- MENU CEPAT --}}
    <div class="section-title">
        Menu Pengelolaan
    </div>

    <div class="row g-4 mb-5">

        <div class="col-12 col-md-6">

            <a
                href="{{ route('users.index') }}"
                class="quick-card"
            >

                <div class="quick-icon">
                    <i class="bi bi-people-fill"></i>
                </div>

                <div>

                    <h6>
                        Kelola Data User
                    </h6>

                    <p>
                        Kelola akun admin, guru, dan orang tua.
                    </p>

                </div>

            </a>

        </div>


        <div class="col-12 col-md-6">

            <a
                href="{{ route('students.index') }}"
                class="quick-card"
            >

                <div class="quick-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>

                <div>

                    <h6>
                        Kelola Data Siswa
                    </h6>

                    <p>
                        Kelola identitas siswa dan hubungan dengan orang tua.
                    </p>

                </div>

            </a>

        </div>

    </div>


    {{-- INFORMASI SISTEM --}}
    <div class="section-title">
        Informasi Sistem
    </div>

    <div class="system-card">

        <div class="system-item">

            <div class="system-icon">
                <i class="bi bi-person-badge-fill"></i>
            </div>

            <div>

                <strong>
                    Pengelolaan Pengguna
                </strong>

                <span>
                    Admin dapat mengelola akun guru dan orang tua
                    yang menggunakan sistem.
                </span>

            </div>

        </div>


        <div class="system-item">

            <div class="system-icon">
                <i class="bi bi-clipboard2-data-fill"></i>
            </div>

            <div>

                <strong>
                    Monitoring Perkembangan
                </strong>

                <span>
                    Data perkembangan diinput oleh guru berdasarkan
                    kegiatan pembelajaran yang dilakukan.
                </span>

            </div>

        </div>


        <div class="system-item">

            <div class="system-icon">
                <i class="bi bi-whatsapp"></i>
            </div>

            <div>

                <strong>
                    Integrasi WhatsApp
                </strong>

                <span>
                    Sistem dapat menyampaikan informasi perkembangan
                    kepada orang tua melalui WhatsApp.
                </span>

            </div>

        </div>

    </div>

@endsection
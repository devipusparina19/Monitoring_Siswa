@extends('layouts.app')

@section('title', 'Dashboard Guru')

{{-- Halaman ini punya pembuka sendiri, jadi banner sambutan layout dimatikan agar tidak tampil dua kali --}}
@section('no_welcome', '1')

@push('styles')
    @include('partials.data-table-styles')
@endpush


@section('content')

<div class="page-head">

    <div>
        <h2><span id="greeting">Selamat datang,</span> {{ auth()->user()->name }}</h2>
        <p>Kelola monitoring perkembangan siswa sesuai tanggung jawab mengajar Anda.</p>
    </div>

    <div class="page-actions">

        <a href="{{ route('teacher.students.index') }}" class="btn btn-primary">
            <i class="bi bi-people-fill" aria-hidden="true"></i>
            Pilih Siswa
        </a>

        <a href="{{ route('videos.index') }}" class="btn btn-secondary">
            <i class="bi bi-play-circle-fill" aria-hidden="true"></i>
            Video Saya
        </a>

    </div>

</div>


{{-- RINGKASAN --}}
<section class="mb-5" aria-label="Ringkasan">

    <div class="row g-4">

        <div class="col-12 col-md-6 col-xl-4">
            <div class="card stat-card">
                <div class="stat-icon" aria-hidden="true"><i class="bi bi-mortarboard-fill"></i></div>
                <div>
                    <div class="stat-value" data-count="{{ $studentCount }}">{{ $studentCount }}</div>
                    <div class="stat-label">Siswa dalam Tanggung Jawab</div>
                    <small class="text-muted d-block mt-1">Siswa sesuai kelas atau mata pelajaran yang diampu</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <div class="card stat-card">
                <div class="stat-icon" aria-hidden="true"><i class="bi bi-clipboard2-check-fill"></i></div>
                <div>
                    <div class="stat-value" data-count="{{ $developmentCount }}">{{ $developmentCount }}</div>
                    <div class="stat-label">Monitoring Saya</div>
                    <small class="text-muted d-block mt-1">Data perkembangan yang telah dicatat</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <div class="card stat-card">
                <div class="stat-icon" aria-hidden="true"><i class="bi bi-play-circle-fill"></i></div>
                <div>
                    <div class="stat-value" data-count="{{ $videoCount }}">{{ $videoCount }}</div>
                    <div class="stat-label">Video Pembelajaran</div>
                    <small class="text-muted d-block mt-1">Rekomendasi video pembelajaran yang dibuat</small>
                </div>
            </div>
        </div>

    </div>

</section>


{{-- PENUGASAN SAYA --}}
<section class="card data-card mb-4" aria-labelledby="assignTitle">

    <div class="data-card-top">
        <strong id="assignTitle">Penugasan Saya</strong>
    </div>

    <div class="p-3">
        @forelse($assignments as $assignment)
            @if($assignment->isHomeroom())
                <span class="badge text-bg-primary me-1 mb-1">Wali Kelas {{ $assignment->classroom?->name }}</span>
            @else
                <span class="badge text-bg-secondary me-1 mb-1">
                    {{ $assignment->subject?->name }} — Kelas {{ $assignment->classroom?->name }}
                </span>
            @endif
        @empty
            <span class="text-muted">Anda belum mendapat penugasan. Hubungi admin sekolah.</span>
        @endforelse
    </div>

</section>


{{-- SISWA YANG DAPAT DIAKSES --}}
<section class="card data-card mb-4" aria-labelledby="studentsTitle">

    <div class="data-card-top">
        <strong id="studentsTitle">Siswa yang Dapat Diakses</strong>

        @if($students->count())
            <a href="{{ route('teacher.students.index') }}" class="btn btn-secondary btn-sm">
                Lihat semua
                <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
        @endif
    </div>

    @if($students->count())

        <div class="table-responsive">
            <table class="table table-hover table-stack align-middle">
                <thead>
                    <tr>
                        <th scope="col">NIS</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td data-label="NIS">{{ $student->nis }}</td>
                            <td data-label="Nama"><span class="person-name">{{ $student->name }}</span></td>
                            <td data-label="Kelas">{{ $student->classroom?->name ?? '-' }}</td>
                            <td data-label="Aksi">
                                <a href="{{ route('teacher.students.show', $student) }}" class="btn btn-primary btn-sm">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @else

        <div class="empty-state">
            <div class="empty-icon" aria-hidden="true"><i class="bi bi-people"></i></div>
            <h3>Belum ada siswa</h3>
            <p>Siswa akan muncul setelah admin memberikan penugasan kelas kepada Anda.</p>
        </div>

    @endif

</section>


{{-- MONITORING TERBARU --}}
<section class="card data-card" aria-labelledby="monitoringTitle">

    <div class="data-card-top">

        <strong id="monitoringTitle">Monitoring Terbaru</strong>

        @if($developments->count())
            <a href="{{ route('developments.index') }}" class="btn btn-secondary btn-sm">
                Lihat semua
                <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
        @endif

    </div>


    @if($developments->count())

        <div class="table-responsive">

            <table class="table table-hover table-stack align-middle">

                <thead>
                    <tr>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Siswa</th>
                        <th scope="col">Mata Pelajaran</th>
                        <th scope="col">Perkembangan</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($developments as $item)

                        <tr>
                            <td data-label="Tanggal">{{ $item->monitoring_date->format('d-m-Y') }}</td>

                            <td data-label="Siswa">
                                <span class="person-name">{{ $item->student?->name ?? '-' }}</span>
                            </td>

                            <td data-label="Mata Pelajaran">
                                <span class="badge text-bg-primary">{{ $item->subject }}</span>
                            </td>

                            <td data-label="Perkembangan">
                                <span class="cell-muted">{{ \Illuminate\Support\Str::limit($item->development, 80) }}</span>
                            </td>
                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="empty-state">

            <div class="empty-icon" aria-hidden="true"><i class="bi bi-clipboard-x"></i></div>

            <h3>Belum ada data monitoring</h3>

            <p>Pilih siswa, lalu catat perkembangan belajar pertamanya.</p>

            <a href="{{ route('teacher.students.index') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                Pilih Siswa
            </a>

        </div>

    @endif

</section>

@endsection
@extends('layouts.app')

@section('title', 'Dashboard Guru')

{{-- Halaman ini punya pembuka sendiri, jadi banner sambutan layout dimatikan agar tidak tampil dua kali --}}
@section('no_welcome', '1')

@push('styles')
    @include('partials.ui-extras')
@endpush


@section('content')

{{-- PEMBUKA --}}
<div class="page-head">

    <div>
        <h2><span id="greeting">Selamat datang,</span> {{ auth()->user()->name }}</h2>
        <p>Pilih siswa, lalu catat perkembangan belajar atau rekomendasikan video pembelajaran.</p>
    </div>

    <div class="page-actions">

        <a href="{{ route('teacher.students.index') }}" class="btn btn-primary">
            <i class="bi bi-people-fill" aria-hidden="true"></i>
            Pilih Siswa
        </a>

        <a href="{{ route('developments.index') }}" class="btn btn-secondary">
            <i class="bi bi-clipboard2-check-fill" aria-hidden="true"></i>
            Riwayat Monitoring
        </a>

    </div>

</div>


{{-- PENUGASAN SAYA --}}
<section class="card mb-4" aria-labelledby="assignTitle">
    <div class="card-body">

        <h3 class="panel-title" id="assignTitle">Penugasan Saya</h3>

        @if($assignments->isEmpty())

            <div class="info-box">
                <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
                <span>Anda belum mendapat penugasan kelas. Hubungi admin sekolah agar siswa muncul di sini.</span>
            </div>

        @else

            <div class="chip-list">
                @foreach($assignments as $assignment)
                    @if($assignment->isHomeroom())
                        <span class="chip is-primary">
                            <i class="bi bi-person-badge-fill" aria-hidden="true"></i>
                            Wali Kelas {{ $assignment->classroom?->name }}
                        </span>
                    @else
                        <span class="chip">
                            <i class="bi bi-journal-bookmark-fill" aria-hidden="true"></i>
                            {{ $assignment->subject?->name }} · Kelas {{ $assignment->classroom?->name }}
                        </span>
                    @endif
                @endforeach
            </div>

        @endif

    </div>
</section>


{{-- RINGKASAN --}}
<section class="mb-4" aria-label="Ringkasan">

    <div class="row g-4">

        <div class="col-12 col-md-6 col-xl-4">
            <div class="card stat-card">
                <div class="stat-icon" aria-hidden="true"><i class="bi bi-mortarboard-fill"></i></div>
                <div>
                    <div class="stat-value" data-count="{{ $studentCount }}">{{ $studentCount }}</div>
                    <div class="stat-label">Siswa dalam Tanggung Jawab</div>
                    <small class="text-muted d-block mt-1">Sesuai kelas atau mata pelajaran yang diampu</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <div class="card stat-card">
                <div class="stat-icon" aria-hidden="true"><i class="bi bi-clipboard2-check-fill"></i></div>
                <div>
                    <div class="stat-value" data-count="{{ $developmentCount }}">{{ $developmentCount }}</div>
                    <div class="stat-label">Monitoring Saya</div>
                    <small class="text-muted d-block mt-1">Catatan perkembangan yang telah dibuat</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <div class="card stat-card">
                <div class="stat-icon" aria-hidden="true"><i class="bi bi-play-circle-fill"></i></div>
                <div>
                    <div class="stat-value" data-count="{{ $videoCount }}">{{ $videoCount }}</div>
                    <div class="stat-label">Video Rekomendasi</div>
                    <small class="text-muted d-block mt-1">Video pembelajaran yang diberikan</small>
                </div>
            </div>
        </div>

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

    @if($students->isEmpty())

        <div class="empty-state">
            <div class="empty-icon" aria-hidden="true"><i class="bi bi-people"></i></div>
            <h3>Belum ada siswa</h3>
            <p>Siswa akan muncul setelah admin memberikan penugasan kelas kepada Anda.</p>
        </div>

    @else

        <div class="table-responsive">
            <table class="table table-hover table-stack align-middle">

                <thead>
                    <tr>
                        <th scope="col">Siswa</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($students as $student)
                        <tr>

                            <td class="cell-name" data-label="Siswa">
                                <div class="person">
                                    <div class="person-avatar" aria-hidden="true">{{ strtoupper(mb_substr($student->name, 0, 1)) }}</div>
                                    <div class="min-w-0">
                                        <div class="person-name">{{ $student->name }}</div>
                                        <div class="cell-muted small">NIS {{ $student->nis }}</div>
                                    </div>
                                </div>
                            </td>

                            <td data-label="Kelas">
                                <span class="badge text-bg-primary">Kelas {{ $student->classroom?->name ?? '-' }}</span>
                            </td>

                            <td class="cell-actions" data-label="Aksi">
                                <div class="actions">
                                    <a href="{{ route('teacher.students.show', $student) }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-eye-fill" aria-hidden="true"></i>
                                        Detail
                                    </a>
                                    <a href="{{ route('developments.create', ['student' => $student->id]) }}" class="btn btn-secondary btn-sm">
                                        <i class="bi bi-plus-lg" aria-hidden="true"></i>
                                        Monitoring
                                    </a>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
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

    @if($developments->isEmpty())

        <div class="empty-state">
            <div class="empty-icon" aria-hidden="true"><i class="bi bi-clipboard-x"></i></div>
            <h3>Belum ada data monitoring</h3>
            <p>Pilih siswa, lalu catat perkembangan belajar pertamanya.</p>
            <a href="{{ route('teacher.students.index') }}" class="btn btn-primary">
                <i class="bi bi-people-fill" aria-hidden="true"></i>
                Pilih Siswa
            </a>
        </div>

    @else

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
                            <td data-label="Siswa"><span class="person-name">{{ $item->student?->name ?? '-' }}</span></td>
                            <td data-label="Mata Pelajaran"><span class="badge text-bg-primary">{{ $item->subject }}</span></td>
                            <td data-label="Perkembangan"><span class="cell-muted">{{ \Illuminate\Support\Str::limit($item->development, 80) }}</span></td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    @endif

</section>

@endsection
@extends('layouts.app')

@section('title', 'Siswa Saya')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / Siswa Saya
@endsection

@push('styles')
    @include('partials.ui-extras')
@endpush

@section('content')

<div class="page-head">
    <p>Daftar siswa sesuai penugasan Anda. Pilih siswa untuk melihat detail atau menambahkan monitoring.</p>
</div>

<div class="card data-card">

    <div class="data-card-top">
        <strong>Daftar Siswa</strong>
        <span class="badge text-bg-primary">{{ $students->total() }} siswa</span>
    </div>

    <form method="GET" action="{{ route('teacher.students.index') }}" class="filter-bar">

        <div class="field grow">
            <label class="form-label" for="q">Cari siswa</label>
            <input id="q" type="search" name="q" class="form-control" placeholder="Nama atau NIS" value="{{ request('q') }}">
        </div>

        <div class="field">
            <label class="form-label" for="classroom_id">Kelas</label>
            <select id="classroom_id" name="classroom_id" class="form-select">
                <option value="">Semua kelas saya</option>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" @selected(request('classroom_id') == $classroom->id)>Kelas {{ $classroom->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="btn-row">
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-search" aria-hidden="true"></i>
                Cari
            </button>
            @if(request()->filled('q') || request()->filled('classroom_id'))
                <a href="{{ route('teacher.students.index') }}" class="btn btn-secondary">Reset</a>
            @endif
        </div>

    </form>

    @if($students->isEmpty())

        <div class="empty-state">
            <div class="empty-icon" aria-hidden="true"><i class="bi bi-people"></i></div>
            @if(request()->filled('q') || request()->filled('classroom_id'))
                <h3>Siswa tidak ditemukan</h3>
                <p>Coba kata kunci atau kelas yang lain.</p>
            @else
                <h3>Belum ada siswa</h3>
                <p>Siswa muncul setelah admin memberikan penugasan kelas kepada Anda.</p>
            @endif
        </div>

    @else

        <div class="table-responsive">
            <table class="table table-hover table-stack align-middle">

                <thead>
                    <tr>
                        <th scope="col">Siswa</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">JK</th>
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

                            <td data-label="JK">{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>

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

        <div class="data-card-bottom">{{ $students->links() }}</div>

    @endif

</div>

@endsection
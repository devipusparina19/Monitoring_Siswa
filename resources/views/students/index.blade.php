@extends('layouts.app')

@section('title', 'Data Siswa')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / Data Siswa
@endsection

@push('styles')
    @include('partials.ui-extras')
@endpush

@section('content')

<div class="page-head">

    <p>Kelola data siswa, kelasnya, dan orang tua atau wali yang terhubung.</p>

    <div class="page-actions">
        <a href="{{ route('students.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg" aria-hidden="true"></i>
            Tambah Siswa
        </a>
    </div>

</div>

<div class="card data-card">

    <div class="data-card-top">
        <strong>Daftar Siswa</strong>
        <span class="badge text-bg-primary">{{ $students->total() }} siswa</span>
    </div>

    <form method="GET" action="{{ route('students.index') }}" class="filter-bar">

        <div class="field grow">
            <label class="form-label" for="q">Cari siswa</label>
            <input id="q" type="search" name="q" class="form-control" placeholder="Nama atau NIS" value="{{ request('q') }}">
        </div>

        <div class="field">
            <label class="form-label" for="classroom_id">Kelas</label>
            <select id="classroom_id" name="classroom_id" class="form-select">
                <option value="">Semua kelas</option>
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
                <a href="{{ route('students.index') }}" class="btn btn-secondary">Reset</a>
            @endif
        </div>

    </form>

    @if($students->isEmpty())

        <div class="empty-state">
            <div class="empty-icon" aria-hidden="true"><i class="bi bi-mortarboard"></i></div>
            @if(request()->filled('q') || request()->filled('classroom_id'))
                <h3>Siswa tidak ditemukan</h3>
                <p>Coba kata kunci atau kelas yang lain.</p>
            @else
                <h3>Belum ada data siswa</h3>
                <p>Tambahkan siswa pertama, lalu hubungkan dengan kelas dan orang tuanya.</p>
                <a href="{{ route('students.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i>
                    Tambah Siswa
                </a>
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
                        <th scope="col">Orang Tua / Wali</th>
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
                                @if($student->classroom)
                                    <span class="badge text-bg-primary">Kelas {{ $student->classroom->name }}</span>
                                @else
                                    <span class="chip is-gold">Belum ada kelas</span>
                                @endif
                            </td>

                            <td data-label="JK">{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>

                            <td data-label="Orang Tua / Wali">
                                @if($student->parents->isNotEmpty())
                                    <div class="chip-list">
                                        @foreach($student->parents as $parent)
                                            <span class="chip">{{ $parent->name }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="chip is-gold">Belum terhubung</span>
                                @endif
                            </td>

                            <td class="cell-actions" data-label="Aksi">
                                <div class="actions">

                                    <a href="{{ route('students.edit', $student) }}" class="btn btn-secondary btn-sm" aria-label="Edit {{ $student->name }}">
                                        <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                        Edit
                                    </a>

                                    <form action="{{ route('students.destroy', $student) }}" method="POST" data-confirm-delete data-name="{{ $student->name }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" aria-label="Arsipkan {{ $student->name }}">
                                            <i class="bi bi-archive" aria-hidden="true"></i>
                                            Arsipkan
                                        </button>
                                    </form>

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

@include('partials.confirm-delete', [
    'noun' => 'siswa',
    'title' => 'Arsipkan siswa ini?',
    'verb' => 'diarsipkan',
    'confirmLabel' => 'Ya, arsipkan',
    'note' => 'Riwayat monitoring dan video siswa tetap tersimpan.',
])

@endsection
@extends('layouts.app')

@section('title', 'Data Kelas')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / Data Kelas
@endsection

@push('styles')
    @include('partials.ui-extras')
@endpush

@section('content')

<div class="page-head">

    <p>Kelola daftar kelas. Wali kelas ditetapkan di menu Penugasan Guru.</p>

    <div class="page-actions">
        <a href="{{ route('classrooms.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg" aria-hidden="true"></i>
            Tambah Kelas
        </a>
    </div>

</div>

<div class="card data-card">

    <div class="data-card-top">
        <strong>Daftar Kelas</strong>
        <span class="badge text-bg-primary">{{ $classrooms->total() }} kelas</span>
    </div>

    @if($classrooms->isEmpty())

        <div class="empty-state">
            <div class="empty-icon" aria-hidden="true"><i class="bi bi-building"></i></div>
            <h3>Belum ada kelas</h3>
            <p>Tambahkan kelas terlebih dahulu sebelum memasukkan siswa dan penugasan guru.</p>
            <a href="{{ route('classrooms.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                Tambah Kelas
            </a>
        </div>

    @else

        <div class="table-responsive">
            <table class="table table-hover table-stack align-middle">

                <thead>
                    <tr>
                        <th scope="col">Kelas</th>
                        <th scope="col">Jumlah Siswa</th>
                        <th scope="col">Wali Kelas</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($classrooms as $classroom)
                        <tr>

                            <td class="cell-name" data-label="Kelas">
                                <div class="person">
                                    <div class="person-avatar" aria-hidden="true"><i class="bi bi-building"></i></div>
                                    <div class="person-name">Kelas {{ $classroom->name }}</div>
                                </div>
                            </td>

                            <td data-label="Jumlah Siswa">
                                <span class="badge text-bg-primary">{{ $classroom->students_count }} siswa</span>
                            </td>

                            <td data-label="Wali Kelas">
                                @if($classroom->homeroomAssignment?->teacher)
                                    <span class="cell-muted">{{ $classroom->homeroomAssignment->teacher->name }}</span>
                                @else
                                    <span class="chip is-gold">Belum ditentukan</span>
                                @endif
                            </td>

                            <td class="cell-actions" data-label="Aksi">
                                <div class="actions">

                                    <a href="{{ route('classrooms.edit', $classroom) }}" class="btn btn-secondary btn-sm" aria-label="Edit kelas {{ $classroom->name }}">
                                        <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                        Edit
                                    </a>

                                    <form action="{{ route('classrooms.destroy', $classroom) }}" method="POST" data-confirm-delete data-name="Kelas {{ $classroom->name }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" aria-label="Hapus kelas {{ $classroom->name }}">
                                            <i class="bi bi-trash3" aria-hidden="true"></i>
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        <div class="data-card-bottom">{{ $classrooms->links() }}</div>

    @endif

</div>

@include('partials.confirm-delete', ['noun' => 'kelas'])

@endsection
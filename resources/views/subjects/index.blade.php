@extends('layouts.app')

@section('title', 'Mata Pelajaran')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / Mata Pelajaran
@endsection

@push('styles')
    @include('partials.ui-extras')
@endpush

@section('content')

<div class="page-head">

    <p>Daftar mata pelajaran yang dipakai pada penugasan guru dan catatan monitoring.</p>

    <div class="page-actions">
        <a href="{{ route('subjects.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg" aria-hidden="true"></i>
            Tambah Mata Pelajaran
        </a>
    </div>

</div>

<div class="card data-card">

    <div class="data-card-top">
        <strong>Daftar Mata Pelajaran</strong>
        <span class="badge text-bg-primary">{{ $subjects->total() }} mapel</span>
    </div>

    @if($subjects->isEmpty())

        <div class="empty-state">
            <div class="empty-icon" aria-hidden="true"><i class="bi bi-journal-bookmark"></i></div>
            <h3>Belum ada mata pelajaran</h3>
            <p>Tambahkan mata pelajaran agar guru dapat ditugaskan dan monitoring dapat dicatat.</p>
            <a href="{{ route('subjects.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                Tambah Mata Pelajaran
            </a>
        </div>

    @else

        <div class="table-responsive">
            <table class="table table-hover table-stack align-middle">

                <thead>
                    <tr>
                        <th scope="col">Mata Pelajaran</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($subjects as $subject)
                        <tr>

                            <td class="cell-name" data-label="Mata Pelajaran">
                                <div class="person">
                                    <div class="person-avatar" aria-hidden="true"><i class="bi bi-journal-bookmark-fill"></i></div>
                                    <div class="person-name">{{ $subject->name }}</div>
                                </div>
                            </td>

                            <td class="cell-actions" data-label="Aksi">
                                <div class="actions">

                                    <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-secondary btn-sm" aria-label="Edit {{ $subject->name }}">
                                        <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                        Edit
                                    </a>

                                    <form action="{{ route('subjects.destroy', $subject) }}" method="POST" data-confirm-delete data-name="{{ $subject->name }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" aria-label="Hapus {{ $subject->name }}">
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

        <div class="data-card-bottom">{{ $subjects->links() }}</div>

    @endif

</div>

@include('partials.confirm-delete', ['noun' => 'mata pelajaran'])

@endsection
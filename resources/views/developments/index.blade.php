@extends('layouts.app')

@section('title', 'Monitoring Perkembangan')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / Monitoring
@endsection

@push('styles')
    @include('partials.ui-extras')
@endpush

@section('content')

<div class="page-head">

    <p>Wali kelas melihat seluruh catatan di kelasnya. Guru mata pelajaran melihat catatan yang ia buat sendiri.</p>

    <div class="page-actions">
        <a href="{{ route('teacher.students.index') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg" aria-hidden="true"></i>
            Input Monitoring
        </a>
    </div>

</div>

<div class="card data-card">

    <div class="data-card-top">
        <strong>Riwayat Monitoring</strong>
        <span class="badge text-bg-primary">{{ $developments->total() }} catatan</span>
    </div>

    <form method="GET" action="{{ route('developments.index') }}" class="filter-bar">

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

        <div class="field">
            <label class="form-label" for="subject_id">Mata pelajaran</label>
            <select id="subject_id" name="subject_id" class="form-select">
                <option value="">Semua mapel</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" @selected(request('subject_id') == $subject->id)>{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="field">
            <label class="form-label" for="teacher_id">Guru</label>
            <select id="teacher_id" name="teacher_id" class="form-select">
                <option value="">Semua guru</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" @selected(request('teacher_id') == $teacher->id)>{{ $teacher->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="field">
            <label class="form-label" for="date_from">Dari tanggal</label>
            <input id="date_from" type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>

        <div class="field">
            <label class="form-label" for="date_to">Sampai tanggal</label>
            <input id="date_to" type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>

        <div class="btn-row">
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-funnel" aria-hidden="true"></i>
                Terapkan
            </button>
            @if(request()->hasAny(['q', 'classroom_id', 'subject_id', 'teacher_id', 'date_from', 'date_to']))
                <a href="{{ route('developments.index') }}" class="btn btn-secondary">Reset</a>
            @endif
        </div>

    </form>

    @if($developments->isEmpty())

        <div class="empty-state">
            <div class="empty-icon" aria-hidden="true"><i class="bi bi-clipboard-x"></i></div>
            <h3>Tidak ada data monitoring</h3>
            <p>Ubah filter, atau pilih siswa untuk membuat catatan baru.</p>
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
                        <th scope="col">Mapel</th>
                        <th scope="col">Guru</th>
                        <th scope="col">Perkembangan</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($developments as $item)
                        <tr>

                            <td data-label="Tanggal">{{ $item->monitoring_date->format('d-m-Y') }}</td>

                            <td class="cell-name" data-label="Siswa">
                                <div class="person">
                                    <div class="person-avatar" aria-hidden="true">{{ strtoupper(mb_substr($item->student?->name ?? '?', 0, 1)) }}</div>
                                    <div class="min-w-0">
                                        <div class="person-name">{{ $item->student?->name ?? '-' }}</div>
                                        <div class="cell-muted small">Kelas {{ $item->student?->classroom?->name ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>

                            <td data-label="Mapel"><span class="badge text-bg-primary">{{ $item->subject }}</span></td>

                            <td data-label="Guru"><span class="cell-muted">{{ $item->teacher?->name ?? '-' }}</span></td>

                            <td data-label="Perkembangan"><span class="cell-muted">{{ \Illuminate\Support\Str::limit($item->development, 80) }}</span></td>

                            <td class="cell-actions" data-label="Aksi">
                                <div class="actions">
                                    @if($item->teacher_id === auth()->id())

                                        <a href="{{ route('developments.edit', $item) }}" class="btn btn-secondary btn-sm" aria-label="Edit monitoring {{ $item->student?->name }}">
                                            <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                            Edit
                                        </a>

                                        <form action="{{ route('developments.destroy', $item) }}" method="POST" data-confirm-delete data-name="{{ $item->subject }} — {{ $item->student?->name }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" aria-label="Hapus monitoring {{ $item->student?->name }}">
                                                <i class="bi bi-trash3" aria-hidden="true"></i>
                                                Hapus
                                            </button>
                                        </form>

                                    @else
                                        <span class="chip">Hanya dilihat</span>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        <div class="data-card-bottom">{{ $developments->links() }}</div>

    @endif

</div>

@include('partials.confirm-delete', ['noun' => 'monitoring'])

@endsection
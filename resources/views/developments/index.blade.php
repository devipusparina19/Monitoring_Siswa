@extends('layouts.app')

@section('title', 'Monitoring Perkembangan')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h2>Monitoring Perkembangan</h2>
    <a href="{{ route('teacher.students.index') }}" class="btn btn-primary">+ Input Monitoring (pilih siswa)</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">

        <form method="GET" action="{{ route('developments.index') }}" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="search" name="q" class="form-control" placeholder="Nama / NIS siswa" value="{{ request('q') }}">
            </div>
            <div class="col-md-2">
                <select name="classroom_id" class="form-select">
                    <option value="">Semua kelas</option>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" @selected(request('classroom_id') == $classroom->id)>{{ $classroom->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="subject_id" class="form-select">
                    <option value="">Semua mapel</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" @selected(request('subject_id') == $subject->id)>{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="teacher_id" class="form-select">
                    <option value="">Semua guru</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" @selected(request('teacher_id') == $teacher->id)>{{ $teacher->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" title="Dari tanggal">
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" title="Sampai tanggal">
            </div>
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary">Terapkan Filter</button>
                <a href="{{ route('developments.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Mapel</th>
                        <th>Guru</th>
                        <th>Perkembangan</th>
                        <th style="width:150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($developments as $item)
                        <tr>
                            <td>{{ $item->monitoring_date->format('d-m-Y') }}</td>
                            <td>{{ $item->student?->name ?? '-' }}</td>
                            <td>{{ $item->student?->classroom?->name ?? '-' }}</td>
                            <td>{{ $item->subject }}</td>
                            <td>{{ $item->teacher?->name ?? '-' }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($item->development, 80) }}</td>
                            <td>
                                @if($item->teacher_id === auth()->id())
                                    <a href="{{ route('developments.edit', $item) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('developments.destroy', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus data monitoring?')">Hapus</button>
                                    </form>
                                @else
                                    <span class="text-muted small">Hanya dilihat</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">Tidak ada data monitoring.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $developments->links() }}
    </div>
</div>

@endsection
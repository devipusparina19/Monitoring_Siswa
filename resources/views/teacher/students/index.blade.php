@extends('layouts.app')

@section('title', 'Siswa Saya')

@section('content')

<h2 class="mb-4">Siswa Saya</h2>

<div class="card shadow-sm">
    <div class="card-body">

        <form method="GET" action="{{ route('teacher.students.index') }}" class="row g-2 mb-3">
            <div class="col-md-5">
                <input type="search" name="q" class="form-control" placeholder="Cari nama atau NIS" value="{{ request('q') }}">
            </div>
            <div class="col-md-4">
                <select name="classroom_id" class="form-select">
                    <option value="">Semua kelas saya</option>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" @selected(request('classroom_id') == $classroom->id)>
                            {{ $classroom->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary">Cari</button>
                <a href="{{ route('teacher.students.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>JK</th>
                        <th style="width:120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>{{ $student->nis }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->classroom?->name ?? '-' }}</td>
                            <td>{{ $student->gender }}</td>
                            <td>
                                <a href="{{ route('teacher.students.show', $student) }}" class="btn btn-primary btn-sm">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Belum ada siswa. Pastikan admin sudah memberikan penugasan kelas kepada Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $students->links() }}
    </div>
</div>

@endsection
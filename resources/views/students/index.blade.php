@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h2>Data Siswa</h2>
    <a href="{{ route('students.create') }}" class="btn btn-primary">+ Tambah Siswa</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">

        <form method="GET" action="{{ route('students.index') }}" class="row g-2 mb-3">
            <div class="col-md-5">
                <input type="search" name="q" class="form-control" placeholder="Cari nama atau NIS" value="{{ request('q') }}">
            </div>
            <div class="col-md-4">
                <select name="classroom_id" class="form-select">
                    <option value="">Semua kelas</option>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" @selected(request('classroom_id') == $classroom->id)>
                            {{ $classroom->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary">Cari</button>
                <a href="{{ route('students.index') }}" class="btn btn-secondary">Reset</a>
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
                        <th>Orang Tua / Wali</th>
                        <th style="width:170px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>{{ $student->nis }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->classroom?->name ?? '-' }}</td>
                            <td>{{ $student->gender }}</td>
                            <td>{{ $student->parents->pluck('name')->join(', ') ?: '-' }}</td>
                            <td>
                                <a href="{{ route('students.edit', $student) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Arsipkan siswa ini? Riwayat monitoring tetap tersimpan.')">Arsipkan</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">Tidak ada data siswa.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $students->links() }}
    </div>
</div>

@endsection
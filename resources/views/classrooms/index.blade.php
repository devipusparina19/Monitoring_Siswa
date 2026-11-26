@extends('layouts.app')

@section('title', 'Data Kelas')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h2>Data Kelas</h2>
    <a href="{{ route('classrooms.create') }}" class="btn btn-primary">+ Tambah Kelas</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Kelas</th>
                        <th>Jumlah Siswa</th>
                        <th>Wali Kelas</th>
                        <th style="width:170px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classrooms as $classroom)
                        <tr>
                            <td>{{ $classroom->name }}</td>
                            <td>{{ $classroom->students_count }}</td>
                            <td>{{ $classroom->homeroomAssignment?->teacher?->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('classrooms.edit', $classroom) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('classrooms.destroy', $classroom) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus kelas ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">Belum ada kelas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $classrooms->links() }}
    </div>
</div>

@endsection
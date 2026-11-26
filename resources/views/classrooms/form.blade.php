@extends('layouts.app')

@section('title', $classroom->exists ? 'Edit Kelas' : 'Tambah Kelas')

@section('content')

<h2 class="mb-4">{{ $classroom->exists ? 'Edit Kelas' : 'Tambah Kelas' }}</h2>

<div class="card shadow-sm">
    <div class="card-body">
        <form
            action="{{ $classroom->exists ? route('classrooms.update', $classroom) : route('classrooms.store') }}"
            method="POST"
        >
            @csrf
            @if($classroom->exists)
                @method('PUT')
            @endif

            <div class="mb-3">
                <label class="form-label">Nama Kelas</label>
                <input
                    name="name"
                    class="form-control"
                    value="{{ old('name', $classroom->name) }}"
                    placeholder="Contoh: V atau 5A"
                    maxlength="50"
                    required
                >
            </div>

            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('classrooms.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

@endsection
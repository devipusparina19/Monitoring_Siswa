@extends('layouts.app')

@section('title', $subject->exists ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran')

@section('content')

<h2 class="mb-4">{{ $subject->exists ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran' }}</h2>

<div class="card shadow-sm">
    <div class="card-body">
        <form
            action="{{ $subject->exists ? route('subjects.update', $subject) : route('subjects.store') }}"
            method="POST"
        >
            @csrf
            @if($subject->exists)
                @method('PUT')
            @endif

            <div class="mb-3">
                <label class="form-label">Nama Mata Pelajaran</label>
                <input
                    name="name"
                    class="form-control"
                    value="{{ old('name', $subject->name) }}"
                    placeholder="Contoh: Matematika"
                    maxlength="100"
                    required
                >
            </div>

            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('subjects.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

@endsection
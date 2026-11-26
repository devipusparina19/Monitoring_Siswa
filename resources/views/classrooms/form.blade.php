@extends('layouts.app')

@section('title', $classroom->exists ? 'Edit Kelas' : 'Tambah Kelas')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> /
    <a href="{{ route('classrooms.index') }}">Data Kelas</a> /
    {{ $classroom->exists ? 'Edit' : 'Tambah' }}
@endsection

@push('styles')
    @include('partials.ui-extras')
@endpush

@section('content')

<div class="page-head">
    <p>Gunakan nama kelas yang singkat dan unik, misalnya V atau 5A.</p>
</div>

<div class="card form-card">
    <div class="card-body">

        <form
            action="{{ $classroom->exists ? route('classrooms.update', $classroom) : route('classrooms.store') }}"
            method="POST"
            data-lock
        >
            @csrf
            @if($classroom->exists)
                @method('PUT')
            @endif

            <div class="form-section">
                <h3 class="form-section-title"><i class="bi bi-building" aria-hidden="true"></i> Data Kelas</h3>
                <p class="form-section-hint">Nama kelas tidak boleh sama dengan kelas lain.</p>

                <label class="form-label" for="name">Nama Kelas</label>
                <input
                    id="name" name="name" maxlength="50" required
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $classroom->name) }}"
                    placeholder="Contoh: V atau 5A"
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg" aria-hidden="true"></i>
                    Simpan
                </button>
                <a href="{{ route('classrooms.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>

    </div>
</div>

@include('partials.form-lock')

@endsection
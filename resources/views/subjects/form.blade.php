@extends('layouts.app')

@section('title', $subject->exists ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> /
    <a href="{{ route('subjects.index') }}">Mata Pelajaran</a> /
    {{ $subject->exists ? 'Edit' : 'Tambah' }}
@endsection

@push('styles')
    @include('partials.ui-extras')
@endpush

@section('content')

<div class="page-head">
    <p>Nama mata pelajaran yang diubah akan ikut diperbarui pada catatan monitoring lama.</p>
</div>

<div class="card form-card">
    <div class="card-body">

        <form
            action="{{ $subject->exists ? route('subjects.update', $subject) : route('subjects.store') }}"
            method="POST"
            data-lock
        >
            @csrf
            @if($subject->exists)
                @method('PUT')
            @endif

            <div class="form-section">
                <h3 class="form-section-title"><i class="bi bi-journal-bookmark-fill" aria-hidden="true"></i> Data Mata Pelajaran</h3>
                <p class="form-section-hint">Gunakan penulisan yang seragam, misalnya Matematika atau Bahasa Inggris.</p>

                <label class="form-label" for="name">Nama Mata Pelajaran</label>
                <input
                    id="name" name="name" maxlength="100" required
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $subject->name) }}"
                    placeholder="Contoh: Matematika"
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
                <a href="{{ route('subjects.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>

    </div>
</div>

@include('partials.form-lock')

@endsection
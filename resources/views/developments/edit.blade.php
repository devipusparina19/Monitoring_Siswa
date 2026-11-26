@extends('layouts.app')

@section('title', 'Edit Monitoring')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> /
    <a href="{{ route('developments.index') }}">Monitoring</a> /
    Edit
@endsection

@push('styles')
    @include('partials.ui-extras')
@endpush

@section('content')

<div class="page-head">
    <p>Siswa tidak dapat diganti agar catatan tidak tertukar. Jika salah siswa, hapus lalu buat catatan baru.</p>
</div>

<div class="student-banner form-card">
    <div class="person-avatar lg" aria-hidden="true">{{ strtoupper(mb_substr($development->student->name, 0, 1)) }}</div>
    <div class="min-w-0">
        <div class="name">{{ $development->student->name }}</div>
        <div class="meta">NIS {{ $development->student->nis }} · Kelas {{ $development->student->classroom?->name ?? '-' }}</div>
    </div>
</div>

<div class="card form-card">
    <div class="card-body">

        <form action="{{ route('developments.update', $development) }}" method="POST" data-lock>
            @csrf
            @method('PUT')

            <div class="form-section">

                <h3 class="form-section-title"><i class="bi bi-journal-bookmark-fill" aria-hidden="true"></i> Mata Pelajaran dan Tanggal</h3>
                <p class="form-section-hint">Hanya mata pelajaran sesuai penugasan Anda yang muncul.</p>

                <div class="row g-3">

                    <div class="col-12 col-md-7">
                        <label class="form-label" for="subject_id">Mata Pelajaran</label>
                        <select id="subject_id" name="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" @selected(old('subject_id', $development->subject_id) == $subject->id)>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-5">
                        <label class="form-label" for="monitoring_date">Tanggal</label>
                        <input id="monitoring_date" type="date" name="monitoring_date" required
                            max="{{ now()->format('Y-m-d') }}"
                            class="form-control @error('monitoring_date') is-invalid @enderror"
                            value="{{ old('monitoring_date', $development->monitoring_date->format('Y-m-d')) }}">
                        @error('monitoring_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

            </div>

            <div class="form-section">

                <h3 class="form-section-title"><i class="bi bi-eye-fill" aria-hidden="true"></i> Hasil Pengamatan</h3>
                <p class="form-section-hint">Apa yang dapat dilakukan siswa, dan bagaimana perkembangannya.</p>

                <div class="mb-3">
                    <label class="form-label" for="ability">Kemampuan</label>
                    <textarea id="ability" name="ability" rows="4" maxlength="2000" required
                        class="form-control @error('ability') is-invalid @enderror">{{ old('ability', $development->ability) }}</textarea>
                    @error('ability')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="form-label" for="development">Perkembangan</label>
                    <textarea id="development" name="development" rows="4" maxlength="2000" required
                        class="form-control @error('development') is-invalid @enderror">{{ old('development', $development->development) }}</textarea>
                    @error('development')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="form-section">

                <h3 class="form-section-title"><i class="bi bi-chat-left-text-fill" aria-hidden="true"></i> Catatan dan Saran</h3>
                <p class="form-section-hint">Opsional.</p>

                <div class="mb-3">
                    <label class="form-label" for="note">Catatan</label>
                    <textarea id="note" name="note" rows="3" maxlength="2000"
                        class="form-control @error('note') is-invalid @enderror">{{ old('note', $development->note) }}</textarea>
                    @error('note')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="form-label" for="suggestion">Saran</label>
                    <textarea id="suggestion" name="suggestion" rows="3" maxlength="2000"
                        class="form-control @error('suggestion') is-invalid @enderror">{{ old('suggestion', $development->suggestion) }}</textarea>
                    @error('suggestion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg" aria-hidden="true"></i>
                    Simpan Perubahan
                </button>
                <a href="{{ route('developments.index') }}" class="btn btn-secondary">Batal</a>
            </div>

        </form>

    </div>
</div>

@include('partials.form-lock')

@endsection
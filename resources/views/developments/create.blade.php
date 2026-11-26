@extends('layouts.app')

@section('title', 'Input Monitoring')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> /
    <a href="{{ route('teacher.students.index') }}">Siswa Saya</a> /
    <a href="{{ route('teacher.students.show', $student) }}">{{ $student->name }}</a> /
    Input Monitoring
@endsection

@push('styles')
    @include('partials.ui-extras')
@endpush

@section('content')

<div class="page-head">
    <p>Tuliskan hasil pengamatan dengan bahasa yang mudah dipahami orang tua.</p>
</div>

<div class="student-banner form-card">
    <div class="person-avatar lg" aria-hidden="true">{{ strtoupper(mb_substr($student->name, 0, 1)) }}</div>
    <div class="min-w-0">
        <div class="name">{{ $student->name }}</div>
        <div class="meta">NIS {{ $student->nis }} · Kelas {{ $student->classroom?->name ?? '-' }}</div>
    </div>
</div>

<div class="card form-card">
    <div class="card-body">

        <form action="{{ route('developments.store') }}" method="POST" data-lock>
            @csrf

            <input type="hidden" name="student_id" value="{{ $student->id }}">

            @error('student_id')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="form-section">

                <h3 class="form-section-title"><i class="bi bi-journal-bookmark-fill" aria-hidden="true"></i> Mata Pelajaran dan Tanggal</h3>
                <p class="form-section-hint">Hanya mata pelajaran sesuai penugasan Anda yang muncul.</p>

                <div class="row g-3">

                    <div class="col-12 col-md-7">
                        <label class="form-label" for="subject_id">Mata Pelajaran</label>
                        <select id="subject_id" name="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
                            <option value="">Pilih mata pelajaran</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-5">
                        <label class="form-label" for="monitoring_date">Tanggal Monitoring</label>
                        <input id="monitoring_date" type="date" name="monitoring_date" required
                            max="{{ now()->format('Y-m-d') }}"
                            class="form-control @error('monitoring_date') is-invalid @enderror"
                            value="{{ old('monitoring_date', now()->format('Y-m-d')) }}">
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
                    <label class="form-label" for="ability">Kemampuan yang Diamati</label>
                    <textarea id="ability" name="ability" rows="4" maxlength="2000" required
                        class="form-control @error('ability') is-invalid @enderror"
                        placeholder="Contoh: Mampu menjumlahkan pecahan berpenyebut sama.">{{ old('ability') }}</textarea>
                    @error('ability')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="form-label" for="development">Perkembangan</label>
                    <textarea id="development" name="development" rows="4" maxlength="2000" required
                        class="form-control @error('development') is-invalid @enderror"
                        placeholder="Tuliskan perkembangan siswa berdasarkan hasil pengamatan.">{{ old('development') }}</textarea>
                    @error('development')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="form-section">

                <h3 class="form-section-title"><i class="bi bi-chat-left-text-fill" aria-hidden="true"></i> Catatan dan Saran</h3>
                <p class="form-section-hint">Opsional, tetapi membantu orang tua mendampingi anak di rumah.</p>

                <div class="mb-3">
                    <label class="form-label" for="note">Catatan</label>
                    <textarea id="note" name="note" rows="3" maxlength="2000"
                        class="form-control @error('note') is-invalid @enderror">{{ old('note') }}</textarea>
                    @error('note')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="form-label" for="suggestion">Saran Belajar</label>
                    <textarea id="suggestion" name="suggestion" rows="3" maxlength="2000"
                        class="form-control @error('suggestion') is-invalid @enderror"
                        placeholder="Materi atau kemampuan yang perlu ditingkatkan.">{{ old('suggestion') }}</textarea>
                    @error('suggestion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            @if(config('services.fonnte.enabled'))
                <div class="info-box">
                    <i class="bi bi-whatsapp" aria-hidden="true"></i>
                    <span>Setelah disimpan, orang tua yang terhubung akan menerima pemberitahuan lewat WhatsApp.</span>
                </div>
            @endif

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg" aria-hidden="true"></i>
                    Simpan Monitoring
                </button>
                <a href="{{ route('teacher.students.show', $student) }}" class="btn btn-secondary">Batal</a>
            </div>

        </form>

    </div>
</div>

@include('partials.form-lock')

@endsection
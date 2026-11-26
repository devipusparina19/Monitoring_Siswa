@extends('layouts.app')

@section('title', $user->exists ? 'Edit User' : 'Tambah User')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> /
    <a href="{{ route('users.index') }}">Kelola User</a> /
    {{ $user->exists ? 'Edit' : 'Tambah' }}
@endsection

@push('styles')
    @include('partials.ui-extras')
@endpush

@section('content')

<div class="page-head">
    <p>Akun guru dan orang tua dibuat di sini. Kelas guru diatur di menu Penugasan Guru, dan anak orang tua diatur di Data Siswa.</p>
</div>

<div class="card form-card">
    <div class="card-body">

        <form
            action="{{ $user->exists ? route('users.update', $user) : route('users.store') }}"
            method="POST"
            data-lock
        >
            @csrf
            @if($user->exists)
                @method('PUT')
            @endif

            {{-- AKUN --}}
            <div class="form-section">

                <h3 class="form-section-title"><i class="bi bi-person-circle" aria-hidden="true"></i> Akun</h3>
                <p class="form-section-hint">Email dipakai untuk masuk ke sistem.</p>

                <div class="row g-3">

                    <div class="col-12">
                        <label class="form-label" for="name">Nama</label>
                        <input id="name" name="name" required maxlength="255"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" placeholder="Nama lengkap">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="email">Email</label>
                        <input id="email" type="email" name="email" required autocomplete="off"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" placeholder="nama@email.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="password">Kata Sandi</label>
                        <input id="password" type="password" name="password" minlength="8" autocomplete="new-password"
                            class="form-control @error('password') is-invalid @enderror"
                            {{ $user->exists ? '' : 'required' }}
                            placeholder="Minimal 8 karakter">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($user->exists)
                            <div class="form-text">Kosongkan jika tidak ingin mengganti kata sandi.</div>
                        @endif
                    </div>

                </div>

            </div>

            {{-- PERAN --}}
            <div class="form-section">

                <h3 class="form-section-title"><i class="bi bi-shield-check" aria-hidden="true"></i> Peran</h3>
                <p class="form-section-hint">Peran menentukan halaman yang dapat dibuka.</p>

                <label class="form-label" for="role">Peran</label>
                <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Administrator</option>
                    <option value="guru" @selected(old('role', $user->role) === 'guru')>Guru</option>
                    <option value="orang_tua" @selected(old('role', $user->role ?: 'orang_tua') === 'orang_tua')>Orang Tua</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            {{-- WHATSAPP --}}
            <div class="form-section">

                <h3 class="form-section-title"><i class="bi bi-whatsapp" aria-hidden="true"></i> WhatsApp</h3>
                <p class="form-section-hint">Dipakai untuk notifikasi perkembangan belajar. Boleh dikosongkan.</p>

                <label class="form-label" for="whatsapp">Nomor WhatsApp</label>
                <input id="whatsapp" name="whatsapp" inputmode="tel" maxlength="20"
                    class="form-control @error('whatsapp') is-invalid @enderror"
                    placeholder="08123456789"
                    value="{{ old('whatsapp', $user->whatsapp) }}">
                @error('whatsapp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Boleh diawali 08 atau 62. Sistem merapikan formatnya otomatis.</div>

            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg" aria-hidden="true"></i>
                    Simpan
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
            </div>

        </form>

    </div>
</div>

@include('partials.form-lock')

@endsection
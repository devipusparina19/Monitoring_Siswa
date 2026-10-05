@extends('layouts.app')

@section('title', $user->exists ? 'Edit User' : 'Tambah User')

@section('content')

<h2 class="mb-4">
    {{ $user->exists ? 'Edit User' : 'Tambah User' }}
</h2>

<div class="card shadow-sm">

    <div class="card-body">

        <form
            action="{{
                $user->exists
                    ? route('users.update', $user)
                    : route('users.store')
            }}"
            method="POST"
        >

            @csrf

            @if($user->exists)
                @method('PUT')
            @endif

            <div class="mb-3">

                <label class="form-label">
                    Nama
                </label>

                <input
                    name="name"
                    class="form-control"
                    value="{{ old('name', $user->name) }}"
                    required
                >

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email', $user->email) }}"
                    required
                >

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    {{ $user->exists ? '' : 'required' }}
                >

                @if($user->exists)
                    <small class="text-muted">
                        Kosongkan jika tidak ingin mengganti password.
                    </small>
                @endif

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Role
                </label>

                <select
                    name="role"
                    class="form-select"
                    required
                >

                    <option value="admin"
                        @selected(old('role', $user->role) === 'admin')>
                        Admin
                    </option>

                    <option value="guru"
                        @selected(old('role', $user->role) === 'guru')>
                        Guru
                    </option>

                    <option value="orang_tua"
                        @selected(old('role', $user->role) === 'orang_tua')>
                        Orang Tua
                    </option>

                </select>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Nomor WhatsApp
                </label>

                <input
                    name="whatsapp"
                    class="form-control"
                    placeholder="08123456789"
                    value="{{ old('whatsapp', $user->whatsapp) }}"
                >

            </div>

            <button class="btn btn-primary">
                Simpan
            </button>

            <a
                href="{{ route('users.index') }}"
                class="btn btn-secondary"
            >
                Kembali
            </a>

        </form>

    </div>

</div>

@endsection
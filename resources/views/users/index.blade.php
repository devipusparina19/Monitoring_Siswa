@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')

<div class="d-flex justify-content-between mb-3">

    <h2>Kelola User</h2>

    <a
        href="{{ route('users.create') }}"
        class="btn btn-primary"
    >
        + Tambah User
    </a>

</div>

<div class="card shadow-sm">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered">

                <thead>

                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>WhatsApp</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($users as $user)

                        <tr>

                            <td>{{ $user->name }}</td>

                            <td>{{ $user->email }}</td>

                            <td>{{ $user->role }}</td>

                            <td>{{ $user->whatsapp ?? '-' }}</td>

                            <td>

                                <a
                                    href="{{ route('users.edit', $user) }}"
                                    class="btn btn-warning btn-sm"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route('users.destroy', $user) }}"
                                    method="POST"
                                    class="d-inline"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus user ini?')"
                                    >
                                        Hapus
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        {{ $users->links() }}

    </div>

</div>

@endsection
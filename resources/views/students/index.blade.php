@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')

<div class="d-flex justify-content-between mb-3">

    <h2>Data Siswa</h2>

    <a
        href="{{ route('students.create') }}"
        class="btn btn-primary"
    >
        + Tambah Siswa
    </a>

</div>

<div class="card shadow-sm">

    <div class="card-body">

        <table class="table table-bordered">

            <thead>

                <tr>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>JK</th>
                    <th>Orang Tua</th>
                    <th>Aksi</th>
                </tr>

            </thead>

            <tbody>

                @foreach($students as $student)

                    <tr>

                        <td>{{ $student->nis }}</td>

                        <td>{{ $student->name }}</td>

                        <td>{{ $student->class_name }}</td>

                        <td>{{ $student->gender }}</td>

                        <td>
                            {{ $student->parent->name ?? '-' }}
                        </td>

                        <td>

                            <a
                                href="{{ route('students.edit', $student) }}"
                                class="btn btn-warning btn-sm"
                            >
                                Edit
                            </a>

                            <form
                                action="{{ route('students.destroy', $student) }}"
                                method="POST"
                                class="d-inline"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus siswa?')"
                                >
                                    Hapus
                                </button>

                            </form>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

        {{ $students->links() }}

    </div>

</div>

@endsection
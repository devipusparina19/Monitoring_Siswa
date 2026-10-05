@extends('layouts.app')

@section('title', 'Monitoring Perkembangan')

@section('content')

<div class="d-flex justify-content-between mb-3">

    <h2>Monitoring Perkembangan</h2>

    <a
        href="{{ route('developments.create') }}"
        class="btn btn-primary"
    >
        + Input Monitoring
    </a>

</div>

<div class="card shadow-sm">

    <div class="card-body">

        <table class="table table-bordered">

            <thead>

                <tr>
                    <th>Tanggal</th>
                    <th>Siswa</th>
                    <th>Mapel</th>
                    <th>Kemampuan</th>
                    <th>Perkembangan</th>
                    <th>Aksi</th>
                </tr>

            </thead>

            <tbody>

                @foreach($developments as $item)

                    <tr>

                        <td>
                            {{ $item->monitoring_date->format('d-m-Y') }}
                        </td>

                        <td>
                            {{ $item->student->name }}
                        </td>

                        <td>
                            {{ $item->subject }}
                        </td>

                        <td>
                            {{ Str::limit($item->ability, 60) }}
                        </td>

                        <td>
                            {{ Str::limit($item->development, 80) }}
                        </td>

                        <td>

                            <a
                                href="{{ route('developments.edit', $item) }}"
                                class="btn btn-warning btn-sm"
                            >
                                Edit
                            </a>

                            <form
                                action="{{ route('developments.destroy', $item) }}"
                                method="POST"
                                class="d-inline"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus data monitoring?')"
                                >
                                    Hapus
                                </button>

                            </form>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

        {{ $developments->links() }}

    </div>

</div>

@endsection
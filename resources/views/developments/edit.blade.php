@extends('layouts.app')

@section('title', 'Edit Monitoring')

@section('content')

<h2 class="mb-4">
    Edit Perkembangan Belajar
</h2>

<div class="card shadow-sm">

    <div class="card-body">

        <form
            action="{{ route('developments.update', $development) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            <div class="mb-3">

                <label class="form-label">
                    Siswa
                </label>

                <select
                    name="student_id"
                    class="form-select"
                    required
                >

                    @foreach($students as $student)

                        <option
                            value="{{ $student->id }}"
                            @selected(
                                $development->student_id == $student->id
                            )
                        >
                            {{ $student->name }}
                            - Kelas {{ $student->class_name }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Tanggal
                </label>

                <input
                    type="date"
                    name="monitoring_date"
                    class="form-control"
                    value="{{ $development->monitoring_date->format('Y-m-d') }}"
                    required
                >

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Mata Pelajaran
                </label>

                <input
                    name="subject"
                    class="form-control"
                    value="{{ $development->subject }}"
                    required
                >

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Kemampuan
                </label>

                <textarea
                    name="ability"
                    class="form-control"
                    rows="4"
                    required
                >{{ $development->ability }}</textarea>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Perkembangan
                </label>

                <textarea
                    name="development"
                    class="form-control"
                    rows="4"
                    required
                >{{ $development->development }}</textarea>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Catatan
                </label>

                <textarea
                    name="note"
                    class="form-control"
                    rows="3"
                >{{ $development->note }}</textarea>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Saran
                </label>

                <textarea
                    name="suggestion"
                    class="form-control"
                    rows="3"
                >{{ $development->suggestion }}</textarea>

            </div>

            <button class="btn btn-primary">
                Update
            </button>

            <a
                href="{{ route('developments.index') }}"
                class="btn btn-secondary"
            >
                Kembali
            </a>

        </form>

    </div>

</div>

@endsection
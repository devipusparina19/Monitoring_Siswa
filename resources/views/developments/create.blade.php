@extends('layouts.app')

@section('title', 'Input Monitoring')

@section('content')

<h2 class="mb-4">
    Input Perkembangan Belajar
</h2>

<div class="card shadow-sm">

    <div class="card-body">

        <form
            action="{{ route('developments.store') }}"
            method="POST"
        >

            @csrf

            <div class="mb-3">

                <label class="form-label">
                    Siswa
                </label>

                <select
                    name="student_id"
                    class="form-select"
                    required
                >

                    <option value="">
                        -- Pilih Siswa --
                    </option>

                    @foreach($students as $student)

                        <option
                            value="{{ $student->id }}"
                            @selected(
                                old('student_id') == $student->id
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
                    Tanggal Monitoring
                </label>

                <input
                    type="date"
                    name="monitoring_date"
                    class="form-control"
                    value="{{ old(
                        'monitoring_date',
                        now()->format('Y-m-d')
                    ) }}"
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
                    placeholder="Contoh: Matematika"
                    required
                >

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Kemampuan yang Diamati
                </label>

                <textarea
                    name="ability"
                    class="form-control"
                    rows="4"
                    placeholder="Contoh: Mampu melakukan operasi penjumlahan pecahan."
                    required
                ></textarea>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Perkembangan
                </label>

                <textarea
                    name="development"
                    class="form-control"
                    rows="4"
                    placeholder="Tuliskan perkembangan siswa berdasarkan hasil pengamatan."
                    required
                ></textarea>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Catatan
                </label>

                <textarea
                    name="note"
                    class="form-control"
                    rows="3"
                ></textarea>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Saran Belajar
                </label>

                <textarea
                    name="suggestion"
                    class="form-control"
                    rows="3"
                    placeholder="Materi atau kemampuan yang perlu ditingkatkan."
                ></textarea>

            </div>

            <button class="btn btn-primary">
                Simpan Monitoring
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
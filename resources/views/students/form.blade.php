@extends('layouts.app')

@section('title', $student->exists ? 'Edit Siswa' : 'Tambah Siswa')

@section('content')

<h2 class="mb-4">
    {{ $student->exists ? 'Edit Siswa' : 'Tambah Siswa' }}
</h2>

<div class="card shadow-sm">

    <div class="card-body">

        <form
            action="{{
                $student->exists
                    ? route('students.update', $student)
                    : route('students.store')
            }}"
            method="POST"
        >

            @csrf

            @if($student->exists)
                @method('PUT')
            @endif

            <div class="mb-3">

                <label class="form-label">
                    NIS
                </label>

                <input
                    name="nis"
                    class="form-control"
                    value="{{ old('nis', $student->nis) }}"
                    required
                >

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Nama Siswa
                </label>

                <input
                    name="name"
                    class="form-control"
                    value="{{ old('name', $student->name) }}"
                    required
                >

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Kelas
                </label>

                <input
                    name="class_name"
                    class="form-control"
                    value="{{ old('class_name', $student->class_name) }}"
                    placeholder="Contoh: V"
                    required
                >

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Jenis Kelamin
                </label>

                <select
                    name="gender"
                    class="form-select"
                    required
                >

                    <option value="L"
                        @selected(old('gender', $student->gender) === 'L')>
                        Laki-laki
                    </option>

                    <option value="P"
                        @selected(old('gender', $student->gender) === 'P')>
                        Perempuan
                    </option>

                </select>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Tanggal Lahir
                </label>

                <input
                    type="date"
                    name="birth_date"
                    class="form-control"
                    value="{{ old(
                        'birth_date',
                        optional($student->birth_date)->format('Y-m-d')
                    ) }}"
                >

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Orang Tua
                </label>

                <select
                    name="parent_id"
                    class="form-select"
                >

                    <option value="">
                        -- Pilih Orang Tua --
                    </option>

                    @foreach($parents as $parent)

                        <option
                            value="{{ $parent->id }}"
                            @selected(
                                old(
                                    'parent_id',
                                    $student->parent_id
                                ) == $parent->id
                            )
                        >
                            {{ $parent->name }}
                            -
                            {{ $parent->whatsapp ?? 'No WA' }}
                        </option>

                    @endforeach

                </select>

            </div>

            <button class="btn btn-primary">
                Simpan
            </button>

            <a
                href="{{ route('students.index') }}"
                class="btn btn-secondary"
            >
                Kembali
            </a>

        </form>

    </div>

</div>

@endsection
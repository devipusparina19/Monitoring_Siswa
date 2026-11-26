@extends('layouts.app')

@section('title', $student->exists ? 'Edit Siswa' : 'Tambah Siswa')

@section('content')

<h2 class="mb-4">{{ $student->exists ? 'Edit Siswa' : 'Tambah Siswa' }}</h2>

<div class="card shadow-sm">
    <div class="card-body">

        <form
            action="{{ $student->exists ? route('students.update', $student) : route('students.store') }}"
            method="POST"
        >
            @csrf
            @if($student->exists)
                @method('PUT')
            @endif

            <div class="mb-3">
                <label class="form-label">NIS</label>
                <input name="nis" class="form-control" value="{{ old('nis', $student->nis) }}" maxlength="30" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Siswa</label>
                <input name="name" class="form-control" value="{{ old('name', $student->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Kelas</label>
                <select name="classroom_id" class="form-select" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" @selected(old('classroom_id', $student->classroom_id) == $classroom->id)>
                            {{ $classroom->name }}
                        </option>
                    @endforeach
                </select>
                @if($classrooms->isEmpty())
                    <small class="text-danger">Belum ada kelas. Tambahkan dulu di menu Data Kelas.</small>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Kelamin</label>
                <select name="gender" class="form-select" required>
                    <option value="L" @selected(old('gender', $student->gender) === 'L')>Laki-laki</option>
                    <option value="P" @selected(old('gender', $student->gender) === 'P')>Perempuan</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Lahir</label>
                <input
                    type="date"
                    name="birth_date"
                    class="form-control"
                    value="{{ old('birth_date', optional($student->birth_date)->format('Y-m-d')) }}"
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Orang Tua / Wali</label>

                @php
                    $selectedParents = collect(old('parent_ids', $student->exists ? $student->parents->pluck('id')->all() : []))
                        ->map(fn ($id) => (int) $id);
                @endphp

                <select name="parent_ids[]" class="form-select" multiple size="6">
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}" @selected($selectedParents->contains($parent->id))>
                            {{ $parent->name }} — {{ $parent->whatsapp ?? 'tanpa nomor WA' }}
                        </option>
                    @endforeach
                </select>

                <small class="text-muted">
                    Tahan Ctrl (atau Cmd) untuk memilih lebih dari satu. Hubungan ini dibuat eksplisit oleh admin.
                </small>
            </div>

            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">Kembali</a>
        </form>

    </div>
</div>

@endsection
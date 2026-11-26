@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start mb-3 gap-2">
    <div>
        <h2 class="mb-1">{{ $student->name }}</h2>
        <p class="text-muted mb-0">
            NIS: {{ $student->nis }} | Kelas: {{ $student->classroom?->name ?? '-' }}
            | {{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
        </p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('developments.create', ['student' => $student->id]) }}" class="btn btn-primary">+ Input Monitoring</a>
        <a href="{{ route('videos.create', ['student' => $student->id]) }}" class="btn btn-secondary">+ Rekomendasi Video</a>
        <a href="{{ route('teacher.students.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <strong>Mata pelajaran yang dapat Anda monitor:</strong>
        @if($isHomeroom)
            <span class="badge text-bg-primary ms-1">Wali Kelas (semua mapel)</span>
        @endif
        @forelse($subjects as $subject)
            @unless($isHomeroom)
                <span class="badge text-bg-secondary ms-1">{{ $subject->name }}</span>
            @endunless
        @empty
            <span class="text-muted">Belum ada.</span>
        @endforelse
    </div>
</div>

<h4 class="mb-3">Riwayat Monitoring</h4>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Mapel</th>
                        <th>Guru</th>
                        <th>Kemampuan</th>
                        <th>Perkembangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($developments as $item)
                        <tr>
                            <td>{{ $item->monitoring_date->format('d-m-Y') }}</td>
                            <td>{{ $item->subject }}</td>
                            <td>{{ $item->teacher?->name ?? '-' }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($item->ability, 70) }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($item->development, 90) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">Belum ada data monitoring.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<h4 class="mb-3">Video Rekomendasi</h4>

<div class="card shadow-sm">
    <div class="card-body">
        @forelse($videos as $video)
            <div class="d-flex justify-content-between border-bottom py-2">
                <div>
                    <strong>{{ $video->title }}</strong>
                    <div class="text-muted small">Materi: {{ $video->material }} | oleh {{ $video->teacher?->name ?? '-' }}</div>
                </div>
                <div class="text-muted small">{{ $video->created_at->format('d-m-Y') }}</div>
            </div>
        @empty
            <span class="text-muted">Belum ada video rekomendasi.</span>
        @endforelse
    </div>
</div>

@endsection
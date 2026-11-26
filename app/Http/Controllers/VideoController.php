<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Video;
use App\Services\ParentNotifier;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::with('student.classroom')
            ->where('teacher_id', auth()->id())
            ->whereHas('student')
            ->latest()
            ->paginate(10);

        return view('videos.index', compact('videos'));
    }

    // Form hanya dibuka dari detail siswa: /videos/create?student=ID
    public function create(Request $request)
    {
        $student = Student::with('classroom')->find($request->query('student'));

        if (!$student) {
            return redirect()
                ->route('teacher.students.index')
                ->with('error', 'Pilih siswa terlebih dahulu.');
        }

        Gate::authorize('view', $student);

        return view('videos.create', compact('student'));
    }

    public function store(Request $request, ParentNotifier $notifier)
    {
        $data = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id,deleted_at,NULL'],
            'title' => ['required', 'string', 'max:255'],
            'youtube_url' => [
                'required',
                'url',
                'max:500',
                function ($attribute, $value, $fail) {
                    if (!Video::extractYoutubeId($value)) {
                        $fail('URL harus berupa tautan video YouTube yang valid.');
                    }
                },
            ],
            'material' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $student = Student::with('classroom')->findOrFail($data['student_id']);

        Gate::authorize('view', $student);

        $videoId = Video::extractYoutubeId($data['youtube_url']);

        $duplicate = Video::where('student_id', $student->id)
            ->where('youtube_video_id', $videoId)
            ->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors(['youtube_url' => 'Video ini sudah pernah direkomendasikan untuk siswa tersebut.']);
        }

        try {
            $video = Video::create([
                'student_id' => $student->id,
                'teacher_id' => $request->user()->id,
                'title' => $data['title'],
                'youtube_url' => $data['youtube_url'],
                'youtube_video_id' => $videoId,
                'material' => $data['material'],
                'description' => $data['description'] ?? null,
            ]);
        } catch (UniqueConstraintViolationException) {
            return back()
                ->withInput()
                ->withErrors(['youtube_url' => 'Video ini sudah pernah direkomendasikan untuk siswa tersebut.']);
        }

        $message =
            "Assalamu'alaikum.\n\n" .
            "Guru merekomendasikan video pembelajaran untuk {$student->name} (Kelas {$student->class_name}).\n\n" .
            "Judul: {$video->title}\n" .
            "Materi: {$video->material}\n\n" .
            "Silakan membuka sistem monitoring untuk menonton videonya.";

        $summary = $notifier->notify($student, 'video', $message);

        return redirect()
            ->route('videos.index')
            ->with(
                'success',
                'Video berhasil ditambahkan. ' . ParentNotifier::describe($summary)
            );
    }

    public function destroy(Video $video)
    {
        Gate::authorize('delete', $video);

        $video->delete();

        return back()->with('success', 'Video berhasil dihapus.');
    }
}
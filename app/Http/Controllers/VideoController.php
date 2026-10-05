<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Student;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::with('student')
            ->where('teacher_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('videos.index', compact('videos'));
    }

    public function create()
    {
        $students = Student::orderBy('name')->get();

        return view(
            'videos.create',
            compact('students')
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'title' => ['required', 'max:255'],
            'youtube_url' => [
                'required',
                'url',
                'regex:/youtube\.com|youtu\.be/'
            ],
            'material' => ['required', 'max:255'],
            'description' => ['nullable'],
        ]);

        $data['teacher_id'] = auth()->id();

        Video::create($data);

        return redirect()
            ->route('videos.index')
            ->with(
                'success',
                'Video pembelajaran berhasil ditambahkan.'
            );
    }

    public function destroy(Video $video)
    {
        abort_unless(
            $video->teacher_id === auth()->id(),
            403
        );

        $video->delete();

        return back()->with(
            'success',
            'Video berhasil dihapus.'
        );
    }
}
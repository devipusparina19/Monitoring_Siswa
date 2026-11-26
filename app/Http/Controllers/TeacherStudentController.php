<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Development;
use App\Models\Student;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TeacherStudentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'classroom_id' => ['nullable', 'integer'],
        ]);

        $students = $user->accessibleStudents()
            ->with('classroom')
            ->when($request->q, function ($query, $term) {
                $query->where(function ($w) use ($term) {
                    $w->where('name', 'like', "%{$term}%")
                        ->orWhere('nis', 'like', "%{$term}%");
                });
            })
            ->when($request->classroom_id, fn ($q, $v) => $q->where('classroom_id', $v))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $classrooms = Classroom::whereIn('id', $user->assignedClassroomIds())
            ->orderBy('name')
            ->get();

        return view('teacher.students.index', compact('students', 'classrooms'));
    }

    public function show(Request $request, Student $student)
    {
        Gate::authorize('view', $student);

        $user = $request->user();
        $student->load('classroom');

        $subjects = $user->subjectsFor($student);
        $isHomeroom = in_array($student->classroom_id, $user->homeroomClassroomIds());

        // Wali kelas melihat semua catatan siswa; guru mapel hanya catatannya sendiri
        $developments = Development::with('teacher')
            ->where('student_id', $student->id)
            ->when(!$isHomeroom, fn ($q) => $q->where('teacher_id', $user->id))
            ->latest('monitoring_date')
            ->limit(15)
            ->get();

        $videos = Video::with('teacher')
            ->where('student_id', $student->id)
            ->when(!$isHomeroom, fn ($q) => $q->where('teacher_id', $user->id))
            ->latest()
            ->limit(10)
            ->get();

        return view('teacher.students.show', compact(
            'student',
            'subjects',
            'isHomeroom',
            'developments',
            'videos'
        ));
    }
}
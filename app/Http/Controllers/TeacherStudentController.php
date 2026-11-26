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

        $student->load('classroom.homeroomAssignment.teacher');

        $subjects = $user->subjectsFor($student);
        $isHomeroom = in_array($student->classroom_id, $user->homeroomClassroomIds());

        // Wali kelas melihat semua catatan siswa; guru mapel hanya catatannya sendiri
        $devBase = Development::where('student_id', $student->id)
            ->when(!$isHomeroom, fn ($q) => $q->where('teacher_id', $user->id));

        $videoBase = Video::where('student_id', $student->id)
            ->when(!$isHomeroom, fn ($q) => $q->where('teacher_id', $user->id));

        $developmentCount = (clone $devBase)->count();
        $videoCount = (clone $videoBase)->count();
        $lastDate = (clone $devBase)->max('monitoring_date');

        $developments = (clone $devBase)
            ->with('teacher')
            ->latest('monitoring_date')
            ->latest('id')
            ->limit(15)
            ->get();

        $videos = (clone $videoBase)
            ->with('teacher')
            ->latest()
            ->limit(10)
            ->get();

        $activity = Development::activityByMonth(
            (clone $devBase)
                ->where('monitoring_date', '>=', now()->subMonths(5)->startOfMonth()->toDateString())
                ->get(['monitoring_date'])
        );

        return view('teacher.students.show', compact(
            'student',
            'subjects',
            'isHomeroom',
            'developments',
            'videos',
            'developmentCount',
            'videoCount',
            'lastDate',
            'activity'
        ));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Development;
use App\Models\Student;
use Illuminate\Support\Facades\Gate;

class ParentController extends Controller
{
    public function show(Student $student)
    {
        // Hanya anak yang terhubung dengan akun orang tua ini
        Gate::authorize('view', $student);

        $student->load([
            'classroom.homeroomAssignment.teacher',
            'developments' => function ($query) {
                $query->with('teacher')
                    ->latest('monitoring_date')
                    ->latest('id');
            },
            'videos' => function ($query) {
                $query->with('teacher')
                    ->latest();
            },
        ]);

        // Untuk pemilih anak jika orang tua punya lebih dari satu anak
        $siblings = auth()->user()->children()->orderBy('name')->get();

        $activity = Development::activityByMonth($student->developments);

        return view('parent.student', compact('student', 'siblings', 'activity'));
    }
}
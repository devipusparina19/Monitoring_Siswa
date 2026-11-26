<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Support\Facades\Gate;

class ParentController extends Controller
{
    public function show(Student $student)
    {
        // Hanya anak yang terhubung dengan akun orang tua ini
        Gate::authorize('view', $student);

        $student->load([
            'classroom',
            'developments' => function ($query) {
                $query->with('teacher')
                    ->latest('monitoring_date');
            },
            'videos' => function ($query) {
                $query->with('teacher')
                    ->latest();
            },
        ]);

        return view(
            'parent.student',
            compact('student')
        );
    }
}
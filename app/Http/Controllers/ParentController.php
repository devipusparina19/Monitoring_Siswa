<?php

namespace App\Http\Controllers;

use App\Models\Student;

class ParentController extends Controller
{
    public function show(Student $student)
    {
        abort_unless(
            $student->parent_id === auth()->id(),
            403
        );

        $student->load([
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
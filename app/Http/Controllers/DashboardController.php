<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Development;
use App\Models\Student;
use App\Models\User;
use App\Models\Video;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return view('dashboard.admin', [
                'studentCount' => Student::count(),
                'teacherCount' => User::where('role', 'guru')->count(),
                'parentCount' => User::where('role', 'orang_tua')->count(),
                'developmentCount' => Development::count(),
                'classroomCount' => Classroom::count(),
            ]);
        }

        if ($user->role === 'guru') {
            return view('dashboard.guru', [
                'studentCount' => $user->accessibleStudents()->count(),
                'developmentCount' => Development::where('teacher_id', $user->id)->count(),
                'videoCount' => Video::where('teacher_id', $user->id)->count(),
                'developments' => Development::with('student')
                    ->where('teacher_id', $user->id)
                    ->whereHas('student')
                    ->latest('monitoring_date')
                    ->take(10)
                    ->get(),
                'assignments' => $user->assignments()
                    ->with(['classroom', 'subject'])
                    ->get(),
                'students' => $user->accessibleStudents()
                    ->with('classroom')
                    ->orderBy('name')
                    ->limit(8)
                    ->get(),
            ]);
        }

        $children = $user->children()
            ->with([
                'classroom',
                'developments' => function ($query) {
                    $query->latest('monitoring_date');
                },
                'videos' => function ($query) {
                    $query->latest();
                },
            ])
            ->get();

        return view('dashboard.orang-tua', compact('children'));
    }
}
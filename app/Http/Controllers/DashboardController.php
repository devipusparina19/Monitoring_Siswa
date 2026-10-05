<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Development;
use App\Models\Video;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return view('dashboard.admin', [
                'studentCount' => Student::count(),
                'teacherCount' => \App\Models\User::where('role', 'guru')->count(),
                'parentCount' => \App\Models\User::where('role', 'orang_tua')->count(),
                'developmentCount' => Development::count(),
            ]);
        }

        if ($user->role === 'guru') {
            return view('dashboard.guru', [
                'studentCount' => Student::count(),
                'developmentCount' => Development::where(
                    'teacher_id',
                    $user->id
                )->count(),
                'videoCount' => Video::where(
                    'teacher_id',
                    $user->id
                )->count(),
                'developments' => Development::with('student')
                    ->where('teacher_id', $user->id)
                    ->latest('monitoring_date')
                    ->take(10)
                    ->get(),
            ]);
        }

        $children = $user->children()
            ->with([
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
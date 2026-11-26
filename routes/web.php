<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DevelopmentController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherStudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {

        Route::resource('users', UserController::class)->except(['show']);

        Route::resource('students', StudentController::class)->except(['show']);

        Route::resource('classrooms', ClassroomController::class)->except(['show']);

        Route::resource('subjects', SubjectController::class)->except(['show']);

        Route::get('/penugasan', [AssignmentController::class, 'index'])
            ->name('assignments.index');

        Route::post('/penugasan', [AssignmentController::class, 'store'])
            ->name('assignments.store');

        Route::delete('/penugasan/{assignment}', [AssignmentController::class, 'destroy'])
            ->name('assignments.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | GURU
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:guru')->group(function () {

        Route::get('/siswa-saya', [TeacherStudentController::class, 'index'])
            ->name('teacher.students.index');

        Route::get('/siswa-saya/{student}', [TeacherStudentController::class, 'show'])
            ->name('teacher.students.show');

        Route::resource('developments', DevelopmentController::class)
            ->except(['show']);

        Route::resource('videos', VideoController::class)
            ->only(['index', 'create', 'store', 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | ORANG TUA
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:orang_tua')->group(function () {

        // Nama route disamakan dengan yang sudah dipakai layout & dashboard orang tua
        Route::get('/anak/{student}', [ParentController::class, 'show'])
            ->name('parent.child');
    });
});
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DevelopmentController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ParentController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {

    Route::get(
        '/login',
        [AuthController::class, 'showLogin']
    )->name('login');

    Route::post(
        '/login',
        [AuthController::class, 'login']
    )->name('login.process');
});

Route::middleware('auth')->group(function () {

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');

    Route::post(
        '/logout',
        [AuthController::class, 'logout']
    )->name('logout');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {

        Route::resource(
            'users',
            UserController::class
        )->except(['show']);

        Route::resource(
            'students',
            StudentController::class
        )->except(['show']);
    });

    /*
    |--------------------------------------------------------------------------
    | GURU
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:guru')->group(function () {

        Route::resource(
            'developments',
            DevelopmentController::class
        );

        Route::resource(
            'videos',
            VideoController::class
        )->only([
            'index',
            'create',
            'store',
            'destroy'
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | ORANG TUA
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:orang_tua')->group(function () {

        Route::get(
            '/anak/{student}',
            [ParentController::class, 'show']
        )->name('parent.student');
    });
});
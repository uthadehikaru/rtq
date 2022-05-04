<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\BatchMemberController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth')->group(function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/', 'index')->name('home');
        Route::get('/dashboard', 'dashboard')->name('dashboard');
    });
    Route::get('/users', [UserController::class,'index'])->name('user');
    Route::get('/members', [MemberController::class,'index'])->name('member');
    Route::get('/courses', [CourseController::class,'index'])->name('course');
    Route::get('/courses/{course_id}/batches', [BatchController::class,'index'])->name('course.batch');
    Route::get('/courses/{course_id}/batches/{batch_id}/members', [BatchMemberController::class,'index'])->name('course.batch.member');
    
    Route::get('/logout', [LoginController::class,'logout'])->name('logout');
    Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->name('logs');
});
Route::get('/login', [LoginController::class,'index'])->name('login');
Route::post('/login', [LoginController::class,'authenticate'])->name('login.authenticate');
